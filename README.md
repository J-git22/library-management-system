# Library Management System

A comprehensive, role-based Library Management System built with raw PHP, adhering to the MVC (Model-View-Controller) architecture. This system provides a robust digital environment for managing library resources, user circulation, and reservations, tailored for Admins, Librarians, and Students.

## 🌟 Key Features

### 👥 Role-Based Access Control
The system features strict routing and access control based on user roles:
- **Admin**: Full access to the entire system. Can manage all users, staff, books, and systemic settings.
- **Librarian**: Operational access. Can manage books, authors, publishers, categories, members, and oversee the circulation desk.
- **Student**: Restricted portal. Can browse recommended books, view their borrowing history, track active borrows, manage book reservations, and check outstanding fines.

### 📚 Comprehensive Book Management
- **Cataloging**: Full CRUD (Create, Read, Update, Delete) capabilities for Books.
- **Taxonomy**: Organize books efficiently with dedicated modules for Authors, Publishers, and Categories.
- **Search & Filter**: Find books easily using dynamic search bars and category filters on the dashboard.

### 🔄 Circulation & Transactions
- **Borrowing & Returning**: Librarians can seamlessly issue books to members and process returns.
- **History Tracking**: The system maintains a permanent log of all transactions, including exact checkout and return timestamps.
- **Fines Calculation**: Automatically calculates and tracks monetary fines for overdue items based on borrowing duration limits.

### 🔖 Reservation System
- Students can place reservations on books directly from their portal.
- Librarians can view pending reservations, fulfill them when the physical book is claimed, or cancel them.
- Students also have the autonomy to cancel their own pending reservations.

### 🔗 User & Member Synchronization
- **Data Unification**: The system ensures perfect synchronization between System Users (login accounts) and Library Members (physical library profiles). 
- Auto-generation of unique `MEM-XXXX-XX` Member IDs upon account creation, with support for custom ID overrides.

## 🛠️ Technology Stack
- **Backend**: PHP 8+ (Raw PHP utilizing OOP and PDO for database interactions)
- **Architecture**: Custom MVC (Model-View-Controller) Framework
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5 (for responsive layouts and UI components)
- **Icons**: FontAwesome

## 🚀 Installation & Setup

1. **Environment Requirements**:
   - A local server environment like XAMPP, WAMP, or MAMP.
   - PHP 8.0 or higher.
   - MySQL / MariaDB.

2. **Clone the Repository**:
   ```bash
   git clone https://github.com/J-git22/library-management-system.git
   ```

3. **Database Setup**:
   - Open phpMyAdmin (or your preferred SQL client).
   - Create a new database named `library_management_db`.
   - Import the provided SQL dump file located at `database/library_management.sql`.

4. **Configuration**:
   - Navigate to `app/config/config.php`.
   - Ensure the database credentials match your local setup:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', ''); // Add your password if applicable
     define('DB_NAME', 'library_management_db');
     ```
   - Update the `URL_ROOT` to match your local directory structure (e.g., `http://localhost/LibraryManagementSystem`).

5. **Accessing the System**:
   - Open your browser and navigate to the `URL_ROOT`.
   - You can log in using the accounts present in the database. 

## 📁 Folder Structure

- `/app` - Core application files (Controllers, Models, Views, Helpers, Config)
- `/public` - Publicly accessible files (index.php, CSS, JS, Images)
- `/database` - SQL dumps and database schemas
- `/scripts` - Utility scripts (e.g., data synchronization, dummy data seeders)

## 🔒 Security
- Secure password hashing using PHP's native `password_hash()` and `password_verify()`.
- Data sanitization and PDO prepared statements to prevent SQL injection.
- Session-based authentication and role verification middleware on restricted routes.
