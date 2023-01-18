<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Mathos - Accueil</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<script src="https://kit.fontawesome.com/16b34d58e9.js" crossorigin="anonymous"></script>
	</head>
	<body class="loggedin">
<?php
	include 'navbar.php';
?>
		<div class="content">
			<h2>Accueil</h2>
			<p style="line-height: 25px;">Salut <?=$_SESSION['name']?>!<br><br>
				Choisis ce que tu veux entraîner:<br>
				<i class="fa-solid fa-angle-right"></i> <a href="addsous.php">Addition et soustraction</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="compl.php">Compléments</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="multidiv.php">Multiplication et division</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="prio.php">Priorité des opérations</a><br><br>
			Amuse-toi bien! &#128515;
			</p>
		</div>
	</body>
</html>