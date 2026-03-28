<?php
$title = 'Mathos - Nouveau mot de passe';
include 'header.php';
?>
		<div class="login">
			<h1><i class="fa-solid fa-calculator fa-1x"></i> Mathos</h1>
<?php
include 'mysql_login.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
	exit;
}

$token = $_GET['token'] ?? '';
if ($token === '' || strlen($token) !== 64) {
	echo "<p><b>Lien invalide.</b><br><a href='login.php'>Retour à la connexion</a></p>";
	echo '</div>';
	include 'footer.php';
	exit;
}

// Validate token
$stmt = $con->prepare('SELECT pr.userid, a.username FROM password_resets pr JOIN accounts a ON a.id = pr.userid WHERE pr.token = ? AND pr.expires_at > NOW()');
$stmt->bind_param('s', $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
	$stmt->close();
	mysqli_close($con);
	echo "<p><b>Lien expiré ou invalide.</b><br><a href='resetmdp.php'>Faire une nouvelle demande</a></p>";
	echo '</div>';
	include 'footer.php';
	exit;
}

$stmt->bind_result($userid, $username);
$stmt->fetch();
$stmt->close();

if (count($_POST)) {
	$password = $_POST['password'] ?? '';
	$passwordcheck = $_POST['passwordcheck'] ?? '';

	if ($password === '') {
		echo "<p><b>Oups!</b><br>Le mot de passe ne peut pas être vide &#129327;<br><br>";
		echo '<a href="javascript:history.back();">Réessaie!</a><br><br></p>';
	} elseif (strlen($password) < 6) {
		echo "<p><b>Oups!</b><br>Le mot de passe doit faire<br>au moins 6 caractères &#129327;<br><br>";
		echo '<a href="javascript:history.back();">Réessaie!</a><br><br></p>';
	} elseif ($password !== $passwordcheck) {
		echo "<p><b>Oups!</b><br>Les deux mots de passe<br>ne sont pas identiques &#129327;<br><br>";
		echo '<a href="javascript:history.back();">Réessaie!</a><br><br></p>';
	} else {
		$hashed = password_hash($password, PASSWORD_DEFAULT);
		$upd = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?');
		$upd->bind_param('si', $hashed, $userid);
		$upd->execute();
		$upd->close();

		$del = $con->prepare('DELETE FROM password_resets WHERE userid = ?');
		$del->bind_param('i', $userid);
		$del->execute();
		$del->close();

		mysqli_close($con);
		echo "<p><b>Mot de passe changé!</b><br>Tu peux maintenant te connecter<br>avec ton nouveau mot de passe &#129395;<br><br><a href='login.php'>Se connecter</a><br><br></p>";
	}
} else {
?>
			<p>Choisis un nouveau mot de passe<br>pour <b><?= htmlspecialchars($username) ?></b></p>
			<form action="resetmdp_confirm.php?token=<?= htmlspecialchars($token) ?>" method="post">
				&nbsp;<label for="password"><i class="fas fa-lock"></i>&nbsp;</label>
				<input type="password" name="password" placeholder="Nouveau mot de passe" id="password" required autofocus><br>
				&nbsp;<label for="passwordcheck"><i class="fas fa-lock"></i>&nbsp;</label>
				<input type="password" name="passwordcheck" placeholder="Confirmer" id="passwordcheck" required>
				<input type="submit" value="Changer le mot de passe">
			</form>
<?php
	if (isset($con)) mysqli_close($con);
}
?>
		</div>
<?php include 'footer.php'; ?>
