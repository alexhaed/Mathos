<?php
$title = 'Mathos - Connexion';
include 'header.php';
?>
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
			<p><a href="resetmdp.php">Mot de passe oublié ?</a></p>
		</div>
<?php include 'footer.php'; ?>
