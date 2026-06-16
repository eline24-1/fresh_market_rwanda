# Fresh Market Rwanda — E-Commerce Web Application

A complete, ready-to-deploy PHP/MySQL e-commerce platform for a fresh produce and grocery
business in Rwanda. Includes a customer-facing storefront and a full admin dashboard.

## Features

### Customer Storefront
- Responsive, mobile-friendly design
- Homepage with hero banner, categories, and featured products
- Product listing with category filters and search
- Product details page with quantity selector
- Session-based shopping cart (add, update quantity, remove)
- Checkout with customer details, delivery info, and order summary
- Mobile Money (MTN/Airtel) and Cash on Delivery payment options
- Order confirmation page with order number
- Customer registration/login and order history (My Account)

### Admin Dashboard
- Secure admin login
- Dashboard with key stats (orders, revenue, customers, low stock)
- Product management (add/edit/delete, image upload, categories, stock, featured flag)
- Category management
- Order management with status updates (pending → processing → shipped → delivered)
- Customer list with order totals

## Tech Stack
- PHP 8+ (PDO + MySQL, prepared statements)
- MySQL / MariaDB
- HTML5, CSS3 (custom design system), vanilla JavaScript
- Font Awesome icons, Google Fonts

## Setup Instructions

### 1. Requirements
- PHP 8.0 or higher
- MySQL or MariaDB
- A local server environment such as XAMPP, WAMP, or Laragon

### 2. Installation

1. Copy the `fresh-market-rwanda` folder into your server's web root:
   - XAMPP: `C:\xampp\htdocs\fresh-market-rwanda`
   - Laragon: `C:\laragon\www\fresh-market-rwanda`

2. Start Apache and MySQL from your server control panel.

3. Create the database:
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Click "Import" and select the file `sql/fresh_market.sql`
   - This will create the `fresh_market_rwanda` database with all tables and sample data.

   Alternatively, via command line:
   ```
   mysql -u root -p < sql/fresh_market.sql
   ```

4. Configure the database connection (if needed):
   - Open `includes/db.php`
   - Update `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` to match your environment
   - Default values work for a standard XAMPP setup (user: root, no password)

5. Visit the site:
   - Storefront: `http://localhost/fresh-market-rwanda/`
   - Admin Dashboard: `http://localhost/fresh-market-rwanda/admin/login.php`

### 3. Default Admin Login
- Username: `admin`
- Password: `admin123`

**Important:** Change this password after first login by updating the `admins` table
(generate a new hash with `password_hash('your_password', PASSWORD_DEFAULT)`).

## Folder Structure

```
fresh-market-rwanda/
├── index.php                  # Homepage
├── products.php               # Product listing + filters/search
├── product-detail.php          # Single product page
├── cart.php                    # Shopping cart
├── checkout.php                # Checkout form
├── order-confirmation.php      # Order success page
├── login.php / register.php    # Customer auth
├── account.php / logout.php    # Customer account & order history
├── about.php / contact.php
├── includes/
│   ├── db.php                  # Database connection
│   ├── functions.php           # Helper functions (cart, auth, formatting)
│   ├── header.php / footer.php # Shared layout
├── admin/
│   ├── login.php / logout.php
│   ├── index.php               # Dashboard
│   ├── products.php / product-form.php
│   ├── categories.php
│   ├── orders.php / order-detail.php
│   ├── customers.php
│   └── includes/header.php / footer.php
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── images/products/        # Product images (uploaded via admin)
└── sql/fresh_market.sql        # Database schema + seed data
```

## Notes
- Product images uploaded via the admin panel are stored in `assets/images/products/`.
  Make sure this folder is writable by the web server.
- If a product image is missing, the storefront automatically displays a placeholder.
- Delivery fee is currently fixed at 1,500 RWF and can be changed in `cart.php` and `checkout.php`.
- Mobile Money payment is simulated (the order is recorded with "pending" payment status
  for manual confirmation by the admin). To integrate a real Mobile Money API (e.g., MTN
  MoMo API), add the API call inside `checkout.php` after the order is created.
