<?php
include 'mysql_login.php';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

$data = json_decode(file_get_contents('php://input'), true);

$stmt = "INSERT INTO scores (`userid`, `exercice`, `nbcalculs`, `reussis`, `temps`) VALUES (".$data["userid"].", '".$data["exercice"]."', ".$data["nbcalculs"].", ".$data["reussis"].", ".$data["duree"].")";

mysqli_query($con, $stmt);

mysqli_close($con);

//echo "ok!";
?>