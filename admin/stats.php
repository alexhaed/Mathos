<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../login.html');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Mathos - Statistiques</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<script src="https://kit.fontawesome.com/16b34d58e9.js" crossorigin="anonymous"></script>
		<script>
			function change(value) {
				window.location = "stats.php?userid=" + value;
			}
		</script>
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1><i class="fa-solid fa-calculator fa-1x"></i> Mathos</h1>
				<a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Quitter</a>
				<a href="../index.php"><i class="fas fa-user-circle"></i>Accueil</a>
			</div>
		</nav>
<?php
		include '../mysql_login.php';
		$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: ".mysqli_connect_error();
		}
?>
		<div class="content">
			<h2>Scores</h2>
				<div><p style="line-height: 25px;">
					<label for="user-select">Choisir un utilisateur:</label>
					<select name="user" id="user-select" onChange="change(this.value);">
<?php
	
	if (isset($_GET['userid'])) $id = $_GET['userid'];
	else $id = $_SESSION['id'];

	if ($users = mysqli_query($con, "SELECT id, username FROM accounts")) {
	    while ($user = mysqli_fetch_array($users)) {  
	    	echo '<option value="'.$user[0].'" ';
	    	if ($user[0] == $id) echo 'selected';
	    	echo '>'.$user[1].'</option>';
	    }
	} else {
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
	}
	echo "</select><br><br>";

	if ($exercices = mysqli_query($con, "SELECT SUM(`reussis`) AS C FROM scores WHERE userid = ".$id)) {
		$row = $exercices->fetch_assoc();
		if ($row["C"] == 0) {
			echo "Tu n'as fait aucun exercice pour l'instant. Reviens plus tard!";
			mysqli_close($con);
			exit();
		}
    	else echo "<i class='fa-solid fa-fire'></i> Total des calculs réussis: <b>".$row["C"]."</b><br><br>";
	} else {
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
	}

	if ($duree = mysqli_query($con, "SELECT SUM(`temps`) AS C FROM scores WHERE userid = ".$id)) {
		$row = $duree->fetch_assoc();
		if ($row["C"] == 0) {
			echo "Tu n'as fait aucun exercice pour l'instant. Reviens plus tard!";
			mysqli_close($con);
			exit();
		}
    	else {
    		$minutes = floor($row["C"] / 60);
    		//$secondes = $row["C"] % 60;
    		echo "<i class='fa-solid fa-hourglass-end'></i> Tu as calculé pendant ".$minutes." minute";
    		if($minutes > 1) echo "s";
    		//echo " et ".$secondes." seconde";
    		//if($secondes > 1) echo "s";
    		echo ".<br><br>";
    	}
	} else {
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
	}

	if ($result = mysqli_query($con, "SELECT `exercice`, COUNT(*) AS C FROM scores WHERE userid = ".$id." GROUP BY `exercice` ORDER BY C DESC;")) {
	    if (mysqli_num_rows($result) > 0) {
	        echo "<i class='fa-brands fa-gratipay'></i> Exercices préférés:<br>";
	        while ($row = mysqli_fetch_array($result)) {
            	echo "&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa-solid fa-angle-right'></i> ";
                switch($row['exercice']) {
                	case "addsous":
                		echo "Addition et soustraction";
                		break;
                	case "compl":
                		echo "Compléments";
                		break;
                	case "multidiv":
                		echo "Multiplication et division";
                		break;
                	case "prio":
                		echo "Priorité des opérations";
                		break;
                	case "addrelatifs":
                		echo "Nombres entiers relatifs";
                		break;
               }
               echo	": ".$row['C']." fois<br>";
	        }
	        echo "<br>";
	        mysqli_free_result($result);
	    } else {
	        echo "No records matching your query were found.";
	    }
	} else {
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
	}

	mysqli_close($con);
?>
			</p>
			</div>
		</div>
	</body>
</html>