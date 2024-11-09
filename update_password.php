<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'tasktrackr'); // Adjust with your credentials

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define username and plain text password
$username = 'admin';  // The username of the user whose password needs to be updated
$password = 'password123';  // The plain text password

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL query to update the password
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $hashedPassword, $username);

// Execute the query
if ($stmt->execute()) {
    echo "Password updated successfully.";
} else {
    echo "Error updating password: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
