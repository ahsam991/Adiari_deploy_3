STOP! READ THIS FIRST.

=======================================================
 ADI ARI FRESH - FINAL DEPLOYMENT PACKAGE
=======================================================

This folder contains the complete, error-free code for your Unified Grocery & POS System.

---
STEP 1: DATABASE SETUP (Hostinger)
---
1. Go to Hostinger -> Databases -> phpMyAdmin.
2. Select your database (`u314077991_adiari_shop`).
3. Import the file: `database/unified_hostinger_setup.sql`
   - This file now includes AUTO-SYNC TRIGGERS.
   - It will create both Online Store and POS tables.
   - It will set up the default Admin/Staff accounts.

---
STEP 2: FILE UPLOAD
---
1. Go to Hostinger -> File Manager -> public_html.
2. Upload EVERYTHING in this folder EXCEPT:
   - [.git]
   - [.DS_Store]
   - [docs] (You can keep this for reference, but not needed for the site to run)
   
3. **CRITICAL:** Ensure `public_html` looks like this:
   /app
   /config
   /public
   /index.php
   /.htaccess
   ...etc...

---
STEP 3: CONFIGURATION
---
1. Open `config/database.php` on Hostinger.
2. Ensure the credentials match your Hostinger Database:
   - DB_HOST: localhost
   - DB_NAME: u314077991_adiari_shop
   - DB_USER: (Your Hostinger DB User)
   - DB_PASS: (Your Hostinger DB Password)
   
---
STEP 4: LOGINS
---
- Online Admin Panel:  https://adiari.shop/admin/login
  User: admin@adiarifresh.com
  Pass: admin123

- POS System:          https://adiari.shop/shop/login
  User: admin
  Pass: admin123
  
=======================================================
DOCUMENTATION
=======================================================
Detailed manuals are now in the `docs/` folder:
- PROJECT_BLUEPRINT.md (Architecture & Features)
- POS_OPERATIONS_MANUAL.md (How to use the scanner)
- INVENTORY_MANAGEMENT_GUIDE.md (How to manage stock)

Your system is now ready. 🚀
