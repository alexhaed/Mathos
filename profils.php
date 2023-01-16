<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Administration des profils</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<script src="https://kit.fontawesome.com/16b34d58e9.js" crossorigin="anonymous"></script>
	</head>
	<body class="loggedin">
<?php
		include 'navbar.php';
?>
		<div class="content">
			<h2>Administration des profils</h2>
				<div>
<?php
	include 'mysql_login.php';
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: ".mysqli_connect_error();
	}

	// Si ajout d'un user
	if(isset($_GET['add']) && $_GET['add'] == 1) {
		echo '<form action="profils.php" method="POST">';
		echo '<input type="text" size="10" name="username" placeholder="Nom" id="username" required autofocus> ';
		echo '<input type="password" name="password" placeholder="Mot de passe" id="password" required> ';
		echo '<input type="submit" value="Créer">';
		echo '</form>';

	// Si edit d'un user
	} elseif (isset($_GET['edit']) && is_numeric($_GET['edit'])) {

		$stmt = $con->prepare('SELECT username, password FROM accounts WHERE id = ?');
		$stmt->bind_param('i', $_GET['edit']);
		$stmt->execute();
		$stmt->bind_result($username, $password);
		$stmt->fetch();
		$stmt->close();

		echo '<form action="profils.php" method="POST">';
		echo '<input type="hidden" name="id" id="id" value="'.$_GET['edit'].'">';
		echo '<input type="hidden" name="oldpassword" id="oldpassword" value="'.$password.'">';		
		echo '<input type="text" size="10" name="username" value="'.$username.'" id="username" required autofocus> ';
		echo '<input type="password" size="12" name="password" value="" id="password"> ';
		echo '<input type="submit" value="Mettre à jour">';
		echo '</form>';

	// Page par défaut
	} else {

		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['id'])) {
			if ($_POST['password'] !== "") $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			else $password = $_POST['oldpassword'];
			$stmt = "UPDATE accounts SET username='".$_POST['username']."', password='".$password."' WHERE id=".$_POST['id'];
			mysqli_query($con, $stmt);
		} elseif (isset($_POST['username']) && isset($_POST['password'])) {
			$stmt = "INSERT INTO accounts (username, password, email) VALUES ('".$_POST['username']."', '".password_hash($_POST['password'], PASSWORD_DEFAULT)."', '')";
			mysqli_query($con, $stmt);
		}

		if ($result = mysqli_query($con, "SELECT id, username FROM accounts")) {
		    if (mysqli_num_rows($result) > 0) {
		        echo "<table>";
		            echo "<tr>";
		                echo "<th>Id</th>";
		                echo "<th>Nom</th>";
		                echo "<th></th>";
		            echo "</tr>";
		        while ($row = mysqli_fetch_array($result)) {
		            echo "<tr>";
		                echo "<td>" . $row['id'] . "</td>";
		                echo "<td>" . $row['username'] . "</td>";
		                echo "<td><a href='profils.php?edit=". $row['id'] ."'>Editer</a></td>";
		            echo "</tr>";
		        }
		        echo "</table>";
		        // Free result set
		        mysqli_free_result($result);
		    } else {
		        echo "No records matching your query were found.";
		    }
		} else {
		    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
		}
		mysqli_close($con);
?>
				<p><a href="profils.php?add=1">Ajouter</p>
<?php
	}
?>
			</div>
		</div>
	</body>
</html>