<?php
session_start();
error_reporting(0);
// memanggil database
require "config/db.php";
// memproses login
if(isset($_POST["login"])) {
	$email = $_POST["email"];
	$password = md5($_POST["password"]);
	$query_user = mysqli_query($conn,"SELECT * FROM users WHERE email = '$email'");
	$data_user = mysqli_fetch_array($query_user);
	if($email == $data_user["email"] && $password == $data_user["password"]) {
		$_SESSION["email"]=$email;
		header("location:dashboard");
	} else {
		header("location:?msg=error_1");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sensus Penduduk - Log In.</title>
	<!-- Meta Tag -->
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CSS / Stylesheet -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<div class="container">
	<div class="row">
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-default top-50">
			<div class="panel-heading text-center">
				<b>Aplikasi Sensus Penduduk</b>
			</div>
			<div class="panel-body">
			<?php
			// menampilkan error
			if($_GET["msg"] == "error_1") {
			?>
			<div class="alert alert-danger">
				<strong>Error:</strong> Email atau password yang anda masukan salah!
			</div>
			<?php
			} elseif($_GET["msg"] == "error_2") {
			?>
			<div class="alert alert-danger">
				<strong>Error:</strong> Restricted area!
			</div>
			<?php
			}
			?>
				<form method="post">
				<div class="form-group">
					<label for="email">Email</label>
					<input id="email" class="form-control" type="email" name="email" placeholder="email" autocomplete="off" required="">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input id="password" class="form-control" type="password" name="password" placeholder="password" required="">
				</div>
				<button type="submit" name="login" class="btn btn-success">Log In <span class="glyphicon glyphicon-log-in"></span></button>
				<button type="reset" name="" class="btn btn-danger">Reset <span class="glyphicon glyphicon-remove-circle"></span></button>
				</form>
			</div>
			<div class="panel-footer">
				&copy; 2019 <a href="https://github.com/iamkuja" target="_blank">Rommy</a> - Sensus Penduduk.
			</div>
		</div>
	</div>
	</div>
</div>
</body>
</html>