<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Enrollment</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<form id="enrollForm" action="../add/process_enrollment.php" method="post">
    <h2>Student Enrollment</h2>
    
    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name" name="full_name" required><br>
    
    <label for="birthday">Birthday:</label>
    <input type="date" id="birthday" name="birthday" required><br>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>
    
    <label for="parent_contact">Parent's Contact:</label>
    <input type="text" id="parent_contact" name="parent_contact" required><br>
    
    <label for="course">Course:</label>
    <select id="course" name="course" required>
        <option value="">Select Course</option>
        <!-- Courses will be populated here -->
    </select><br>
    
    <label for="enroll_year">Year Level:</label>
    <select id="enroll_year" name="enroll_year" required>
        <option value="">Select Year</option>
        <option value="1">1st Year</option>
        <option value="2">2nd Year</option>
        <option value="3">3rd Year</option>
        <option value="4">4th Year</option>
    </select><br>

    <label for="subject">Subject:</label>
    <select id="subject" name="subject" required>
        <option value="">Select Subject</option>
        <!-- Subjects will be dynamically populated based on the course selection -->
    </select><br>
    
    <button type="submit">Enroll Student</button>
</form>

<script>
$(document).ready(function() {
    let subjects = []; // Declare this in the broader scope

    // Fetch courses immediately
    fetchCourses();

    // When the course selection changes, fetch the corresponding subjects
    $('#course').change(function() {
        let courseId = $(this).val();
        fetchSubjects(courseId);
    });

    // When the year level changes, re-filter the subjects based on the selected year
    $('#enroll_year').change(function() {
        filterSubjects();
    });

    function fetchCourses() {
        fetch('../fetch/fetch_courses.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(course => {
                    $('#course').append(new Option(course.name, course.id));
                });
            });
    }

    function fetchSubjects(courseId) {
        $('#subject').empty().append(new Option('Select Subject', ''));
        if (!courseId) {
            return; // No course selected
        }
        fetch(`../fetch/fetch_subjects.php?courseId=${courseId}`)
            .then(response => response.json())
            .then(data => {
                subjects = data; // Update the subjects variable with the fetched data
                filterSubjects(); // Filter subjects for the initially selected year (if any)
            });
    }

    function filterSubjects() {
        let selectedYear = $('#enroll_year').val();
        let filteredSubjects = subjects.filter(subject => subject.year_level == selectedYear);

        $('#subject').empty().append(new Option('Select Subject', ''));
        filteredSubjects.forEach(subject => {
            $('#subject').append(new Option(subject.name, subject.id));
        });
    }
});

</script>

</body>
</html>
