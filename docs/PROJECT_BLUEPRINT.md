# 🌐 The Unified "Duo Project" Blueprint
**ADI ARI Fresh: Grocery & POS Ecosystem**

## 1. Executive Summary
The "Duo Project" is the strategic unification of two previously separate systems into a single, cohesive retail platform.
1.  **Online Store (E-commerce):** Serves home-delivery customers.
2.  **In-Store System (POS):** Serves walk-in customers and staff.

**Core Philosophy:** "One Database, Two Fronts."
Regardless of where a sale happens (online or offline), the inventory, sales records, and customer data are synchronized instantly because they live in the same database tables.

---

## 2. System Architecture

### 2.1 The "Bridge" Concept
The system uses a shared database architecture to bind the two worlds together.

| Feature | Online Store (Customer) | In-Store POS (Staff) | Shared Resource (The "Bridge") |
| :--- | :--- | :--- | :--- |
| **Product Catalog** | Displays Title, Image, Price | Displays Title, Barcode, Price | `inventory_items` Table |
| **Inventory** | Checks `quantity` before Add-to-Cart | Checks `quantity` before Scan | Real-time Stock Counter |
| **Sales** | Records to `orders` table | Records to `invoices` table | Financial Reporting |
| **Users** | `users` table (Email/Pass) | `shop_users` table (Username/Pass) | Separate Auth, Same DB |

### 2.2 Database Binding
*   **File:** `app/core/ShopDatabase.php`
*   **Function:** This class acts as the "glue". It connects the POS logic (`ShopController`) to the main application's database configuration (`config/database.php`).
*   **Result:** You do not need to maintain two databases. `adiari_grocery` (Local) and `u314077991_adiari_shop` (Hostinger) serve both systems simultaneously.

---

## 3. Feature Matrix (Implemented vs. Upcoming)

### ✅ Implemented Features (Ready to Use)
These features are confirmed working in the current `adiari_fixed_complete` build.

#### 🛍️ E-commerce (Online)
*   **Customer Auth:** Login/Register/Logout.
*   **Product Browsing:** Category filters, Search bar.
*   **Shopping Cart:** Add/Remove items, dynamic tax calculation.
*   **Checkout:** Shipping address form, Order creation.
*   **Order Tracking:** "My Orders" page showing Status (Pending/Shipped).

#### 🏪 POS (In-Store)
*   **Staff Auth:** Secure login for Managers/Cashiers.
*   **Barcode Scanning:**
    *   **Hardware:** Compatible with USB Barcode Scanners (acts as keyboard input).
    *   **Software:** Smart search finds products by Barcode OR Name.
*   **Real-time Stock:**
    *   Scanning an item *instantly* checks availability.
    *   Completing a sale *instantly* deducts stock (preventing online overselling).
*   **Receipts:** Generates printable HTML invoices.

#### 🔧 Management (Back Office)
*   **Product Manager:** Add/Edit/Delete products.
*   **Order Manager:** View online orders, change status to "Shipped".
*   **Inventory Manager:** Quick-edit stock levels.

### 🚀 Upcoming Features (Roadmap)
These are planned for future updates to enhance the "Duo" experience.

1.  **Shared Loyalty Program:**
    *   *Goal:* A customer buys online and earns points. They walk into the store, give their phone number, and redeem those points.
    *   *Status:* Database structure exists (`customers` table), logic needs unification.
2.  **Advanced Analytics Dashboard:**
    *   *Goal:* A single graph showing "Online vs Offline" sales performance side-by-side.
    *   *Status:* Separate reports exist; need merging.
3.  **Customer Kiosk Mode:**
    *   *Goal:* A tablet interface for customers to self-checkout in-store.
    *   *Status:* Conceptual.

---

## 4. Technical Deep Dive

### 4.1 How Barcode Scanning Works
*   **Location:** `ShopController::posSearchProduct()`
*   **Logic:**
    1.  POS Input field receives text (e.g., "123456789").
    2.  AJAX request sent to server.
    3.  Server queries `SELECT * FROM inventory_items WHERE barcode = '123456789'`.
    4.  If found -> Returns JSON product data.
    5.  Frontend Javascript adds item to the "Ticket" list.

### 4.2 Checkout Tracking
*   **Location:** `OrderController` & `ManagerController`
*   **Logic:**
    1.  Customer places order -> `status = 'pending'`.
    2.  Manager sees order in Admin Panel.
    3.  Manager checks items, packs box.
    4.  Manager clicks "Mark as Shipped" -> `status = 'shipped'`.
    5.  Customer visits "My Orders" -> Sees "Shipped".

---

## 5. Summary
The **Duo Project** is not two separate websites. It is **one robust engine** driving two different vehicles. By maintaining this unified architecture, you ensure that your business scales efficiently without data conflicts.
