# 🍱 MyFoodMart - Campus Food Ordering System

A professional, web-based e-commerce platform designed for convenient campus meal ordering under the **Business to Consumer (B2C)** category.

---

## ✨ Key Features
* **Smart Search:** Quickly find local favorites like Laksa Sarawak and Mee Kolok.
* **Responsive UI:** Modern design using the **Poppins** font and a dark charcoal navbar for a premium feel.
* **Standardized Menu:** Automatic image scaling to perfect squares using `object-fit: cover` for a clean, consistent grid layout.
* **Secure Auth:** Role-based access control (RBAC) ensuring only authorized admins can access management tools.

## 🛠️ Technology Stack
* **Frontend:** HTML5, Bootstrap 5.3, Google Fonts (Poppins).
* **Backend:** PHP (Session-based authentication & role management).
* **Database:** MySQL (Relational schema with `users`, `products`, `categories`, and `orders`).

## 🚀 Installation
1. **Clone** this repository into your `xampp/htdocs/` folder.
2. **Export** your local database as `myfoodmart.sql` using phpMyAdmin.
3. **Import** `myfoodmart.sql` into your local MySQL server.
4. **Open** `localhost/myfoodmart` in your browser.

## ⚙️ Admin Management
The administrative suite is accessible only to users with the **admin** role.

### 📦 Product Management
* **Inventory Control:** Add, edit, or delete food items directly from the dashboard.
* **Live Updates:** Modify prices, food categories, and menu visibility (Active/Inactive status).

### 📋 Order Overview
* **Transaction Tracking:** View a master list of customer orders with real-time status updates.
* **Detailed Logs:** Access order IDs, customer names, total amounts (RM), and payment methods.
* **Fulfillment Monitoring:** Track order timestamps using the `created_at` database synchronization.
