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
		</style>
		<div class="header">
			<h1>tasktrackr</h1>
		</div>
	</header>
	<body>
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
			</div>
		<div class="footer">
			<h1>© Copyright 2024 by tasktrackr</h1>
		</div>
		</div>
	</body>
</html>