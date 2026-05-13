# Halimaw POS Inventory System — Technical Documentation

> **Last Updated:** May 13, 2026  
> **Version:** 2.1  
> **Stack:** CodeIgniter 4 · PHP 8.1 · MySQL/MariaDB · Bootstrap 5.3

---

## 1. Project Overview

**Halimaw POS Inventory System** (formerly Halimaw Siomai) is a web-based Point of Sale (POS) and Inventory Management System. It supports multi-role access (Admin, Staff), real-time inventory tracking, transactional sales with receipts, stock request workflows, food waste pull-out management, customer returns processing, and a REST API for external ordering platforms.

### Branding & Identity
- **App Name:** Halimaw POS Inventory Siomai (UI) / Halimaw POS Inventory System (System)
- **Primary Logo:** `public/Images/Inventa.png`
- **Theme:** Professional Dark Sidebar with Unified 12px Radius.

### Technology Stack

| Layer | Technology |
|---|---|
| Framework | CodeIgniter 4 (MVC) |
| Language | PHP 8.1+ |
| Database | MySQL / MariaDB (MySQLi driver) |
| Frontend | HTML5, Vanilla CSS, JavaScript (ES6+) |
| UI Framework | Bootstrap 5.3, Bootstrap Icons, Select2 4.1 |
| Fonts | Google Fonts (Poppins) |
| Libraries | `phpoffice/phpspreadsheet` (CSV/Excel export) |
| Server | Apache (XAMPP) |

---

## 2. System Architecture

The application follows the **Model-View-Controller (MVC)** pattern with explicit routing (auto-routing disabled).

### Directory Structure

```
Halimaw_Siomai/
├── app/
│   ├── Config/          # Routes.php, Filters.php, Database.php
│   ├── Controllers/     # 21 controllers (see §5)
│   ├── Models/          # 15 models (see §4)
│   ├── Views/
│   │   ├── admin/       # dashboard.php, pull_outs.php, returns.php
│   │   ├── auth/        # login.php, adminlogin.php, register.php
│   │   ├── customer/    # Customer-facing order page
│   │   ├── items/       # add, edit, list, logs, stock_requests, etc.
│   │   ├── partials/    # admin_sidebar.php, header.php, footer.php
│   │   ├── pos/         # index.php (Admin POS), staff.php (Staff POS)
│   │   ├── sales/       # list.php, transactions.php
│   │   └── user/        # dashboard.php, deleted-items.php, expiring-soon.php
│   └── Filters/         # AuthFilter, AdminAuthFilter, RoleFilter
├── public/              # index.php, CSS, JS, images, uploads/
├── vendor/              # Composer dependencies
├── writable/            # Cache, logs, sessions
└── .env                 # Environment configuration
```

---

## 3. Database Configuration

| Setting | Value |
|---|---|
| Database Name | `halimawsiomai` |
| Hostname | `localhost` |
| Username | `root` |
| Port | `3306` |
| Driver | MySQLi |

Configured via `.env` file. The `/setup-db` route provides database schema synchronization.

---

## 4. Database Schema (Models)

### 4.1 `users` — User accounts

| Field | Notes |
|---|---|
| `id`, `username`, `email`, `password`, `role` | Role: `admin` or `user` (staff) |
| `created_at`, `updated_at` | Auto-managed timestamps |

- Passwords are auto-hashed via `beforeInsert`/`beforeUpdate` callbacks.
- Login supports username or email via `findByLogin()`.

### 4.2 `items` — Product inventory

| Field | Notes |
|---|---|
| `id`, `product_id`, `name`, `sku` | Core identification |
| `quantity`, `price`, `category`, `subcategory` | Stock & classification |
| `pack_small_qty`, `pack_medium_qty`, `pack_biggest_qty` | Legacy Siomai pack quantities |
| `pack_small_price`, `pack_medium_price`, `pack_biggest_price` | Legacy pack-specific pricing |
| `expiration_date`, `status`, `auto_delete` | Expiry management (`active`, `expiring_soon`, `expired`) |
| `barcode`, `image_path` | Product identification |
| `is_variation_child`, `variation_group_id`, `variation_label` | **Enhanced Variation System** (see §8.6) |
| `is_expiring_seen`, `is_expired_seen` | Notification tracking |

---

## 5. Controllers Reference

### 5.1 Authentication

| Controller | Purpose |
|---|---|
| `Auth` | Unified login (`/login`), registration (`/register`), logout |
| `AdminAuth` | Admin-specific login (`/admin/login`) |
| `UserAuth` | User/Staff login helpers |
| `BaseController` | Session init, `checkLogin()`, `checkAdmin()`, `checkUser()`, redirect-if-logged-in |

### 5.2 Dashboards

| Controller | Route | Purpose |
|---|---|---|
| `Items::dashboard` | `/admin/dashboard` | Admin dashboard with inventory analytics |
| `UserDashboard::index` | `/user/dashboard` | Staff dashboard with inventory view, pull-out modal, return modal |

### 5.3 Point of Sale

| Controller | Route | Purpose |
|---|---|---|
| `PosController::adminIndex` | `/admin/pos` | Admin POS with VAT, email receipts |
| `PosController::staffIndex` | `/admin/staff/pos` | Staff POS (same sell logic as Admin) |
| `PosController::sell` | POST `/admin/pos/sell` | Atomic batch sale — creates `transactions` + `sales` records + deducts inventory |
| `UserPosController::index` | `/user/pos` | User POS (simplified) |
| `UserPosController::sell` | POST `/user/pos/sell` | Creates `transactions` + batch `sales` records + deducts inventory |

**POS UI Logic:**
- Variation labels are displayed exactly as entered in the admin (e.g., "Small", "Large").
- Hardcoded " Pack" suffix has been removed for a cleaner interface.

### 5.4 Inventory Management

| Controller | Route | Purpose |
|---|---|---|
| `Items::index` | `/items/` | Full inventory list with search, filter, sort |
| `Items::add` / `store` | `/items/add`, POST `/items/store` | Add new items with variation support |
| `Items::edit` / `update` | `/items/edit/:id` | Edit item details |
| `Items::delete` | `/items/delete/:id` | Soft delete items |
| `Items::bulkUpload` | POST `/items/bulk-upload` | CSV/Excel bulk import |
| `Items::exportCsv` | `/items/export-csv` | Chunked CSV export (200 rows/chunk) |

---

## 8. Key Business Logic

### 8.1 Transaction ID Generation
All POS sales generate a unique Transaction ID: `OUT-YYYYMMDD-XXXX` (e.g., `OUT-20260513-0042`).

### 8.2 Product ID Generation (`getNextProductId`)
The system auto-suggests the next numeric Product ID (e.g., `P001`, `P002`).
- The logic strips variation suffixes (e.g., `P001-SML`) to find the true maximum base ID.
- The field is **fully editable** in the UI, allowing users to override the suggestion.
- Manual collision checks are performed for both the parent ID and all potential variation IDs before insertion.

### 8.3 Database Transactions & Error Logging
All critical write operations use atomic database transactions with detailed error logging.
```php
try {
    $db->transStart();
    // ... logic ...
    $db->transComplete();
    if ($db->transStatus() === false) {
        $error = $db->error();
        log_message('error', 'Transaction failed: ' . json_encode($error));
        throw new \Exception('Database transaction failed: ' . ($error['message'] ?? ''));
    }
} catch (\Exception $e) {
    $db->transRollback();
    log_message('error', 'Exception: ' . $e->getMessage());
}
```

### 8.6 Item Variation System (Refactored)
Items can be added as single entries or with **Size Variations**.
- **Suffix Generation:** Uses a label-based suffixing algorithm (e.g., "Small" → `SML`, "Extra Large" → `EL`).
- **Storage:** Child rows store the base product name in the `name` field and the specific size in `variation_label`.
- **Validation:** Performs per-child uniqueness checks for both `product_id` and `sku` before starting a transaction.
- **Grouped POST:** The Add Item view sends variations as a grouped array `variations[idx][field]` for better data integrity.

---

## 10. UI & Design System

### Design Tokens
| Token | Value |
|---|---|
| Primary Color | `#4e73df` |
| Sidebar BG | `#2c3e50` |
| Border Radius | `12px` (unified across all elements) |
| Brand Name | HALIMAW POS INVENTORY SYSTEM |

### Key UI Features
- **Unified 12px border-radius** on all cards, buttons, inputs, tables, modals.
- **Editable Product ID** with auto-suggested next ID.
- **Clean POS labels** with no hardcoded suffixes.
- **Mobile responsive** sidebar with hamburger toggle and overlay.

---

## 11. Maintenance

### Scheduled Tasks
The `Cron::checkExpiry` method should be called periodically to update item statuses.

### Logging
- Application logs: `writable/logs/` (logs detailed database errors for failed transactions).
- Item changes tracked in `item_logs` table.

---
*(Refer to version 2.0 for unchanged legacy route maps and schema details.)*
