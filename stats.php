<?php
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
		<title>Mathos - Statistiques</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<script src="https://kit.fontawesome.com/16b34d58e9.js" crossorigin="anonymous"></script>
	</head>
	<body class="loggedin">
<?php
		include 'navbar.php';
?>
		<div class="content">
			<h2>Scores de <?=$_SESSION['name']?></h2>
				<div><p style="line-height: 25px;">
<?php
	include 'mysql_login.php';
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: ".mysqli_connect_error();
	}

	$id = $_SESSION['id'];

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
    		$secondes = $row["C"] % 60;
    		echo "<i class='fa-solid fa-hourglass-end'></i> Tu as calculé pendant ".$minutes." minute";
    		if($minutes > 1) echo "s";
    		echo " et ".$secondes." seconde";
    		if($secondes > 1) echo "s";
    		echo "<br><br>";
    	}
	} else {
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
	}

	if ($result = mysqli_query($con, "SELECT `exercice`, COUNT(*) AS C FROM scores WHERE userid = ".$id." GROUP BY `exercice` ORDER BY C DESC;")) {
	    if (mysqli_num_rows($result) > 0) {
	        echo "Exercices préférés:<br>";
	        while ($row = mysqli_fetch_array($result)) {
            	echo "<i class='fa-solid fa-angle-right'></i> ";
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

	echo "Bien joué! &#128515;<br><br>";
	echo '<div style="text-align: center"><i class="fa-solid fa-arrow-rotate-right"></i> <a href="index.php">Continue à t\'entraîner</a></div>';

	mysqli_close($con);
?>
			</p>
			</div>
		</div>
	</body>
</html>