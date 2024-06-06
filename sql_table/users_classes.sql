CREATE TABLE class_registrations (
    classreg_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    course_id INT,
    subject_id INT,
    year_level INT,
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(users_id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

