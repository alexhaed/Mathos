<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Mathos - Inscription</title>
		<script src="https://kit.fontawesome.com/16b34d58e9.js" crossorigin="anonymous"></script>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
	</head>
	<body>
		<div class="login">
			<h1><i class="fa-solid fa-calculator fa-1x"></i> Mathos</h1>
<?php
if (count($_POST)) {

	include 'mysql_login.php';
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: ".mysqli_connect_error();
	}

	$username = $_POST['username'] ?? '';
	$password = $_POST['password'] ?? '';
	$passwordcheck = $_POST['passwordcheck'] ?? '';

	if ($password != $passwordcheck) {
		echo "<p><b>Oups!</b><br>Les deux mots de passe<br>ne sont pas identitiques &#129327<br><br>";
		echo '<a href="javascript:history.back();">Réessaie!</a><br><br></p>';
	} else if ($password == "" || $username == "") {
		echo "<p><b>Oups!</b><br>Tu dois remplir tous les champs &#129327<br><br>";
		echo '<a href="javascript:history.back();">Réessaie!</a><br><br></p>';
	} else {
		$stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?');
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			echo "<p><b>Oups!</b><br>Ce nom d'utilisateur est déjà pris &#128579;<br><br>";
			echo '<a href="javascript:history.back();">Trouves-en un autre!</a><br><br></p>';
		} else {
			$stmt->close();
			$hashed = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $con->prepare('INSERT INTO accounts (username, password) VALUES (?, ?)');
			$stmt->bind_param('ss', $username, $hashed);
			$stmt->execute();
			$stmt->close();
			mysqli_close($con);
			echo "<p><b>Bravo, c'est fait!</b><br>Tu peux maintenant te connecter<br>avec ces informations &#129395;<br><br><a href='index.php'>Se connecter</a><br><br></p>";

			$message = "L'utilisateur ".$username." a été créé.";
			$header = 'From: Mathos <mathos@haederli.me>'."\r\n";
			$header .= 'Content-Type: text/plain; charset=utf-8'."\r\n";
			mail("mathos@haederli.me", "Nouvel utilisateur créé sur Mathos", $message, $header);
		}
		if (isset($stmt)) $stmt->close();
	}
} else {
?>
			<p>Pour utiliser Mathos, tu as besoin d'indiquer un&nbsp;nom et un mot&nbsp;de&nbsp;passe</p>
			<form action="nouvelutilisateur.php" method="post">
				&nbsp;<label for="username"><i class="fas fa-user"></i>&nbsp;</label>
				<input type="text" name="username" placeholder="Nom" id="username" required><br>
				&nbsp;<label for="password"><i class="fas fa-lock"></i>&nbsp;</label>
				<input type="password" name="password" placeholder="Mot de passe" id="password" required><br>
				&nbsp;<label for="passwordcheck"><i class="fas fa-lock"></i>&nbsp;</label>
				<input type="password" name="passwordcheck" placeholder="Mot de passe (vérification)" id="password" required>
				<input type="submit" value="Inscription">
			</form>
<?php
}
?>
		</div>
	</body>
</html>