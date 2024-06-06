<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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

    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $user_email = $_POST['user_email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $role = $_POST['role']; // Assuming you have a role field in your registration form

        if ($password !== $confirm_password) {
            echo '<div class="message" style="display:block;">Passwords do not match!</div>';
            echo '<script>hideMessage();</script>';
        } else {
            $conn = new mysqli('localhost', 'root', '', 'mydb');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $userCheck = $conn->prepare("SELECT username FROM users WHERE username = ?");
            $userCheck->bind_param("s", $username);
            $userCheck->execute();
            $result = $userCheck->get_result();
            if ($result->num_rows > 0) {
                echo '<div class="message" style="display:block;">Username already exists, please try another!</div>';
                echo '<script>hideMessage();</script>';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (name, username, user_email, password, role) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $name, $username, $user_email, $hashed_password, $role);

                if ($stmt->execute()) {
                    echo "<script>alert('Saved Successfully!')</script>";
                    echo "<script>window.location='login.php'</script>";
                } else {
                    echo '<div class="message" style="display:block;">Error: ' . $stmt->error . '</div>';
                    echo '<script>hideMessage();</script>';
                }
                $stmt->close();
            }
            $userCheck->close();
            $conn->close();
        }
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
            <form action="register.php" method="post" class="registration-form">
                <h2>Registration Form</h2>
                <input type="text" id="name" name="name" placeholder="Full Name" required><br>
                <input type="text" id="username" name="username" placeholder="Username" required><br>
                <input type="text" id="user_email" name="user_email" placeholder="Email" required><br>
                <input type="password" id="password" name="password" placeholder="Password" required><br>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required><br>
                <label for="role">Select Role:</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select><br>
                <button type="submit" name="register" class="btn">Register</button>
                <p>Already a member? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
    <script>
        function hideMessage() {
            var msg = document.querySelector('.message');
            if (msg) {
                setTimeout(function() {
                    msg.style.display = 'none';
                }, 1000); // Hide the message after 3 seconds for better readability
            }
        }
    </script>
</body>
</html>
