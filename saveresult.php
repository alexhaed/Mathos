<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	http_response_code(401);
	exit;
}

include 'mysql_login.php';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	http_response_code(500);
	exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$userid = $_SESSION['id'];
$exercice = $data["exercice"] ?? '';
$nbcalculs = (int)($data["nbcalculs"] ?? 0);
$reussis = (int)($data["reussis"] ?? 0);
$duree = (int)($data["duree"] ?? 0);

$stmt = $con->prepare("INSERT INTO scores (`userid`, `exercice`, `nbcalculs`, `reussis`, `temps`) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isiii", $userid, $exercice, $nbcalculs, $reussis, $duree);
$stmt->execute();
$stmt->close();

mysqli_close($con);
?>
