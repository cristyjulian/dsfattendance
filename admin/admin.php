<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php'); // Redirect to login page if not admin
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Attendance</title>
    <link rel="shortcut icon" href="../dsficon.png">
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/admin.css" rel="stylesheet" />


    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <div class="logo-img">
            <img src="../DSFLOGO.png" alt="DSF Logo">
        </div>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <nav class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </nav>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="../admin/admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            HOME
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link" href="../admin/department.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                            Department
                        </a>
                        <a class="nav-link" href="../admin/subject_record.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                            Subjects
                        </a>
                        <a class="nav-link" href="../admin/student_endroll_record.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                            Students Enrolled
                        </a>
                        <a class="nav-link" href="../admin/class_record.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                Class Record
                            </a>
                        <a class="nav-link" href="../admin/attendance_record.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Attendance Record
                        </a>
                        <a class="nav-link" href="../admin/users_record.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                            Instructors
                        </a>
                        <a class="nav-link" href="../charts.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Charts
                        </a>
                        <a class="nav-link" href="#">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Data Table
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">ADMIN</h1>
                    <ol class="breadcrumb mb-4">
                        <li class=""><a href=""></a></li>
                        <li class=""></li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">

                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class=""></i>
                            ENDROLL STUDENTS
                        </div>
                        <div class="db">
                            <form id="enrollForm" action="../add/process_enrollment.php" method="post">
                                <label for="full_name">Full Name:</label>
                                <input type="text" id="full_name" name="full_name" required><br>

                                <label for="birthday">Birthday:</label>
                                <input type="date" id="birthday" name="birthday" required><br>

                                <label for="email">Parent Email:</label>
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
                                <center>
                                    <button type="submit">Enroll Student</button>
                                </center>
                            </form>
                        </div>
                        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class=""></i>
                                    ADD SUBJECT FOR EVERY COURSE
                                </div>
                                <div class="scd">
                                    <!-- Form for Adding Subjects -->
                                    <form action="../add/add_subject.php" method="post">
                                        Course: <select name="course_id" required>
                                            <option value="">Select a Course</option>
                                            <!-- Courses will be populated here by JavaScript -->
                                        </select><br>
                                        Subject Name: <input type="text" name="subject_name" required><br>
                                        Year Level: <input type="number" name="year_level" required><br>
                                        <input type="submit" value="Add Subject">
                                    </form>
                                </div>
                                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class=""></i>
                                    ADD COURSE
                                </div>
                                <div class="">
                                    <div class="sc">
                                        <!-- Form for Adding Courses -->
                                        <form action="../add/add_course.php" method="post">
                                            Course Name: <input type="text" name="course_name" required><br>
                                            <input type="submit" value="Add Course">
                                        </form>
                                    </div>
                                    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="assets/demo/chart-pie-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            // Fetch courses and populate the dropdowns for both adding subjects and student enrollment
            $.getJSON('../fetch/fetch_courses.php', function(data) {
                data.forEach(function(course) {
                    $('select[name="course_id"], #course').append(new Option(course.name, course.id));
                });
            });

            let subjects = []; // To hold subjects for filtering

            // Event handler for course selection in enrollment form
            $('#course').change(function() {
                const courseId = $(this).val();
                fetchSubjects(courseId);
            });

            // Event handler for year level selection in enrollment form
            $('#enroll_year').change(filterSubjects);

            // Fetch subjects for a given course
            function fetchSubjects(courseId) {
                $('#subject').empty().append(new Option('Select Subject', ''));
                if (!courseId) return;

                fetch(`../fetch/fetch_subjects.php?courseId=${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        subjects = data;
                        filterSubjects();
                    });
            }

            // Filter subjects based on selected year level
            function filterSubjects() {
                const selectedYear = $('#enroll_year').val();
                const filteredSubjects = subjects.filter(subject => subject.year_level == selectedYear);

                $('#subject').empty().append(new Option('Select Subject', ''));
                filteredSubjects.forEach(subject => {
                    $('#subject').append(new Option(subject.name, subject.id));
                });
            }
        });
    </script>
</body>

</html>