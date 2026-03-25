<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php?redirect='.urlencode(basename($_SERVER['REQUEST_URI'])));
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Mathos - Changer mon mot de passe</title>
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
echo "\n";
?>
		<div class="content">
			<h2>Changer mon mot de passe</h2>
			<p style="line-height: 35px;">
<?php
if (count($_POST)) {

	$actuel     = $_POST['actuel'] ?? '';
	$nouveau    = $_POST['nouveau'] ?? '';
	$confirme   = $_POST['confirme'] ?? '';

	if ($actuel == '' || $nouveau == '' || $confirme == '') {
		echo '<i class="fa-solid fa-circle-xmark"></i> Tu dois remplir tous les champs.<br><br>';
		echo '<i class="fa-solid fa-arrow-rotate-left"></i> <a href="javascript:history.back();">Retour</a>';
	} else if ($nouveau !== $confirme) {
		echo '<i class="fa-solid fa-circle-xmark"></i> Les deux nouveaux mots de passe ne sont pas identiques.<br><br>';
		echo '<i class="fa-solid fa-arrow-rotate-left"></i> <a href="javascript:history.back();">Retour</a>';
	} else if (strlen($nouveau) < 6) {
		echo '<i class="fa-solid fa-circle-xmark"></i> Le nouveau mot de passe doit faire au moins 6 caractères.<br><br>';
		echo '<i class="fa-solid fa-arrow-rotate-left"></i> <a href="javascript:history.back();">Retour</a>';
	} else {
		include 'mysql_login.php';
		$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: ".mysqli_connect_error();
			exit;
		}

		$id = (int)$_SESSION['id'];

		$stmt = $con->prepare('SELECT password FROM accounts WHERE id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($hash);
		$stmt->fetch();
		$stmt->close();

		if (!password_verify($actuel, $hash)) {
			echo '<i class="fa-solid fa-circle-xmark"></i> Mot de passe actuel incorrect.<br><br>';
			echo '<i class="fa-solid fa-arrow-rotate-left"></i> <a href="javascript:history.back();">Retour</a>';
		} else {
			$new_hash = password_hash($nouveau, PASSWORD_DEFAULT);
			$stmt = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?');
			$stmt->bind_param('si', $new_hash, $id);
			$stmt->execute();
			$stmt->close();
			mysqli_close($con);
			echo '<i class="fa-solid fa-circle-check"></i> Mot de passe changé avec succès!<br><br>';
			echo '<i class="fa-solid fa-arrow-rotate-right"></i> <a href="index.php">Retour à l\'accueil</a>';
		}
	}

} else {
?>
			<form method="POST" action="changermdp.php">
				<i class="fa-solid fa-lock"></i> Mot de passe actuel:<br>
				<input type="password" name="actuel" required autofocus><br><br>
				<i class="fa-solid fa-lock"></i> Nouveau mot de passe:<br>
				<input type="password" name="nouveau" required><br><br>
				<i class="fa-solid fa-lock"></i> Confirmer le nouveau mot de passe:<br>
				<input type="password" name="confirme" required><br><br>
				<input type="submit" value="Changer">
			</form>
<?php
}
?>
			</p>
		</div>
	</body>
</html>