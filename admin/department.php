<?php
session_start();
require '../rec.php'; // This includes your PDO connection setup

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php'); // Redirect to login page if not admin
    exit();
}

function printFilteredRecords($records)
{
    echo "<script>";
    echo "window.print();";
    echo "</script>";
}



// Fetch courses from the database
$query = "SELECT * FROM courses";
$stmt = $pdo->query($query);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bind search term if it exists
if (!empty($searchTerm)) {
    $likeTerm = '%' . $searchTerm . '%';
    $stmt->bindValue(':search_term', $likeTerm, PDO::PARAM_STR);
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
                    <h1 class="mt-4">Department Record</h1>
                    <ol class="breadcrumb mb=4">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Department Record</li>
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
                    <div class="card-body">
                        <main class="container">
                            <center>
                            <h5>COURSES</h5>
                            </center>
                                <div class="table-container">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>

                                                <th>Course Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($courses as $course) : ?>
                                                <tr>
                                                    <td><?php echo $course['name']; ?></td>
                                                    <td>
                                                        <a href="#" class="edit-button" data-id="<?php echo $course['id']; ?>" data-name="<?php echo $course['name']; ?>"><i class="fas fa-edit"></i> Edit</a>
                                                        <a href="../admin/delete_course.php?id=<?php echo $course['id']; ?>" onclick="return confirm('Are you sure you want to delete this course?');"><i class="fas fa-trash"></i> Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </main>
                    </div>



            </main>
        </div>
    </div>
    </main>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Course</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="../admin/update_course.php" method="post">
                        <input type="hidden" name="id" id="editCourseId"> <!-- Hidden input field to store the course ID -->
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; Your Website 2021</div>
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
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        function populateEditModal(id, name) {
            $('#editCourseId').val(id); // Set the course ID in the hidden input field
            $('#editName').val(name);   // Set the course name in the form field
            $('#editModal').modal('show');
        }

        $('.edit-button').click(function() {
            var id = $(this).data('id');     // Get the course ID from data attribute
            var name = $(this).data('name'); // Get the course name from data attribute
            populateEditModal(id, name);     // Call the function to populate the edit modal
        });
    });
</script>

</body>

</html>