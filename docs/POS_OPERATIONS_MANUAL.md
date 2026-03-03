# 🛒 Duo Project: POS Operations Manual

**Target Audience:** Store Managers, Cashiers, Staff.
**System Area:** In-Store Point of Sale (POS).

---

## 1. Getting Started

### 1.1 Accessing the POS
1.  Open Chrome/Edge on the store tablet or PC.
2.  Navigate to: `https://adiari.shop/shop/login` (or `localhost/adiari/shop/login`).
3.  **Login:** Enter your assigned username (e.g., `staff1`) and password.

### 1.2 Dashboard Overview
Once logged in, you will see:
*   **Today's Sales:** Total Yen amount sold since midnight.
*   **Quick Actions:** Large buttons for "New Sale", "Inventory", "Customers".

---

## 2. Processing a Sale (The Core Workflow)

### Step 1: Add Items to Ticket
There are two ways to add an item:
*   **Method A: Barcode Scan (Fastest)**
    1.  Click the "Barcode/Search" input field to focus it.
    2.  Scan the product with your USB scanner.
    3.  *Beep!* The item is automatically added to the list.
*   **Method B: Manual Search**
    1.  Type the product name (e.g., "Tomato").
    2.  Select the correct item from the dropdown list.

### Step 2: Adjust Quantity
*   If a customer buys 5 of the same item, scan it 5 times OR click the item in the list and change "Qty" to 5.

### Step 3: Identify Customer (Optional)
*   If the customer is a member, click "Select Customer".
*   Search by Name or Phone Number.
*   *Benefit:* This saves the order to their history for future loyalty rewards.

### Step 4: Checkout & Payment
1.  Verify the **Total Amount**.
2.  Ask customer for payment.
3.  Enter the **Amount Paid** (Cash).
4.  The system calculates **Change Due**.
5.  Click **"Complete Sale"**.

### Step 5: Receipt
*   A receipt preview appears.
*   Click "Print" to give a physical copy, or "Email" to send it digitally (if customer attached).

---

## 3. End-of-Day Procedures

### 3.1 Z-Report (Daily Closing)
1.  Go to **Sales History**.
2.  Review all transactions for the day.
3.  Verify cash in drawer matches "Today's Sales" total.
4.  Logout to secure the terminal.

---

## 4. Troubleshooting Common Issues

**Problem:** "Product Not Found" when scanning.
*   **Solution:** The barcode might be missing in the system. Go to *Inventory > Add New*, scan the barcode there to register it.

**Problem:** "Insufficient Stock" error.
*   **Solution:** The system thinks you have 0. Physically count the item, then update the quantity in *Inventory Manager*.
