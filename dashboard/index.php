<?php
session_start();
error_reporting(0);
// memanggil database
require "../config/db.php";
// menampilkan halaman jika mempunyai session email
if(isset($_SESSION["email"])) {
	$email = $_SESSION["email"];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sensus Penduduk - Dasboard.</title>
	<!-- Meta Tag -->
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CSS / Stylesheet -->
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
	<nav class="nav navbar-default">
		<div class="container">
			<div class="nav navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="nav navbar-brand" href="index.php">Sensus Penduduk</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="active"><a href="index.php"><span class="glyphicon glyphicon-home"></span> Beranda</a></li>
				<li><a href="?menu=data_penduduk"><span class="glyphicon glyphicon-list"></span> Data Penduduk</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $email; ?></a></li>
				<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
			</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			<div class="panel panel-default top-10">
			<div class="panel-body">
			<?php
				if($_GET["menu"] == "data_penduduk") {
					require "data_penduduk.php";
				} else {
					require "data_daerah.php";
				}
			?>
			</div>
			</div>
			</div>
		</div>
	</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
</body>
</html>
<?php
} else {
	header("location:../?msg=error_2");
}
?>