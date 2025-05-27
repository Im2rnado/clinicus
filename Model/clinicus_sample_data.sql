-- Clinicus Database Complete Setup
-- Drop database if exists and create fresh
DROP DATABASE IF EXISTS clinicus_db;
CREATE DATABASE clinicus_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinicus_db;

-- Create Tables (in dependency order)

-- 1. Languages
CREATE TABLE Languages (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. User_Types
CREATE TABLE User_Types (
    userTypeID INT AUTO_INCREMENT PRIMARY KEY,
    Type VARCHAR(50) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3. Address
CREATE TABLE Address (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 4. Department
CREATE TABLE Department (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 5. Positions (renamed to avoid reserved keyword conflict)
CREATE TABLE Positions (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 6. Users
CREATE TABLE Users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    dob DATETIME,
    addressID INT,
    roleID INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (addressID) REFERENCES Address(Id),
    FOREIGN KEY (roleID) REFERENCES User_Types(userTypeID)
);

-- 7. Blood_Type
CREATE TABLE Blood_Type (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Type VARCHAR(50) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 8. Patients
CREATE TABLE Patients (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    bloodType INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID),
    FOREIGN KEY (bloodType) REFERENCES Blood_Type(ID)
);

-- 9. Doctor_Types
CREATE TABLE Doctor_Types (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Specialization VARCHAR(255) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 10. Doctors
CREATE TABLE Doctors (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    yearsOfExperince INT DEFAULT 0,
    rating INT DEFAULT 0,
    doctorType INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID),
    FOREIGN KEY (doctorType) REFERENCES Doctor_Types(ID)
);

-- 11. Staff
CREATE TABLE Staff (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    hiredAt DATE,
    departmentID INT,
    positionID INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID),
    FOREIGN KEY (departmentID) REFERENCES Department(ID),
    FOREIGN KEY (positionID) REFERENCES Positions(ID)
);

-- 12. Telephone
CREATE TABLE Telephone (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    telephone VARCHAR(13) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

-- 13. Email
CREATE TABLE Email (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

-- 14. Appointment
CREATE TABLE Appointment (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    DoctorID INT NOT NULL,
    userID INT NOT NULL,
    appointmentDate DATETIME NOT NULL,
    status INT DEFAULT 0,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (DoctorID) REFERENCES Doctors(ID),
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

-- 15. Category
CREATE TABLE Category (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 16. Services
CREATE TABLE Services (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category INT,
    price DECIMAL(10,2),
    active BOOLEAN DEFAULT TRUE,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category) REFERENCES Category(ID)
);

-- 17. Service_Types
CREATE TABLE Service_Types (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    serviceName VARCHAR(50) NOT NULL,
    description TEXT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 18. Dosage_Form
CREATE TABLE Dosage_Form (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Form VARCHAR(100) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 19. Unit
CREATE TABLE Unit (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(15) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 20. Medication_Info
CREATE TABLE Medication_Info (
    serviceID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    brandName VARCHAR(100),
    dosageForm INT,
    strenght INT,
    unit INT,
    category INT,
    expireDuration INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dosageForm) REFERENCES Dosage_Form(ID),
    FOREIGN KEY (unit) REFERENCES Unit(ID),
    FOREIGN KEY (category) REFERENCES Category(ID)
);

-- 21. Appointment_Details
CREATE TABLE Appointment_Details (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    appointmentID INT NOT NULL,
    serviceID INT NOT NULL,
    quantity INT DEFAULT 1,
    pricePerUnit DECIMAL(10,2),
    totalPrice DECIMAL(10,2),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (appointmentID) REFERENCES Appointment(ID),
    FOREIGN KEY (serviceID) REFERENCES Services(ID)
);

-- 22. Medical_History
CREATE TABLE Medical_History (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    patientID INT NOT NULL,
    serviceID INT,
    appointmentID INT,
    description TEXT,
    createdBy INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patientID) REFERENCES Patients(ID),
    FOREIGN KEY (serviceID) REFERENCES Services(ID),
    FOREIGN KEY (appointmentID) REFERENCES Appointment(ID),
    FOREIGN KEY (createdBy) REFERENCES Users(userID)
);

-- 23. Insurance_Provider
CREATE TABLE Insurance_Provider (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    discount INT DEFAULT 0,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 24. Patient_Insurance
CREATE TABLE Patient_Insurance (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    patientID INT NOT NULL,
    providerID INT NOT NULL,
    insuranceNO VARCHAR(255) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patientID) REFERENCES Patients(ID),
    FOREIGN KEY (providerID) REFERENCES Insurance_Provider(ID)
);

-- 25. Payment_Methods
CREATE TABLE Payment_Methods (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 26. Options
CREATE TABLE Options (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    Type VARCHAR(255),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 27. Payment_Method_Options
CREATE TABLE Payment_Method_Options (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    paymentID INT NOT NULL,
    optionID INT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paymentID) REFERENCES Payment_Methods(ID),
    FOREIGN KEY (optionID) REFERENCES Options(ID)
);

-- 28. Payment_Method_Values
CREATE TABLE Payment_Method_Values (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    PMOpID INT NOT NULL,
    value DECIMAL(10,2),
    appointementID INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (PMOpID) REFERENCES Payment_Method_Options(ID),
    FOREIGN KEY (appointementID) REFERENCES Appointment(ID)
);

-- 29. Render_Payment_Methods
CREATE TABLE Render_Payment_Methods (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    pmID INT NOT NULL,
    HTML TEXT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pmID) REFERENCES Payment_Methods(ID)
);

-- 30. Pages
CREATE TABLE Pages (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    friendlyName VARCHAR(255) NOT NULL,
    linkAddress VARCHAR(255),
    HTML TEXT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 31. User_Type_Pages
CREATE TABLE User_Type_Pages (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    userTypeID INT NOT NULL,
    pageID INT NOT NULL,
    orderValue INT DEFAULT 0,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userTypeID) REFERENCES User_Types(userTypeID),
    FOREIGN KEY (pageID) REFERENCES Pages(ID)
);

-- 32. Message_Type
CREATE TABLE Message_Type (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Type TEXT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 33. Messages
CREATE TABLE Messages (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    messageTemplate TEXT NOT NULL,
    typeID INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (typeID) REFERENCES Message_Type(Id)
);

-- 34. Message_User
CREATE TABLE Message_User (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    messageID INT NOT NULL,
    userID INT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (messageID) REFERENCES Messages(Id),
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

-- 35. Word
CREATE TABLE Word (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    word VARCHAR(50) NOT NULL,
    languageID INT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (languageID) REFERENCES Languages(ID)
);

-- 36. Translation
CREATE TABLE Translation (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    firstLangCode INT NOT NULL,
    secondLangCode INT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (firstLangCode) REFERENCES Languages(ID),
    FOREIGN KEY (secondLangCode) REFERENCES Languages(ID)
);

-- 37. Translation_Details
CREATE TABLE Translation_Details (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TransID INT NOT NULL,
    pageID INT,
    wordID INT NOT NULL,
    TransWordID INT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (TransID) REFERENCES Translation(ID),
    FOREIGN KEY (pageID) REFERENCES Pages(ID),
    FOREIGN KEY (wordID) REFERENCES Word(ID),
    FOREIGN KEY (TransWordID) REFERENCES Word(ID)
);

-- 38. Customized_Report
CREATE TABLE Customized_Report (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    reportName TEXT NOT NULL,
    SQLstatement TEXT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

-- INSERT SAMPLE DATA

-- Languages
INSERT INTO Languages (name) VALUES 
('Arabic'), ('English'), ('French'), ('German'), ('Spanish'), ('Italian'), ('Turkish');

-- User_Types
INSERT INTO User_Types (Type) VALUES 
('Admin'), ('Doctor'), ('Patient'), ('Nurse'), ('Receptionist'), ('Pharmacist'), ('Manager');

-- Address
INSERT INTO Address (name) VALUES 
('15 Tahrir Square, Downtown Cairo'),
('28 Zamalek Street, Zamalek, Cairo'),
('45 Nasr City, New Cairo'),
('12 Alexandria Road, Giza'),
('33 Heliopolis, Cairo'),
('67 Maadi, Cairo'),
('89 Dokki, Giza'),
('23 6th October City');

-- Department
INSERT INTO Department (name) VALUES 
('Cardiology'), ('Pediatrics'), ('Orthopedics'), ('Neurology'), ('Emergency'), ('Pharmacy'), ('Administration'), ('Reception');

-- Positions
INSERT INTO Positions (name) VALUES 
('Head Doctor'), ('Senior Nurse'), ('Junior Doctor'), ('Pharmacist'), ('Receptionist'), ('Manager'), ('Administrator'), ('Technician');

-- Users (Mixed roles)
INSERT INTO Users (FirstName, LastName, username, password, dob, addressID, roleID) VALUES 
('Ahmed', 'Hassan', 'ahmed.hassan', 'hashed_password_1', '1980-05-15', 1, 2),
('Fatma', 'Mohamed', 'fatma.mohamed', 'hashed_password_2', '1985-08-22', 2, 3),
('Omar', 'Ali', 'omar.ali', 'hashed_password_3', '1975-12-10', 3, 2),
('Nadia', 'Ibrahim', 'nadia.ibrahim', 'hashed_password_4', '1990-03-18', 4, 4),
('Khaled', 'Mahmoud', 'khaled.mahmoud', 'hashed_password_5', '1978-07-25', 5, 1),
('Amira', 'Youssef', 'amira.youssef', 'hashed_password_6', '1988-11-12', 6, 5),
('Mostafa', 'Abdel Rahman', 'mostafa.abdel', 'hashed_password_7', '1982-09-05', 7, 3),
('Mona', 'Farouk', 'mona.farouk', 'hashed_password_8', '1987-04-28', 8, 6);

-- Blood_Type
INSERT INTO Blood_Type (Type) VALUES 
('A+'), ('A-'), ('B+'), ('B-'), ('AB+'), ('AB-'), ('O+'), ('O-');

-- Patients (referencing patient users)
INSERT INTO Patients (userID, bloodType) VALUES 
(2, 1), (7, 3), (8, 5);

-- Doctor_Types
INSERT INTO Doctor_Types (Specialization) VALUES 
('Cardiologist'), ('Pediatrician'), ('Orthopedic Surgeon'), ('Neurologist'), ('General Practitioner'), ('Emergency Medicine'), ('Radiologist');

-- Doctors (referencing doctor users)
INSERT INTO Doctors (userID, yearsOfExperince, rating, doctorType) VALUES 
(1, 15, 95, 1),
(3, 20, 98, 3),
(5, 12, 92, 5);

-- Staff (referencing staff users)
INSERT INTO Staff (userID, hiredAt, departmentID, positionID) VALUES 
(4, '2020-01-15', 2, 2),
(6, '2019-06-10', 8, 5),
(8, '2021-03-20', 6, 4);

-- Telephone
INSERT INTO Telephone (userID, telephone) VALUES 
(1, '+201012345678'),
(2, '+201087654321'),
(3, '+201123456789'),
(4, '+201198765432'),
(5, '+201234567890'),
(6, '+201156789012'),
(7, '+201178901234'),
(8, '+201145678901');

-- Email
INSERT INTO Email (userID, email) VALUES 
(1, 'ahmed.hassan@clinicus.com'),
(2, 'fatma.mohamed@gmail.com'),
(3, 'omar.ali@clinicus.com'),
(4, 'nadia.ibrahim@clinicus.com'),
(5, 'khaled.mahmoud@clinicus.com'),
(6, 'amira.youssef@clinicus.com'),
(7, 'mostafa.abdel@gmail.com'),
(8, 'mona.farouk@clinicus.com');

-- Category
INSERT INTO Category (Name) VALUES 
('Consultation'), ('Laboratory'), ('Radiology'), ('Surgery'), ('Pharmacy'), ('Emergency'), ('Therapy');

-- Services
INSERT INTO Services (name, category, price, active) VALUES 
('General Consultation', 1, 200.00, TRUE),
('Blood Test', 2, 150.00, TRUE),
('X-Ray', 3, 300.00, TRUE),
('Heart Surgery', 4, 15000.00, TRUE),
('Paracetamol', 5, 25.00, TRUE),
('Emergency Visit', 6, 500.00, TRUE),
('Physiotherapy', 7, 180.00, TRUE),
('ECG', 2, 120.00, TRUE);

-- Service_Types
INSERT INTO Service_Types (serviceName, description) VALUES 
('Medical Consultation', 'General medical consultation with doctor'),
('Diagnostic Test', 'Laboratory and radiology tests'),
('Surgical Procedure', 'Surgical interventions and operations'),
('Medication', 'Prescribed medications and drugs'),
('Emergency Care', 'Emergency medical services'),
('Therapy', 'Physical and rehabilitation therapy');

-- Dosage_Form
INSERT INTO Dosage_Form (Form) VALUES 
('Tablet'), ('Capsule'), ('Syrup'), ('Injection'), ('Cream'), ('Drops'), ('Inhaler');

-- Unit
INSERT INTO Unit (Name) VALUES 
('mg'), ('ml'), ('g'), ('mcg'), ('IU'), ('tablets'), ('capsules');

-- Medication_Info
INSERT INTO Medication_Info (name, brandName, dosageForm, strenght, unit, category, expireDuration) VALUES 
('Paracetamol', 'Panadol', 1, 500, 1, 5, 36),
('Amoxicillin', 'Augmentin', 2, 250, 1, 5, 24),
('Salbutamol', 'Ventolin', 7, 100, 4, 5, 36),
('Insulin', 'Lantus', 4, 100, 5, 5, 18),
('Aspirin', 'Aspocid', 1, 75, 1, 5, 48),
('Omeprazole', 'Losec', 2, 20, 1, 5, 36);

-- Appointment
INSERT INTO Appointment (DoctorID, userID, appointmentDate, status) VALUES 
(1, 2, '2025-05-26 10:00:00', 1),
(2, 7, '2025-05-27 14:30:00', 0),
(3, 8, '2025-05-28 09:15:00', 1),
(1, 2, '2025-05-29 11:00:00', 0),
(2, 7, '2025-05-30 16:00:00', 0);

-- Appointment_Details
INSERT INTO Appointment_Details (appointmentID, serviceID, quantity, pricePerUnit, totalPrice) VALUES 
(1, 1, 1, 200.00, 200.00),
(1, 2, 1, 150.00, 150.00),
(2, 1, 1, 200.00, 200.00),
(3, 3, 1, 300.00, 300.00),
(4, 8, 1, 120.00, 120.00);

-- Medical_History
INSERT INTO Medical_History (patientID, serviceID, appointmentID, description, createdBy) VALUES 
(1, 1, 1, 'Patient complained of chest pain. ECG normal. Prescribed rest.', 1),
(2, 1, 2, 'Regular checkup. Blood pressure slightly elevated.', 3),
(3, 3, 3, 'X-ray showed minor fracture in left wrist.', 2),
(1, 2, 1, 'Blood test results normal except slightly high cholesterol.', 1);

-- Insurance_Provider
INSERT INTO Insurance_Provider (name, discount) VALUES 
('Egyptian Health Insurance', 20),
('Private Medical Insurance', 15),
('Government Employee Insurance', 25),
('Student Health Insurance', 30),
('Senior Citizen Insurance', 35),
('Corporate Health Plan', 18);

-- Patient_Insurance
INSERT INTO Patient_Insurance (patientID, providerID, insuranceNO) VALUES 
(1, 1, 'EHI-2025-001234'),
(2, 2, 'PMI-2025-005678'),
(3, 3, 'GEI-2025-009012');

-- Payment_Methods
INSERT INTO Payment_Methods (name) VALUES 
('Cash'), ('Credit Card'), ('Debit Card'), ('Bank Transfer'), ('Insurance'), ('Mobile Payment');

-- Options
INSERT INTO Options (name, Type) VALUES 
('Installments', 'Payment'),
('Full Payment', 'Payment'),
('Partial Payment', 'Payment'),
('Insurance Coverage', 'Coverage'),
('Self Pay', 'Coverage');

-- Payment_Method_Options
INSERT INTO Payment_Method_Options (paymentID, optionID) VALUES 
(1, 2), (2, 1), (2, 2), (3, 2), (4, 2), (5, 4), (6, 2);

-- Payment_Method_Values
INSERT INTO Payment_Method_Values (PMOpID, value, appointementID) VALUES 
(1, 350.00, 1),
(3, 200.00, 2),
(4, 300.00, 3),
(5, 120.00, 4);

-- Render_Payment_Methods
INSERT INTO Render_Payment_Methods (pmID, HTML) VALUES 
(1, '<div class="cash-payment">Cash Payment Form</div>'),
(2, '<div class="card-payment">Credit Card Form</div>'),
(5, '<div class="insurance-payment">Insurance Claim Form</div>');

-- Pages
INSERT INTO Pages (friendlyName, linkAddress, HTML) VALUES 
('Dashboard', '/dashboard', '<h1>Dashboard</h1>'),
('Appointments', '/appointments', '<h1>Appointments</h1>'),
('Patients', '/patients', '<h1>Patients</h1>'),
('Doctors', '/doctors', '<h1>Doctors</h1>'),
('Reports', '/reports', '<h1>Reports</h1>'),
('Settings', '/settings', '<h1>Settings</h1>');

-- User_Type_Pages
INSERT INTO User_Type_Pages (userTypeID, pageID, orderValue) VALUES 
(1, 1, 1), (1, 5, 2), (1, 6, 3),
(2, 1, 1), (2, 2, 2), (2, 3, 3),
(3, 1, 1), (3, 2, 2),
(4, 1, 1), (4, 3, 2),
(5, 1, 1), (5, 2, 2);

-- Message_Type
INSERT INTO Message_Type (Type) VALUES 
('Appointment Reminder'),
('Test Results'),
('Payment Due'),
('General Notification'),
('Emergency Alert'),
('Prescription Ready');

-- Messages
INSERT INTO Messages (messageTemplate, typeID) VALUES 
('Your appointment with Dr. {doctor_name} is scheduled for {date} at {time}', 1),
('Your test results are ready. Please contact the clinic.', 2),
('Payment of {amount} EGP is due for your recent visit.', 3),
('Welcome to Clinicus Health System!', 4),
('Emergency: Please contact the clinic immediately.', 5),
('Your prescription is ready for pickup.', 6);

-- Message_User
INSERT INTO Message_User (messageID, userID) VALUES 
(1, 2), (2, 7), (3, 8), (4, 2), (1, 7), (6, 8);

-- Word
INSERT INTO Word (word, languageID) VALUES 
('Dashboard', 2), ('لوحة التحكم', 1),
('Appointment', 2), ('موعد', 1),
('Patient', 2), ('مريض', 1),
('Doctor', 2), ('طبيب', 1),
('Medicine', 2), ('دواء', 1);

-- Translation
INSERT INTO Translation (firstLangCode, secondLangCode) VALUES 
(1, 2), (2, 1), (1, 3), (2, 3);

-- Translation_Details
INSERT INTO Translation_Details (TransID, pageID, wordID, TransWordID) VALUES 
(1, 1, 2, 1),
(2, 1, 1, 2),
(1, 2, 4, 3),
(2, 2, 3, 4);

-- Customized_Report
INSERT INTO Customized_Report (userID, reportName, SQLstatement) VALUES 
(5, 'Monthly Patient Report', 'SELECT COUNT(*) FROM Patients WHERE MONTH(createdAt) = MONTH(NOW())'),
(5, 'Doctor Performance', 'SELECT d.ID, u.FirstName, u.LastName, COUNT(a.ID) as appointments FROM Doctors d JOIN Users u ON d.userID = u.userID LEFT JOIN Appointment a ON d.ID = a.DoctorID GROUP BY d.ID'),
(1, 'Revenue Report', 'SELECT SUM(totalPrice) as total_revenue FROM Appointment_Details WHERE DATE(createdAt) >= DATE_SUB(NOW(), INTERVAL 30 DAY)');

-- Display completion message
SELECT 'Clinicus Database Setup Complete!' as Status;
SELECT 'Total Tables Created: 38' as Info;
SELECT 'Sample Data Inserted Successfully' as Data_Status;