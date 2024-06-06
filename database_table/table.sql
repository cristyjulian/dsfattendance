CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);
CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    name VARCHAR(255) NOT NULL,
    year_level INT,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    email VARCHAR(255) NOT NULL,
    parent_contact VARCHAR(255) NOT NULL,
    subject_id INT,
    enroll_year INT,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);
CREATE TABLE class_registrations (
    classreg_id INT AUTO_INCREMENT PRIMARY KEY,
    users_id INT,
    course_id INT,
    subject_id INT,
    year_level INT,
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (users_id) REFERENCES users(users_id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    status VARCHAR(10) CHECK (status IN ('present', 'absent')),
    submitted_by INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id)
);
