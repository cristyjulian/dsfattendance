<?php
session_start();
require '../rec.php'; // This includes your PDO connection setup

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

try {
    

    // Prepare the SQL query to fetch attendance records along with user, subject, course, and year level details
    $query = "SELECT a.date, s.full_name, s.email, s.birthday, sub.name as subject_name, c.name as course_name, cr.year_level, 
                     COALESCE(a.status, 'Absent') AS status, s.parent_contact as parent_email, u.username as instructor_name
              FROM attendance a
              JOIN students s ON a.student_id = s.id
              JOIN subjects sub ON s.subject_id = sub.id
              JOIN class_registrations cr ON s.subject_id = cr.subject_id
              JOIN courses c ON cr.course_id = c.id
              JOIN users u ON a.submitted_by = u.users_id
              ORDER BY a.date DESC, s.full_name, sub.name, c.name, u.username";

    $stmt = $pdo->prepare($query);

    // Bind search term if it exists
    

    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize records by instructor, course, subject, and year level
    $instructor_course_subject_records = [];
    foreach ($records as $record) {
        $instructor_name = $record['instructor_name'] ?? 'Unknown Instructor';
        $course_name = $record['course_name'] ?? 'Unknown Course';
        $subject_name = $record['subject_name'] ?? 'Unknown Subject';
        $year_level = $record['year_level'] ?? 'Unknown Year';
        $instructor_course_subject_records[$instructor_name][$course_name][$subject_name][$year_level][] = $record;
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
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
    <link rel="stylesheet" href="../css/search.css">
   
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
                    <h1 class="mt-4">Attendance Record</h1>
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
                    </div>
                    <div class="search_container">
                        <form class="search-form" method="post" action="">
                            <input class="form-control" type="search" name="search1" placeholder="Search" aria-label="Search">
                            <button class="btn btn-primary" type="submit" name="buttonname"><i class="fas fa-search"></i> Search</button>
                            <button class="print-button" type="button" onclick="window.print();"><i class="fas fa-print"></i> Print</button>
                        </form>
                    </div>
                    <div class="table-container">
                        <main class="container">
                            <center>
                                <?php if (!empty($instructor_course_subject_records)) : ?>
                                    <?php foreach ($instructor_course_subject_records as $instructor => $courses) : ?>
                                        <h3>Instructor: <?= htmlspecialchars($instructor) ?></h3>
                                        <?php foreach ($courses as $course => $subjects) : ?>
                                            <h4><?= htmlspecialchars($course) ?></h4>
                                            <?php foreach ($subjects as $subject => $year_levels) : ?>
                                                <h5><?= htmlspecialchars($subject) ?></h5>
                                                <?php foreach ($year_levels as $year_level => $records) : ?>
                                                    <h6>Year Level: <?= htmlspecialchars($year_level) ?></h6>
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Student Name</th>
                                                                <th>Email</th>
                                                                <th>Birthday</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($records as $record) : ?>
                                                                <tr>
                                                                    <td><?= htmlspecialchars($record['date']) ?></td>
                                                                    <td><?= htmlspecialchars($record['full_name']) ?></td>
                                                                    <td><?= htmlspecialchars($record['email']) ?></td>
                                                                    <td><?= htmlspecialchars($record['birthday']) ?></td>
                                                                    <td><?= htmlspecialchars($record['status']) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                            </center>
                        <?php else : ?>
                            <p>No records found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; Your Website 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>
