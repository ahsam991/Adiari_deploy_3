<?php
/**
 * View Class
 * Handles rendering of views with layout support
 */

class View {
    private $viewPath;
    private $data = [];

    /**
     * Automatically resolve the correct layout for a given view path.
     *
     * Rules (in priority order):
     *  1. If $layout is explicitly passed (non-null), always use it.
     *  2. Views whose path starts with 'admin/' or 'manager/' → layouts/admin
     *  3. All other views → layouts/main (shop layout)
     *
     * This means no controller ever has to remember to override view();
     * the correct layout is chosen automatically based on where the view lives.
     *
     * @param  string      $view   The view path (e.g. 'admin/dashboard')
     * @param  string|null $layout Explicit override, or null for auto-detect
     * @return string
     */
    private function resolveLayout($view, $layout) {
        if ($layout !== null) {
            return $layout; // Explicit override always wins
        }
        // Admin / manager panels use the clean admin layout
        if (strncmp($view, 'admin/', 6) === 0 || strncmp($view, 'manager/', 8) === 0) {
            return 'layouts/admin';
        }
        // All shop / public pages use the full store layout
        return 'layouts/main';
    }

    /**
     * Render view
     * @param string $view View file path (without .php)
     * @param array $data Data to pass to view
     * @param string|null $layout Explicit layout override (null = auto-detect)
     */
    public function render($view, $data = [], $layout = null) {
        $this->data = $data;
        
        // Extract data variables
        extract($data);

        // Resolve which layout to use
        $layoutPath = $this->resolveLayout($view, $layout);

        // Build view file path
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: {$viewFile}");
        }

        // Start output buffering
        ob_start();
        try {
            // CRITICAL FIX: Make View instance available to template
            // View files can now use $this->productImage(), $this->url(), etc.
            // The $this variable is automatically available because we're inside
            // an instance method and require includes the file in this scope
            require $viewFile;
        } catch (Throwable $e) {
            // Clean the buffer and log the error
            ob_end_clean();
            error_log("View render error in '{$view}': " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
            // Re-throw so the global handler catches it
            throw $e;
        }
        $content = ob_get_clean();

        // Render with layout if specified
        if ($layoutPath) {
            $layoutFile = __DIR__ . '/../views/' . $layoutPath . '.php';
            
            if (file_exists($layoutFile)) {
                // Make content and data available to layout
                extract($data);
                try {
                    require $layoutFile;
                } catch (Throwable $e) {
                    // Log the layout error
                    error_log("View layout error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
                    // Layout may have output partial HTML; echo content as safety net
                    echo $content;
                }
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Include a partial view
     * @param string $partial Partial view path
     * @param array $data Data to pass
     */
    public function partial($partial, $data = []) {
        extract($data);
        $partialFile = __DIR__ . '/../views/' . $partial . '.php';
        
        if (file_exists($partialFile)) {
            require $partialFile;
        } else {
            throw new Exception("Partial view not found: {$partialFile}");
        }
    }

    /**
     * Escape HTML to prevent XSS
     * @param string $string String to escape
     * @return string
     */
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate URL
     * @param string $path URL path
     * @return string
     */
    public function url($path) {
        return Router::url($path);
    }

    /**
     * Get asset URL
     * @param string $path Asset path
     * @return string
     */
    public function asset($path) {
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        return $basePath . '/' . ltrim($path, '/');
    }

    /**
     * Display flash message
     * @param string $type Message type
     * @return string|null
     */
    public function flash($type = null) {
        if ($type) {
            return Session::getFlash($type);
        }
        return Session::getAllFlash();
    }

    /**
     * Get CSRF token input field
     * @return string
     */
    public function csrfField() {
        $token = Security::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Get CSRF token value
     * @return string
     */
    public function csrfToken() {
        return Security::generateCsrfToken();
    }

    /**
     * Get the correct URL for a product image
     * Handles: absolute URLs (http/https), paths starting with /uploads/, bare filenames, and null/empty
     * @param string|null $imagePath  The stored image path (e.g. /uploads/products/file.png)
     * @param string      $placeholder Fallback URL when image is empty
     * @return string
     */
    public function productImage($imagePath, $placeholder = 'https://placehold.co/400x400?text=Product') {
        // If no image path provided, return placeholder
        if (empty($imagePath) || $imagePath === 'null') {
            return $placeholder;
        }

        // External URL — use as-is
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }
        
        // Normalize path: remove leading slashes and 'public/' if present
        $cleanPath = ltrim($imagePath, '/');
        if (str_starts_with($cleanPath, 'public/')) {
            $cleanPath = substr($cleanPath, 7);
            $cleanPath = ltrim($cleanPath, '/');
        }
        
        // If it's a bare filename (no slashes), it's likely in the products upload dir
        if (!str_contains($cleanPath, '/')) {
            $cleanPath = 'uploads/products/' . $cleanPath;
        }
        
        // Ensure standard folders are preserved, otherwise assume it's a product image
        $standardFolders = ['uploads/', 'images/', 'assets/', 'css/', 'js/'];
        $isStandard = false;
        foreach ($standardFolders as $folder) {
            if (str_starts_with($cleanPath, $folder)) {
                $isStandard = true;
                break;
            }
        }

        if (!$isStandard) {
            $cleanPath = 'uploads/products/' . $cleanPath;
        }

        // Check if the image file actually exists on disk before returning the URL
        // The public directory is where the web server serves files from
        $publicDir = defined('ROOT_PATH') ? ROOT_PATH . '/public/' : __DIR__ . '/../../public/';
        $absolutePath = $publicDir . $cleanPath;
        
        if (!file_exists($absolutePath)) {
            // File doesn't exist on disk — return the placeholder instead of a broken image URL
            return $placeholder;
        }

        // Generate the final URL using the base path
        return $this->url('/' . $cleanPath);
    }
}
