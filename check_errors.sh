#!/bin/bash
# Comprehensive Error Check Script for ADI ARI Fresh Project
# This script checks for common PHP errors and issues

echo "================================"
echo "ADI ARI FRESH - ERROR CHECK"
echo "================================"
echo ""

# Check for undefined variables/array keys
echo "🔍 Checking for potential undefined array keys..."
grep -r "offer_title" --include="*.php" app/views/ 2>/dev/null || echo "  ✅ No 'offer_title' references found"

# Check for syntax errors
echo ""
echo "🔍 Checking PHP syntax errors..."
find app/ -name "*.php" -exec php -l {} \; 2>&1 | grep -i "error" || echo "  ✅ No syntax errors found"

# Check for missing required files
echo ""
echo "🔍 Checking required files..."
REQUIRED_FILES=(
    "app/core/Application.php"
    "app/core/Controller.php"
    "app/core/Model.php"
    "app/core/View.php"
    "app/core/Router.php"
    "app/core/Database.php"
    "config/database.php"
    "app/models/User.php"
    "app/models/Product.php"
    "app/models/Order.php"
    "app/models/Offer.php"
    "app/controllers/HomeController.php"
    "app/controllers/AdminController.php"
    "app/views/home/index.php"
)

MISSING=0
for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        echo "  ❌ Missing: $file"
        MISSING=$((MISSING+1))
    fi
done

if [ $MISSING -eq 0 ]; then
    echo "  ✅ All required files present"
fi

# Check database configuration
echo ""
echo "🔍 Checking database configuration..."
if [ -f "config/database.php" ]; then
    echo "  ✅ Database config found"
    grep -q "u314077991_adiari_shop" config/database.php && echo "  ✅ Production database configured" || echo "  ⚠️  Production database not found (may be intentional for local dev)"
else
    echo "  ❌ Database config missing"
fi

# Check for common problematic patterns
echo ""
echo "🔍 Checking for common problematic patterns..."

# Check for undefined index access
echo "  - Checking for potential undefined index issues..."
grep -rn "\$.*\[.*\]" app/views/home/index.php | grep "deal\['offer_title'\]" && echo "    ❌ Found undefined 'offer_title' usage" || echo "    ✅ No 'offer_title' undefined index found"

# Check .htaccess
echo ""
echo "🔍 Checking .htaccess configuration..."
if [ -f ".htaccess" ]; then
    echo "  ✅ .htaccess found"
    grep -q "RewriteEngine On" .htaccess && echo "  ✅ URL rewriting enabled" || echo "  ⚠️  URL rewriting not enabled"
else
    echo "  ❌ .htaccess missing"
fi

# Check routes
echo ""
echo "🔍 Checking routes configuration..."
if [ -f "routes/web.php" ]; then
    echo "  ✅ Routes file found"
    grep -q "home" routes/web.php && echo "  ✅ Home route configured" || echo "  ⚠️  Home route not found"
else
    echo "  ❌ Routes file missing"
fi

echo ""
echo "================================"
echo "✅ ERROR CHECK COMPLETE"
echo "================================"
