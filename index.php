<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Mathos - Accueil</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<script src="https://kit.fontawesome.com/16b34d58e9.js" crossorigin="anonymous"></script>
		<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
		<link rel="manifest" href="favicon/site.webmanifest">
	</head>
	<body class="loggedin">
<?php
include 'navbar.php';
echo "\n";
?>
		<div class="content">
			<h2>Salut <?=$_SESSION['name']?>!</h2>
			<p style="line-height: 25px;">
<?php

include 'mysql_login.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

$id = $_SESSION['id'];

// JOURS D'AFFILEE
if ($result = mysqli_query($con, "SELECT DISTINCT DATE_FORMAT(`timestamp`, '%Y-%m-%d') AS D FROM scores WHERE userid = ".$id." ORDER BY D DESC;")) {
	if (mysqli_num_rows($result) > 0) {
	    $today = DateTime::createFromFormat('Y-m-d', date("Y-m-d"))->format('d.m.Y');
	    $affilee = 0;
	    $last = mysqli_fetch_array($result);
		$last = DateTime::createFromFormat('Y-m-d', $last['D'])->format('d.m.Y');
	    if ($last == $today) {
	    	$last_text = "aujourd'hui";
			$affilee += 1;
	    	while ($row = mysqli_fetch_array($result)) {
	    		$row_date = DateTime::createFromFormat('Y-m-d', $row['D'])->format('d.m.Y');
				if (($last - $row_date) <= 1) {
					$affilee += 1;
					$last = $row_date;
				}
			}
		    echo "<span style='white-space:nowrap;'>Série en cours: ".$affilee."&nbsp;jour".($affilee > 1 ? "s d'affilée!</span>" : ".</span> Reviens demain pour continuer!")."<br>";
		    for ($i = 1; $i <= $affilee; $i++) { 
				echo "<i class='fa-solid fa-fire'></i>&nbsp;";
			}
		} else {
			$last_jour = $today - $last;
			echo "<i class='fa-solid fa-fire'></i> Dernier entraînement ".($last_jour == 1 ? "hier. Fais un exercice aujourd'hui pour continuer ta&nbsp;série!" : "il y a ".$last_jour." jours.");
		}
	}
    mysqli_free_result($result);
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
}

?>
			<br><br>Choisis ce que tu veux entraîner:<br>
				<i class="fa-solid fa-angle-right"></i> <a href="addsous.php">Addition et soustraction</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="compl.php">Compléments</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="trous.php">Calculs à trous</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="multidiv.php">Multiplication et division</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="division.php">Division avec reste</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="prio.php">Priorité des opérations</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="relatifs.php">Nombres relatifs</a><br>
				<i class="fa-solid fa-angle-right"></i> <a href="decimaux.php">Nombres décimaux</a> <span class="new">&#128072;</span><br><br>
				Amuse-toi bien! &#128515;
			</p>
<?php
include "levels.php";
echo "\n";

if ($_SESSION['admin'] == 1) {
	echo '<h2 style="margin-top: 0px">Admin</h2>';
	echo '<p style="line-height: 25px;"><i class="fa-solid fa-users-gear"></i> <a href="admin/utilisateurs.php">Gérer les utilisateurs</a><br>';
	echo '<i class="fa-solid fa-ranking-star"></i> <a href="admin/stats.php">Voir les statistiques</a></p>';
}
?>
		</div>
	</body>
</html>