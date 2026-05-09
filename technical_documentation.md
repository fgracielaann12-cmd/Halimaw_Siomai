# Halimaw Siomai - Technical Documentation

## 1. Project Overview
**Halimaw Siomai** is a web-based comprehensive Point of Sale (POS) and Inventory Management System. It is designed to handle multiple user roles, manage product stock, process point-of-sale transactions, handle stock requests, manage pull-outs and returns, and provide an API for an external online ordering platform.

- **Framework:** CodeIgniter 4
- **Language:** PHP 8.1+
- **Database:** MySQL / MariaDB (MySQLi driver)
- **Frontend Stack:** HTML, CSS (Vanilla), JavaScript, Bootstrap (assumed based on standard CI4 apps), PHP (Views)
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

1. **Admin:** Full access to the system. Can manage inventory, view all sales, handle stock requests, manage users, and access the Admin POS.
2. **Staff:** A sub-group of Admin. Has access to the Staff POS, customer returns submission, and specific dashboards.
3. **User (Franchisee / Regular Staff):** Has limited access. Can view their dashboard, request stock adjustments, submit pull-outs, and use the User POS.

## 5. Core Modules & Features

### 5.1. Authentication
- **Controllers:** `Auth.php`, `AdminAuth.php`, `UserAuth.php`
- **Features:** Login, Registration, Logout, and Session Management.

### 5.2. Point of Sale (POS)
- **Controllers:** `PosController.php` (Admin & Staff), `UserPosController.php` (Users)
- **Routes:** `/admin/pos`, `/admin/staff/pos`, `/user/pos`
- **Features:** Processing of sales, cart management, checkout, and **dynamic size variation grouping** (groups size variants like "Small", "Medium" under a single parent item card dynamically based on `product_id` suffix logic). Records data into `TransactionModel` and `SalesModel`.

### 5.3. Inventory Management
- **Controller:** `Items.php`
- **Models:** `ItemModel`, `ProductModel`, `ItemLogModel`, `DeletedItemModel`
- **Features:** 
  - Add, Edit, Delete (Soft and Permanent) items.
  - Bulk upload functionality.
  - Tracking of expired and expiring-soon items.
  - Logging of item activities.
  - CSV Export capabilities (`export-logs-csv`, `export-sales-csv`).

### 5.4. Sales and Transactions
- **Controller:** `SalesController.php`
- **Models:** `SalesModel`, `TransactionModel`
- **Features:** Viewing overall sales, detailed transaction history, and breakdown of items per transaction.

### 5.5. Stock Requests & Pull-outs
- **Controllers:** `AdminRequests.php`, `UserRequestController.php`, `PullOutController.php`, `ReturnsController.php`
- **Models:** `StockRequestModel`, `StockRequestLogModel`, `PullOutModel`, `ReturnModel`
- **Features:** 
  - Users can request stock adjustments.
  - Admins can approve or reject stock requests.
  - Handling of food waste (pull-outs) and customer returns.

### 5.6. External API (Online Ordering)
- **Controller:** `ApiController.php`
- **Routes Group:** `/api/*`
- **Models:** `OnlineOrderModel`, `OnlineOrderItemModel`
- **Features:** Exposes endpoints for an external platform to fetch products, submit orders, view pending orders, and confirm orders. It handles CORS preflight requests.

## 6. Routing (app/Config/Routes.php)
The application utilizes explicit routing groups for better security and organization:
- `user/*`: Routes for regular users.
- `admin/*`: Routes for administrators.
- `admin/staff/*`: Nested routes for staff members.
- `items/*`: Inventory-specific operations.
- `api/*`: Stateless endpoints for external integrations.

*Note: Auto-routing is explicitly disabled (`$routes->setAutoRoute(false);`) as a security best practice.*

## 7. Email Configuration
The system uses Gmail SMTP for dispatching emails (configured in `.env`):
- **Protocol:** `smtp`
- **SMTP Host:** `smtp.gmail.com`
- **SMTP Port:** `465` (SSL)

## 8. Deployment and Maintenance Tools
The root directory contains several utility PHP scripts (e.g., `update_badge_dot.php`, `update_sticky_navbar.php`) which appear to be custom migration or find-and-replace scripts used during development to update UI elements across multiple view files simultaneously.
