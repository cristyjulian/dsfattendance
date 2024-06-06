<?php
session_start();
require '../connn.php'; // Make sure the connection file is included

// Check if the user is logged in as an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
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
    <link rel="shortcut icon" href="../dsficon.png">
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet" />
    <link href="../css/add.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../assets/css/lightbox.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/session.css">
    <link rel="stylesheet" href="../assets/css/record.css">
    <link rel="stylesheet" href="../assets/css/table.css">
    <link rel="stylesheet" href="../css/class.css">
  
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <div class="logo-img">
            <a href="index.php"><img src="../DSFLOGO.png" alt="DSF Logo"></a>
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
                        <li><button style="width: 100%; padding: 8px 16px; border: none; background-color: #007bff; color: white;" onclick="window.location.href='../logout.php';">Log-out</button></li>
                    <?php else : ?>
                        <li><button style="width: 100%; padding: 8px 16px; border: none; background-color: #007bff; color: white;" onclick="window.location.href='../login.php';">Log-in</button></li>
                        <li><button style="width: 100%; padding: 8px 16px; border: none; background-color: #007bff; color: white;" onclick="window.location.href='../register.php';">Register</button></li>
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
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Subject Record</h1>
                    <ol class="breadcrumb mb=4">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Attendance Record</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Add Classes</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="../add.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">Checking Attendance</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="../session.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Attendance Record</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="../record.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <!--  
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">Charts</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="charts.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                         -->
                    </div>
                    <div class="search_container">
                        <form action="" method="post" class="search-form">
                            <input class="form-control" type="text" placeholder="Search for..." name="search1" id="search1" aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                            <button class="btn" id="btnNavbarSearch" name="buttonname" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </form>
                        <button class="print-button" onclick="window.print();">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Classes Added by Users
                        </div>
                        <div class="card-body">
                            <table id="allClassesTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Instructor</th>
                                        <th>Course</th>
                                        <th>Year Level</th>
                                        <th>Subject</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    // Fetch and display all classes added by users in random order
                                    $sql = "SELECT u.username, c.name AS course_name, cr.year_level, s.name AS subject_name, cr.classreg_id
                                            FROM class_registrations cr
                                            JOIN courses c ON cr.course_id = c.id
                                            JOIN subjects s ON cr.subject_id = s.id
                                            JOIN users u ON cr.user_id = u.users_id
                                            ORDER BY RAND()";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['year_level']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                                            echo "<td>
                                                    <a href='#' class='edit-button btn btn-sm btn-primary' data-id='" . $row['classreg_id'] . "' data-username='" . htmlspecialchars($row['username']) . "' data-course='" . htmlspecialchars($row['course_name']) . "' data-year-level='" . htmlspecialchars($row['year_level']) . "' data-subject='" . htmlspecialchars($row['subject_name']) . "'><i class='fas fa-edit'></i> Edit</a>
                                                    <a href='#' class='delete-button btn btn-sm btn-danger' data-id='" . $row['classreg_id'] . "'><i class='fas fa-trash'></i> Delete</a>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No classes added yet.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Class Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="../admin/edit_class_record.php">
                        <input type="hidden" name="classreg_id" id="editClassregId">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCourse" class="form-label">Course</label>
                            <input type="text" class="form-control" id="editCourse" name="course" required>
                        </div>
                        <div class="mb-3">
                            <label for="editYearLevel" class="form-label">Year Level</label>
                            <input type="text" class="form-control" id="editYearLevel" name="year_level" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="editSubject" name="subject" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this class record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
                </div>
            </div>
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
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script>
        // JavaScript to handle the edit button click and populate the modal with data
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const classregId = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                const course = this.getAttribute('data-course');
                const yearLevel = this.getAttribute('data-year-level');
                const subject = this.getAttribute('data-subject');

                document.getElementById('editClassregId').value = classregId;
                document.getElementById('editUsername').value = username;
                document.getElementById('editCourse').value = course;
                document.getElementById('editYearLevel').value = yearLevel;
                document.getElementById('editSubject').value = subject;

                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });

        // JavaScript to handle the delete button click and show the delete modal
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const classregId = this.getAttribute('data-id');
                document.getElementById('confirmDelete').setAttribute('data-id', classregId);
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            });
        });

        // JavaScript to handle the delete confirmation button click
        document.getElementById('confirmDelete').addEventListener('click', function() {
            const classregId = this.getAttribute('data-id');
            window.location.href = `../admin/delete_class_record.php?id=${classregId}`;
        });
    </script>
</body>

</html>