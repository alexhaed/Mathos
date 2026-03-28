<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['admin'] != 1) {
	header('Location: ../404.html');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Gestion des utilisateurs</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
		<link rel="manifest" href="../favicon/site.webmanifest">
		<script src="https://kit.fontawesome.com/16b34d58e9.js" crossorigin="anonymous"></script>
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1><i class="fa-solid fa-calculator fa-1x"></i> Mathos</h1>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Quitter</a>
				<a href="../index.php"><i class="fas fa-user-circle"></i>Accueil</a>
			</div>
		</nav>
		<div class="content">
			<h2><i class="fa-solid fa-users-gear"></i> Gestion des utilisateurs</h2>
<?php
include '../mysql_login.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

// SI AJOUT D'UN USER
if (isset($_GET['add']) && $_GET['add'] == 1) {
?>
			<div>
				<form action="utilisateurs.php" method="POST" style="line-height:2.2">
					<i class="fa-solid fa-user-plus"></i>&nbsp; <input type="text" size="20" name="username" placeholder="Nom d'utilisateur" id="username" required autofocus><br>
					<i class="fa-solid fa-user-lock"></i>&nbsp; <input type="password" size="20" name="password" placeholder="Mot de passe" id="password" required><br><br>
					<input type="submit" name="action" value="Créer">
					&nbsp;&nbsp;<a href="utilisateurs.php"><i class="fa-solid fa-arrow-rotate-left"></i> Annuler</a>
				</form>
			</div>
<?php

// SI MODIFICATION D'UN USER
} elseif (isset($_GET['edit']) && is_numeric($_GET['edit'])) {

	$stmt = $con->prepare('SELECT username, password FROM accounts WHERE id = ?');
	$stmt->bind_param('i', $_GET['edit']);
	$stmt->execute();
	$stmt->bind_result($username, $password);
	$stmt->fetch();
	$stmt->close();
?>
			<div>
				<form action="utilisateurs.php" method="POST" style="line-height:2.2">
					<input type="hidden" name="id" value="<?= (int)$_GET['edit'] ?>">
					<input type="hidden" name="oldpassword" value="<?= htmlspecialchars($password) ?>">
					<input type="hidden" name="oldusername" value="<?= htmlspecialchars($username) ?>">
					<i class="fa-solid fa-user-pen"></i>&nbsp; <input type="text" size="20" name="username" value="<?= htmlspecialchars($username) ?>" id="username" required autofocus><br>
					<i class="fa-solid fa-user-lock"></i>&nbsp; <input type="password" size="20" name="password" placeholder="Laisser vide pour ne pas changer" id="password"><br><br>
					<input type="submit" name="action" value="Mettre à jour">
					&nbsp;&nbsp;<input type="submit" name="action" value="Supprimer" style="background-color:#ef4444;border-radius:6px;" onclick="return confirm('Supprimer cet utilisateur et tous ses scores?')">
					&nbsp;&nbsp;<a href="utilisateurs.php"><i class="fa-solid fa-arrow-rotate-left"></i> Annuler</a>
				</form>
			</div>
<?php

// LISTE DES USERS
} else {

	$action = $_POST['action'] ?? '';
	$post_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
	$post_username = $_POST['username'] ?? '';
	$post_password = $_POST['password'] ?? '';
	$post_oldpassword = $_POST['oldpassword'] ?? '';
	$post_oldusername = $_POST['oldusername'] ?? '';

	// SI MODIFICATION D'UN USER
	if ($action == 'Mettre à jour') {
		$password = ($post_password !== "") ? password_hash($post_password, PASSWORD_DEFAULT) : $post_oldpassword;

		if ($post_username !== $post_oldusername) {
			$chk = $con->prepare('SELECT id FROM accounts WHERE username = ?');
			$chk->bind_param('s', $post_username);
			$chk->execute();
			$chk->store_result();
			if ($chk->num_rows > 0) {
				echo "<div><p><i class='fa-solid fa-circle-xmark' style='color:#ef4444'></i> Nom d'utilisateur déjà pris. <a href='utilisateurs.php'>Retour</a></p></div>";
				$chk->close();
				exit();
			}
			$chk->close();
		}

		$stmt = $con->prepare('UPDATE accounts SET username = ?, password = ? WHERE id = ?');
		$stmt->bind_param('ssi', $post_username, $password, $post_id);
		$stmt->execute();
		$stmt->close();
		echo "<div><p><i class='fa-solid fa-circle-check' style='color:#22c55e'></i> Utilisateur mis à jour.</p></div>";

	// SI AJOUT D'UN USER
	} elseif ($action == 'Créer') {
		$chk = $con->prepare('SELECT id FROM accounts WHERE username = ?');
		$chk->bind_param('s', $post_username);
		$chk->execute();
		$chk->store_result();
		if ($chk->num_rows > 0) {
			echo "<div><p><i class='fa-solid fa-circle-xmark' style='color:#ef4444'></i> Nom d'utilisateur déjà pris. <a href='utilisateurs.php'>Retour</a></p></div>";
			$chk->close();
			exit();
		}
		$chk->close();
		$hashed = password_hash($post_password, PASSWORD_DEFAULT);
		$stmt = $con->prepare('INSERT INTO accounts (username, password) VALUES (?, ?)');
		$stmt->bind_param('ss', $post_username, $hashed);
		$stmt->execute();
		$stmt->close();
		echo "<div><p><i class='fa-solid fa-circle-check' style='color:#22c55e'></i> Utilisateur ajouté.</p></div>";

	// SI SUPPRESSION D'UN USER
	} elseif ($action == 'Supprimer') {
		$stmt = $con->prepare('DELETE FROM accounts WHERE id = ?');
		$stmt->bind_param('i', $post_id);
		$stmt->execute();
		$stmt->close();
		$stmt = $con->prepare('DELETE FROM scores WHERE userid = ?');
		$stmt->bind_param('i', $post_id);
		$stmt->execute();
		$stmt->close();
		echo "<div><p><i class='fa-solid fa-circle-check' style='color:#22c55e'></i> Utilisateur supprimé.</p></div>";
	}

	// DEMANDES DE REINITIALISATION EN ATTENTE
	$pending = mysqli_query($con, "SELECT pr.id, a.username, pr.token, pr.expires_at FROM password_resets pr JOIN accounts a ON a.id = pr.userid WHERE pr.expires_at > NOW() ORDER BY pr.expires_at ASC");
	if ($pending && mysqli_num_rows($pending) > 0) {
		echo "<div style='background:#fffbeb;border-left:4px solid #fbbf24;padding:16px 20px'>";
		echo "<p style='margin:0 0 10px 0;font-weight:700;color:#92400e'><i class='fa-solid fa-key'></i>&nbsp; Demandes de réinitialisation en attente</p>";
		while ($pr = mysqli_fetch_assoc($pending)) {
			$link = '../resetmdp_confirm.php?token='.htmlspecialchars($pr['token']);
			echo "<p style='margin:4px 0'><i class='fa-solid fa-user' style='color:#888'></i> <b>".htmlspecialchars($pr['username'])."</b>";
			echo " <span style='color:#aaa;font-size:13px'>— expire le ".date('d.m.Y H:i', strtotime($pr['expires_at']))."</span>";
			echo " &nbsp;<a href='".$link."' target='_blank'><i class='fa-solid fa-link'></i> Lien de réinitialisation</a></p>";
		}
		echo "</div>";
		mysqli_free_result($pending);
	}

	// LISTE DES USERS
	if ($result = mysqli_query($con, "SELECT id, username FROM accounts ORDER BY id ASC")) {
		if (mysqli_num_rows($result) > 0) {
			echo "<div>";
			echo "<table style='width:100%;border-collapse:collapse'>";
			echo "<tr style='border-bottom:2px solid #e0e0e3;color:#3274d6'>";
			echo "<th style='text-align:left;padding:8px 12px'>Id</th>";
			echo "<th style='text-align:left;padding:8px 12px'>Nom</th>";
			echo "<th></th>";
			echo "</tr>";
			while ($row = mysqli_fetch_array($result)) {
				echo "<tr style='border-bottom:1px solid #f0f0f3'>";
				echo "<td style='padding:10px 12px;color:#888;font-size:14px'>".(int)$row['id']."</td>";
				echo "<td style='padding:10px 12px;font-weight:600'>".htmlspecialchars($row['username'])."</td>";
				echo "<td style='padding:10px 12px;text-align:right'><a href='utilisateurs.php?edit=".(int)$row['id']."'><i class='fa-solid fa-user-pen'></i> Éditer</a></td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<p style='text-align:right;margin-top:12px'><a href='utilisateurs.php?add=1'><i class='fa-solid fa-user-plus'></i> Ajouter un utilisateur</a></p>";
			echo "</div>";
			mysqli_free_result($result);
		}
	}
	mysqli_close($con);
}
?>
		</div>
	</body>
</html>
