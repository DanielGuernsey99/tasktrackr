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
            background-color: black;
            color: white;
            margin: 0;
            display: flex;
            text-align: center;
            justify-content: center;
            align-items: center;
            font-size: 15px;
        }
        .header h1 {
            margin: 0;
        }
        body {
            margin: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: calc(100vh - 50px); /* Adjusted for header and footer */
            background-color: #BEBEBE;
        }
        .loginForm {
            background-color: #FFF;
            padding: 20px;
            border: 1px solid #CCC;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .loginForm h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .loginForm input[type="text"],
        .loginForm input[type="password"],
        .loginForm input[type="email"] { /* Add styling for email input */
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #CCC;
            border-radius: 4px;
        }
        /* Exclude password field from the custom font */
        .loginForm input[type="password"] {
            font-family: Arial, sans-serif; /* Default font for password */
        }
        .loginForm input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #FFF;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .loginForm input[type="submit"]:hover {
            background-color: #555;
        }
        .footer {
            background-color: black;
            color: white;
            height: 50px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
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
                <input type="email" name="email" placeholder="Email" required><br> <!-- Added email field -->
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
