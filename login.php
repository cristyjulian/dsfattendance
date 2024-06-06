<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
   <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/templatemo-grad-school.css">
    <link rel="shortcut icon" href="dsficon.png">
</head>
<style>
 .container {
    width: 800px;
    margin: 0 auto; /* This will center the container horizontally */
    display: flex;
    justify-content: space-between;
}

.left-content {
    width: 45%;
    padding: 20px;
    box-sizing: border-box;
}

.right-content {
    width: 45%;
    padding: 20px;
    box-sizing: border-box;
}

.logo {
    width: 100px;
}

.mission-vision {
    margin-top: 50px;
}

.registration-form {
    background-color: #f2f2f2;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.registration-form h2 {
    margin-bottom: 20px;
    text-align: center;
}

.registration-form input[type="text"],
.registration-form input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.registration-form button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.registration-form button:hover {
    background-color: #45a049;
}

.registration-form p {
    text-align: center;
}

.registration-form a {
    color: #007bff;
    text-decoration: none;
}

.registration-form a:hover {
    text-decoration: underline;
}

.message {
display: none;
    color: red;
    margin-bottom: 10px;
    text-align: center;
}   
</style>
<body>
<?php
session_start();
include 'connn.php'; // Assuming this script sets up the database connection.

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use the connection already established in 'connect.php' if possible.
    $conn = new mysqli('localhost', 'root', '', 'mydb');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT users_id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['users_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store the user's role in session

            // Redirect based on user role
            if ($user['role'] === 'admin') {
                header("Location: ./admin/admin.php");
                exit();
            } else {
                header("Location: add.php");
                exit();
            }
        } else {
            echo "Invalid login credentials.";
        }
    } else {
        echo "Invalid login credentials.";
    }

    $stmt->close();
    $conn->close();
}
?>


   
    <div class="container">
        <div class="left-content">
            <img src="dsficon.png" alt="Logo" class="logo">
            <div class="mission-vision">
                <h3>Mission</h3>
                <p>To Develop Graduates with High Competence and skills in Fisheries and Allied Sciences through Quality Assured Training Programs and positive Work Values.</p>
                <h3>Vision</h3>
                <p>To be a World Class Fishery and Allied sciences training institution and the Provider of Highly Compettitive Skills.</p> 
            </div>
        </div>
         <div class="right-content">
            <form action="login.php" method="post" class="registration-form">
            <h2>Login Form</h2>
            <!-- Use type="password" for the password input -->
            <input type="text" id="username" name="username" placeholder="Username" required><br>
            <input type="password" id="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="login" class="btn">Login</button>
            <p>Not a member yet? <a href="register.php">Register</a></p>
            </form>
        </div>
</div>
    </form>
</body>
</html>
