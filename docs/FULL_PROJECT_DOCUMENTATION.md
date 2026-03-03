# 📘 ADI ARI Fresh - Master Project Documentation

**Project Name:** ADI ARI Fresh (Grocery & Halal Food E-commerce)
**Location:** Tokyo, Japan
**Version:** 2.0 (Unified Database & Hostinger Ready)
**Last Updated:** February 17, 2026

---

# 📚 Table of Contents

1.  **[Book I: Executive Summary & System Overview](#book-i-executive-summary--system-overview)**
    *   1.1 Project Vision
    *   1.2 System Architecture
    *   1.3 Key Technologies
    *   1.4 Credentials
2.  **[Book II: Process Manuals (How-To Guides)](#book-ii-process-manuals)**
    *   2.1 Customer Journey (Shopping Process)
    *   2.2 Admin Management (Back Office)
    *   2.3 Shop/POS Operations (In-Store)
3.  **[Book III: Technical Developer Guide](#book-iii-technical-developer-guide)**
    *   3.1 File Structure
    *   3.2 Database Schema
    *   3.3 Routing System
    *   3.4 Model-View-Controller (MVC) Flow
4.  **[Book IV: Deployment & Maintenance](#book-iv-deployment--maintenance)**
    *   4.1 Localhost Installation
    *   4.2 Hostinger Deployment Guide
    *   4.3 Troubleshooting
    *   4.4 Roadmap

---

# Book I: Executive Summary & System Overview

## 1.1 Project Vision
**ADI ARI Fresh** is a hybrid retail platform designed to bridge the gap between physical retail and online shopping for fresh vegetables and halal food in Tokyo. It allows customers to order online for delivery while simultaneously empowering store staff to manage walk-in customers using a tablet-based Point of Sale (POS) system.

**Unique Selling Point:** A single unified inventory. Whether a tomato is sold online or over the counter, the stock count is updated centrally, preventing overselling.

## 1.2 System Architecture
The application is built on a custom **PHP MVC Framework**. This ensures:
*   **Speed:** No heavy framework bloat (like Laravel/Symfony) for shared hosting environments.
*   **Control:** Every aspect of the request life-cycle is explicitly controlled.
*   **Portability:** Works seamlessly on XAMPP (Local) and Hostinger (Live).

## 1.3 Key Technologies
*   **Backend:** PHP 7.4 / 8.0+
*   **Database:** MySQL 5.7+ / 8.0 (Unified structure)
*   **Frontend:** HTML5, Vanilla CSS, Vanilla JS (No build steps required)
*   **Server:** Apache (.htaccess routing)

## 1.4 Credentials (Defaults)
*Important: Change passwords immediately after deployment.*

| Role | Email / Username | Password | Access URL |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@adiarifresh.com` | `admin123` | `/login` then `/admin` |
| **Store Manager** | `manager@adiarifresh.com` | `admin123` | `/login` then `/manager` |
| **Customer** | `customer@example.com` | `admin123` | `/login` |
| **POS Admin** | `admin` | `admin123` | `/shop/login` |
| **POS Staff** | `staff1` | `admin123` | `/shop/login` |

---

# Book II: Process Manuals

## 2.1 Customer Journey (Shopping Process)

### A. Browse & Search
1.  **Landing:** Users arrive at `/` (Home). They see "Weekly Deals", "Fresh Arrivals", and Categories.
2.  **Filtering:** Users click "Products" or specific Categories (e.g., "Halal Meat").
3.  **Search:** The search bar in the header allows finding products by name or description.

### B. Cart Management
1.  **Add to Cart:** Clicking "Add to Cart" sends an AJAX request to `/cart/add`.
2.  **Validation:** System checks `stock_quantity`. If insufficient, an error is shown.
3.  **Cart View:** `/cart` shows items, taxes, and shipping estimates.

### C. Checkout Flow
1.  **Initiation:** User clicks "Checkout". Must be logged in (`auth/login`).
2.  **Form:** User fills Shipping Info (Name, Address, Phone).
3.  **Processing:**
    *   System calculates Subtotal + Tax + Shipping.
    *   Verifies stock *one last time*.
    *   Creates record in `orders` table.
    *   Creates items in `order_items` table.
    *   **Decrements Stock** from `products` table.
    *   Clears user's cart.
4.  **Confirmation:** User is redirected to `/order/{id}` showing their Invoice/Receipt.

## 2.2 Admin Management (Back Office)

### A. Product Management (`/manager/products`)
1.  **Create:** Click "Add Product".
    *   **Required:** Name, SKU (Unique), Price, Category, Stock.
    *   **Image:** Uploads to `public/uploads/products/`.
2.  **Import:** Managers can upload a CSV/Excel file to bulk import items.
3.  **Low Stock Alerts:** Dashboard highlights items where `stock_quantity <= min_stock_level`.

### B. Order Fulfillment (`/manager/orders`)
1.  **View:** See list of all orders. Filter by 'Pending', 'Processing', etc.
2.  **Process:** Click an order ID.
    *   Change status from `Pending` -> `Processing` -> `Shipped`.
    *   Add "Admin Notes" for internal reference.

## 2.3 Shop/POS Operations (In-Store)

### A. Login & Session
*   Staff access `/shop/login`.
*   System creates a secure session.
*   **Dashboard:** Shows today's sales and total inventory value.

### B. Point of Sale (POS) Flow (`/shop/pos`)
1.  **Scan/Search:** Staff uses a barcode scanner (input field focused) or types a name.
    *   System queries `inventory_items` table via AJAX.
2.  **Add to Ticket:** Product appears on the right-side receipt panel.
3.  **Customer (Optional):** Click "Select Customer" to link sale to a profile (Loyalty tracking).
4.  **Checkout:**
    *   Enter "Amount Paid" (Cash).
    *   System calculates "Change Due".
    *   Click "Complete Sale".
5.  **Result:**
    *   Invoice created in `invoices` table.
    *   Stock deducted from `inventory_items`.
    *   Printable receipt opens.

---

# Book III: Technical Developer Guide

## 3.1 File Structure Breakdown
```
adiari_fixed_complete/
├── app/
│   ├── config/database.php    # Smart connection logic
│   ├── controllers/           # Request Handlers
│   │   ├── CartController.php # Shopping logic
│   │   ├── ShopController.php # POS logic
│   ├── models/                # Data Access Layer
│   │   ├── Product.php        # E-commerce products
│   │   ├── ShopInventory.php  # POS products
│   ├── views/                 # Visual Templates
├── public/                    # Web Root
│   ├── index.php              # Entry Point
│   ├── css/                   # Styles
│   ├── js/                    # Scripts
├── routes/
│   └── web.php                # URL Definitions
```

## 3.2 Database Schema (The "Unified" Model)
We use a **Unified Database** approach.
*   **Hostinger Name:** `u314077991_adiari_shop`
*   **Local Name:** `adiari_grocery` (with `inventory`/`analytics` merged in logic)

### Critical Tables
*   `products`: The master catalog for the website.
*   `inventory_items`: The master catalog for the POS (Physical store).
*   *Note: In v3.0, these two tables will be merged completely. Currently, they operate side-by-side to allow separate management of "Online Exclusive" vs "In-Store" stock.*

## 3.3 Routing System (`routes/web.php`)
The router maps URLs to `Controller@method`.
*   **GET /cart** -> `CartController->index()`
*   **POST /checkout/process** -> `CheckoutController->process()`

## 3.4 MVC Flow Example (Viewing a Product)
1.  **User** requests `/product/15`.
2.  **Router** calls `ProductController::show(15)`.
3.  **Controller** calls `ProductModel::getProductDetails(15)`.
4.  **Model** queries SQL database and returns array.
5.  **Controller** passes array to `views/product/show.php`.
6.  **View** renders HTML to user.

---

# Book IV: Deployment & Maintenance

## 4.1 Localhost Installation
1.  **Download:** Unzip project to `xampp/htdocs/adiari`.
2.  **Database:**
    *   Go to `localhost/phpmyadmin`.
    *   Create DB: `adiari_grocery`.
    *   Import: `database/unified_hostinger_setup.sql`.
3.  **Run:** Open browser to `localhost/adiari/public`.

## 4.2 Hostinger Deployment Guide
1.  **File Upload:**
    *   Zip the `adiari_fixed_complete` folder.
    *   Upload to Hostinger File Manager (`public_html`).
    *   Extract. Move `public/*` contents to `public_html/` root if you want the site at the root domain.
2.  **Database:**
    *   Create MySQL Database: `u314077991_adiari_shop`.
    *   User: `u314077991_adiari_shop`.
    *   Password: `Bangladesh12*#`.
    *   Import: `database/unified_hostinger_setup.sql`.
3.  **Configuration:**
    *   **NONE REQUIRED.** `config/database.php` auto-detects Hostinger environment.

## 4.3 Troubleshooting

### "Database Connection Failed"
*   **Check:** Is the database password correct?
*   **Check:** Is the user added to the database with all privileges?
*   **Fix:** Run `php verify_setup.php` via SSH/Terminal to diagnose.

### "404 Not Found" on pages other than Home
*   **Cause:** `.htaccess` missing or RewriteBase incorrect.
*   **Fix:** Ensure `.htaccess` exists in `public/` and `app/`.

## 4.4 Roadmap & Next Steps
1.  **Content:** Delete dummy products. Add real inventory.
2.  **Images:** Replace placeholders with real photos (Use `manager/products` to upload).
3.  **SSL:** Ensure HTTPS is active on Hostinger.
4.  **Launch:** Announce to customers!

---

*Documentation Generated by Antigravity*
