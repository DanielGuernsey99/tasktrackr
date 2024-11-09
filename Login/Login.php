<?php
// Start the session to store user info
session_start();

// Database connection and login verification logic
if (isset($_POST['login'])) {
    $conn = new mysqli('localhost', 'root', '', 'tasktrackr'); // Adjust the connection as needed

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize user input
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Don't sanitize; it's used directly with password_verify()

    // Use prepared statements for security
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $hashedPasswordFromDB); // Fetch the user ID and password hash
        $stmt->fetch();

        // Verify the password with the stored hash
        if (password_verify($password, $hashedPasswordFromDB)) {
            // Store the user ID and username in the session
            $_SESSION['user_id'] = $userId;  // Store the user ID
            $_SESSION['username'] = $username; // Store the username

            // Redirect to the home page using PHP
            header("Location: ../home.php");
            exit();  // Make sure to exit after the redirect
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - tasktrackr</title>
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
        .loginForm input[type="password"] {
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
            <h2>Login</h2>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="submit" name="login" value="Login">
            </form>
            <p><a href="createAccount.php">Create an account</a></p>
        </div>
    </div>
    <div class="footer">
        <h1>© Copyright 2024 by tasktrackr</h1>
    </div>
</body>
</html>
