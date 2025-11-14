# 🐾 Pet Haven – Adopt, Shop & Care  
A complete pet adoption, accessories, and vet appointment management system.

---

## 📌 Overview  
Pet Haven is a full-stack web application where users can:

- 🐶 Adopt pets  
- 🛒 Buy pet accessories  
- 🩺 Book vet appointments  
- 👤 Manage their profile, orders, and appointments  

Admins can:

- Add and manage pets  
- Add and manage products  
- Review upcoming appointments  
- View and manage orders  

Built using **PHP**, **MySQL**, and a clean, modern UI.

---

## 🌟 Key Features  

### 🐕 Pet Adoption  
- Browse pets with images  
- View details (name, type, breed, age)  
- Submit adoption requests  
- Requests stored in the database  

### 🛍️ Pet Products Shop  
- Product catalog with images  
- Category filters  
- Add, edit, delete products (Admin)  
- Image upload support  

### 🩺 Vet Appointment Booking  
- Choose a doctor  
- Validates availability and working days  
- Auto-assigns the earliest free 30-minute slot  
- Fully stored and manageable from the admin panel  

### 🧑‍💻 Admin Panel  
| Feature | Description |
|--------|-------------|
| 🐶 Manage Pets | Add, edit, delete pets |
| 🛒 Manage Products | Add, edit, delete products |
| 📅 Manage Appointments | View today’s and upcoming appointments |
| 📦 Manage Orders | View and delete orders |

---

## 🏗️ Tech Stack  
| Category | Technologies |
|---------|--------------|
| Frontend | HTML5, CSS3, Inline CSS, JavaScript |
| Backend | PHP (PDO) |
| Database | MySQL |
| Server | Apache (XAMPP) |
| Images | Stored under `/assets/images/` |

---

## 📂 Project Structure  
PetHaven/
│
├── includes/
│ ├── header.php
│ ├── footer.php
│ ├── admin_header.php
│ ├── admin_footer.php
│ └── db_connect.php
│
├── assets/
│ ├── css/
│ ├── js/
│ ├── images/
│ ├── Pets/
│ └── Products/
│
├── admin/
│ ├── manage_pets.php
│ ├── manage_products.php
│ ├── check_appointments.php
│ ├── admin_login.php
│ └── admin_signup.php
│
├── adopt.php
├── adopt_request.php
├── shop.php
├── appointment.php
├── orders.php
├── dashboard.php
├── index.php
│
└── README.md


---

## 🗄️ Database Setup  

1. Start XAMPP and enable **Apache** + **MySQL**  
2. Open **phpMyAdmin**  
3. Create a database named:  


petshop

4. Import your SQL file:  


petshop.sql

5. Make sure your `includes/db_connect.php` matches:

```php
$host = 'localhost';
$db = 'petshop';
$user = 'root';
$pass = '';
$port = 3306;

try {
 $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
 die("Connection failed: " . $e->getMessage());
}

🚀 How to Run the Project
Step 1 — Move Project

Copy the folder to:

C:\xampp\htdocs\PetHaven\

Step 2 — Start Server

Open XAMPP → Start Apache + MySQL

Step 3 — Visit Website
http://localhost/pet-shop/

📸 Screenshots

Add your actual screenshots in the repo:

Page	Screenshot
Home	—
Adopt	—
Appointment Booking	—
Admin Dashboard	—
🚧 Upcoming Enhancements

Online payment integration

Email notifications

User and admin analytics

Multi-pet adoption

Cart system

👥 Contributors  

## 👥 Contributors  

<p align="left">

<!-- Kanika -->
<a href="https://github.com/kanikadrouna-02" target="_blank">
  <img src="https://github.com/kanikadrouna-02.png" width="90" height="90" style="border-radius:50%; margin-right:20px;" alt="Kanika Drouna"/>
</a>

<!-- Priya -->
<a href="https://github.com/Tiwari-priya16" target="_blank">
  <img src="https://github.com/Tiwari-priya16.png" width="90" height="90" style="border-radius:50%; margin-right:20px;" alt="Priya Tiwari"/>
</a>

<!-- Harsh -->
<a href="https://github.com/Harsh-Kapadiya" target="_blank">
  <img src="https://github.com/Harsh-Kapadiya.png" width="90" height="90" style="border-radius:50%; margin-right:20px;" alt="Harsh Kapadiya"/>
</a>

<!-- Sakshi (Placeholder) -->
<a href="#" target="_blank">
  <img src="## 👥 Contributors  

<p align="left">

<!-- Kanika -->
<a href="https://github.com/kanikadrouna-02" target="_blank">
  <img src="https://github.com/kanikadrouna-02.png" width="90" height="90" style="border-radius:50%; margin-right:20px;" alt="Kanika Drouna"/>
</a>

<!-- Priya -->
<a href="https://github.com/Tiwari-priya16" target="_blank">
  <img src="https://github.com/Tiwari-priya16.png" width="90" height="90" style="border-radius:50%; margin-right:20px;" alt="Priya Tiwari"/>
</a>

<!-- Harsh -->
<a href="https://github.com/Harsh-Kapadiya" target="_blank">
  <img src="https://github.com/Harsh-Kapadiya.png" width="90" height="90" style="border-radius:50%; margin-right:20px;" alt="Harsh Kapadiya"/>
</a>

<!-- Sakshi (Placeholder) -->
<a href="#" target="_blank">
  <img src="https://via.placeholder.com/90?text=S" width="90" height="90" style="border-radius:50%; margin-right:20px;" alt="Sakshi"/>
</a>

</p>
" width="90" height="90" style="border-radius:50%; margin-right:20px;" alt="Sakshi"/>
</a>

</p>



📜 License

This project is licensed under the MIT License.

❤️ Created by Harsh Kapadiya