# Mobile Phone Repository

A complete **PHP + MySQL** web application for managing a mobile phone data repository.  
Built with PHP, HTML/CSS (no external frameworks), and a MySQL database via XAMPP.

---

## Features

| Requirement | Covered By |
|---|---|
| Login / Session authentication | `index.php`, `login.php`, `logout.php` |
| Add phone record via HTML form | `add_phone.php`, `insert_phone.php` |
| Retrieve & display all records | `retrieve_all.php` |
| Filter: price 10,000 – 20,000 | `price_10k_20k.php` |
| Filter: price above 20,000 | `price_above_20k.php` |
| Edit a phone record | `edit_phone.php` |
| Delete a phone record | `delete_phone.php` |
| Database connection | `db_connect.php` |
| Shared layout (header/footer) | `../includes/header.php`, `../includes/footer.php` |

---

## Concepts Demonstrated

- **Syntax & Logical Errors** — all inputs are validated (empty-check, price > 0, type casting)
- **Exception Handling** — every database operation wrapped in `try / catch (Exception $e)`; errors shown as alert banners
- **Forms / GUIs** — styled HTML forms with client-side `required` attributes and server-side re-validation
- **Database Operations (CRUD)**  
  - **Create** — `insert_phone.php` inserts via prepared statement  
  - **Read** — `retrieve_all.php`, `price_10k_20k.php`, `price_above_20k.php`  
  - **Update** — `edit_phone.php` updates via prepared statement  
  - **Delete** — `delete_phone.php` deletes via prepared statement  
- **SQL Injection Prevention** — all queries use `mysqli` prepared statements with `bind_param`
- **Session Management** — login state stored in `$_SESSION`; every protected page redirects unauthenticated users

---

## Database Schema

**Table 1 — `users`** (login credentials)

| Column | Type | Notes |
|---|---|---|
| id | INT AUTO_INCREMENT PK | |
| username | VARCHAR(50) UNIQUE | |
| password | VARCHAR(255) | plain-text for dev; hash in production |
| created_at | TIMESTAMP | auto-set |

**Table 2 — `mobile_phones`** (phone repository)

| Column | Type | Notes |
|---|---|---|
| id | INT AUTO_INCREMENT PK | |
| name | VARCHAR(100) | e.g. "Samsung Galaxy S24" |
| brand | VARCHAR(50) | e.g. "Samsung" |
| model | VARCHAR(50) | e.g. "S24 Ultra" |
| price | DECIMAL(10,2) | CHECK price > 0 |
| created_at | TIMESTAMP | auto-set |
| updated_at | TIMESTAMP | auto-updated |

---

## Setup

1. Install **XAMPP** and start **Apache** + **MySQL**.
2. Place the project folder at:  
   `C:\xampp\htdocs\Mobile data files\`
3. Ensure the shared layout folder exists at:  
   `C:\xampp\htdocs\includes\` (contains `header.php` and `footer.php`)
4. Import the schema in **phpMyAdmin** or via CLI:
   ```
   mysql -u root -p < schema.sql
   ```
5. Open the app:  
   `http://localhost/Mobile%20data%20files/index.php`

---

## Default Login

| Field | Value |
|---|---|
| Username | `admin` |
| Password | `admin123` |

---

## Project File Map

```
Mobile data files/
├── index.php           Login page (UI + form)
├── login.php           Login form processor
├── logout.php          Session destroy + redirect
├── home.php            Dashboard with stats & quick actions
├── add_phone.php       Add-phone form
├── insert_phone.php    Insert form processor (Create)
├── retrieve_all.php    List all phones (Read + Edit/Delete links)
├── edit_phone.php      Edit-phone form + processor (Update)
├── delete_phone.php    Delete processor (Delete)
├── price_10k_20k.php   Filtered view: price > 10,000 and < 20,000
├── price_above_20k.php Filtered view: price > 20,000
├── db_connect.php      MySQLi connection with exception handling
└── schema.sql          DB + table creation + sample data

../includes/            (C:\xampp\htdocs\includes\)
├── header.php          HTML boilerplate, CSS, sticky navbar
└── footer.php          Closing tags + footer bar
```

> **Security Note:** Passwords are stored as plain text for demonstration purposes only.  
> In a production environment, use `password_hash()` / `password_verify()` and HTTPS.
