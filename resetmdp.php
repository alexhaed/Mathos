<?php
$title = 'Mathos - Mot de passe oublié';
include 'header.php';
?>
		<div class="login">
			<h1><i class="fa-solid fa-calculator fa-1x"></i> Mathos</h1>
<?php
if (count($_POST)) {

	include 'mysql_login.php';
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: ".mysqli_connect_error();
	}

	$username = trim($_POST['username'] ?? '');

	if ($username === '') {
		echo "<p><b>Oups!</b><br>Indique ton nom d'utilisateur &#129327;<br><br>";
		echo '<a href="javascript:history.back();">Réessaie!</a><br><br></p>';
	} else {
		$stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?');
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows === 0) {
			// Don't reveal if the user exists
			echo "<p><b>Demande envoyée!</b><br>Si ce nom existe, un administrateur<br>va recevoir ta demande &#128522;<br><br>";
			echo '<a href="login.php">Retour à la connexion</a><br><br></p>';
		} else {
			$stmt->bind_result($userid);
			$stmt->fetch();
			$stmt->close();

			// Generate a secure token
			$token = bin2hex(random_bytes(32));
			$expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

			// Delete any existing reset for this user
			$del = $con->prepare('DELETE FROM password_resets WHERE userid = ?');
			$del->bind_param('i', $userid);
			$del->execute();
			$del->close();

			// Store new token
			$ins = $con->prepare('INSERT INTO password_resets (userid, token, expires_at) VALUES (?, ?, ?)');
			$ins->bind_param('iss', $userid, $token, $expires);
			$ins->execute();
			$ins->close();

			// Notify admin
			$resetlink = 'https://mathos.haederli.me/resetmdp_confirm.php?token='.$token;
			$message = "L'utilisateur \"".$username."\" a demandé une réinitialisation de mot de passe.\n\n";
			$message .= "Lien de réinitialisation (valable 24h):\n".$resetlink."\n\n";
			$message .= "Tu peux aussi gérer les demandes depuis le panneau admin.";
			$header = 'From: Mathos <mathos@haederli.me>'."\r\n";
			$header .= 'Content-Type: text/plain; charset=utf-8'."\r\n";
			mail("mathos@haederli.me", "Réinitialisation de mot de passe - ".$username, $message, $header);

			echo "<p><b>Demande envoyée!</b><br>Un administrateur va recevoir<br>ta demande et t'envoyer un lien &#128522;<br><br>";
			echo '<a href="login.php">Retour à la connexion</a><br><br></p>';
		}
	}
	mysqli_close($con);

} else {
?>
			<p>Indique ton nom d'utilisateur<br>et un admin va t'envoyer un lien<br>pour changer ton mot de passe</p>
			<form action="resetmdp.php" method="post">
				&nbsp;<label for="username"><i class="fas fa-user"></i>&nbsp;</label>
				<input type="text" name="username" placeholder="Nom" id="username" required autofocus>
				<input type="submit" value="Envoyer la demande">
			</form>
<?php
}
?>
		</div>
		<div class="inscription">
			<p><a href="login.php"><i class="fa-solid fa-arrow-left"></i> Retour à la connexion</a></p>
		</div>
<?php include 'footer.php'; ?>
