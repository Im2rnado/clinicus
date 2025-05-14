-- Sample data for Clinicus database (Egyptian Arabic context)

-- Insert user types
INSERT INTO `usertype` (`Id`, `UserType`, `RoleId`) VALUES
(1, 'Admin', 1),
(2, 'Doctor', 2),
(3, 'Patient', 3),
(4, 'Staff', 4);

-- Insert users
INSERT INTO `users` (`Id`, `Name`, `Email`, `Password`, `Phone`, `DOB`, `Gender`, `Address`, `Role`) VALUES
-- Admin
(1, 'Ahmed Mohamed', 'ahmed.mohamed@clinicus.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0100123456', '1985-05-15', 'Male', 'El Moez Street, Cairo', 1),
-- Doctors
(2, 'Dr. Mohamed Abdullah', 'mohamed.abdullah@clinicus.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0111234567', '1975-03-20', 'Male', 'Arab League St, Giza', 2),
(3, 'Dr. Fatma Hussein', 'fatma.hussein@clinicus.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0122345678', '1980-08-12', 'Female', 'Pyramid Street, Giza', 2),
(4, 'Dr. Amir Hassan', 'amir.hassan@clinicus.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0133456789', '1972-11-05', 'Male', 'Maadi, Cairo', 2),
-- Patients
(5, 'Nour Ibrahim', 'nour.ibrahim@email.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0144567890', '1990-04-25', 'Female', 'El Nasr Road, Nasr City', 3),
(6, 'Kareem Saeed', 'kareem.saeed@email.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0155678901', '1988-09-17', 'Male', 'Fayoum Street, Mohandessin', 3),
(7, 'Mariam Khaled', 'mariam.khaled@email.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0166789012', '1995-01-30', 'Female', 'Street 9, Mokattam', 3),
(8, 'Youssef Omar', 'youssef.omar@email.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0177890123', '1982-06-14', 'Male', 'Sheikh Zayed Road, October', 3),
-- Staff
(9, 'Lina Adel', 'lina.adel@clinicus.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0188901234', '1987-07-22', 'Female', 'Ramses Street, Downtown', 4),
(10, 'Tarek Mahmoud', 'tarek.mahmoud@clinicus.com', '$2y$10$9GxS7Jgd1Oa0t3Kx5z9zHuQQ8Jg9Jx9Jx9Jx9Jx9Jx9Jx9Jx9', '0199012345', '1983-12-10', 'Male', 'Abbas El Akkad St, Nasr City', 4);

-- Insert patients
INSERT INTO `patients` (`Id`, `UserId`, `HasHistory`) VALUES
(1, 5, TRUE),
(2, 6, FALSE),
(3, 7, TRUE),
(4, 8, TRUE);

-- Insert doctors
INSERT INTO `doctors` (`Id`, `UserId`, `Specialization`, `YearsOfExperience`, `Rating`) VALUES
(1, 2, 'Cardiology', 15, 5),
(2, 3, 'Pediatrics', 10, 4),
(3, 4, 'Neurology', 20, 5);

-- Insert staff
INSERT INTO `staff` (`Id`, `UserId`, `RoleDescription`) VALUES
(1, 9, 'Receptionist'),
(2, 10, 'Nurse');

-- Insert appointments
INSERT INTO `appointments` (`Id`, `PatientId`, `DoctorId`, `AppointmentDate`, `Status`) VALUES
(1, 1, 1, '2025-03-25 10:00:00', 'Scheduled'),
(2, 2, 2, '2025-03-26 11:30:00', 'Scheduled'),
(3, 3, 3, '2025-03-25 14:00:00', 'Scheduled'),
(4, 4, 1, '2025-03-27 09:15:00', 'Scheduled'),
(5, 1, 2, '2025-03-29 13:45:00', 'Scheduled');

-- Insert medical history
INSERT INTO `medical_history` (`Id`, `PatientId`, `Diagnosis`, `Treatment`, `RecordDate`) VALUES
(1, 1, 'Hypertension', 'Medication and lifestyle modifications', '2024-12-15'),
(2, 3, 'Asthma', 'Inhaler prescription and regular follow-up', '2025-01-10'),
(3, 4, 'Type 2 Diabetes', 'Diet plan and oral medication', '2025-02-05');

-- Insert medications
INSERT INTO `medications` (`Id`, `Name`, `Description`, `SideEffects`) VALUES
(1, 'Lisinopril', 'ACE inhibitor for treating hypertension', 'Dry cough, dizziness, headache'),
(2, 'Metformin', 'Oral medication for type 2 diabetes', 'Nausea, diarrhea, stomach upset'),
(3, 'Albuterol', 'Bronchodilator for asthma', 'Tremors, nervousness, increased heart rate'),
(4, 'Atorvastatin', 'Statin for high cholesterol', 'Muscle pain, elevated liver enzymes'),
(5, 'Amoxicillin', 'Antibiotic for bacterial infections', 'Diarrhea, rash, nausea');

-- Insert prescriptions
INSERT INTO `prescriptions` (`Id`, `PatientId`, `DoctorId`, `MedicationId`, `Dosage`, `Instructions`, `DatePrescribed`) VALUES
(1, 1, 1, 1, '10 mg', 'Take once daily in the morning with water', '2025-03-10'),
(2, 3, 3, 3, '90 mcg', 'Inhale as needed for shortness of breath, up to 4 times daily', '2025-03-12'),
(3, 4, 1, 2, '500 mg', 'Take twice daily with meals', '2025-03-15'),
(4, 2, 2, 5, '500 mg', 'Take three times daily for 7 days', '2025-03-18');

-- Insert storage data
INSERT INTO `storage` (`Id`, `Capacity`, `CurrentStock`, `LastRefilledDate`) VALUES
(1, 1000, 750, '2025-03-01'),
(2, 500, 350, '2025-03-05'),
(3, 200, 150, '2025-03-10');

-- Insert audit logs
INSERT INTO `audit_logs` (`Id`, `UserId`, `Action`, `Timestamp`) VALUES
(1, 1, 'User login', '2025-03-20 08:30:00'),
(2, 2, 'Updated patient record', '2025-03-20 10:15:00'),
(3, 9, 'Created new appointment', '2025-03-20 11:45:00'),
(4, 3, 'Issued new prescription', '2025-03-20 13:20:00'),
(5, 10, 'Updated inventory', '2025-03-20 15:10:00'),
(6, 1, 'System backup', '2025-03-20 17:00:00');