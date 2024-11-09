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
        .loginForm input[type="password"] {
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
