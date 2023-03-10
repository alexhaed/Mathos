<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Mathos - Connexion</title>
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
			<p>Bienvenue sur Mathos,<br>le site qui va t'aider à&nbsp;progresser en&nbsp;math!</p>
			<form action="authenticate.php" method="post">
<?php
	if (isset($_GET['redirect'])) {
		echo '<input type="hidden" name="redirect" value="'.$_GET['redirect'].'">';
	}
?>
				&nbsp;<label for="username"><i class="fas fa-user"></i>&nbsp;</label>
				<input type="text" name="username" placeholder="Nom" id="username" required><br>
				&nbsp;<label for="password"><i class="fas fa-lock"></i>&nbsp;</label>
				<input type="password" name="password" placeholder="Mot de passe" id="password" required>
				<input type="submit" value="Connexion">
			</form>
		</div>
		<div class="inscription">
			<p>Pssst! Pas encore de compte? <a href="nouvelutilisateur.php">Inscris-toi&nbsp;vite!</a></p>
		</div>
	</body>
</html>