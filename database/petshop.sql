-- ----------------------------------------------
-- -------------- DATABASE CREATION -------------
-- ----------------------------------------------
CREATE DATABASE petshop;
USE petshop;


-- ----------------------------------------------
-- 1. PETS TABLE
-- ----------------------------------------------

CREATE TABLE Pets(
    pet_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    breed VARCHAR(100),
    age INT
);

INSERT INTO Pets (pet_id, name, type, breed, age) VALUES
(1, 'Luna', 'cat', 'Persian', 2),
(2, 'Charlie', 'dog', 'Golden Retriever', 3),
(3, 'Milo', 'cat', 'Siamese', 1),
(4, 'Bella', 'dog', 'Beagle', 4),
(5, 'Rocky', 'dog', 'Labrador', 2),
(6, 'Coco', 'cat', 'Maine Coon', 3),
(7, 'Max', 'dog', 'Pug', 1),
(8, 'Simba', 'cat', 'Bengal', 2),
(9, 'Buddy', 'dog', 'Husky', 4),
(10, 'Daisy', 'cat', 'British Shorthair', 5),
(11, 'Bruno', 'dog', 'Bulldog', 3),
(12, 'Oreo', 'cat', 'Ragdoll', 2),
(13, 'Shadow', 'dog', 'German Shepherd', 5),
(14, 'Chloe', 'cat', 'Sphynx', 1);


-- ----------------------------------------------
-- 2. PRODUCTS TABLE
-- ----------------------------------------------

CREATE TABLE Products(
    product_id INT PRIMARY KEY,
    product_name VARCHAR(100),
    product_for VARCHAR(50),
    price DECIMAL(10,2),
    stock_quantity INT
);

INSERT INTO Products (product_id, product_name, product_for, price, stock_quantity) VALUES
(1, 'Cat Food', 'cat', 20.00, 50),
(2, 'Dog Toy', 'dog', 15.00, 30),
(3, 'Leash', 'dog', 25.00, 20),
(4, 'Cat Scratcher', 'cat', 30.00, 15),
(5, 'Dog Shampoo', 'dog', 18.00, 25),
(6, 'Cat Litter', 'cat', 12.00, 40),
(7, 'Pet Bed', 'dog', 35.00, 10),
(8, 'Cat Collar', 'cat', 8.00, 60),
(9, 'Dog Bowl', 'dog', 10.00, 50),
(10, 'Cat Treats', 'cat', 14.00, 70),
(11, 'Pet Blanket', 'dog', 22.00, 20),
(12, 'Fish Food', 'fish', 5.00, 80),
(13, 'Bird Cage', 'bird', 45.00, 10),
(14, 'Turtle Tank', 'turtle', 90.00, 5);


-- ----------------------------------------------
-- 3. CUSTOMERS TABLE
-- ----------------------------------------------

CREATE TABLE Customers(
    cid INT PRIMARY KEY,
    cname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
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


-- ----------------------------------------------
-- 4. DOCTORS TABLE
-- ----------------------------------------------

CREATE TABLE Doctors(
    doctor_id INT PRIMARY KEY,
    doctor_name VARCHAR(100)
);

INSERT INTO Doctors (doctor_id, doctor_name) VALUES
(1, 'Dr. Smith'),
(2, 'Dr. Johnson'),
(3, 'Dr. Mehta'),
(4, 'Dr. Sharma'),
(5, 'Dr. Verma'),
(6, 'Dr. Singh'),
(7, 'Dr. Kapoor'),
(8, 'Dr. Das'),
(9, 'Dr. Bose'),
(10, 'Dr. Rao'),
(11, 'Dr. Menon'),
(12, 'Dr. Iyer'),
(13, 'Dr. Naik'),
(14, 'Dr. Reddy');


-- ----------------------------------------------
-- 5. APPOINTMENT TABLE
-- ----------------------------------------------

CREATE TABLE Book_appointment(
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


-- ----------------------------------------------
-- 6. ADMIN TABLE
-- ----------------------------------------------

CREATE TABLE Admin(
    admin_id INT PRIMARY KEY,
    shop_address VARCHAR(255),
    ph_no BIGINT,
    doctor_id INT,
    product_id INT,
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

INSERT INTO Admin (admin_id, shop_address, ph_no, doctor_id, product_id) VALUES
(1, '456 Pet St, Cityville', 9876543210, 1, 2),
(2, '789 Park Ave, Metrocity', 9876500020, 2, 4),
(3, '123 Hill Rd, Hilltown', 9876500030, 3, 6),
(4, '45 Rose St, Townsville', 9876500040, 4, 1),
(5, '67 Lake View, Eastend', 9876500050, 5, 3),
(6, '88 Maple Rd, Cityville', 9876500060, 6, 5),
(7, '32 Pearl Ave, Westend', 9876500070, 7, 7),
(8, '99 Sun St, Smalltown', 9876500080, 8, 8),
(9, '41 River Dr, Northcity', 9876500090, 9, 9),
(10, '76 Star Rd, Patnagarh', 9876500100, 10, 10),
(11, '12 Moon Rd, Patna', 9876500110, 11, 11),
(12, '65 Greenway, Metrocity', 9876500120, 12, 12),
(13, '84 Ocean Ave, Cityville', 9876500130, 13, 13),
(14, '21 Elm St, Eastend', 9876500140, 14, 14);


-- ----------------------------------------------
-- 7. ADOPTIONS TABLE
-- ----------------------------------------------

CREATE TABLE Adoptions(
    adoption_id INT PRIMARY KEY,
    adoption_date DATETIME DEFAULT CURRENT_TIMESTAMP,
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


-- ----------------------------------------------
-- 8. ORDERS TABLE
-- ----------------------------------------------

CREATE TABLE Orders(
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


-- ----------------------------------------------
-- Database setup completed
-- ----------------------------------------------
