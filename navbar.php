<?php
// We need to use sessions, so you should always start sessions using the below code.
//session_start();
// If the user is not logged in redirect to the login page...
//if (!isset($_SESSION['loggedin'])) {
//	header('Location: login.html');
//	exit;
//}
?>
		<nav class="navtop">
			<div>
				<h1><i class="fa-solid fa-calculator fa-1x"></i> Mathos</h1>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Quitter</a>
				<a href="index.php"><i class="fas fa-user-circle"></i>Accueil</a>
			</div>
		</nav>