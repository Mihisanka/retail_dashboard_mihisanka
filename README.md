# Retail Dashboard

A web-based retail management dashboard built with PHP and MySQL. This application allows retail shop owners and staff to manage sales, customers, users, and generate reports through a modern, responsive interface.

## Features

- **User Authentication:** Secure login/logout for users with roles (Super Admin, Admin, Manager, Staff).
- **Sales Management:** Add, edit, delete, and view sales records. Sales are linked to customers and include date and total amount.
- **Customer Management:** Add, edit, delete, and search customers. Each customer has a name, email, and phone number.
- **User Management:** Admins can add, edit, and delete users, assign roles, and manage user profiles.
- **Admin Profile:** View and update admin profile details, including password change.
- **Sales Reporting:** Dashboard and reports for total sales, number of sales, unique customers, sales by customer, and sales by month. Visual charts included.
- **Responsive UI:** Built with Bootstrap 5 and custom CSS for a clean, modern look.

## Project Structure

- `index.php` — Dashboard with sales summary and charts
- `login.php` / `logout.php` — User authentication
- `add_sale.php` — Add a new sale
- `sales.php` — View, edit, and delete sales
- `customers.php` — Manage customers
- `users.php` — Manage users and roles
- `admin_profile.php` — Admin profile management
- `report.php` — Detailed sales reports
- `navbar.php`, `topbar.php` — Navigation components
- `style.css` — Custom styles

## Database Structure

The application expects a MySQL database named `retail_db` with the following tables:

### `users`
| Field      | Type         | Description           |
|------------|--------------|----------------------|
| id         | INT, PK, AI  | User ID              |
| username   | VARCHAR(50)  | Login username       |
| password   | VARCHAR(255) | Hashed password      |
| role       | VARCHAR(20)  | User role            |
| full_name  | VARCHAR(100) | Full name            |
| email      | VARCHAR(100) | Email address        |
| phone      | VARCHAR(30)  | Phone number         |
| mobile     | VARCHAR(30)  | Mobile number        |
| address    | VARCHAR(255) | Address              |

### `customers`
| Field   | Type         | Description      |
|---------|--------------|-----------------|
| id      | INT, PK, AI  | Customer ID     |
| name    | VARCHAR(100) | Customer name   |
| email   | VARCHAR(100) | Email address   |
| phone   | VARCHAR(30)  | Phone number    |

### `sales`
| Field    | Type         | Description         |
|----------|--------------|--------------------|
| id       | INT, PK, AI  | Sale ID            |
| date     | DATE         | Date of sale       |
| customer | VARCHAR(100) | Customer name      |
| total    | DECIMAL(10,2)| Total sale amount  |

> **Note:** Table creation is handled in the PHP files if not present, but you may want to create them manually for production.

## Setup Instructions

1. **Clone the repository:**
   ```sh
   git clone https://github.com/YOUR-USERNAME/REPO-NAME.git
   ```
2. **Database Setup:**
   - Create a MySQL database named `retail_db`.
   - (Optional) Use the following SQL to create tables:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL,
  full_name VARCHAR(100),
  email VARCHAR(100),
  phone VARCHAR(30),
  mobile VARCHAR(30),
  address VARCHAR(255)
);

CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  phone VARCHAR(30)
);

CREATE TABLE sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE NOT NULL,
  customer VARCHAR(100) NOT NULL,
  total DECIMAL(10,2) NOT NULL
);
```

3. **Configure Database Connection:**
   - The database connection is set in each PHP file as:
     ```php
     $conn = new mysqli('localhost', 'root', '', 'retail_db');
     ```
   - Update credentials if your MySQL setup is different.

4. **Run the Project:**
   - Place the project folder in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Start Apache and MySQL from your XAMPP/WAMP control panel.
   - Open your browser and go to:
     ```
     http://localhost/retail_dashboard/
     ```

## Usage

- **Login:** Access `login.php` to log in.
- **Dashboard:** View sales summary and charts in `index.php`.
- **Add Sale:** Use `add_sale.php` or the modal in `sales.php` to add a new sale.
- **View/Edit Sales:** Go to `sales.php` to view, edit, or delete sales.
- **Manage Customers:** Use `customers.php` to add, edit, delete, or search customers.
- **Manage Users:** Use `users.php` to add, edit, or delete users and assign roles.
- **Admin Profile:** Access `admin_profile.php` to view or update your profile.
- **Reports:** View detailed sales reports in `report.php`.
- **Logout:** Use the sidebar or `logout.php` to log out.

## Authentication & Roles

- Users must log in to access the dashboard and management features.
- User roles include: Super Admin, Admin, Manager, Staff.
- Role-based access controls what actions are available (e.g., only Super Admin can delete users).
- Passwords are securely hashed.

## Technologies Used

- PHP (Procedural)
- MySQL
- Bootstrap 5
- Chart.js (for dashboard charts)
- Custom CSS

## License

This project is for testing purposes. 
---

**Project URL:**
```
https://github.com/YOUR-USERNAME/REPO-NAME
```
Replace `YOUR-USERNAME` and `REPO-NAME` with your actual GitHub username and repository name.
