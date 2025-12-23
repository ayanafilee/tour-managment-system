# 🌍 TourConnect – Tour Management System

**TourConnect** is a PHP-based Tour Management System that connects **Tourists**, **Tour Guides**, and **Administrators** on a single platform.
It enables tour discovery, booking management, and administrative oversight through a structured backend–frontend architecture.

---

## ✨ Features

### 👤 Tourist

* Browse available tours
* Book tours easily
* View personal bookings
* Manage profile and password

### 🗺️ Tour Guide

* Create, edit, and delete tours
* View bookings for assigned tours
* Track personal tour listings

### 🛡️ Admin

* View system statistics
* Manage users
* Monitor all bookings and tours

---

## 📸 Screenshots


<img width="1899" height="826" alt="Screenshot 2025-12-23 102022" src="https://github.com/user-attachments/assets/6cd89b97-c388-4926-9d87-ff03054f5393" />
<img width="1919" height="818" alt="Screenshot 2025-12-23 101942" src="https://github.com/user-attachments/assets/e1f5dbe8-32bc-4c2d-b14b-7bffd8a7fd16" />
<img width="1919" height="807" alt="Screenshot 2025-12-23 101913" src="https://github.com/user-attachments/assets/df6c4db3-5b8e-4a40-ac8b-3a1e851f1aff" />
<img width="1914" height="827" alt="Screenshot 2025-12-23 101853" src="https://github.com/user-attachments/assets/c5244e04-fa3f-4bd3-945b-3817df947c56" />

---

## 🛠️ Tech Stack

* **Frontend:** HTML5, CSS3, JavaScript
* **Backend:** PHP 8.x
* **Database:** MySQL
* **Local Server:** XAMPP
* **Alerts & UX:** SweetAlert2

---

## 🚀 Getting Started (Local Setup with XAMPP)

### 1. Prerequisites

* Install **XAMPP**
* Start **Apache** and **MySQL**

---


### 2. Project Setup

#### Step 1: Clone the Repository

Open **Command Prompt (CMD)** or your terminal and run:

```bash
git clone https://github.com/your-username/TOUR-MANAGMENT-SYSTEM.git
```

This will download the project to your current directory.

#### Step 2: Move the Project to XAMPP `htdocs`

After cloning, move the project folder into your XAMPP `htdocs` directory:

```bash
move TOUR-MANAGMENT-SYSTEM C:\xampp\htdocs\
```

> Alternatively, you can manually copy and paste the folder into:
>
> ```text
> C:\xampp\htdocs\
> ```

Once done, the project path should look like this:

```text
C:\xampp\htdocs\TOUR-MANAGMENT-SYSTEM
```

---


### 2. Project Setup

Move the project folder into your XAMPP `htdocs` directory:

```bash
C:\xampp\htdocs\TOUR-MANAGMENT-SYSTEM
```

---

### 3. Database Setup

1. Open `http://localhost/phpmyadmin`
2. Create a database named:

```text
tour_management_system
```

3. Import the SQL file:

   * Go to **Import**
   * Select `database/db.sql`
   * Click **Go**

---

### 4. Database Configuration

Edit the database configuration file:

```php
// backend/db_config.php
$host = "localhost";
$user = "root";
$password = "";
$database = "tour_management_system";
```

---

### 5. Run the Application

Open your browser and navigate to:

```text
http://localhost/TOUR-MANAGMENT-SYSTEM/frontend/index.php
```

---

## 📂 Project Structure

```text
TOUR-MANAGMENT-SYSTEM/
│
├── backend/
│   ├── admin_get_bookings.php
│   ├── admin_get_users.php
│   ├── admin_stats.php
│   ├── auth_login.php
│   ├── auth_signup.php
│   ├── book_tour.php
│   ├── cancel_booking.php
│   ├── change_password.php
│   ├── db_config.php
│   ├── delete_tour.php
│   ├── edit_tour.php
│   ├── get_all_tours.php
│   ├── get_customer_bookings.php
│   ├── get_guide_bookings.php
│   ├── get_my_tours.php
│   ├── get_tours.php
│   └── logout.php
│
├── database/
│   └── db.sql
│
├── frontend/
│   ├── add_tour.php
│   ├── all_bookings.php
│   ├── dashboard.php
│   ├── guide_bookings.php
│   ├── index.php
│   ├── login.php
│   ├── manage_users.php
│   ├── my_bookings.php
│   ├── profile.php
│   ├── signup.php
│   └── tourist_home.php
│
└── README.md
```

---


## 📌 Notes

* Ensure Apache and MySQL are running before accessing the system.
* All backend logic is separated from frontend pages for maintainability.
* SQL file **must be imported before first use**.

---

## 📄 License

This project is for academic and learning purposes.

---


