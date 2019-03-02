<?php

$conn = mysqli_connect("localhost","user","pass","sensus_penduduk");

if(!$conn) {
	echo "<h1>Connection error!</h1>";
}