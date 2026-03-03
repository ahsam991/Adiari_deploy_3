<!DOCTYPE html>
<html lang="<?= Language::current() ?>" class="light" style="width:100%;max-width:100%;overflow-x:hidden;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'ADI ARI Fresh'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= $this->url('/images/favicon.svg') ?>">
    <link rel="shortcut icon" type="image/svg+xml" href="<?= $this->url('/images/favicon.svg') ?>">
    
    <!-- Fonts - Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Sans+JP:wght@300;400;500;700;900&family=Noto+Sans+Devanagari:wght@300;400;500;700&family=Noto+Sans+Bengali:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN (In production, compile and serve locally) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries,aspect-ratio"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": {
                            50: "#f0fdf4",
                            100: "#dcfce7",
                            200: "#bbf7d0",
                            300: "#86efac",
                            400: "#4ade80",
                            500: "#10b981",
                            600: "#059669",
                            700: "#047857",
                            800: "#065f46",
                            900: "#064e3b",
                            950: "#022c22"
                        },
                        "accent": {
                            50: "#fffbeb",
                            100: "#fef3c7",
                            200: "#fde68a",
                            300: "#fcd34d",
                            400: "#fbbf24",
                            500: "#f59e0b",
                            600: "#d97706",
                            700: "#b45309",
                            800: "#92400e",
                            900: "#78350f"
                        },
                        "text-main": {
                            "light": "#1f2937",
                            "dark": "#f9fafb"
                        },
                        "text-sub": {
                            "light": "#6b7280",
                            "dark": "#9ca3af"
                        },
                        "background": {
                            "light": "#f9fafb",
                            "dark": "#111827"
                        },
                        "border": {
                            "light": "#e5e7eb",
                            "dark": "#374151"
                        }
                    },
                    fontFamily: {
                        "sans": ["Inter", "Noto Sans JP", "Noto Sans Devanagari", "Noto Sans Bengali", "system-ui", "sans-serif"],
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "4xl": "2rem",
                        "5xl": "2.5rem",
                    },
                    boxShadow: {
                        "soft": "0 2px 15px rgba(0, 0, 0, 0.08)",
                        "glow": "0 0 20px rgba(16, 185, 129, 0.3)",
                        "glow-lg": "0 0 30px rgba(16, 185, 129, 0.4)",
                    },
                    animation: {
                        "float": "float 3s ease-in-out infinite",
                        "fade-in-up": "fadeInUp 0.6s ease-out forwards",
                        "pulse-glow": "pulseGlow 2s ease-in-out infinite",
                    },
                    keyframes: {
                        float: {
                            "0%, 100%": { transform: "translateY(0px)" },
                            "50%": { transform: "translateY(-10px)" }
                        },
                        fadeInUp: {
                            from: { opacity: "0", transform: "translateY(20px)" },
                            to: { opacity: "1", transform: "translateY(0)" }
                        },
                        pulseGlow: {
                            "0%, 100%": { boxShadow: "0 0 20px rgba(16, 185, 129, 0.3)" },
                            "50%": { boxShadow: "0 0 30px rgba(16, 185, 129, 0.6)" }
                        }
                    }
                },
            },
        }
    </script>
    
    <!-- Modern Theme CSS -->
    <link rel="stylesheet" href="<?= $this->url('/css/modern-theme.css') ?>">
    
    <style>
        /* ── Full-width page guarantee ─────────────────────────────
           Prevents the "split screen" / squished layout bug where
           content renders as narrow columns instead of full-width.
           This is the source of truth for layout width at the root.
        ────────────────────────────────────────────────────────── */
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 5rem;
        }

        /* High-quality image rendering */
        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            -ms-interpolation-mode: bicubic;
        }
        
        /* Custom scrollbar - Professional Design */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }
    </style>
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1GZ9KN4B25"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-1GZ9KN4B25');
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans text-gray-900 dark:text-gray-100" style="width:100%;max-width:100%;overflow-x:hidden;">
    <div class="min-h-screen flex flex-col w-full">
    
    <!-- Top Utility Bar - Modern -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 relative z-[60] shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-10">
                <!-- Left Side: Trust Badges / Info -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2 text-xs font-semibold text-primary-700 dark:text-primary-400">
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                        <span><?= Language::get('halal_certified') ?></span>
                    </div>
                    <div class="h-4 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
                    <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-gray-600 dark:text-gray-400">
                        <span class="material-symbols-outlined text-[16px]">local_shipping</span>
                        <span><?= Language::get('free_shipping') ?></span>
                    </div>
                </div>
                <!-- Right Side: Utility Links -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <a class="hidden sm:inline-block text-xs font-medium text-gray-500 hover:text-emerald-600 dark:text-gray-400 dark:hover:text-emerald-400 transition-colors" href="<?= $this->url('/contact') ?>"><?= Language::get('help_center') ?></a>
                    
                    <!-- Language Switcher -->
                    <div class="relative group z-50">
                        <?php
                        $langFlags = ['en' => '🇬🇧', 'ja' => '🇯🇵', 'bn' => '🇧🇩', 'ne' => '🇳🇵'];
                        $currentFlag = $langFlags[Language::current()] ?? '🌐';
                        ?>
                        <button id="lang-switcher-btn" class="flex items-center gap-1.5 cursor-pointer focus:outline-none py-1 hover:text-emerald-600 transition-colors">
                            <span class="text-base leading-none"><?= $currentFlag ?></span>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300"><?= strtoupper(Language::current()) ?></span>
                            <span class="material-symbols-outlined text-[14px] text-gray-500 group-hover:rotate-180 transition-transform duration-300">expand_more</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="lang-dropdown" class="absolute right-0 top-full mt-1 w-44 bg-white dark:bg-gray-800 rounded-xl shadow-2xl py-2 border border-gray-100 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-1 group-hover:translate-y-0 transition-all duration-200 z-[100]">
                            <?php foreach(Language::available() as $code => $label): ?>
                                <a href="<?= $this->url('/language/' . $code) ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors <?= Language::current() == $code ? 'text-emerald-700 font-bold bg-emerald-50 dark:bg-emerald-900/20' : 'text-gray-700 dark:text-gray-300' ?>">
                                    <span class="text-base"><?= $langFlags[$code] ?? '🌐' ?></span>
                                    <span><?= $label ?></span>
                                    <?php if(Language::current() == $code): ?>
                                        <span class="ml-auto material-symbols-outlined text-[16px] text-emerald-600">check</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <a href="https://www.google.com/maps/search/?api=1&query=114-0031+Higashi+Tabata+2-3-1+Otsu+building+101,+Tokyo,+Japan" target="_blank" rel="noopener noreferrer" class="hidden sm:flex items-center gap-1.5 pl-4 ml-2 border-l border-gray-200 dark:border-gray-700 text-xs font-bold text-gray-700 dark:text-gray-300 hover:text-emerald-600 transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-primary">location_on</span>
                        <span>Tokyo, Japan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Header Bar - Modern Clean Design -->
    <header class="bg-white dark:bg-gray-800 sticky top-0 z-50 shadow-md border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between gap-6">
                <!-- Logo -->
                <a class="flex items-center shrink-0 group" href="<?= $this->url('/') ?>">
                    <img src="<?= $this->url('/images/logo.svg') ?>" alt="ADI ARI - Fresh Vegetables and Halal Food" class="h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 60%22%3E%3Ctext x=%2210%22 y=%2240%22 font-family=%22Arial%22 font-size=%2224%22 font-weight=%22bold%22 fill=%22%2310b981%22%3EADI ARI%3C/text%3E%3C/svg%3E';">
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="<?= $this->url('/') ?>" class="nav-link px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <?= Language::get('home') ?? 'Home' ?>
                    </a>
                    <a href="<?= $this->url('/products') ?>" class="nav-link px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <?= Language::get('all_products') ?? 'All Products' ?>
                    </a>
                    <a href="<?= $this->url('/deals') ?>" class="nav-link px-4 py-2 text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">local_offer</span>
                        <span><?= Language::get('deals') ?? 'Deals' ?></span>
                    </a>
                    <a href="<?= $this->url('/about') ?>" class="nav-link px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <?= Language::get('about') ?? 'About' ?>
                    </a>
                    <a href="<?= $this->url('/contact') ?>" class="nav-link px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <?= Language::get('contact') ?? 'Contact' ?>
                    </a>
                </nav>
                
                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:flex flex-1 max-w-xl">
                    <form action="<?= $this->url('/products') ?>" method="GET" class="flex w-full items-center rounded-xl bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-100 dark:focus-within:ring-primary-900 transition-all overflow-hidden">
                        <input name="search" class="w-full bg-transparent border-none py-3 px-4 text-sm text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:ring-0" placeholder="<?= Language::get('search_placeholder') ?? 'Search products...' ?>" type="text">
                        <button type="submit" class="px-4 text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                            <span class="material-symbols-outlined text-[24px]">search</span>
                        </button>
                    </form>
                </div>

                <!-- Action Icons: Account, Wishlist, Cart -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Account -->
                    <a href="<?= $this->url(Session::isLoggedIn() ? '/account' : '/login') ?>" class="hidden lg:flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                        <span class="material-symbols-outlined text-[22px] text-gray-600 dark:text-gray-300 group-hover:text-emerald-600">person</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-emerald-600">
                            <?= Session::isLoggedIn() ? htmlspecialchars(Session::get('name')) : (Language::get('login') ?? 'Login') ?>
                        </span>
                    </a>

                    <!-- Wishlist -->
                    <a href="<?= $this->url('/wishlist') ?>" class="hidden sm:flex p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                        <span class="material-symbols-outlined text-[22px] text-gray-600 dark:text-gray-300 group-hover:text-red-500">favorite</span>
                    </a>

                    <!-- Cart (Hidden on mobile due to sticky bottom nav) -->
                    <a href="<?= $this->url('/cart') ?>" class="hidden lg:flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors group">
                        <div class="relative">
                            <span class="material-symbols-outlined text-[22px] text-emerald-700 dark:text-emerald-400">shopping_cart</span>
                            <?php
                            $cartCount = 0;
                            $cartSubtotal = 0;
                            if (Session::isLoggedIn()) {
                                try {
                                    require_once __DIR__ . '/../../models/Cart.php';
                                    $cartModel = new Cart();
                                    $cartCount = $cartModel->getCartCount(Session::get('user_id'));
                                    $cartSubtotal = $cartModel->getCartTotals(Session::get('user_id'))['subtotal'];
                                } catch (Throwable $e) { $cartCount = 0; $cartSubtotal = 0; }
                            }
                            ?>
                            <?php if ($cartCount > 0): ?>
                            <span class="absolute -top-1.5 -right-1.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full"><?= $cartCount ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="hidden sm:flex flex-col">
                            <span class="text-[10px] font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide"><?= Language::get('my_cart') ?? 'Cart' ?></span>
                            <span class="text-sm font-bold text-emerald-900 dark:text-emerald-300">¥<?= number_format($cartSubtotal) ?></span>
                        </div>
                    </a>

                    <!-- Mobile Menu Toggle -->
                    <button onclick="toggleMobileMenu()" aria-label="Open mobile menu" class="lg:hidden p-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-[26px]">menu</span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Mobile Menu Drawer -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-[70] hidden lg:hidden" onclick="toggleMobileMenu()"></div>
    <div id="mobile-menu-drawer" class="fixed top-0 right-0 h-full w-[280px] bg-white dark:bg-gray-800 shadow-2xl z-[80] transform translate-x-full transition-transform duration-300 lg:hidden overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
            <a href="<?= $this->url('/') ?>" onclick="toggleMobileMenu()">
                <img src="<?= $this->url('/images/logo.svg') ?>" alt="ADI ARI" class="h-10 w-auto object-contain">
            </a>
            <button onclick="toggleMobileMenu()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        
        <!-- Mobile Search -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <form action="<?= $this->url('/products') ?>" method="GET" class="flex items-center rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <input name="search" class="flex-1 bg-transparent border-none py-2.5 px-3 text-sm text-gray-900 dark:text-white placeholder:text-gray-500" placeholder="<?= Language::get('search_placeholder') ?>" type="text">
                <button type="submit" class="p-2.5 text-emerald-600">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                </button>
            </form>
        </div>

        <!-- Mobile Categories -->
        <div class="p-4">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"><?= Language::get('categories') ?></p>
            <nav class="space-y-1">
                <?php
                // Fetch dynamic categories if not already fetched
                if (!isset($navCategories)) {
                    $navCategories = [];
                    try {
                        require_once __DIR__ . '/../../models/Category.php';
                        $navCategoryModel = new Category();
                        $navCategories = $navCategoryModel->getActiveCategories();
                    } catch (Throwable $e) {
                        error_log("Layout: Failed to load navigation categories: " . $e->getMessage());
                    }
                }
                
                foreach ($navCategories as $navCat):
                ?>
                <a href="<?= $this->url('/products?category=' . $navCat['slug']) ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <?php if (!empty($navCat['icon'])): ?>
                        <span class="material-symbols-outlined text-[20px] text-emerald-600"><?= $navCat['icon'] ?></span>
                    <?php endif; ?>
                    <span class="text-sm font-medium"><?= htmlspecialchars($navCat['name']) ?></span>
                </a>
                <?php endforeach; ?>
                <a href="<?= $this->url('/deals') ?>" class="flex items-center gap-3 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-[20px] animate-pulse">local_offer</span>
                    <span class="text-sm font-bold"><?= Language::get('weekly_deals') ?></span>
                </a>
            </nav>
        </div>

        <!-- Mobile Account Links -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"><?= Language::get('account') ?></p>
            <nav class="space-y-1">
                <?php if (Session::isLoggedIn()): ?>
                    <a href="<?= $this->url('/account') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-[20px] text-emerald-600">person</span>
                        <span class="text-sm font-medium"><?= Language::get('my_account') ?></span>
                    </a>
                    <a href="<?= $this->url('/account/orders') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-[20px] text-emerald-600">receipt_long</span>
                        <span class="text-sm font-medium"><?= Language::get('orders') ?></span>
                    </a>
                    <a href="<?= $this->url('/wishlist') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-[20px] text-emerald-600">favorite</span>
                        <span class="text-sm font-medium"><?= Language::get('wishlist') ?></span>
                    </a>
                    <a href="<?= $this->url('/logout') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        <span class="text-sm font-medium"><?= Language::get('logout') ?></span>
                    </a>
                <?php else: ?>
                    <a href="<?= $this->url('/login') ?>" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-[20px] text-emerald-600">login</span>
                        <span class="text-sm font-medium"><?= Language::get('login') ?></span>
                    </a>
                    <a href="<?= $this->url('/register') ?>" class="flex items-center gap-3 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                        <span class="text-sm font-bold"><?= Language::get('register') ?></span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 w-full">
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-gray-950 text-gray-300 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <!-- Col 1: About ADI ARI -->
                <div class="space-y-4">
                    <img src="<?= $this->url('/images/logo.svg') ?>" alt="ADI ARI Fresh" class="h-12 w-auto object-contain brightness-0 invert mb-2" onerror="this.style.display='none'; document.getElementById('footer-logo-text').style.display='block'">
                    <span id="footer-logo-text" class="text-2xl font-black text-white hidden">ADI ARI Fresh</span>
                    <p class="text-sm text-gray-400 leading-relaxed">Your trusted source for halal-certified meats and farm-fresh organic produce in Tokyo, serving the Bangladeshi, Nepali and Muslim community since 2020.</p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="https://www.facebook.com/profile.php?id=61555341635418" target="_blank" rel="noopener noreferrer" title="Facebook" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white/10 hover:bg-[#1877F2] text-gray-400 hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="https://wa.me/818034088044" target="_blank" rel="noopener noreferrer" title="WhatsApp" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white/10 hover:bg-[#25D366] text-gray-400 hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Shop -->
                <div class="space-y-4">
                    <h3 class="font-bold text-white text-base uppercase tracking-wider">Shop</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="<?= $this->url('/products') ?>" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> All Products</a></li>
                        <li><a href="<?= $this->url('/products?category=vegetables') ?>" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> Fresh Vegetables</a></li>
                        <li><a href="<?= $this->url('/products?category=halal-meat') ?>" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> Halal Meat</a></li>
                        <li><a href="<?= $this->url('/products?category=dairy') ?>" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> Dairy Products</a></li>
                        <li><a href="<?= $this->url('/deals') ?>" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-red-500">&rsaquo;</span> Weekly Deals</a></li>
                    </ul>
                </div>

                <!-- Col 3: Customer Service -->
                <div class="space-y-4">
                    <h3 class="font-bold text-white text-base uppercase tracking-wider">Customer Service</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="<?= $this->url('/about') ?>" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> <?= Language::get('about') ?></a></li>
                        <li><a href="<?= $this->url('/contact') ?>" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> <?= Language::get('contact') ?></a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> <?= Language::get('faq') ?></a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> <?= Language::get('shipping_info') ?></a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors flex items-center gap-2"><span class="text-emerald-600">&rsaquo;</span> <?= Language::get('returns') ?></a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact / Map -->
                <div class="space-y-4">
                    <h3 class="font-bold text-white text-base uppercase tracking-wider">Contact Us</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2 text-gray-400">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500 shrink-0 mt-0.5">location_on</span>
                            <span>114-0031 Higashi Tabata 2-3-1<br>Otsu building 101, Tokyo</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500">call</span>
                            <a href="tel:+818034088044" class="text-gray-400 hover:text-emerald-400 transition-colors">080-3408-8044</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500">schedule</span>
                            <span class="text-gray-400">Daily 8:00 AM – 10:00 PM</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500">mail</span>
                            <a href="mailto:info@adiari.shop" class="text-gray-400 hover:text-emerald-400 transition-colors">info@adiari.shop</a>
                        </li>
                    </ul>
                    <div class="rounded-xl overflow-hidden shadow-lg mt-2">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3040.2375887429785!2d139.75845017540416!3d35.74042412672079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188d001af1c979%3A0xe044a1aded276bfb!2zQURJIEFSSSDnlLDnq68!5e1!3m2!1sen!2sbd!4v1770797299608!5m2!1sen!2sbd"
                                width="100%" height="140" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- Bottom bar -->
            <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">&copy; 2026 ADI ARI FRESH. <?= Language::get('copyright') ?></p>
                <!-- Payment Method Icons -->
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 font-medium">We accept:</span>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-white rounded text-[10px] font-black text-blue-800 shadow">VISA</span>
                        <span class="px-2 py-1 bg-white rounded text-[10px] font-black text-red-600 shadow">MC</span>
                        <span class="px-2 py-1 bg-white rounded text-[10px] font-black text-blue-600 shadow">PayPay</span>
                        <span class="px-2 py-1 bg-white rounded text-[10px] font-black text-green-600 shadow">LINE Pay</span>
                        <span class="px-2 py-1 bg-white rounded text-[10px] font-black text-gray-800 shadow">Cash</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    </div>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
       class="scroll-to-top fixed bottom-[5.5rem] left-4 z-50 w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center rounded-full shadow-xl hover:shadow-2xl transition-all hover:scale-110 lg:bottom-6 opacity-0 invisible"
       title="Back to top" aria-label="Scroll to top">
        <span class="material-symbols-outlined">keyboard_arrow_up</span>
    </button>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/818034088044" target="_blank" rel="noopener noreferrer"
       class="fixed bottom-[5.5rem] right-4 z-50 w-14 h-14 bg-[#25D366] hover:bg-[#20b858] flex items-center justify-center rounded-full shadow-xl hover:shadow-2xl transition-all hover:scale-110 lg:bottom-6"
       title="Chat on WhatsApp" aria-label="Chat on WhatsApp">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    <!-- Sticky Mobile Bottom Nav -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-2xl">
        <div class="grid grid-cols-4 h-16">
            <a href="<?= $this->url('/') ?>" class="flex flex-col items-center justify-center gap-1 text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors group">
                <span class="material-symbols-outlined text-[24px] group-hover:fill-1">home</span>
                <span class="text-[10px] font-semibold">Home</span>
            </a>
            <a href="<?= $this->url('/products') ?>" class="flex flex-col items-center justify-center gap-1 text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors group">
                <span class="material-symbols-outlined text-[24px]">grid_view</span>
                <span class="text-[10px] font-semibold">Categories</span>
            </a>
            <a href="<?= $this->url('/cart') ?>" class="flex flex-col items-center justify-center gap-1 text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors group relative">
                <div class="relative">
                    <span class="material-symbols-outlined text-[24px]">shopping_cart</span>
                    <?php if (!empty($cartCount) && $cartCount > 0): ?>
                    <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center"><?= $cartCount ?></span>
                    <?php endif; ?>
                </div>
                <span class="text-[10px] font-semibold">Cart</span>
            </a>
            <a href="<?= $this->url(Session::isLoggedIn() ? '/account' : '/login') ?>" class="flex flex-col items-center justify-center gap-1 text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors group">
                <span class="material-symbols-outlined text-[24px]">person</span>
                <span class="text-[10px] font-semibold">Account</span>
            </a>
        </div>
    </nav>

    <!-- Bottom padding for mobile nav -->
    <div class="h-16 lg:hidden"></div>

    <!-- Custom Scripts -->
    <script>
    // Mobile Menu Toggle
    function toggleMobileMenu() {
        const drawer = document.getElementById('mobile-menu-drawer');
        const overlay = document.getElementById('mobile-menu-overlay');
        
        if (drawer && overlay) {
            drawer.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }
    }

    // Scroll to Top Button
    (function() {
        const scrollBtn = document.getElementById('scrollToTop');
        if (scrollBtn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 400) {
                    scrollBtn.classList.remove('opacity-0', 'invisible');
                    scrollBtn.classList.add('opacity-100', 'visible');
                } else {
                    scrollBtn.classList.add('opacity-0', 'invisible');
                    scrollBtn.classList.remove('opacity-100', 'visible');
                }
            });
        }
    })();

    // Image lazy loading enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(function(img) {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    img.classList.add('loaded');
                });
            }
        });
    });

    // Add page transition class
    document.body.classList.add('page-transition');
    </script>
    <script src="<?= $this->url('/js/main.js') ?>"></script>
</body>
</html>
