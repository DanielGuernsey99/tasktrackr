<?php
// Start the session
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'tasktrackr'); // Adjust the connection as needed

// Handle user registration
if (isset($_POST['register'])) {
    // Fetch form values
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']); // Email field
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password == $confirm_password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists
        $check_email_sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_email_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email already exists
            echo "<script>alert('Email already exists!');</script>";
        } else {
            // Insert user into the database
            $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                // Registration successful, start a session
                $_SESSION['username'] = $username; // Store the username in the session
                echo "<script>window.location.href = '../home.php';</script>"; // Redirect to home page
            } else {
                echo "<script>alert('Error creating account');</script>";
            }

            $stmt->close();
        }
    } else {
        echo "<script>alert('Passwords do not match');</script>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Account - tasktrackr</title>
    <style>
        @font-face {
            font-family: 'BitendDemo-Regular';
            src: url(BitendDEMO.otf);
        }
        * {
            font-family: 'BitendDemo-Regular';
        }
        .header {
            width: 100%;
            background-color: #333;
            color: white;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        body {
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 20px;
        }
        .loginForm {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .loginForm h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .loginForm input[type="text"],
        .loginForm input[type="password"],
        .loginForm input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .loginForm input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .loginForm input[type="submit"]:hover {
            background-color: #555;
        }
        .loginForm p {
            text-align: center;
            margin-top: 10px;
        }
        .loginForm p a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }
        .loginForm p a:hover {
            text-decoration: underline;
        }
        .footer {
            background-color: #333;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>tasktrackr</h1>
    </div>
    <div class="container">
        <div class="loginForm">
            <h2>Create Account</h2>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
                <input type="submit" name="register" value="Create Account">
            </form>
            <p><a href="login.php">Already have an account? Login</a></p>
        </div>
    </div>
    <div class="footer">
        <h1>© Copyright 2024 by tasktrackr</h1>
    </div>
</body>
</html>
