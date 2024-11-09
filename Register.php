<!DOCTYPE html>
<html>
	<header>
		<style>
			@font-face{
				font-family: 'BitendDemo-Regular';
				src: url(BitendDEMO.otf);
			}
			*{
				font-family: 'BitendDemo-Regular';
			}
			.header{
				width:100%;
				background-color:black;
				color:white;
				margin:0px;
				display:flex;
				text-align:center;
				justify-content:center;
				align-items:center;
				font-size:15px;
			}
			.header h1{
				margin:0px;
			}
			body{
				margin:0px;
			}
			.container{
				height:1119px;
				width:100%;
			}
			.leftNav{
				height:100%;
				width:150px;
				background-color:#989898;
				border:1px solid black;
				float:left;
			}
			.leftNav a{
				display:block;
				padding-left:5px;
				margin-bottom: 7px;
				margin-top: 7px;
			}
			.bottomNav{
				border-top:1px solid black;
				margin:0px;
			}
			.mainContent{
				height:100%;
				margin-top:0px;
				background-color:#BEBEBE;
			}
			.footer{
				background-color:black;
				color:white;
				height:50px;
				margin:0px;
			}
			.footer h1{
				height:100%;
				margin:0px;
				display:flex;
				text-align:center;
				justify-content:center;
				align-items:center;
				font-size:20px;
			}
			.middleNav{
				display:flex;
				justify-content:center;
				border-top:1px solid black;
			}
			.topNav{
				display:flex;
				justify-content:center;
				border-top:1px solid black;
			}
			.profileNav{
				display:flex;
				justify-content:center;
			}
			.mainContent h1{
				margin:0px;
				margin-bottom:15px;
				text-align:center;
			}
			.mainContent{
				padding-top:25px;
				text-align:center;
			}
			.createButton{
				padding: 10px 20px;
				background-color: #4CAF50;
				color: white;
				border: none;
				cursor: pointer;
				text-decoration: none;
				display: inline-block;
				margin-top: 20px;
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
                <p>Profile Name</p>
            </div>
            <div class="topNav">
                <a href="http://localhost/tasktrackr/home.php"><u>Home</u></a>
            </div>
            <div class="middleNav">
                <a href="http://localhost/tasktrackr/Calendar.php"><u>Calendar</u></a>
            </div>
            <div class="bottomNav">
                <a href="http://localhost/tasktrackr/AboutUs.php"><u>About Us</u></a>
                <a href="http://localhost/tasktrackr/Register.php"><u>Register</u></a>
                <a href="http://localhost/tasktrackr/Members.php"><u>Members</u></a>
            </div>
        </div>

        <div class="mainContent">
            <h1>Register</h1>

            <!-- Registration Form -->
            <form action="register.php" method="POST">
                <input type="text" name="name" placeholder="Full Name" required><br><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="text" name="phone" placeholder="Phone Number" required><br><br>
                <button type="submit" name="register">Register</button>
            </form>

            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "tasktrackr");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle Registration
            if (isset($_POST['register'])) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];

                // Sanitize inputs
                $name = $conn->real_escape_string($name);
                $email = $conn->real_escape_string($email);
                $phone = $conn->real_escape_string($phone);

                // Check if the user already exists
                $sql_check = "SELECT * FROM users WHERE email = '$email'";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows > 0) {
                    echo "<p>Email already registered. Please use a different email.</p>";
                } else {
                    // Insert new user
                    $sql = "INSERT INTO users (name, email, phone) VALUES ('$name', '$email', '$phone')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<p>Registration successful! <a href='login.php'>Login here</a></p>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }

            $conn->close();
            ?>

            <!-- Button to View Members -->
            <a href="http://localhost/tasktrackr/Members.php" class="createButton">View Members</a>

        </div>
    </div>

    <div class="footer">
        <h1>© Copyright 2024 by tasktrackr</h1>
    </div>
</body>
</html>
