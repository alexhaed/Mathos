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
			<h2>Gestion des utilisateurs</h2>
				<div>
<?php
include '../mysql_login.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

// SI AJOUT D'UN USER
if(isset($_GET['add']) && $_GET['add'] == 1) {
	echo '<form action="utilisateurs.php" method="POST">';
	echo '<i class="fa-solid fa-user-plus"></i>&nbsp;&nbsp;<input type="text" size="15" name="username" placeholder="Nom" id="username" required autofocus><br><br>';
	echo '<i class="fa-solid fa-user-lock"></i>&nbsp;&nbsp;<input type="password" size="15" name="password" placeholder="Mot de passe" id="password" required><br><br>';
	echo '<input type="submit" name="action" value="Créer">';
	echo '</form>';
	echo '<br><p style="text-align: center;"><i class="fa-solid fa-arrow-rotate-left"></i> <a href="utilisateurs.php">Retour</a></p>';

// SI MODIFICATION D'UN USER
} elseif (isset($_GET['edit']) && is_numeric($_GET['edit'])) {

	$stmt = $con->prepare('SELECT username, password FROM accounts WHERE id = ?');
	$stmt->bind_param('i', $_GET['edit']);
	$stmt->execute();
	$stmt->bind_result($username, $password);
	$stmt->fetch();
	$stmt->close();

	echo '<form action="utilisateurs.php" method="POST">';
	echo '<input type="hidden" name="id" id="id" value="'.$_GET['edit'].'">';
	echo '<input type="hidden" name="oldpassword" id="oldpassword" value="'.$password.'">';
	echo '<input type="hidden" name="oldusername" id="oldusername" value="'.$username.'">';		
	echo '<i class="fa-solid fa-user-pen"></i>&nbsp;&nbsp;<input type="text" size="15" name="username" value="'.$username.'" id="username" required autofocus><br><br>';
	echo '<i class="fa-solid fa-user-lock"></i>&nbsp;&nbsp;<input type="password" size="15" name="password" placeholder="*********" id="password"><br><br>';
	echo '<input type="submit" name="action" value="Mettre à jour"> &nbsp;&nbsp;';
	echo '<input type="submit" name="action" value="Supprimer" style="background-color: red;" onclick="return confirm(\'Êtes-vous certain?\')">';
	echo '</form>';
	echo '<br><p style="text-align: center;"><i class="fa-solid fa-arrow-rotate-left"></i> <a href="utilisateurs.php">Retour</a></p>';

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
				echo "<b>Erreur</b><br>Nom d'utilisateur déjà pris";
				echo '<br><br><p style="text-align: center;"><a href="utilisateurs.php">Retour</a></p>';
				$chk->close();
				exit();
			}
			$chk->close();
		}

		$stmt = $con->prepare('UPDATE accounts SET username = ?, password = ? WHERE id = ?');
		$stmt->bind_param('ssi', $post_username, $password, $post_id);
		$stmt->execute();
		$stmt->close();
		echo "<p style='text-align: center;'>Utilisateur mis à jour!</p>";

	// SI AJOUT D'UN USER
	} elseif ($action == 'Créer') {
		$chk = $con->prepare('SELECT id FROM accounts WHERE username = ?');
		$chk->bind_param('s', $post_username);
		$chk->execute();
		$chk->store_result();
		if ($chk->num_rows > 0) {
			echo "<b>Erreur</b><br>Nom d'utilisateur déjà pris";
			echo '<br><br><p style="text-align: center;"><a href="utilisateurs.php">Retour</a></p>';
			$chk->close();
			exit();
		}
		$chk->close();
		$hashed = password_hash($post_password, PASSWORD_DEFAULT);
		$stmt = $con->prepare('INSERT INTO accounts (username, password) VALUES (?, ?)');
		$stmt->bind_param('ss', $post_username, $hashed);
		$stmt->execute();
		$stmt->close();
		echo "<p style='text-align: center;'>Utilisateur ajouté!<p>";

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
		echo "<p style='text-align: center;'>Utilisateur supprimé</p>";
	}

	if ($result = mysqli_query($con, "SELECT id, username FROM accounts ORDER BY id ASC")) {
	    if (mysqli_num_rows($result) > 0) {
	        echo "<table style='padding: 5px;border:0.5px solid black;margin-left:auto;margin-right:auto;'>";
	            echo "<tr>";
	                echo "<th style='text-align: left;'>Id</th>";
	                echo "<th style='text-align: left;padding-left: 5px;'>Nom</th>";
	                echo "<th></th>";
	            echo "</tr>";
	        while ($row = mysqli_fetch_array($result)) {
	            echo "<tr>";
	                echo "<td>" . $row['id'] . "</td>";
	                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
	                echo "<td>&nbsp;&nbsp;<i class='fa-solid fa-user-pen'></i>&nbsp;<a href='utilisateurs.php?edit=". (int)$row['id'] ."'>Editer</a></td>";
	            echo "</tr>";
	        }
	        echo "</table><br>";
	        mysqli_free_result($result);
	    } else {
	        echo "No records matching your query were found.";
	    }
	} else {
	    echo "ERROR: Could not execute query. " . mysqli_error($con);
	}
	mysqli_close($con);
?>
				<p style="text-align: center;"><i class="fa-solid fa-user-plus"></i> <a href="utilisateurs.php?add=1">Ajouter</p>
<?php
	}
?>
			</div>
		</div>
	</body>
</html>