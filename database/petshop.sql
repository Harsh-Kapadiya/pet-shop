-- -----------------------------------------------------
-- DATABASE CREATION
-- -----------------------------------------------------
CREATE DATABASE petshop;
USE petshop;

-- -----------------------------------------------------
-- 1. PETS TABLE
-- -----------------------------------------------------
CREATE TABLE Pets (
    pet_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    breed VARCHAR(100),
    age INT,
    images VARCHAR(150)
);

INSERT INTO Pets (pet_id, name, type, breed, age, images) VALUES
(1, 'Luna', 'cat', 'Persian', 2, 'luna.jpg'),
(2, 'Charlie', 'dog', 'Golden Retriever', 3,'charlie.jpg'),
(3, 'Milo', 'cat', 'Siamese', 1, 'milo.jpg'),
(4, 'Bella', 'dog', 'Beagle', 4, 'bella.jpg'),
(5, 'Rocky', 'dog', 'Labrador', 2, 'rocky.jpg'),
(6, 'Coco', 'cat', 'Maine Coon', 3, 'coco.jpg'),
(7, 'Max', 'dog', 'Pug', 1, 'max.jpg'),
(8, 'Simba', 'cat', 'Bengal', 2, 'simba.jpg'),
(9, 'Buddy', 'dog', 'Husky', 4, 'buddy.jpg'),
(10, 'Daisy', 'cat', 'British Shorthair', 5, 'daisy.jpg'),
(11, 'Bruno', 'dog', 'Bulldog', 3, 'bruno.jpg'),
(12, 'Oreo', 'cat', 'Ragdoll', 2, 'oreo.jpg'),
(13, 'Shadow', 'dog', 'German Shepherd', 5, 'shadow.jpg'),
(14, 'Chloe', 'cat', 'Sphynx', 1, 'chloe.jpg');

-- -----------------------------------------------------
-- 2. PRODUCTS TABLE (updated with image path)
-- -----------------------------------------------------
CREATE TABLE Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100),
    product_for VARCHAR(50),
    price DECIMAL(10,2),
    stock_quantity INT,
    images VARCHAR(255)
);

INSERT INTO Products (product_id, product_name, product_for, price, stock_quantity, images) VALUES
(1, 'Cat Food', 'cat', 20.00, 50, 'cat_food.jpg'),
(2, 'Dog Toy', 'dog', 15.00, 30, 'dog_toy.jpg'),
(3, 'Leash', 'dog', 25.00, 20, 'leash.jpg'),
(4, 'Cat Scratcher', 'cat', 30.00, 15, 'cat_scratcher.jpg'),
(5, 'Dog Shampoo', 'dog', 18.00, 25, 'dog_shampoo.jpg'),
(6, 'Cat Litter', 'cat', 12.00, 40, 'cat_litter.jpg'),
(7, 'Pet Bed', 'dog', 35.00, 10, 'pet_bed.jpg'),
(8, 'Cat Collar', 'cat', 8.00, 60, 'cat_collar.jpg'),
(9, 'Dog Food', 'dog', 10.00, 50, 'dog_food_2.jpg'),
(10, 'Cat Treats', 'cat', 14.00, 70, 'cat_treats.jpg'),
(11, 'Pet Blanket', 'dog', 22.00, 20, 'pet_blanket.jpg'),
(12, 'Fish Food', 'fish', 5.00, 80, 'fish_food.jpg'),
(13, 'Bird Cage', 'bird', 45.00, 10, 'bird_cage.jpg'),
(14, 'Turtle Tank', 'turtle', 90.00, 5, 'turtle_tank.jpg');

-- -----------------------------------------------------
-- 3. CUSTOMERS TABLE
-- -----------------------------------------------------
CREATE TABLE Customers (
    cid INT AUTO_INCREMENT PRIMARY KEY,
    cname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    address VARCHAR(255),
    ph_no BIGINT,
    password VARCHAR(255) NOT NULL
);

INSERT INTO Customers (cid, cname, email, address, ph_no, password) VALUES
(1, 'Harsh', 'harsh@gmail.com', '123 Main St, Cityville', 1234567890, 'user123'),
(2, 'Alice', 'alice@gmail.com', '45 Green St, Townsville', 9876500012, 'alicepass'),
(3, 'Ravi', 'ravi@gmail.com', '56 River Rd, Hilltown', 8876500056, 'ravi@123'),
(4, 'Neha', 'neha@gmail.com', '89 Lake Ave, Metrocity', 7568901234, 'neha456'),
(5, 'Amit', 'amit@gmail.com', '101 Rose St, Patnagarh', 9988776655, 'amitpwd'),
(6, 'Kiran', 'kiran@gmail.com', '78 Maple St, Newcity', 8123456789, 'kiran789'),
(7, 'Suman', 'suman@gmail.com', '12 Lotus Rd, Smalltown', 7098654321, 'sum123'),
(8, 'Raj', 'raj@gmail.com', '65 Hill Rd, Metrocity', 7758996623, 'raj999'),
(9, 'Priya', 'priya@gmail.com', '88 Pearl St, Cityville', 9800012345, 'priyapass'),
(10, 'Vikram', 'vikram@gmail.com', '91 Riverbank, Eastend', 9977886655, 'vikram321'),
(11, 'Arjun', 'arjun@gmail.com', '22 Ocean Dr, Patna', 8899001122, 'arjun555'),
(12, 'Sneha', 'sneha@gmail.com', '78 Park St, Westend', 9098765432, 'sneha321'),
(13, 'Riya', 'riya@gmail.com', '45 Sun Rd, Patna', 9090123456, 'riya456'),
(14, 'Kunal', 'kunal@gmail.com', '87 River Rd, Northcity', 9123456780, 'kunal007');

-- -----------------------------------------------------
-- 4. DOCTORS TABLE
-- -----------------------------------------------------
CREATE TABLE Doctors(
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_name VARCHAR(100),
    specialization VARCHAR(100),
    working_days VARCHAR(255),
    start_time TIME,
    end_time TIME,
    max_patients_per_day INT
);

INSERT INTO Doctors (doctor_id, doctor_name, specialization, working_days, start_time, end_time, max_patients_per_day) VALUES
(1, 'Dr. Smith', 'Small Animal Internal Medicine', 'Mon, Wed, Fri', '09:00:00', '17:00:00', 15),
(2, 'Dr. Johnson', 'Dermatology', 'Tue, Thu', '10:00:00', '18:00:00', 12),
(3, 'Dr. Lee', 'Avian & Exotics', 'Mon-Fri', '08:30:00', '16:30:00', 20),
(4, 'Dr. Sharma', 'Neurology', 'Wed, Fri', '11:00:00', '19:00:00', 10),
(5, 'Dr. Verma', 'Orthopedic Surgery', 'Mon, Tue, Thu', '09:30:00', '17:30:00', 14),
(6, 'Dr. Singh', 'General Practice', 'Mon-Sat', '09:00:00', '13:00:00', 25),
(7, 'Dr. Kapoor', 'Ophthalmology', 'Tue, Thu, Sat', '10:00:00', '16:00:00', 18),
(8, 'Dr. Das', 'Equine Medicine', 'Mon, Wed, Fri', '09:00:00', '17:00:00', 16),
(9, 'Dr. Bose', 'Behaviorist', 'Tue, Thu', '10:00:00', '18:00:00', 8),
(10, 'Dr. Rao', 'Reproductive Medicine', 'Mon, Wed, Fri', '08:00:00', '16:00:00', 15),
(11, 'Dr. Menon', 'Emergency Care', 'Tue, Thu, Sat', '09:00:00', '17:00:00', 13),
(12, 'Dr. Iyer', 'Oncology', 'Mon, Wed', '10:00:00', '18:00:00', 10),
(13, 'Dr. Naik', 'Wildlife Medicine', 'Tue, Thu, Fri', '09:30:00', '17:30:00', 11),
(14, 'Dr. Reddy', 'Dentistry', 'Mon, Wed, Sat', '08:30:00', '16:30:00', 14);

-- -----------------------------------------------------
-- 5. BOOK APPOINTMENT TABLE
-- -----------------------------------------------------
CREATE TABLE Book_appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT,
    time DATETIME,
    pet_category VARCHAR(50),
    breed VARCHAR(100),
    pet_id INT,
    cid INT,
    FOREIGN KEY (pet_id) REFERENCES Pets(pet_id),
    FOREIGN KEY (cid) REFERENCES Customers(cid),
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id)
);

INSERT INTO Book_appointment (appointment_id, doctor_id, time, pet_category, breed, pet_id, cid) VALUES
(1, 1, '2025-12-01 10:00:00', 'dog', 'Golden Retriever', 2, 1),
(2, 2, '2025-12-02 11:30:00', 'cat', 'Persian', 1, 2),
(3, 3, '2025-12-03 09:15:00', 'dog', 'Beagle', 4, 3),
(4, 4, '2025-12-04 13:00:00', 'dog', 'Labrador', 5, 4),
(5, 5, '2025-12-05 15:45:00', 'cat', 'Bengal', 8, 5),
(6, 6, '2025-11-06 10:15:00', 'dog', 'Pug', 7, 6),
(7, 7, '2025-11-07 14:00:00', 'cat', 'Siamese', 3, 7),
(8, 8, '2025-11-08 12:00:00', 'dog', 'Bulldog', 11, 8),
(9, 9, '2025-11-09 16:30:00', 'cat', 'Ragdoll', 12, 9),
(10, 10, '2026-11-10 09:45:00', 'dog', 'Husky', 9, 10),
(11, 11, '2025-11-11 11:00:00', 'dog', 'German Shepherd', 13, 11),
(12, 12, '2026-11-12 15:00:00', 'cat', 'Sphynx', 14, 12),
(13, 13, '2025-11-13 10:30:00', 'cat', 'Maine Coon', 6, 13),
(14, 14, '2025-11-14 13:30:00', 'dog', 'Beagle', 4, 14);

-- -----------------------------------------------------
-- 6. ADMIN TABLE
-- -----------------------------------------------------
CREATE TABLE Admin (
    admin_name VARCHAR(50),
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    shop_address VARCHAR(255),
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255),
    ph_no BIGINT,
    doctor_id INT NULL,
    product_id INT NULL,
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

INSERT INTO Admin (admin_name, admin_id, shop_address, email, password, ph_no, doctor_id, product_id) VALUES
('Harsh',1, '456 Pet St, Cityville', 'harsh2345@gmail.com','123456789', 9876543210, 1, 2),
('Vikas', 2, '789 Park Ave, Metrocity','vk284356@gmail.com', '456789123', 9876500020, 2, 4);

-- -----------------------------------------------------
-- 7. ADOPTIONS TABLE
-- -----------------------------------------------------
CREATE TABLE Adoptions (
    adoption_id INT AUTO_INCREMENT PRIMARY KEY,
    cid INT,
    pet_id INT,
    FOREIGN KEY (cid) REFERENCES Customers(cid),
    FOREIGN KEY (pet_id) REFERENCES Pets(pet_id)
);

INSERT INTO Adoptions (adoption_id, cid, pet_id) VALUES
(1, 1, 4),
(2, 2, 2),
(3, 3, 5),
(4, 4, 9),
(5, 5, 11),
(6, 6, 7),
(7, 7, 12),
(8, 8, 10),
(9, 9, 6),
(10, 10, 13),
(11, 11, 1),
(12, 12, 14),
(13, 13, 3),
(14, 14, 8);

-- -----------------------------------------------------
-- 8. ORDERS TABLE
-- -----------------------------------------------------
CREATE TABLE Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    cid INT,
    product_id INT,
    quantity INT,
    total_price DECIMAL(10,2),
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cid) REFERENCES Customers(cid),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

INSERT INTO Orders (order_id, cid, product_id, quantity, total_price) VALUES
(1, 1, 1, 2, 40.00),
(2, 2, 2, 1, 15.00),
(3, 3, 3, 1, 25.00),
(4, 4, 4, 2, 60.00),
(5, 5, 5, 3, 54.00),
(6, 6, 6, 2, 24.00),
(7, 7, 7, 1, 35.00),
(8, 8, 8, 5, 40.00),
(9, 9, 9, 2, 20.00),
(10, 10, 10, 4, 56.00),
(11, 11, 11, 1, 22.00),
(12, 12, 12, 2, 10.00),
(13, 13, 13, 1, 45.00),
(14, 14, 14, 1, 90.00);

-- -----------------------------------------------------
-- ✅ Database setup completed successfully
-- -----------------------------------------------------
    