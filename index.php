<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}

$title = 'Mathos - Accueil';
$body_class = 'loggedin';
include 'header.php';
echo "\n";
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

$id = (int)$_SESSION['id'];

// JOURS D'AFFILEE
$stmt = $con->prepare("SELECT DISTINCT DATE_FORMAT(`timestamp`, '%Y-%m-%d') AS D FROM scores WHERE userid = ? ORDER BY D DESC");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result) {
	if (mysqli_num_rows($result) > 0) {
	    $today = DateTime::createFromFormat('Y-m-d', date("Y-m-d"))->format('Y-m-d');
	    $affilee = 0;
	    $last_res = mysqli_fetch_array($result);
		$last = DateTime::createFromFormat('Y-m-d', $last_res['D'])->format('Y-m-d');
	    if ($last == $today) {
			$affilee += 1;
	    	while ($row = mysqli_fetch_array($result)) {
	    		$row_date = DateTime::createFromFormat('Y-m-d', $row['D'])->format('Y-m-d');
				if (((strtotime($last) - strtotime($row_date)) / (3600 * 24)) <= 1) {
					$affilee += 1;
					$last = $row_date;
				}
			}
		    echo "				Série en cours: ".$affilee."&nbsp;jour".($affilee > 1 ? "s d'affilée!<br>\n" : ". Reviens demain pour continuer! ")."				";
		    for ($i = 1; $i <= $affilee; $i++) {
				echo "<i class='fa-solid fa-fire'></i>&nbsp;";
			}
		} else {
			$last_jour = (strtotime($today) - strtotime($last)) / (3600 * 24);
			echo "				<i class='fa-solid fa-fire'></i> Dernier entraînement ".($last_jour == 1 ? "hier. Fais un exercice aujourd'hui pour continuer ta&nbsp;série!" : "il y a ".$last_jour." jours.")."\n";
		}
	}
    mysqli_free_result($result);
}

?>

				<br>
				Amuse-toi bien! &#128515;
			</p>
			<div class="exercise-grid">
				<a href="exercise.php?type=addsous" class="exercise-card">
					<i class="fa-solid fa-plus"></i>
					<span>Addition et soustraction</span>
				</a>
				<a href="exercise.php?type=compl" class="exercise-card">
					<i class="fa-solid fa-puzzle-piece"></i>
					<span>Compléments</span>
				</a>
				<a href="exercise.php?type=trous" class="exercise-card">
					<i class="fa-solid fa-circle-question"></i>
					<span>Calculs à trous</span>
				</a>
				<a href="exercise.php?type=multidiv" class="exercise-card">
					<i class="fa-solid fa-xmark"></i>
					<span>Multiplication et division</span>
				</a>
				<a href="exercise.php?type=division" class="exercise-card">
					<i class="fa-solid fa-divide"></i>
					<span>Division avec reste</span>
				</a>
				<a href="exercise.php?type=prio" class="exercise-card">
					<i class="fa-solid fa-layer-group"></i>
					<span>Priorité des opérations</span>
				</a>
				<a href="exercise.php?type=relatifs" class="exercise-card">
					<i class="fa-solid fa-arrows-left-right"></i>
					<span>Nombres relatifs</span>
				</a>
				<a href="exercise.php?type=decimaux" class="exercise-card">
					<i class="fa-solid fa-circle-dot"></i>
					<span>Nombres décimaux</span>
				</a>
				<a href="exercise.php?type=doublemoitie" class="exercise-card">
					<i class="fa-solid fa-scale-balanced"></i>
					<span>Double et moitié</span>
					<span class="badge-new">Nouveau</span>
				</a>
			</div>
<?php
include "levels.php";
echo "\n";

if ($_SESSION['admin'] == 1) {
	echo "			<h2 style='margin-top: 0px'>Admin</h2>\n";
	echo "			<p style='line-height: 25px;'><i class='fa-solid fa-users-gear'></i> <a href='admin/utilisateurs.php'>Gérer les utilisateurs</a><br>\n";
	echo "			<i class='fa-solid fa-ranking-star'></i> <a href='admin/stats.php'>Voir les statistiques</a></p>\n";
}
?>
		</div>
<?php include 'footer.php'; ?>
