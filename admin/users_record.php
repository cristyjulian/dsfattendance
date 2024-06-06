<?php
session_start();
require '../rec.php'; // This includes your PDO connection setup

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php'); // Redirect to login page if not admin
    exit();
}

// Function to print the filtered records
function printFilteredRecords($records) {
    echo "<script>";
    echo "window.print();";
    echo "</script>";
}

// Fetch current user's ID based on the username
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
    $searchQuery = " AND (name LIKE :search_term OR username LIKE :search_term_2 OR user_email LIKE :search_term_3)";
}

// Prepare the SQL query to fetch user records
$query = "SELECT users_id, name, username, user_email FROM users WHERE role = 'user' $searchQuery ORDER BY name ASC";
$stmt = $pdo->prepare($query);

// Bind search term if it exists
if (!empty($searchTerm)) {
    $likeTerm = '%' . $searchTerm . '%';
    $stmt->bindValue(':search_term', $likeTerm, PDO::PARAM_STR);
    $stmt->bindValue(':search_term_2', $likeTerm, PDO::PARAM_STR);
    $stmt->bindValue(':search_term_3', $likeTerm, PDO::PARAM_STR);
}

$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link href="css/styles.css" rel="stylesheet" />
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
                    <li><hr class="dropdown-divider" /></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <span> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <li><button style="width: 100%; padding: 8px 16px; border: none; background-color: #007bff; color: white;" onclick="window.location.href='../logout.php';">Log-out</button></li>
                    <?php else: ?>
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
                    <h1 class="mt-4">Instructor Record</h1>
                    <ol class="breadcrumb mb=4">
                        <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                        <li class="breadcrumb-item active">Instructor Record</li>
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
                            <h5>Instructors</h5>
                            </center>
                           <div class="table-container">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($records as $record): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($record['name']); ?></td>
                                            <td><?php echo htmlspecialchars($record['username']); ?></td>
                                            <td><?php echo htmlspecialchars($record['user_email']); ?></td>
                                            <td>
                                            <a href="#" class="edit-button" data-id="<?php echo $record['users_id']; ?>" data-name="<?php echo htmlspecialchars($record['name']); ?>" data-username="<?php echo htmlspecialchars($record['username']); ?>" data-email="<?php echo htmlspecialchars($record['user_email']); ?>"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="../admin/delete_users.php?id=<?php echo $record['users_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </main>
                   
            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm" action="../admin/edit_users.php" method="post">
                                <input type="hidden" name="users_id" id="edit-users_id">
                                <div class="mb-3">
                                    <label for="edit-name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="edit-name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="edit-username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit-email" name="user_email" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        </div>
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
    // Function to handle edit button clicks
    $(document).ready(function() {
        $('.edit-button').on('click', function() {
            // Get user data from the button's data attributes
            const userId = $(this).data('id');
            const userName = $(this).data('name');
            const userUsername = $(this).data('username');
            const userEmail = $(this).data('email');

            // Populate the modal with the user data
            $('#edit-users_id').val(userId);
            $('#edit-name').val(userName);
            $('#edit-username').val(userUsername);
            $('#edit-email').val(userEmail);

            // Show the modal
            $('#editModal').modal('show');
        });
    });
</script>

</body>
</html>
