CREATE TABLE student_enrollment_bytheusers (
    student_endroll_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    course_id INT,
    subject_id INT,
    year_level INT,
    student_id INT,
    FOREIGN KEY (user_id) REFERENCES teachusers(users_id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (student_id) REFERENCES students(id)
);
