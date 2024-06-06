<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

try {
    // SQL query to fetch classes registered by the user
    $classesQuery = "SELECT cr.*, c.name AS course_name, s.name AS subject_name 
                     FROM class_registrations cr  
                     JOIN courses c ON cr.course_id = c.id
                     JOIN subjects s ON cr.subject_id = s.id 
                     WHERE cr.user_id = :userId";

    $classesStmt = $conn->prepare($classesQuery);
    $classesStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $classesStmt->execute();
    $classes = $classesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}


// Handle search button click
if (isset($_POST['buttonname'])) {
    $searchTerm = isset($_POST['search1']) ? trim($_POST['search1']) : '';
    
    if (!empty($searchTerm)) {
        // Filter classes based on search term
        $filteredClasses = [];
        foreach ($classes as $class) {
            if (stripos($class['course_name'], $searchTerm) !== false ||
                stripos($class['subject_name'], $searchTerm) !== false) {
                $filteredClasses[] = $class;
            }
        }
        $classes = $filteredClasses;
    }
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
        <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="assets/css/templatemo-grad-school.css">
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />
        <link href="css/add.css" rel="stylesheet" />
        <link href="css/session.css" rel="stylesheet" />
        <link href="css/table.css" rel="stylesheet" />
        <link href="css/search.css" rel="stylesheet" />
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
                        <li><hr class="dropdown-divider" /></li>
                        <?php if (isset($_SESSION['username'])): ?>
                            <span> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <li><button style="width: 100%; padding: 8px 16px; border: none; background-color: #007bff; color: white;" onclick="window.location.href='logout.php';">Log-out</button></li>
                        <?php else: ?>
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
                            <a class="nav-link" href="charts.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="#">
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
                        <h1 class="mt-4">Instructor</h1>
                        <ol class="breadcrumb mb=4">
                        </ol>
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
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Attendance Table
                            </div>
                            <div class="search_container">
                            <form action="" method="post" class="search-form">
                                <input class="form-control" type="text" placeholder="Search for..." name="search1" id="search1"
                                    aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                                <button class="btn" id="btnNavbarSearch" name="buttonname" type="submit">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </form>
                            <button class="print-button" onclick="window.print();">
                                <i class="fas fa-print"></i> Print
                            </button>
                            </div>
                            <div class="card-body">
                                <div class="container">
                                    <?php foreach ($classes as $class): ?>
                                        <div class="class-table-container">
                                            <center>
                                            <h5 class="class-course-header">
                                                <?= htmlspecialchars($class['course_name']) ?> 
                                                 <?= htmlspecialchars($class['year_level']) ?>
                                            </h5>
                                            <h6 class="class-header"><?= htmlspecialchars($class['subject_name']) ?></h6>
                                           </center>
                                           <?php
                                            // Database connection should be established here
                                            $servername = "localhost";
                                            $username = "root";
                                            $password = "";
                                            $dbname = "mydb";
                                            $conn = new mysqli($servername, $username, $password, $dbname);

                                            if ($conn->connect_error) {
                                                die("Connection failed: " . $conn->connect_error);
                                            }

                                            // Query to fetch students enrolled in the subject
                                            $studentsQuery = "SELECT id, full_name, email, birthday FROM students WHERE subject_id = ?";
                                            $studentsStmt = $conn->prepare($studentsQuery);
                                            $studentsStmt->bind_param('i', $class['subject_id']);
                                            $studentsStmt->execute();
                                            $studentsResult = $studentsStmt->get_result();
                                            $students = $studentsResult->fetch_all(MYSQLI_ASSOC);
                                            $studentsStmt->close();
                                            $conn->close();
                                            ?>

                                            <!-- Form for submitting attendance -->
                                            <form method="post" action="submit_attendance.php">
                                                <input type="hidden" name="submitted_by" value="<?= $_SESSION['user_id'] ?>">
                                                <input type="hidden" name="subject_id" value="<?= $class['subject_id'] ?>">

                                                <!-- Table to display student information and input for attendance -->
                                                <table class="class-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Birthday</th>
                                                            <th>Attendance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($students as $student): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($student['full_name']) ?></td>
                                                                <td><?= htmlspecialchars($student['email']) ?></td>
                                                                <td><?= htmlspecialchars($student['birthday']) ?></td>
                                                                <td>
                                                                    <label>
                                                                        <input type="radio" name="attendance[<?= $student['id'] ?>]" value="present"> Present
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="attendance[<?= $student['id'] ?>]" value="absent"> Absent
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>

                                                <!-- Submit button -->
                                                <center>
                                                <div class="submit-container">
                                                    <button type="submit" class="btn btn-primary">Submit Attendance</button>
                                                </div>
                                                </center>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                </div>                            
                                <center>
                                <p class="date-today">Today's Date: <span id="current-date"></span></p>
                                </center>
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
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script>
            document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        </script>
    </body>
</html>
