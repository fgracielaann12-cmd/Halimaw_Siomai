# Halimaw Siomai — Technical Documentation

> **Last Updated:** May 13, 2026  
> **Version:** 2.0  
> **Stack:** CodeIgniter 4 · PHP 8.1 · MySQL/MariaDB · Bootstrap 5.3

---

## 1. Project Overview

**Halimaw Siomai** is a web-based Point of Sale (POS) and Inventory Management System. It supports multi-role access (Admin, Staff), real-time inventory tracking, transactional sales with receipts, stock request workflows, food waste pull-out management, customer returns processing, and a REST API for external ordering platforms.

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
| `pack_small_qty`, `pack_medium_qty`, `pack_biggest_qty` | Siomai pack quantities |
| `pack_small_price`, `pack_medium_price`, `pack_biggest_price` | Pack-specific pricing |
| `expiration_date`, `status`, `auto_delete` | Expiry management (`active`, `expiring_soon`, `expired`, `manually deleted`) |
| `barcode`, `image_path` | Product identification |
| `is_variation_child`, `variation_group_id`, `variation_label` | Size variation system |
| `is_expiring_seen`, `is_expired_seen` | Notification tracking |

### 4.3 `transactions` — Transaction summaries

| Field | Notes |
|---|---|
| `transaction_id` | Format: `OUT-YYYYMMDD-XXXX` (e.g., `OUT-20260513-0042`) |
| `user_id`, `total_amount`, `payment_method` | Sale metadata |
| `customer_name`, `customer_email` | Optional customer info |
| `vat_applied`, `vat_type` | VAT: `included` or `excluded` |

### 4.4 `sales` — Individual sale line items

| Field | Notes |
|---|---|
| `transaction_id` | FK to `transactions` |
| `user_id`, `product_id`, `quantity`, `price`, `total` | Sale details |
| `pack` | Pack type (e.g., `Small Pack`, `6pcs`) |
| `payment_method`, `customer_name`, `customer_email` | Denormalized for quick access |
| `is_seen` | Notification badge tracking |

### 4.5 `pull_outs` — Food waste / pull-out records

| Field | Notes |
|---|---|
| `product_id`, `variation`, `quantity` | What was pulled out |
| `unit_cost`, `total_loss` | Financial impact |
| `pull_out_reason` | `Shortage`, `Spoilage`, `Damaged Packaging` |
| `category` | `Food Waste` or `Customer Return` |
| `reason_note`, `image_path` | Supporting details |
| `reported_by`, `date_reported` | Who reported and when |
| `status` | `PENDING`, `APPROVED`, `REJECTED` |
| `approved_by`, `approved_at` | Admin approval tracking |

### 4.6 `returns` — Customer return records

| Field | Notes |
|---|---|
| `transaction_id`, `item_id`, `variation`, `quantity` | What was returned |
| `reason`, `evidence_path` | Return justification |
| `return_condition` | `RESTOCKABLE` or `NON-RESTOCKABLE` |
| `action_taken` | `RESTOCKED` or `PULL_OUT` |
| `processed_by`, `created_at` | Processing metadata |

### 4.7 `stock_requests` — Staff stock adjustment requests

| Field | Notes |
|---|---|
| `user_id`, `item_id`, `quantity`, `action`, `reason` | Request details |
| `status` | `pending`, `approved`, `rejected` |

### 4.8 `online_orders` — External API orders

| Field | Notes |
|---|---|
| `order_id`, `customer_name`, `customer_email`, `customer_phone` | Customer info |
| `total_amount`, `status`, `created_at` | Order tracking |

### Other Models

- **`AdminModel`** — Admin-specific user queries (`admins` table)
- **`ProductModel`** — Alias for product queries (`items` table)
- **`ItemLogModel`** — Audit trail (`item_logs` table: `item_id`, `old_data`, `new_data`, `updated_by`)
- **`DeletedItemModel`** — Soft-deleted items archive (`deleted_items` table)
- **`StockRequestLogModel`** — Stock request audit trail (`stock_request_logs` table)
- **`OnlineOrderItemModel`** — Line items for online orders (`online_order_items` table)

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

**POS Sell Flow (Admin/Staff):**
1. Validate all cart items and check stock availability
2. Generate unique Transaction ID (`OUT-YYYYMMDD-XXXX`)
3. Insert transaction summary into `transactions` table
4. Batch-insert all line items into `sales` table
5. Batch-update inventory quantities per stock column
6. Optionally send email receipt via Gmail SMTP
7. All wrapped in `$db->transStart()` / `$db->transComplete()`

### 5.4 Inventory Management

| Controller | Route | Purpose |
|---|---|---|
| `Items::index` | `/items/` | Full inventory list with search, filter, sort |
| `Items::add` / `store` | `/items/add`, POST `/items/store` | Add new items with variation support |
| `Items::edit` / `update` | `/items/edit/:id` | Edit item details |
| `Items::delete` | `/items/delete/:id` | Soft delete items |
| `Items::increaseQuantity` | POST `/items/increaseQuantity/:id` | Manual stock increase |
| `Items::decreaseQuantity` | POST `/items/decreaseQuantity/:id` | Manual stock decrease |
| `Items::bulkUpload` | POST `/items/bulk-upload` | CSV/Excel bulk import |
| `Items::exportCsv` | `/items/export-csv` | Chunked CSV export (200 rows/chunk) |
| `Items::exportSalesCsv` | `/items/export-sales-csv` | Sales data CSV export |
| `Items::exportLogsCsv` | `/items/export-logs-csv` | Audit logs CSV export |

**Item Variation System:**
- Parent items have `is_variation_child = 0`
- Child variations have `is_variation_child = 1` and share a `variation_group_id`
- Each child can have its own `variation_label`, price, and quantity
- POS views dynamically group variations for display

### 5.5 Sales & Transactions

| Controller | Route | Purpose |
|---|---|---|
| `SalesController::index` | `/admin/sales` | Sales overview with notification badge clearing |
| `SalesController::transactions` | `/admin/sales/transactions` | Full transaction history |
| `SalesController::getTransactionItems` | GET `/admin/sales/transaction-items/:txn_id` | JSON API: fetch items for a transaction (used by Returns) |
| `SalesController::markViewed` | POST `/admin/sales/mark-viewed` | Batch mark all sales as seen |

The `getTransactionItems` endpoint is also exposed at `/user/sales/transaction-items/:txn_id` for staff access.

### 5.6 Stock Requests

| Controller | Route | Purpose |
|---|---|---|
| `AdminRequests::index` | `/admin/stock-requests` | View all pending/processed stock requests |
| `AdminRequests::approve` | POST `/admin/approve-request/:id` | Approve and apply stock adjustment |
| `AdminRequests::reject` | POST `/admin/reject-request/:id` | Reject request |
| `UserRequestController::submitStockRequest` | POST `/user/submit-stock-request` | Staff submits stock adjustment request |

**Workflow:** Staff submits request → Status `pending` → Admin approves (inventory adjusted) or rejects.

### 5.7 Pull-Outs (Food Waste)

| Controller | Route | Purpose |
|---|---|---|
| `PullOutController::submitPullOut` | POST `/user/submit-pull-out` | Staff reports food waste |
| `PullOutController::index` | `/admin/pull-outs` | Admin views all pull-out records |
| `PullOutController::approve` | POST `/admin/approve-pull-out/:id` | Admin approves → inventory deducted |
| `PullOutController::reject` | POST `/admin/reject-pull-out/:id` | Admin rejects → no inventory change |

**Workflow:**
1. Staff submits pull-out → record created with `status = PENDING`
2. **No inventory deduction at submission time**
3. Admin reviews and clicks **Approve** → inventory deducted + audit log created → `status = APPROVED`
4. Or Admin clicks **Reject** → `status = REJECTED`, no inventory change

**Standardized Reasons:** `Shortage`, `Spoilage`, `Damaged Packaging`

### 5.8 Customer Returns

| Controller | Route | Purpose |
|---|---|---|
| `ReturnsController::index` | `/admin/returns` | Admin returns dashboard with metrics |
| `ReturnsController::processReturn` | POST `/user/submit-return` | Staff/Admin processes a return |

**Return Processing Flow:**
1. Staff enters Transaction ID → system fetches items from that transaction via AJAX
2. Staff selects item, quantity, reason, and evaluates condition
3. **If RESTOCKABLE:** Quantity is added back to inventory immediately + audit logged
4. **If NON-RESTOCKABLE:** A `PENDING` pull-out record is auto-generated (category: `Customer Return`) for admin review
5. All operations are atomic (database transaction)

**Key Metrics (Admin Dashboard):**
- Total Returns count
- Return Rate (returns / total transactions × 100)
- Pull-Out Rate (non-restockable / total returns × 100)
- Financial Loss (sum of approved pull-outs with category `Customer Return`)

### 5.9 External API

| Controller | Route | Purpose |
|---|---|---|
| `ApiController::getProducts` | GET `/api/products` | Public product listing with CORS |
| `ApiController::submitOrder` | POST `/api/submit-order` | Atomic order submission with stock validation |
| `ApiController::getPendingOrders` | GET `/api/pending-orders` | Fetch unconfirmed orders |
| `ApiController::confirmOrder` | POST `/api/confirm-order` | Confirm and finalize order |
| `CustomerOrderController::index` | GET `/order` | Customer-facing order page |

### 5.10 System Maintenance

| Controller | Route | Purpose |
|---|---|---|
| `Cron::checkExpiry` | CLI/scheduled | Batch expiry sweep: marks `expiring_soon` (≤10 days) and `expired` items in 200-row chunks |
| `Setup::index` | GET `/setup-db` | Database schema sync and initialization |
| `UserManagement` | `/admin/staff/users/*` | CRUD for staff accounts |

---

## 6. Routing Architecture

### Route Groups

| Group | Prefix | Auth Required | Description |
|---|---|---|---|
| Auth | `/` | No | Login, register, logout |
| User | `/user/*` | Staff login | Dashboard, POS, pull-outs, returns |
| Admin | `/admin/*` | Admin login | Full system access |
| Admin > Staff | `/admin/staff/*` | Admin login | Staff POS, user management |
| Items | `/items/*` | Admin login | Inventory CRUD |
| API | `/api/*` | None (CORS) | External ordering platform |

### Security

- **Auto-routing disabled** (`$routes->setAutoRoute(false)`)
- **Role-based guards** via `BaseController::checkLogin($role)`
- **CSRF protection** on all POST forms
- **Back-button protection** via `Cache-Control: no-store` headers + `enforceClientAuth()` JS
- **AJAX validation** via `X-Requested-With: XMLHttpRequest` header checks

---

## 7. User Roles & Access

| Feature | Admin | Staff |
|---|---|---|
| Admin Dashboard | ✅ | ❌ |
| Staff Dashboard (Inventory View) | ❌ | ✅ |
| Admin POS | ✅ | ❌ |
| Staff POS | ✅ | ✅ |
| User POS | ❌ | ✅ |
| Manage Inventory (CRUD) | ✅ | ❌ (View only) |
| Submit Stock Requests | ✅ | ✅ |
| Approve/Reject Stock Requests | ✅ | ❌ |
| Submit Pull-Outs | ✅ | ✅ (→ Pending) |
| Approve/Reject Pull-Outs | ✅ | ❌ |
| Process Customer Returns | ✅ | ✅ |
| View Sales & Transactions | ✅ | ❌ |
| Manage Staff Accounts | ✅ | ❌ |
| View Audit Logs | ✅ | ✅ |
| CSV/Excel Export | ✅ | ❌ |
| External API Access | Public | Public |

---

## 8. Key Business Logic

### 8.1 Transaction ID Generation
All POS sales generate a unique Transaction ID: `OUT-YYYYMMDD-XXXX` (e.g., `OUT-20260513-0042`). This ID links the `transactions` summary record to individual `sales` line items.

### 8.2 Siomai Pack System
Siomai products use a separate quantity/pricing system:
- **Small Pack** → `pack_small_qty` / `pack_small_price`
- **Medium Pack** → `pack_medium_qty` / `pack_medium_price`
- **Large Pack** → `pack_biggest_qty` / `pack_biggest_price`

Non-siomai items use the standard `quantity` / `price` fields.

### 8.3 Batch Processing Pattern
All critical write operations use atomic database transactions:
```php
$db->transStart();
// ... batch inserts / updates ...
$db->transComplete();
if ($db->transStatus() === false) {
    $db->transRollback();
    throw new \Exception('Transaction failed.');
}
```
Methods using this pattern: `PosController::sell`, `UserPosController::sell`, `PullOutController::submitPullOut`, `ReturnsController::processReturn`, `ApiController::submitOrder`, `SalesController::markViewed`, `Cron::checkExpiry`.

### 8.4 VAT Handling (Admin POS)
- **VAT Included:** Total already includes 12% VAT → `vatableSales = total / 1.12`
- **VAT Excluded:** 12% VAT added on top → `grandTotal = total × 1.12`

### 8.5 Email Receipts
Admin POS supports emailing HTML receipts via Gmail SMTP (configured in `.env`). Email failures do not roll back the sale — they are logged and appended as a warning to the success response.

---

## 9. Views Reference

### Admin Views
| View | Path | Description |
|---|---|---|
| Admin Dashboard | `admin/dashboard.php` | Inventory analytics, charts |
| Pull-Outs | `admin/pull_outs.php` | Pull-out table with approve/reject modals |
| Returns | `admin/returns.php` | Returns dashboard with metrics and process return modal |

### Staff/User Views
| View | Path | Description |
|---|---|---|
| Staff Dashboard | `user/dashboard.php` | Inventory view + modals for pull-outs, returns, stock requests |
| Deleted Items | `user/deleted-items.php` | Soft-deleted items archive |
| Expiring Soon | `user/expiring-soon.php` | Items nearing expiration |

### POS Views
| View | Path | Description |
|---|---|---|
| Admin POS | `pos/index.php` | Full-featured POS with VAT, email, payment methods |
| Staff POS | `pos/staff.php` | POS with pull-out and return modals |

### Inventory Views
| View | Path | Description |
|---|---|---|
| Item List | `items/list.php` | Full inventory table with filters |
| Add Item | `items/add.php` | New item form with variation support |
| Edit Item | `items/edit.php` | Item editing |
| Logs | `items/logs.php` | Audit trail viewer |
| Stock Requests | `items/stock_requests.php` | Admin stock request management |

### Shared Partials
| Partial | Path | Description |
|---|---|---|
| Admin Sidebar | `partials/admin_sidebar.php` | Navigation sidebar with badge notifications |
| Header | `partials/header.php` | Page header template |
| Footer | `partials/footer.php` | Page footer template |

---

## 10. UI & Design System

### Design Tokens
| Token | Value |
|---|---|
| Primary Color | `#4e73df` |
| Sidebar BG | `#2c3e50` |
| Success | `#1cc88a` |
| Danger | `#e74a3b` |
| Warning | `#f6c23e` |
| Border Radius | `12px` (unified across all elements) |
| Font Family | Poppins, system fallbacks |

### Key UI Features
- **Unified 12px border-radius** on all cards, buttons, inputs, tables, modals
- **Sticky table headers** for scroll visibility
- **Mobile responsive** sidebar with hamburger toggle and overlay
- **Startup animations** using CSS `@keyframes` (fadeSlideDown, fadeSlideUp, fadeScaleUp)
- **Select2** dropdowns for item selection in modals
- **Real-time search/filter/sort** on inventory tables (client-side JS)
- **Notification badges** on sidebar for unviewed sales and pending requests
- **Back-button protection** prevents post-logout data exposure

---

## 11. Deployment & Maintenance

### Environment Setup
1. Install XAMPP (Apache + MySQL + PHP 8.1+)
2. Clone project to `htdocs/Halimaw_Siomai/`
3. Run `composer install`
4. Copy `.env.example` to `.env` and configure database credentials
5. Navigate to `/setup-db` to initialize the database schema
6. Access at `http://localhost/Halimaw_Siomai/`

### Email Configuration (`.env`)
```ini
email.protocol = smtp
email.SMTPHost = smtp.gmail.com
email.SMTPUser = your-email@gmail.com
email.SMTPPass = your-app-password
email.SMTPPort = 465
email.SMTPCrypto = ssl
```

### Scheduled Tasks
The `Cron::checkExpiry` method should be called periodically to update item statuses:
- Marks items as `expiring_soon` (within 10 days of expiration)
- Marks items as `expired` (past expiration date)
- Auto-deletes expired items when `auto_delete` is enabled
- Processes in chunks of 200 rows for memory efficiency

### Logging
- Application logs: `writable/logs/`
- POS errors logged via `log_message('error', ...)`
- Item changes tracked in `item_logs` table with old/new data JSON

---

## 12. Appendix: Complete Route Map

```
AUTH
  GET  /                               → Auth::login
  GET  /login                          → Auth::login
  POST /authenticate                   → Auth::authenticate
  GET  /logout                         → Auth::logout
  GET  /register                       → Auth::register
  POST /register/save                  → Auth::save

USER (Staff)
  GET  /user/dashboard                 → UserDashboard::index
  GET  /user/dashboard/expired         → UserDashboard::expired
  GET  /user/dashboard/expiring-soon   → UserDashboard::expiringSoon
  GET  /user/dashboard/deleted-items   → UserDashboard::deleted
  GET  /user/dashboard/logs            → UserDashboard::logs
  POST /user/submit-stock-request      → UserRequestController::submitStockRequest
  POST /user/submit-pull-out           → PullOutController::submitPullOut
  POST /user/submit-return             → ReturnsController::processReturn
  GET  /user/sales/transaction-items/X → SalesController::getTransactionItems
  GET  /user/dashboard/faqs            → UserDashboard::faqs
  GET  /user/pos                       → UserPosController::index
  POST /user/pos/sell                  → UserPosController::sell

ADMIN
  GET  /admin/dashboard                → Items::dashboard
  GET  /admin/sales                    → SalesController::index
  GET  /admin/sales/transactions       → SalesController::transactions
  POST /admin/sales/mark-viewed        → SalesController::markViewed
  GET  /admin/sales/transaction-items/X→ SalesController::getTransactionItems
  GET  /admin/stock-requests           → AdminRequests::index
  POST /admin/approve-request/:id      → AdminRequests::approve
  POST /admin/reject-request/:id       → AdminRequests::reject
  GET  /admin/pull-outs                → PullOutController::index
  POST /admin/approve-pull-out/:id     → PullOutController::approve
  POST /admin/reject-pull-out/:id      → PullOutController::reject
  GET  /admin/returns                  → ReturnsController::index
  GET  /admin/pos                      → PosController::adminIndex
  POST /admin/pos/sell                 → PosController::sell
  GET  /admin/staff/pos                → PosController::staffIndex
  POST /admin/staff/pos/sell           → PosController::sell
  GET  /admin/staff/users              → UserManagement::index
  POST /admin/staff/users/save         → UserManagement::save
  GET  /admin/stock-request-logs       → AdminRequests::logs

ITEMS (Admin)
  GET  /items/                         → Items::index
  GET  /items/add                      → Items::add
  POST /items/store                    → Items::store
  GET  /items/edit/:id                 → Items::edit
  POST /items/update/:id              → Items::update
  POST /items/delete/:id              → Items::delete
  POST /items/bulk-upload              → Items::bulkUpload
  GET  /items/export-csv               → Items::exportCsv
  GET  /items/export-sales-csv         → Items::exportSalesCsv
  GET  /items/export-logs-csv          → Items::exportLogsCsv
  GET  /items/logs                     → Items::logs
  GET  /items/expired                  → Items::expired
  GET  /items/expiring-soon            → Items::expiringSoon

API (Public)
  GET  /api/products                   → ApiController::getProducts
  POST /api/submit-order               → ApiController::submitOrder
  GET  /api/pending-orders             → ApiController::getPendingOrders
  POST /api/confirm-order              → ApiController::confirmOrder

OTHER
  GET  /order                          → CustomerOrderController::index
  GET  /setup-db                       → Setup::index
```
