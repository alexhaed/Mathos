<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['admin'] != 1) {
	header('Location: ../login.html');
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

	// SI MODIFICATION D'UN USER
	if ($_POST['action'] == 'Mettre à jour') {
		if ($_POST['password'] !== "") $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		else $password = $_POST['oldpassword'];
		if ($_POST['username'] !== $_POST['oldusername']) {
		  	$sql_u = "SELECT * FROM accounts WHERE username='".$_POST['username']."'";
		  	$res_u = mysqli_query($con, $sql_u);
		  	if (mysqli_num_rows($res_u) > 0) {
		  	 	echo "<b>Erreur</b><br>Nom d'utilisateur déjà pris";
				echo '<br><br><p style="text-align: center;"><a href="utilisateurs.php">Retour</a></p>';
		  	 	exit();
		  	}
		}
		$stmt = "UPDATE accounts SET username='".$_POST['username']."', password='".$password."' WHERE id=".$_POST['id'];
		mysqli_query($con, $stmt);
		echo "<p style='text-align: center;'>Utilisateur mis à jour!</p>";
	// SI AJOUT D'UN USER
	} elseif ($_POST['action'] == 'Créer') {
	  	$sql_u = "SELECT * FROM accounts WHERE username='".$_POST['username']."'";
	  	$res_u = mysqli_query($con, $sql_u);
	  	if (mysqli_num_rows($res_u) > 0) {
	  	 	 echo "<b>Erreur</b><br>Nom d'utilisateur déjà pris";
			echo '<br><br><p style="text-align: center;"><a href="utilisateurs.php">Retour</a></p>';
	  	 	 exit();
	  	} else {
			$stmt = "INSERT INTO accounts (username, password) VALUES ('".$_POST['username']."', '".password_hash($_POST['password'], PASSWORD_DEFAULT)."')";
			mysqli_query($con, $stmt);
			echo "<p style='text-align: center;'>Utilisateur ajouté!<p>";
		}
	// SI SUPPRESSION D'UN USER
	} elseif ($_POST['action'] == 'Supprimer') {
			$stmt = "DELETE FROM accounts WHERE id=".$_POST['id'];
			mysqli_query($con, $stmt);
			$stmt = "DELETE FROM scores WHERE userid=".$_POST['id'];
			mysqli_query($con, $stmt);
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
	                echo "<td>" . $row['username'] . "</td>";
	                echo "<td>&nbsp;&nbsp;<i class='fa-solid fa-user-pen'></i>&nbsp;<a href='utilisateurs.php?edit=". $row['id'] ."'>Editer</a></td>";
	            echo "</tr>";
	        }
	        echo "</table><br>";
	        mysqli_free_result($result);
	    } else {
	        echo "No records matching your query were found.";
	    }
	} else {
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
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