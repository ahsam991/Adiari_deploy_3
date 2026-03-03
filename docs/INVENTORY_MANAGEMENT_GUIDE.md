# 📦 Duo Project: Inventory Management Guide

**Target Audience:** Warehouse Managers, Store Staff.
**System Area:** Online Store (Admin) & In-Store (POS).

---

## 1. Unified Stock Control
**Core Concept:** Your stock (Inventory) is a single, shared resource.

*   **100% Shared:** If you add 10 Apples online, the POS instantly sees 10.
*   **Real-time Deduction:** If a POS sale happens, the online "Quantity" drops immediately.
    *   *Example:* POS sells 2 apples. Online store updates from `10` -> `8`.

---

## 2. Managing Products (Online & Offline)

### 2.1 Adding New Items (Online Default)
1.  Go to **Admin Panel** > **Products**.
2.  Click **Add New Product**.
3.  **Critical Fields:**
    *   `Product Name`: Must be clear (e.g., "Organic Tomato - 1kg").
    *   `Barcode (SKU)`: Scan the item here. This links it to the POS scanner.
    *   `Price`: Set valid retail price (Yen).
    *   `Stock Quantity`: Count your physical stock accurately.
    *   `Category`: Assign correctly (e.g., "Vegetables").
4.  **Save.** It is now live on both the website and the POS.

### 2.2 Updating Stock Levels (Restocking)
1.  Go to **Inventory Manager** (Admin or POS).
2.  Search for the item.
3.  Click "Edit" or use "+" buttons next to quantity.
4.  **Important:** Always count physical stock before updating.

---

## 3. Barcode Best Practices

### 3.1 Scanning Workflow
*   **Device:** Use a standard USB Barcode Scanner (HID Mode). Plug it into the POS computer.
*   **Troubleshooting:** Ensure the scanned number matches the `barcode` field in `inventory_items` database table.

### 3.2 Label Printing (Future Feature)
*   For loose items (e.g., specific vegetables), you may need to print custom barcode stickers soon.
*   *Current Workaround:* Use `POS Search` by name if barcode is missing.

---

## 4. Bulk Operations

### 4.1 Importing Products
1.  Prepare a CSV/Excel file with columns: `Name`, `Barcode`, `Price`, `Stock`.
2.  Go to **Admin** > **Tools** > **Import**.
3.  Upload file.
4.  System will add new items and update stock for existing barcodes.

### 4.2 Low Stock Alerts
*   The dashboard highlights items with `quantity <= 5`.
*   **Action:** Reorder these items immediately to prevent lost sales.
