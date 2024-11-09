<!DOCTYPE html>
<html>
    <header>
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
                margin: 0px;
                display: flex;
                text-align: center;
                justify-content: center;
                align-items: center;
                font-size: 15px;
            }
            .header h1 {
                margin: 0px;
            }
            body {
                margin: 0px;
            }
            .container {
                height: 1119px;
                width: 100%;
            }
            .leftNav {
                height: 100%;
                width: 150px;
                background-color: #989898;
                border: 1px solid black;
                float: left;
            }
            .leftNav a {
                display: block;
                padding-left: 5px;
                margin-bottom: 7px;
                margin-top: 7px;
            }
            .bottomNav {
				display: flex;
				justify-content: center;
                border-top: 1px solid black;
                margin: 0px;
            }
            .mainContent {
                height: 100%;
                margin-top: 0px;
                background-color: #BEBEBE;
            }
            .footer {
                background-color: black;
                color: white;
                height: 50px;
                margin: 0px;
            }
            .footer h1 {
                height: 100%;
                margin: 0px;
                display: flex;
                text-align: center;
                justify-content: center;
                align-items: center;
                font-size: 20px;
            }
            .middleNav {
                display: flex;
                justify-content: center;
                border-top: 1px solid black;
            }
            .topNav {
                display: flex;
                justify-content: center;
                border-top: 1px solid black;
            }
			.profileNav {
				padding-left:45px;
			}
			.profileNav p{
				padding-left:5px;
			}
            .memberContainer {
                text-align: center;
            }
            .memberContainer h1 {
                padding-top: 25px;
                margin: 0px;
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
        </style>
    </header>
<body>
    <div class="header">
        <h1>tasktrackr</h1>
    </div>

    <div class="container">
        <div class="leftNav">
            <div class="profileNav">
                <?php
                session_start(); // Start the session to access the logged-in user's information

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
                <a href="http://localhost/tasktrackr/home.php"><u>Home</u></a>
            </div>
            <div class="middleNav">
                <a href="http://localhost/tasktrackr/Calendar.php"><u>Calendar</u></a>
            </div>
            <div class="bottomNav">
                <a href="http://localhost/tasktrackr/Members.php"><u>Members</u></a>
            </div>
        </div>

        <div class="mainContent">
            <div class="memberContainer">
                <h1>Members List</h1>

                <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "tasktrackr");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Update Member Information
                if (isset($_POST['update_member'])) {
                    $member_id = $_POST['member_id'];
                    $updated_name = $_POST['name'];
                    $updated_email = $_POST['email'];
                    $updated_phone = $_POST['phone'];

                    // Update query
                    $update_sql = "UPDATE users SET name='$updated_name', email='$updated_email', phone='$updated_phone' WHERE id=$member_id";
                    if ($conn->query($update_sql) === TRUE) {
                        echo "Member updated successfully!";
                    } else {
                        echo "Error updating member: " . $conn->error;
                    }
                }

                // Fetch Members
                $sql = "SELECT id, username, email FROM users";  // Fetching username and email from the users table
                $result = $conn->query($sql);

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
