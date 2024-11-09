<?php
session_start(); // Start the session to access the logged-in user's information

// Check if the user is logged in and display the username
if (isset($_SESSION['username'])) {
    echo "<p>" . htmlspecialchars($_SESSION['username']) . "</p>";
} else {
}

// Database connection
$conn = new mysqli("localhost", "root", "", "tasktrackr");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission before any output
if (isset($_POST['update_member'])) {
    $member_id = $_POST['member_id'];
    $updated_name = $_POST['name'];
    $updated_email = $_POST['email'];
    $updated_phone = $_POST['phone'];

    // Update query
    $update_sql = "UPDATE users SET name='$updated_name', email='$updated_email', phone='$updated_phone' WHERE id=$member_id";
    if ($conn->query($update_sql) === TRUE) {
        // Redirect to the same page to refresh the data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); // Ensure no further code is executed after the redirect
    } else {
        echo "Error updating member: " . $conn->error;
    }
}

// Fetch Members after the update form processing
$sql = "SELECT id, username, email FROM users";  // Fetching username and email from the users table
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        @font-face {
            font-family: 'BitendDemo-Regular';
            src: url(BitendDEMO.otf);
        }
        * {
            font-family: 'BitendDemo-Regular';
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f4f4f4;
            font-size: 16px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            background-color: #333;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px 0;
        }
        .header h1 {
            font-size: 24px;
        }
        .container {
            display: flex;
            height: calc(100vh - 150px);
        }
        .leftNav {
            width: 200px;
            background-color: #2c2c2c;
            color: white;
            padding: 15px;
        }
        .leftNav a {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .leftNav a:hover {
            background-color: #444;
        }
        .mainContent {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            overflow-y: auto;
        }
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }
        .memberContainer {
            text-align: center;
        }
        .memberContainer h1 {
            padding-top: 25px;
            margin: 0;
        }
        .memberList {
            list-style-type: none;
            padding: 0;
        }
        .memberList li {
            margin-bottom: 10px;
        }
        .memberForm input,
        .memberForm button {
            margin: 5px;
        }
        .memberForm {
            margin-top: 20px;
        }
        .createButton {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .createButton:hover {
            background-color: #45a049;
        }
        .profileNav p {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .profileNav{
            border-bottom:1px solid black;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>tasktrackr</h1>
    </div>

    <div class="container">
        <div class="leftNav">
            <div class="profileNav">
                <?php
                // Check if the user is logged in and display the username
                if (isset($_SESSION['username'])) {
                    echo "<p>" . htmlspecialchars($_SESSION['username']) . "</p>";
                } else {
                    echo "<p>Guest</p>";
                }
                ?>
                <a href="login/logout.php">Logout</a>
            </div>
            <div class="topNav">
                <a href="http://localhost/tasktrackr/home.php">Home</a>
            </div>
            <div class="middleNav">
                <a href="http://localhost/tasktrackr/Calendar.php">Calendar</a>
            </div>
            <div class="bottomNav">
                <a href="http://localhost/tasktrackr/Members.php">Members</a>
            </div>
        </div>

        <div class="mainContent">
            <div class="memberContainer">
                <h1>Members List</h1>

                <?php
                if ($result->num_rows > 0) {
                    echo "<ul class='memberList'>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row['username']) . " - " . htmlspecialchars($row['email']) . " 
                              <a href='#' onclick='editMember(" . $row['id'] . ", \"" . addslashes($row['username']) . "\", \"" . addslashes($row['email']) . "\")'>Edit</a>
                              </li>";
                    }
                    echo "</ul>";
                } else {
                    echo "No members found!";
                }

                $conn->close();
                ?>
            </div>

            <!-- Edit Member Form -->
            <div class="memberForm" id="editForm" style="display: none;">
                <h2>Edit Member</h2>
                <form method="POST" action="">
                    <input type="hidden" id="member_id" name="member_id">
                    <input type="text" id="name" name="name" placeholder="Name" required><br>
                    <input type="email" id="email" name="email" placeholder="Email" required><br>
                    <input type="text" id="phone" name="phone" placeholder="Phone" required><br>
                    <button type="submit" name="update_member">Update Member</button>
                    <button type="button" onclick="cancelEdit()">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <h1>© Copyright 2024 by tasktrackr</h1>
    </div>

    <script>
        function editMember(id, username, email) {
            // Populate the edit form with the member's details
            document.getElementById('member_id').value = id;
            document.getElementById('name').value = username;
            document.getElementById('email').value = email;

            // Show the edit form
            document.getElementById('editForm').style.display = 'block';
        }

        function cancelEdit() {
            // Hide the edit form
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
</body>
</html>
