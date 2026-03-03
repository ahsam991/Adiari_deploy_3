<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Admin Panel'); ?> — ADI ARI Fresh</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    fontFamily: { sans: ["Inter", "system-ui", "sans-serif"] },
                    colors: {
                        primary: {
                            50: "#f0fdf4", 100: "#dcfce7", 200: "#bbf7d0",
                            300: "#86efac", 400: "#4ade80", 500: "#10b981",
                            600: "#059669", 700: "#047857", 800: "#065f46", 900: "#064e3b"
                        }
                    }
                }
            }
        }
    </script>

    <style>
        html, body { height: 100%; margin: 0; padding: 0; overflow-x: hidden; }
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #059669; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-900 h-full">
    <div class="min-h-screen flex flex-col">
        <main class="flex-1 w-full">
            <?php echo $content; ?>
        </main>
    </div>

    <script>
    /* Auto-dismiss flash messages after 5 s */
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-flash]').forEach(function (el) {
            setTimeout(function () {
                el.style.transition = 'opacity .5s';
                el.style.opacity = '0';
                setTimeout(function(){ el.remove(); }, 500);
            }, 5000);
        });
    });
    </script>
</body>
</html>
