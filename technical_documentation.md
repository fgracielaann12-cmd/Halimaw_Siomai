# Halimaw POS Inventory System — Technical Documentation

> **Last Updated:** May 14, 2026  
> **Version:** 2.2  
> **Stack:** CodeIgniter 4 · PHP 8.1 · MySQL/MariaDB · Bootstrap 5.3

---

## 1. Project Overview

**Halimaw POS Inventory System** is a web-based Point of Sale (POS) and Inventory Management System. It supports multi-role access (Admin, Staff), real-time inventory tracking, transactional sales with receipts, stock request workflows, food waste pull-out management, customer returns processing, and a REST API for external ordering platforms.

### Branding & Identity
- **App Name:** Halimaw POS Inventory System
- **Primary Logo:** `public/Images/Inventa.png`
- **Theme:** Professional Dark Sidebar with Unified 12px Radius.

### Technology Stack

| Layer | Technology |
|---|---|
| Framework | CodeIgniter 4 (MVC) |
| Language | PHP 8.2 |
| Database | MySQL / MariaDB (MySQLi driver) |
| Frontend | HTML5, Vanilla CSS, JavaScript (ES6+) |
| UI Framework | Bootstrap 5.3, Bootstrap Icons, Select2 4.1 |
| Fonts | Google Fonts (Poppins) |
| Libraries | `phpoffice/phpspreadsheet` (CSV/Excel export) |
| Server | Apache (XAMPP) |

---

## 2. System Architecture

The application follows the **Model-View-Controller (MVC)** pattern.

### Directory Structure

```
Halimaw_Siomai/
├── app/
│   ├── Config/          # Routes.php, Filters.php, Database.php
│   ├── Controllers/     # AdminRequests, Items, PullOutController, etc.
│   ├── Models/          # ItemModel, PullOutModel, etc.
│   ├── Views/
│   │   ├── admin/       # dashboard.php, pull_outs.php, returns.php
│   │   ├── auth/        # login.php
│   │   ├── customer/    # order.php
│   │   ├── items/       # add, edit, list.php
│   │   ├── pos/         # staff.php
│   │   └── user/        # dashboard.php
├── public/              # index.php, JS, images, uploads/
├── writable/            # Cache, logs, sessions
└── .env                 # Environment configuration
```

---

## 3. Database Updates

### Items Table Schema (Recent Additions)
The `items` table has been extended to support variations and improved data integrity:
- `sku` (VARCHAR, Unique Index)
- `is_variation_child` (TINYINT)
- `variation_group_id` (VARCHAR)
- `variation_label` (VARCHAR)
- `image_path` (VARCHAR)

Database synchronization is managed by `Setup::index()` (`/setup-db` route), which includes schema checks to ensure these columns exist.

---

## 4. Key Features & Recent Updates

### 4.1 Auto-FIFO Sorting
Inventory is automatically sorted based on expiration urgency (FIFO).
- **Sorting Logic:** Query uses a `CASE` statement to assign `expiry_priority`:
  - 0: Expiring TODAY
  - 1: Expiring Soon (<= 10 days)
  - 2: Active
  - 3: Non-Expirable
  - 4: Expired
- **UI:** The inventory list (`items/list.php` and `user/dashboard.php`) defaults to FIFO sorting.
- **Visuals:** Rows are color-coded (Red for Expired, Yellow for Expiring Soon/Today).

### 4.2 Dynamic Item Labels
Inventory modals and dropdowns (Stock Adjustment, Pull-Outs, Returns) now use a dynamically generated `display_label` derived from SQL `CONCAT`:
- `(name, ' — ', variation_label, ' (', product_id, ')')` for variations.
- `(name, ' (', product_id, ')')` for standard items.
- This eliminated legacy "N/A" placeholders.

### 4.3 Value % Calculation
Inventory tables display a "Value %" column:
- **Calculation:** `(item_price / max_price_in_inventory) * 100` (formatted to 1 decimal).
- **Fetch:** `Items::index()` pre-calculates `$maxPrice` via SQL `selectMax`.

### 4.4 Non-Expirable Items
- **Storage:** Saved with `NULL` expiration dates and `auto_delete = 0`.
- **UI:** Displayed as "Active" with a green badge; expiration date column shows "Non-Expirable".

### 4.5 Route Fixes
- Standardized routes to include `/index.php/` prefix in `base_url` for form actions and redirects (e.g., `/items/store`, `/authenticate`) to prevent 404 errors in specific server configurations.

---

## 5. Maintenance

### Scheduled Tasks
The `Cron::checkExpiry` method updates item statuses.

### Route Cache
If routing issues (404s) persist after configuration changes, clear `writable/cache/` (e.g., `Get-ChildItem -Path writable\cache\ -Recurse | Remove-Item -Force`).

---
*(Refer to version 2.0/2.1 for unchanged legacy route maps and schema details.)*
