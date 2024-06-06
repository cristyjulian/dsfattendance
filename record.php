<?php
session_start();
require 'rec.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

function printFilteredRecords($records) {
    echo "<script>";
    echo "window.print();";
    echo "</script>";
}

function sendEmailToParent($parentEmail, $studentName, $status, $submittedTime) {
    if (!filter_var($parentEmail, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email address: ' . htmlspecialchars($parentEmail);
        return;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cristyjulian6@gmail.com'; // Your Gmail username
        $mail->Password = 'vpovxvhhhksxqlhp'; // Your Gmail app password (generated as described above)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient settings
        $mail->setFrom('cristyjulian6@gmail.com', 'Admin');
        $mail->addAddress($parentEmail); // Parent's email
        $mail->addReplyTo('cristyjulian6@gmail.com', 'Admin');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Attendance Report for ' . $studentName . ' - Submitted at ' . $submittedTime;
        $mail->Body    = 'Dear Parent,<br><br>'
                        . 'This is to inform you that your child, ' . $studentName . ', has the following attendance status: <b>' . $status . '</b>.<br><br>'
                        . 'Regards,<br>'
                        . 'Admin';

        $mail->send();
        echo 'Email has been sent successfully.';
    } catch (Exception $e) {
        echo 'Email could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

try {
    // First, fetch the current user's ID based on the username
    $userQuery = "SELECT users_id FROM users WHERE username = :username";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
    $userStmt->execute();
    $userIdResult = $userStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$userIdResult) {
        throw new Exception("User not found.");
    }

    $userId = $userIdResult['users_id'];

    // Initialize search variables
    $searchTerm = '';
    $searchQuery = '';

    // Check if a search term was submitted
    if (isset($_POST['buttonname']) && !empty($_POST['search1'])) {
        $searchTerm = $_POST['search1'];
        $searchQuery = " AND c.name LIKE :search_term";
    }

    // Prepare the SQL query to fetch attendance records along with subject, course, and year level details
    $query = "SELECT a.date, s.full_name, s.email, s.birthday, sub.name as subject_name, c.name as course_name, cr.year_level, 
                     COALESCE(a.status, 'Absent') AS status, s.parent_contact as parent_email
              FROM attendance a
              JOIN students s ON a.student_id = s.id
              JOIN subjects sub ON s.subject_id = sub.id
              JOIN class_registrations cr ON s.subject_id = cr.subject_id
              JOIN courses c ON cr.course_id = c.id
              WHERE a.submitted_by = :user_id
              $searchQuery
              ORDER BY a.date DESC, s.full_name, sub.name, c.name";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

    // Bind search term if it exists
    if (!empty($searchTerm)) {
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->bindValue(':search_term', $likeTerm, PDO::PARAM_STR);
    }

    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize records by course, subject, and year level
    $course_subject_records = [];
    foreach ($records as $record) {
        $course_name = $record['course_name'] ?? 'Unknown Course';
        $subject_name = $record['subject_name'] ?? 'Unknown Subject';
        $year_level = $record['year_level'] ?? 'Unknown Year';
        $course_subject_records[$course_name][$subject_name][$year_level][] = $record;
    }

    // Send email to each parent
    foreach ($records as $record) {
        $parentEmail = $record['email']; // Using parent's email from the students table
        $studentName = $record['full_name'];
        $status = $record['status'];
        $submittedTime = $record['date']; // Assuming the 'date' field is the submission time
       

        sendEmailToParent($parentEmail, $studentName, $status, $submittedTime);
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
    <link rel="shortcut icon" href="dsficon.png">
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/add.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/session.css">
    <link rel="stylesheet" href="assets/css/record.css">
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="stylesheet" href="css/search.css">


</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <div class="logo-img">
            <a href="index.php"><img src="DSFLOGO.png" alt="DSF Logo"></a>
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
                        <a class="nav-link" href="">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Charts
                        </a>
                        <a class="nav-link" href="">
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
                        <main class="container">
                            <?php if (empty($course_subject_records)): ?>
                                <p>No attendance records found for your submissions.</p>
                            <?php else: ?>
                                <?php foreach ($course_subject_records as $course_name => $subjects): ?>
                                    
                                        <h3 class="text-center"><?= htmlspecialchars($course_name); ?></h3>
                                        <?php foreach ($subjects as $subject_name => $years): ?>
                                            
                                                <h4 class="text-center"><?= htmlspecialchars($subject_name); ?></h4>
                                                <?php foreach ($years as $year_level => $records): ?>
                                                    
                                                        <h5 class="text-center">Year Level: <?= htmlspecialchars($year_level); ?></h5>
                                                        <div class="table-container">
                                                            <table class="table table-bordered">
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
                                                                    <?php foreach ($records as $record): ?>
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
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </main>
                    </div>
                </div>
            </main>
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
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    </script>
</body>
</html>
