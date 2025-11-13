-- -----------------------------------------------------
-- DATABASE CREATION
-- -----------------------------------------------------
CREATE DATABASE petshop;
USE petshop;

-- -----------------------------------------------------
-- 1. PETS TABLE
-- -----------------------------------------------------
CREATE TABLE Pets (
    pet_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    breed VARCHAR(100),
    age INT,
    images varchar(150)
);

INSERT INTO Pets (pet_id, name, type, breed, age, images) VALUES
(1, 'Luna', 'cat', 'Persian', 2, 'pets/luna.jpg'),
(2, 'Charlie', 'dog', 'Golden Retriever', 3,'pets/charlie.jpg'),
(3, 'Milo', 'cat', 'Siamese', 1, 'pets/milo.jpg'),
(4, 'Bella', 'dog', 'Beagle', 4, 'pets/bella.jpg'),
(5, 'Rocky', 'dog', 'Labrador', 2, 'pets/rocky.jpg'),
(6, 'Coco', 'cat', 'Maine Coon', 3, 'pets/coco.jpg'),
(7, 'Max', 'dog', 'Pug', 1, 'pets/max.jpg'),
(8, 'Simba', 'cat', 'Bengal', 2, 'pets/simba.jpg'),
(9, 'Buddy', 'dog', 'Husky', 4, 'pets/buddy.jpg'),
(10, 'Daisy', 'cat', 'British Shorthair', 5, 'pets/daisy.jpg'),
(11, 'Bruno', 'dog', 'Bulldog', 3, 'pets/bruno.jpg'),
(12, 'Oreo', 'cat', 'Ragdoll', 2, 'pets/oreo.jpg'),
(13, 'Shadow', 'dog', 'German Shepherd', 5, 'pets/shadow.jpg'),
(14, 'Chloe', 'cat', 'Sphynx', 1, 'pets/chloe.jpg');

-- -----------------------------------------------------
-- 2. PRODUCTS TABLE (updated with image path)
-- -----------------------------------------------------
CREATE TABLE Products (
    product_id INT PRIMARY KEY,
    product_name VARCHAR(100),
    product_for VARCHAR(50),
    price DECIMAL(10,2),
    stock_quantity INT,
    images VARCHAR(255)
);

INSERT INTO Products (product_id, product_name, product_for, price, stock_quantity, images) VALUES
(1, 'Cat Food', 'cat', 20.00, 50, 'Products/cat_food.jpg'),
(2, 'Dog Toy', 'dog', 15.00, 30, 'Products/dog_toy.jpg'),
(3, 'Leash', 'dog', 25.00, 20, 'Products/leash.jpg'),
(4, 'Cat Scratcher', 'cat', 30.00, 15, 'Products/cat_scratcher.jpg'),
(5, 'Dog Shampoo', 'dog', 18.00, 25, 'Products/dog_shampoo.jpg'),
(6, 'Cat Litter', 'cat', 12.00, 40, 'Products/cat_litter.jpg'),
(7, 'Pet Bed', 'dog', 35.00, 10, 'Products/pet_bed.jpg'),
(8, 'Cat Collar', 'cat', 8.00, 60, 'Products/cat_coller.jpg'),
(9, 'Dog Food', 'dog', 10.00, 50, 'Products/dog_food_2.jpg'),
(10, 'Cat Treats', 'cat', 14.00, 70, 'Products/cat_treats.jpg'),
(11, 'Pet Blanket', 'dog', 22.00, 20, 'Products/pet_blankets.jpg'),
(12, 'Fish Food', 'fish', 5.00, 80, 'Products/fish_food.jpg'),
(13, 'Bird Cage', 'bird', 45.00, 10, 'Products/bird_cage.jpg'),
(14, 'Turtle Tank', 'turtle', 90.00, 5, 'Products/turtle_tank.jpg');


-- -----------------------------------------------------
-- 3. CUSTOMERS TABLE
-- -----------------------------------------------------
CREATE TABLE Customers (
    cid INT PRIMARY KEY,
    cname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    address VARCHAR(255),
    ph_no BIGINT,
    password VARCHAR(255) NOT NULL
);

INSERT INTO Customers (cid, cname, email, address, ph_no, password) VALUES
(1, 'Harsh', 'harsh2gmail.com', '123 Main St, Cityville', 1234567890, 'user123'),
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
CREATE TABLE Veterinarians(
    vet_id INT PRIMARY KEY,
    vet_name VARCHAR(100),
    specialization VARCHAR(100),
    working_days VARCHAR(255),
    start_time TIME,
    end_time TIME,
    max_patients_per_day INT
);

INSERT INTO Veterinarians (vet_id, vet_name, specialization, working_days, start_time, end_time, max_patients_per_day) VALUES
(1, 'Dr. Smith', 'Small Animal Internal Medicine', 'Monday, Wednesday, Friday', '09:00:00', '17:00:00', 15),
(2, 'Dr. Johnson', 'Dermatology (Animals)', 'Tuesday, Thursday', '10:00:00', '18:00:00', 12),
(3, 'Dr. Lee', 'Avian & Exotics', 'Monday, Tuesday, Wednesday, Thursday, Friday', '08:30:00', '16:30:00', 20),
(4, 'Dr. Sharma', 'Veterinary Neurology', 'Wednesday, Friday', '11:00:00', '19:00:00', 10),
(5, 'Dr. Verma', 'Orthopedic Surgery (Animals)', 'Monday, Tuesday, Thursday', '09:30:00', '17:30:00', 14),
(6, 'Dr. Singh', 'General Practice (Companion Animals)', 'Monday, Tuesday, Wednesday, Thursday, Friday, Saturday', '09:00:00', '13:00:00', 25),
(7, 'Dr. Kapoor', 'Veterinary Ophthalmology', 'Tuesday, Thursday, Saturday', '10:00:00', '16:00:00', 18),
(8, 'Dr. Das', 'Equine Medicine', 'Monday, Wednesday, Friday', '09:00:00', '17:00:00', 16),
(9, 'Dr. Bose', 'Animal Behaviorist', 'Tuesday, Thursday', '10:00:00', '18:00:00', 8),
(10, 'Dr. Rao', 'Reproductive Medicine (Animals)', 'Monday, Wednesday, Friday', '08:00:00', '16:00:00', 15),
(11, 'Dr. Menon', 'Emergency & Critical Care', 'Tuesday, Thursday, Saturday', '09:00:00', '17:00:00', 13),
(12, 'Dr. Iyer', 'Veterinary Oncology', 'Monday, Wednesday', '10:00:00', '18:00:00', 10),
(13, 'Dr. Naik', 'Zoo & Wildlife Medicine', 'Tuesday, Thursday, Friday', '09:30:00', '17:30:00', 11),
(14, 'Dr. Reddy', 'Veterinary Dentistry', 'Monday, Wednesday, Saturday', '08:30:00', '16:30:00', 14);


-- -----------------------------------------------------
-- 5. BOOK APPOINTMENT TABLE
-- -----------------------------------------------------
CREATE TABLE Book_appointment (
    appointment_id INT PRIMARY KEY,
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
(1, 1, '2024-07-01 10:00:00', 'dog', 'Golden Retriever', 2, 1),
(2, 2, '2024-07-02 11:30:00', 'cat', 'Persian', 1, 2),
(3, 3, '2024-07-03 09:15:00', 'dog', 'Beagle', 4, 3),
(4, 4, '2024-07-04 13:00:00', 'dog', 'Labrador', 5, 4),
(5, 5, '2024-07-05 15:45:00', 'cat', 'Bengal', 8, 5),
(6, 6, '2024-07-06 10:15:00', 'dog', 'Pug', 7, 6),
(7, 7, '2024-07-07 14:00:00', 'cat', 'Siamese', 3, 7),
(8, 8, '2024-07-08 12:00:00', 'dog', 'Bulldog', 11, 8),
(9, 9, '2024-07-09 16:30:00', 'cat', 'Ragdoll', 12, 9),
(10, 10, '2024-07-10 09:45:00', 'dog', 'Husky', 9, 10),
(11, 11, '2024-07-11 11:00:00', 'dog', 'German Shepherd', 13, 11),
(12, 12, '2024-07-12 15:00:00', 'cat', 'Sphynx', 14, 12),
(13, 13, '2024-07-13 10:30:00', 'cat', 'Maine Coon', 6, 13),
(14, 14, '2024-07-14 13:30:00', 'dog', 'Beagle', 4, 14);

-- -----------------------------------------------------
-- 6. ADMIN TABLE
-- -----------------------------------------------------
CREATE TABLE Admin (
    admin_name varchar(50),
    admin_id INT PRIMARY KEY,
    shop_address VARCHAR(255),
    email VARCHAR(100) UNIQUE NOT NULL,
    PASSWORD BIGINT,
    ph_no BIGINT,
    doctor_id INT,
    product_id INT,
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

INSERT INTO Admin (admin_name, admin_id, shop_address, emial, password, ph_no, doctor_id, product_id) VALUES
('Harsh',1, '456 Pet St, Cityville', 'harsh2345@gmail.com',123456789, 9876543210, 1, 2),
('Vikas', 2, '789 Park Ave, Metrocity','vk284356@gmail.com', 456789123, 9876500020, 2, 4)

-- -----------------------------------------------------
-- 7. ADOPTIONS TABLE
-- -----------------------------------------------------
CREATE TABLE Adoptions (
    adoption_id INT PRIMARY KEY,
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
    order_id INT PRIMARY KEY,
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
