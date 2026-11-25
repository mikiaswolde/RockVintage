# RockVintage: Project Improvement & 30-Day Execution Guide

This document outlines the architectural improvements, high-impact features, free cloud hosting strategy, and a day-by-day 30-day GitHub commit roadmap for the **RockVintage** e-commerce platform.

---

## 1. Architectural & File Structure Improvements

### A. File & Directory Renaming (Eliminate Spaces)
Spaces in file names (e.g. `AD Product Management.html`, `Contact Us.js`) create `%20` encoding issues in browsers, break automated CLI deployment scripts, and violate clean URL standards on Linux web servers.

Recommended target layout:
```text
rockvintage/
├── config/                  # DB connection, env loader, constants
│   ├── db.php
│   └── env.php
├── database/                # Schema definitions & migrations
│   ├── schema.sql
│   └── seed.sql
├── public/                  # Public document root
│   ├── admin/               # Admin portal pages and scripts
│   │   ├── index.html       # (formerly AD Homepage.html)
│   │   ├── products.html    # (formerly AD Product Management.html)
│   │   ├── orders.html      # (formerly AD Order Management.html)
│   │   ├── profile.html     # (formerly AD Profile.html)
│   │   └── logout.html
│   ├── assets/              # Static media, icons, uploaded images
│   ├── css/                 # Modern modular stylesheets
│   ├── js/                  # Reusable client scripts
│   │   ├── api.js           # Centralized fetch wrapper & error handling
│   │   ├── auth.js          # Auth state & session utilities
│   │   ├── cart-store.js    # Persistent shopping cart state
│   │   └── toast.js         # Non-blocking notification UI
│   └── pages/               # Storefront subpages
│       ├── about.html
│       ├── cart.html
│       ├── contact.html
│       ├── login.html
│       ├── profile.html     # (formerly CP.html)
│       ├── shop.html
│       └── signup.html
├── api/                     # Backend REST-like JSON endpoints
│   ├── auth/                # login.php, signup.php, logout.php, session.php
│   ├── products/            # get.php, create.php, update.php, delete.php
│   ├── orders/              # create.php, get.php, update.php, delete.php
│   └── contact/             # submit.php
├── Dockerfile               # Container definition for local dev & cloud
├── docker-compose.yml       # Local Apache + MySQL orchestration
├── .env.example             # Template for environment credentials
└── README.md                # Comprehensive documentation
```

### B. Security & Configuration Best Practices
* **Environment Variables:** Migrate database credentials out of code into `.env` using PHP's `getenv()` or `$_ENV`.
* **Database Migration & Seeding:** Maintain explicit `schema.sql` and `seed.sql` files so anyone can reproduce the environment instantly.
* **Upload Sanitization:** Validate image uploads on the server using `finfo_file()` for actual MIME inspection rather than relying on client file extensions.
* **Role-Based Guards:** Enforce server-side session authentication on all administrative API endpoints.

---

## 2. High-Impact Features to Add

1. **Catalog & Search:**
   - Dynamic product category filtering (Jackets, Denim, Sweaters, Boots, Accessories).
   - Real-time debounced keyword search bar.
   - Price sorting (Low to High, High to Low, Newest arrivals).
   - Interactive quick-view product modal with size and stock indicators.
2. **Cart & Checkout:**
   - Persistent `localStorage` cart that stays intact across page reloads and browsing.
   - Quantity adjustment controls (+/-) and line-item deletion.
   - Realistic checkout simulation with shipping details form and order summary.
   - Instant order confirmation modal with auto-generated Order Reference ID.
3. **Inventory & Order Management:**
   - Automatic inventory deduction upon successful order submission.
   - Order tracking lifecycle (`Pending` → `Processing` → `Shipped` → `Delivered` → `Cancelled`).
   - Customer profile order history accordion with itemized receipts.
4. **Admin Dashboard Analytics:**
   - Overview metrics: Total Revenue ($), Active Orders, Customer Count, and Low-Stock warnings.
   - Contact form submissions inbox for admin review.

---

## 3. Free Hosting Strategy (100% Free Tier)

| Service | Recommended Provider | Notes |
| :--- | :--- | :--- |
| **MySQL Database** | **TiDB Cloud (Serverless)** or **Aiven MySQL** | Permanently free tier (5GB storage, secure TLS connection, standard MySQL 8.0 compatibility). |
| **Web Server / PHP** | **Render.com** (Web Service) or **Koyeb** | Free tier (750 instance hours/month, automated deployments from GitHub repository via Docker). |
| **Media / Storage** | Local Docker volume or **Cloudinary Free Tier** | Cloudinary free tier gives 25GB storage for product and avatar uploads. |
| **SSL & Domain** | Automatic free `*.onrender.com` or `*.koyeb.app` | Managed Let's Encrypt SSL certificates automatically included. |

---

## 4. 30-Day GitHub Commit Roadmap

### Week 1: Foundation, Restructure, & Auth Security
* **Day 1:** Project Restructure & Normalization
  - *Task:* Rename all files to kebab-case/lowercase, remove spaces, standardize relative paths.
  - *Commit:* `refactor: normalize file naming and organize admin/storefront directories`
* **Day 2:** Database Schema & Seed Data
  - *Task:* Create `database/schema.sql` and `database/seed.sql` with categories, products, and admin user.
  - *Commit:* `feat(db): add comprehensive schema and seed data for catalog`
* **Day 3:** Environment Configuration & DB Connection
  - *Task:* Add `.env.example`, implement environment variable reader in `db.php`.
  - *Commit:* `feat(config): support environment variables for database connection`
* **Day 4:** Standardized JSON Response & API Helper
  - *Task:* Implement `send_json_response()` in PHP and create `js/api.js` for uniform frontend fetch handling.
  - *Commit:* `refactor(api): standardize json response structure and frontend fetch helper`
* **Day 5:** Modern Toast Notification System
  - *Task:* Build non-blocking toast notifications in CSS/JS to replace native `alert()` popups.
  - *Commit:* `feat(ui): add reusable toast notification system`
* **Day 6:** Authentication Hardening
  - *Task:* Add server-side email validation, strong password checks, and secure session cookie flags (`HttpOnly`, `SameSite`).
  - *Commit:* `sec(auth): add server validation and secure session cookie flags`
* **Day 7:** Role-Based Route Guards
  - *Task:* Implement session role checks in PHP middleware and redirect unauthenticated users away from admin views.
  - *Commit:* `feat(auth): enforce strict role-based access control for admin endpoints`

---

### Week 2: Catalog Enhancements & Product Experience
* **Day 8:** Product Categories Schema & API
  - *Task:* Add category relations in MySQL and support `?category=...` query param in `get_products.php`.
  - *Commit:* `feat(catalog): add category relation to products API`
* **Day 9:** Shop Category Filter Pills
  - *Task:* Add interactive category pill buttons on the shop page with dynamic filtering.
  - *Commit:* `feat(shop): add category filter pills and dynamic product filtering`
* **Day 10:** Real-time Keyword Search
  - *Task:* Implement debounced live keyword search filtering by product title and description.
  - *Commit:* `feat(shop): implement client-side live search with debouncing`
* **Day 11:** Price Sorting & Responsive Grid
  - *Task:* Add sorting options (Price Low-High, Price High-Low, Newest) and optimize responsive card grid.
  - *Commit:* `feat(shop): add price sorting options and improve responsive product grid`
* **Day 12:** Product Detail Modal (Quick View)
  - *Task:* Build quick-view modal showing product details, available sizes, and stock availability.
  - *Commit:* `feat(shop): add interactive product quick-view modal`
* **Day 13:** Dynamic Homepage New Arrivals
  - *Task:* Connect the homepage "New Arrivals" section to fetch dynamic data from the API.
  - *Commit:* `feat(home): dynamic new arrivals section powered by products API`
* **Day 14:** Mobile Navigation & Drawer Menu
  - *Task:* Create mobile hamburger toggle and navigation drawer for small screens.
  - *Commit:* `feat(nav): implement responsive mobile navigation drawer`

---

### Week 3: Cart, Checkout, & Customer Experience
* **Day 15:** Persistent LocalStorage Cart Store
  - *Task:* Implement `js/cart-store.js` for persistent cart state and dynamic navbar counter badge.
  - *Commit:* `feat(cart): persistent local-storage cart store and navbar badge`
* **Day 16:** Cart Page Interactive Management
  - *Task:* Render cart item rows with image, quantity steppers (+/-), unit price, and remove action.
  - *Commit:* `feat(cart): dynamic cart item listing and quantity adjustments`
* **Day 17:** Subtotal, Tax, & Coupon System
  - *Task:* Add calculation for line-item totals, tax, shipping, and discount promo code input.
  - *Commit:* `feat(cart): calculate subtotal, taxes, and coupon code support`
* **Day 18:** Checkout Form & Client Validation
  - *Task:* Build shipping address and contact form with input validation.
  - *Commit:* `feat(checkout): add customer shipping info form and client validation`
* **Day 19:** Atomic Order Placement & Inventory Deduction
  - *Task:* Wrap order insertion, order items insertion, and stock deduction inside a SQL transaction.
  - *Commit:* `feat(orders): implement atomic order placement and inventory deduction`
* **Day 20:** Order Confirmation Screen
  - *Task:* Display post-checkout success view with generated Order Reference ID and itemized receipt.
  - *Commit:* `feat(checkout): display order confirmation screen and receipt summary`
* **Day 21:** Customer Order History
  - *Task:* Enhance Customer Profile (`profile.html`) to display past orders and statuses.
  - *Commit:* `feat(profile): display user order history and itemized receipts`

---

### Week 4: Admin Dashboard, Inventory, & Order Management
* **Day 22:** Image Upload Security Hardening
  - *Task:* Enforce `finfo_file` MIME inspection, 2MB size limit, and name sanitization in upload endpoints.
  - *Commit:* `sec(uploads): validate MIME types and file size for product images`
* **Day 23:** Admin Product Management Modal
  - *Task:* Streamline product creation and editing modals with stock input and live image preview.
  - *Commit:* `feat(admin): enhance product modal with image preview and inventory fields`
* **Day 24:** Admin Order Status Workflow
  - *Task:* Add status change dropdown in Admin Orders with AJAX status updating.
  - *Commit:* `feat(admin): live order status updates and tracking lifecycle`
* **Day 25:** Admin Overview Dashboard Metrics
  - *Task:* Hook dashboard stat cards to live queries: Total Revenue ($), Orders, Low Stock warnings.
  - *Commit:* `feat(admin): real-time revenue and sales stats on dashboard overview`
* **Day 26:** Contact Inquiries Management
  - *Task:* Store contact submissions in a `messages` table and create an admin inquiry viewer.
  - *Commit:* `feat(contact): persist contact submissions to db with admin review view`
* **Day 27:** Profile Avatar Upload & Security
  - *Task:* Sanitize and display dynamic profile avatars for both customers and admin users.
  - *Commit:* `feat(profile): secure avatar upload and dynamic avatar header display`

---

### Week 5: Deployment, Free Cloud Hosting, & Final Polish
* **Day 28:** Production Dockerfile
  - *Task:* Create `Dockerfile` based on `php:8.2-apache` with `mysqli`, `pdo_mysql`, and rewrite modules.
  - *Commit:* `ci(docker): add production-ready Dockerfile for containerized deployment`
* **Day 29:** Cloud Deployment (TiDB Cloud + Render / Koyeb)
  - *Task:* Provision free database, run schema migration, configure environment variables, and deploy web container.
  - *Commit:* `chore(deploy): configure cloud environment variables and test live endpoints`
* **Day 30:** Documentation, Badges, & Release
  - *Task:* Finalize `README.md` with live demo link, architecture diagram, credentials, and setup instructions.
  - *Commit:* `docs: complete README with live demo link, architecture overview, and credentials`
