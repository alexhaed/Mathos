<?php
session_start();
include 'mysql_login.php';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Merci de remplir le nom et le mot de passe!');
}

if ($stmt = $con->prepare('SELECT id, password, admin FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($id, $password, $admin);
		$stmt->fetch();
		if (password_verify($_POST['password'], $password)) {
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $_POST['username'];
			$_SESSION['id'] = $id;
			$_SESSION['admin'] = $admin;
			header('Location: index.php');
		} else {
			echo '<html><head><title>Mathos - Se connecter</title><link href="style.css" rel="stylesheet" type="text/css"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body><div class="login"><h1>Mathos</h1><p style="padding: 20px;text-align: center;">Nom ou mot de passe incorrect! &#128579;<br><br><a href="javascript:history.back();">Réessayer</a></p></div></body></html>';
		}
	} else {
		echo '<html><head><title>Mathos - Se connecter</title><link href="style.css" rel="stylesheet" type="text/css"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body><div class="login"><h1>Mathos</h1><p style="padding: 20px;text-align: center;">Nom ou mot de passe incorrect! &#128579;<br><br><a href="javascript:history.back();">Réessayer</a></p></div></body></html>';
	}
	$stmt->close();
}
?>