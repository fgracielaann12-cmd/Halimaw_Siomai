# Halimaw Siomai - Technical Documentation

## 1. Project Overview
**Halimaw Siomai** is a web-based comprehensive Point of Sale (POS) and Inventory Management System. It is designed to handle multiple user roles, manage product stock, process point-of-sale transactions, handle stock requests, manage pull-outs and returns, and provide an API for an external online ordering platform.

- **Framework:** CodeIgniter 4
- **Language:** PHP 8.1+
- **Database:** MySQL / MariaDB (MySQLi driver)
- **Frontend Stack:** HTML, CSS (Vanilla), JavaScript, Bootstrap 5.3, Bootstrap Icons
- **Key Libraries:** `phpoffice/phpspreadsheet` (for CSV/Excel exports)

## 2. System Architecture

The application strictly follows the Model-View-Controller (MVC) architectural pattern:
- **Models (`app/Models/`):** Handles data manipulation, database queries, and business logic related to entities.
- **Views (`app/Views/`):** Contains the HTML templates and UI components.
- **Controllers (`app/Controllers/`):** Handles incoming HTTP requests, interacts with models, and renders the appropriate views.

### Directory Structure
- `app/`: Contains the core application code (Models, Views, Controllers, Config).
- `public/`: The document root of the application containing `index.php`, CSS, JS, and image assets.
- `vendor/`: Composer dependencies.
- `writable/`: Directory for cache, logs, session files, and uploads.
- `tests/`: PHPUnit test files.

## 3. Database Configuration
The database configuration is managed primarily through the `.env` file for environments.
- **Database Name:** `halimawsiomai`
- **Hostname:** `localhost`
- **Username:** `root`
- **Port:** `3306`
- **Driver:** MySQLi

The system also includes a `Setup` controller (`/setup-db`) for database synchronization and initialization.

## 4. User Roles and Access Levels

The system supports multiple user roles, managed through various authentication controllers and route groups:

1. **Admin:** Full access to the system. Can manage inventory, view all sales, handle stock requests, manage users/staff, and access the Admin POS.
2. **Staff:** Managed via the `UserManagement` controller. Has access to the Staff POS, customer returns submission, and a dedicated staff-level dashboard. Terminologically, "Staff" is the primary operational role created by Admins.
3. **User (Franchisee / Regular Staff):** Has limited access. Primarily uses the `UserDashboard`. Can request stock adjustments, submit pull-outs, and use the User POS.

## 5. Core Modules & Features

### 5.1. Authentication & Security
- **Controllers:** `Auth.php`, `AdminAuth.php`, `UserAuth.php`
- **Filters:** `AuthFilter.php`, `AdminAuthFilter.php`, `RoleFilter.php`
- **Features:** 
    - Login, Registration, Logout, and Session Management.
    - **Back Button Protection:** Prevents viewing sensitive data after logout using `Cache-Control` headers and JS `enforceClientAuth`.
    - **Role-Based Access Control (RBAC):** Enforced via filters and route groups.

### 5.2. Point of Sale (POS)
- **Controllers:** `PosController.php` (Admin & Staff), `UserPosController.php` (Users)
- **Routes:** `/admin/pos`, `/admin/staff/pos`, `/user/pos`
- **Features:** Processing of sales, cart management, checkout, and **dynamic size variation grouping**. Records data into `TransactionModel` and `SalesModel`.

### 5.3. Inventory Management
- **Controller:** `Items.php`
- **Models:** `ItemModel`, `ProductModel`, `ItemLogModel`, `DeletedItemModel`
- **Features:** 
  - Add, Edit, Delete (Soft and Permanent) items.
  - Bulk upload functionality (CSV/Excel support).
  - Tracking of expired and expiring-soon items with auto-delete logic.
  - Logging of item activities.
  - CSV Export capabilities.
  - **Item Variation Support:** Tracks child variations (e.g., sizes) using `is_variation_child` and `variation_group_id`.

### 5.4. Sales and Transactions
- **Controller:** `SalesController.php`
- **Models:** `SalesModel`, `TransactionModel`
- **Features:** Viewing overall sales, detailed transaction history, and notification badges for unviewed sales.

### 5.5. Stock Requests, Pull-outs & Returns
- **Controllers:** `AdminRequests.php`, `UserRequestController.php`, `PullOutController.php`, `ReturnsController.php`
- **Models:** `StockRequestModel`, `StockRequestLogModel`, `PullOutModel`, `ReturnModel`
- **Features:** 
  - Users can request stock adjustments with reasons.
  - Admins can approve or reject stock requests/pull-outs.
  - Handling of food waste (pull-outs) and customer returns.

### 5.6. External API & Customer Frontend
- **Controller:** `ApiController.php`, `CustomerOrderController.php`
- **Routes Group:** `/api/*`, `/order`
- **Features:** 
    - Stateless API for external online ordering platforms (Fetch products, submit orders).
    - Lightweight customer-facing order view (`/order`).

## 6. UI & User Experience
- **Unified Radius:** The system implements a unified `12px` border-radius across all UI elements (cards, buttons, inputs) for a modern, consistent look.
- **Mobile Responsive:** Features a mobile menu toggle (hamburger menu) and sidebar overlay for smaller screens.
- **Sticky Headers:** Tables use sticky headers for better data visibility during scrolling.

## 7. Routing (app/Config/Routes.php)
The application utilizes explicit routing groups for better security and organization:
- `user/*`: Routes for regular users.
- `admin/*`: Routes for administrators.
- `admin/staff/*`: Nested routes for staff members.
- `items/*`: Inventory-specific operations.
- `api/*`: Stateless endpoints for external integrations.

*Note: Auto-routing is explicitly disabled (`$routes->setAutoRoute(false);`) as a security best practice.*

## 8. Deployment and Maintenance Tools
- **Cron Controller:** Handles periodic automated tasks such as `checkExpiry` to update item statuses.
- **Setup Controller:** Accessible via `/setup-db` for initializing or syncing database schema.
- **Utility Scripts:** The root directory contains several utility PHP scripts (e.g., `update_badge_dot.php`, `update_sticky_navbar.php`) used as custom migration or find-and-replace scripts to update UI elements system-wide.
- **Email Configuration:** Gmail SMTP is used for dispatching emails (configured in `.env`).
