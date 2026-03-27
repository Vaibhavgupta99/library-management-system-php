# 📚 Library Management System

A fully functional **Library Management System** built using **PHP, MySQL, Bootstrap 5, and JavaScript**.
This project simulates real-world library operations including book management, membership handling, issuing/returning books, and fine calculation.

---

## 🚀 Features

### 🔐 Authentication

* Admin & User login system
* Session-based authentication
* Role-based access control

### 📚 Library Operations

* Book & Movie Management
* Membership Management
* Issue & Return System
* Fine Calculation (₹5/day for overdue)

### 📊 Reports

* Master List of Books & Movies
* Membership Records
* Active Issues
* Overdue Returns
* Pending Requests

### ⚙️ Admin Panel

* Add / Update Books & Movies
* Add / Update Memberships
* User Management

---

## 🛠 Tech Stack

* **Frontend:** HTML, Bootstrap 5, JavaScript
* **Backend:** PHP (Core PHP)
* **Database:** MySQL

---

## ⚡ How to Run Locally

1. Install **XAMPP**
2. Move project folder to:

   ```bash
   htdocs/library
   ```
3. Start:

   * Apache
   * MySQL
4. Open browser:

   ```
   http://localhost/library
   ```

👉 Database and tables will be created automatically on first run.

---

## 🔑 Login Credentials

**Admin**

* Username: `adm`
* Password: `adm`

**User**

* Username: `user`
* Password: `user`

---

## 📂 Project Structure

```
library/
│── index.php        # Main router
│── conn.php         # Backend logic (all operations)
│── header.php       # UI header
│── footer.php       # UI footer
│── login.php        # Login page
│── home.php         # Dashboard
│── transactions/    # Issue, Return, Fine modules
│── reports/         # Reports module
│── maintenance/     # Admin management
```

---

## 🎯 Key Highlights

* Modular architecture with centralized routing
* Real-world business logic implementation
* Automatic serial number generation
* Fine calculation based on overdue days
* Clean and responsive UI design
* Full CRUD operations

---

## 📸 Screenshots

(Add your screenshots here)

```
screenshots/login.png
screenshots/dashboard.png
screenshots/transactions.png
```

---

## ⚠️ Important Note

This project is built for **learning and demonstration purposes**.
For production use:

* Replace MD5 with secure hashing (bcrypt)
* Use prepared statements to prevent SQL injection

---

## 👨‍💻 Author

Vaibhav Gupta

---

💡 This project demonstrates strong understanding of backend development, database design, and real-world application workflows.

