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
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
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
				<i class="fa-solid fa-angle-right"></i> <a href="trous.php">Calculs à trous</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="multidiv.php">Multiplication et division</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="prio.php">Priorité des opérations</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="addrelatifs.php">Nombres entiers relatifs</a><br><br>
			Amuse-toi bien! &#128515;
			</p>

<?php
	
	include "levels.php";

	if($_SESSION['admin'] == 1) {
		echo '<h2 style="margin-top: 0px">Admin</h2>';
		echo '<p style="line-height: 25px;"><i class="fa-solid fa-users-gear"></i> <a href="admin/utilisateurs.php">Gérer les utilisateurs</a><br>';
		echo '<i class="fa-solid fa-ranking-star"></i> <a href="admin/stats.php">Voir les statistiques</a></p>';
	}
?>
		</div>
	</body>
</html>