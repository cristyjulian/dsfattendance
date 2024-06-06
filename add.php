<?php
session_start();
require 'connn.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
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
    <link rel="shortcut icon" href="dsficon.png">
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/add.css" rel="stylesheet" />
    <link href="css/addform.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <div class="logo-img">
            <img src="DSFLOGO.png" alt="DSF Logo">
        </div>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <?php if (isset($_SESSION['username'])) : ?>
                        <span> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <li><button style="width: 100%; padding: 8px 16px; border: none; background-color: #007bff; color: white;" onclick="window.location.href='logout.php';">Log-out</button></li>
                    <?php else : ?>
                        <li><button style="width: 100%; padding: 8px 16px; border: none; background-color: #007bff; color: white;" onclick="window.location.href='login.php';">Log-in</button></li>
                        <li><button style="width: 100%; padding: 8px 16px; border: none; background-color: #007bff; color: white;" onclick="window.location.href='register.php';">Register</button></li>
                    <?php endif; ?>
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
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            HOME
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link" href="add.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                            Add Classes
                        </a>
                        <a class="nav-link" href="session.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-check"></i></div>
                            Checking Attendance
                        </a>
                        <a class="nav-link" href="record.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                            Attendance Record
                        </a>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Charts
                        </a>
                        <a class="nav-link" href="">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tables
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
                    <h1 class="mt-4">Professor</h1>
                    <ol class="breadcrumb mb-4"></ol>
                    <center>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Add Classes</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="add.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Checking Attendance</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="session.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Attendance Record</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="record.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                    </center>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class=""></i>
                                    Add Your Class
                                </div>
                                <div class="sccontainer">
                                    <div class="sc">
                                        <form action="record_class.php" method="post">
                                            <label for="course">Course:</label>
                                            <select id="course" name="course" required>
                                                <option value="">Select Course</option>
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
                                            </select><br>
                                            <button type="submit">Add Class</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Add Class Form Section (Existing Code) -->

                    <!-- Display Added Classes Table Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Your Added Classes
                        </div>
                        <div class="card-body">
                            <table id="addedClassesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Year Level</th>
                                        <th>Subject</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch and display the classes added by the user
                                    $userId = $_SESSION['user_id'];
                                    $sql = "SELECT c.name AS course_name, cr.year_level, s.name AS subject_name, cr.classreg_id
                                    FROM class_registrations cr
                                    JOIN courses c ON cr.course_id = c.id
                                    JOIN subjects s ON cr.subject_id = s.id
                                    WHERE cr.user_id = $userId";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>{$row['course_name']}</td>";
                                            echo "<td>{$row['year_level']}</td>";
                                            echo "<td>{$row['subject_name']}</td>";
                                           
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No classes added yet.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <div id="editClassModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Class</h2>
                    <form id="editClassForm">
                        <label for="edit_course">Course:</label>
                        <select id="edit_course" name="edit_course" required>
                            <option value="">Select Course</option>
                        </select><br>

                        <label for="edit_enroll_year">Year Level:</label>
                        <select id="edit_enroll_year" name="edit_enroll_year" required>
                            <option value="">Select Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option><!-- Options will be populated dynamically -->
                        </select><br>

                        <label for="edit_subject">Subject:</label>
                        <select id="edit_subject" name="edit_subject" required>
                            <option value="">Select Subject</option>
                        </select><br>

                        <button type="submit">Update Class</button>
                    </form>
                </div>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let subjects = [];
            fetchCourses();
            fetchClasses();

            $('#course').change(function() {
                let courseId = $(this).val();
                fetchSubjects(courseId);
            });

            $('#enroll_year').change(function() {
                filterSubjects();
            });

            function fetchCourses() {
                fetch('./fetch/fetch_courses.php')
                    .then(response => response.json())
                    .then(data => {
                        $('#course').empty().append(new Option('Select Course', ''));
                        data.forEach(course => {
                            $('#course').append(new Option(course.name, course.id));
                        });
                    });
            }

            function fetchSubjects(courseId) {
                $('#subject').empty().append(new Option('Select Subject', ''));
                if (!courseId) return;
                fetch(`./fetch/fetch_subjects.php?courseId=${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        subjects = data;
                        filterSubjects();
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

            function fetchClasses() {
                $.ajax({
                    url: './fetch/fetch_classes.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let tableBody = $('#addedClassesTable tbody').empty(); // Clear existing table rows
                        data.forEach(function(registration) {
                            tableBody.append(
                                `<tr id="class-row-${registration.id}">
                            <td>${registration.course_name}</td>
                            <td>${registration.year_level}</td>
                            <td>${registration.subject_name}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="openEditModal(${registration.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteClass(${registration.id})">Delete</button>
                            </td>
                        </tr>`
                            );
                        });
                        // Initialize DataTable
                        new simpleDatatables.DataTable("#addedClassesTable");
                    },
                    
                });
            }


            // Function to delete class
           
        });
    </script>

</body>

</html>