<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php?redirect='.urlencode(basename($_SERVER['REQUEST_URI'])));
	exit;
}

$title = 'Mathos - Scores';
$body_class = 'loggedin';
include 'header.php';
echo "\n";
include 'navbar.php';
echo "\n";
?>
		<div class="content">
			<h2>Scores de <?=$_SESSION['name']?></h2>
			<p style="line-height: 25px;">
<?php
include 'mysql_login.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

$id = (int)$_SESSION['id'];

// NOMBRE DE CALCULS REUSSIS
$stmt = $con->prepare("SELECT SUM(`reussis`) AS C FROM scores WHERE userid = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$exercices = $stmt->get_result();
$stmt->close();
if ($exercices) {
	$row = $exercices->fetch_assoc();
	if ($row["C"] == 0) {
		echo "Tu n'as fait aucun exercice pour l'instant. Reviens plus tard!";
		mysqli_close($con);
		exit();
	}
	else echo "				<i class='fa-solid fa-trophy'></i> Total des calculs réussis: <b>".$row["C"]."</b><br><br>\n";
}

// DUREE TOTALE DE CALCUL
$stmt = $con->prepare("SELECT SUM(`temps`) AS C FROM scores WHERE userid = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$duree = $stmt->get_result();
$stmt->close();
if ($duree) {
	$row = $duree->fetch_assoc();
	if ($row["C"] == 0) {
		echo "Tu n'as fait aucun exercice pour l'instant. Reviens plus tard!";
		mysqli_close($con);
	}
	else {
		$minutes = floor($row["C"] / 60);
		echo "				<i class='fa-solid fa-hourglass-end'></i> Tu as calculé pendant ".$minutes."&nbsp;minute";
		if($minutes > 1) echo "s";
		echo ".<br><br>\n";
	}
}

// JOURS D'AFFILEE
$stmt = $con->prepare("SELECT DISTINCT DATE_FORMAT(`timestamp`, '%Y-%m-%d') AS D FROM scores WHERE userid = ? ORDER BY D DESC");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result) {
    $today = DateTime::createFromFormat('Y-m-d', date("Y-m-d"))->format('Y-m-d');
    $affilee = 0;
    $last_res = mysqli_fetch_array($result);
	$last = DateTime::createFromFormat('Y-m-d', $last_res['D'])->format('Y-m-d');
    if ($last == $today) {
    	$last_text = "aujourd'hui";
		$affilee += 1;
    	while ($row = mysqli_fetch_array($result)) {
    		$row_date = DateTime::createFromFormat('Y-m-d', $row['D'])->format('Y-m-d');
			if (((strtotime($last) - strtotime($row_date)) / (3600 * 24)) <= 1) {
				$affilee += 1;
				$last = $row_date;
			}
		}
	} else {
		$last_jour = (strtotime($today) - strtotime($last)) / (3600 * 24);
		$last_text = "il y a ".$last_jour." jour".($last_jour > 1 ? "s" : "");
	}
    echo "<i class='fa-solid fa-fire'></i> Tu as joué ".$affilee." jour".($affilee > 1 ? "s" : "")." d'affilée.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dernier&nbsp;exercice:&nbsp;".$last_text.".<br><br>\n";
    mysqli_free_result($result);
}

// EXERCICES FAVORIS
$stmt = $con->prepare("SELECT `exercice`, COUNT(*) AS C FROM scores WHERE userid = ? GROUP BY `exercice` ORDER BY C DESC");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result && mysqli_num_rows($result) > 0) {
    echo "				<i class='fa-brands fa-gratipay'></i> Exercices préférés:<br>\n";
    while ($row = mysqli_fetch_array($result)) {
    	echo "				&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa-solid fa-angle-right'></i> ";
        switch($row['exercice']) {
        	case "addsous": echo "Addition et soustraction"; break;
        	case "compl": echo "Compléments"; break;
        	case "multidiv": echo "Multiplication et division"; break;
        	case "division": echo "Division avec reste"; break;
        	case "prio": echo "Priorité des opérations"; break;
        	case "relatifs": echo "Nombres relatifs"; break;
        	case "trous": echo "Calculs à trous"; break;
        	case "decimaux": echo "Nombres décimaux"; break;
        	case "doublemoitie": echo "Double et moitié"; break;
       }
       echo ": ".$row['C']." fois<br>\n";
    }
    mysqli_free_result($result);
}

// FOOTER
echo "				<br>Bien joué! &#128515;\n			</p>\n";
echo "			<p style='text-align: center'>\n				<i class='fa-solid fa-arrow-rotate-right'></i> <a href='index.php'>Continue à t'entraîner</a>\n			</p>\n";

// HISTORIQUE DES SESSIONS
$stmt = $con->prepare("SELECT timestamp, exercice, reussis, nbcalculs, temps FROM scores WHERE userid = ? ORDER BY timestamp DESC LIMIT 10");
$stmt->bind_param('i', $id);
$stmt->execute();
$historique = $stmt->get_result();
$stmt->close();
if ($historique && mysqli_num_rows($historique) > 0) {
	$noms = [
		'addsous' => 'Addition et soustraction',
		'compl' => 'Compléments',
		'multidiv' => 'Multiplication et division',
		'division' => 'Division avec reste',
		'prio' => 'Priorité des opérations',
		'relatifs' => 'Nombres relatifs',
		'trous' => 'Calculs à trous',
		'decimaux' => 'Nombres décimaux',
		'doublemoitie' => 'Double et moitié',
	];
	echo "\t\t\t<h2><i class='fa-solid fa-clock-rotate-left'></i> Dernières sessions</h2>\n";
	echo "\t\t\t<div style='overflow-x:auto'><table style='width:100%;border-collapse:collapse'>\n";
	echo "\t\t\t\t<tr style='border-bottom:1px solid #e0e0e3;color:#3274d6'>\n";
	echo "\t\t\t\t\t<th style='text-align:left;padding:8px 10px'>Date</th>\n";
	echo "\t\t\t\t\t<th style='text-align:left;padding:8px 10px'>Exercice</th>\n";
	echo "\t\t\t\t\t<th style='text-align:center;padding:8px 10px'>Réussis</th>\n";
	echo "\t\t\t\t\t<th style='text-align:center;padding:8px 10px'>Durée</th>\n";
	echo "\t\t\t\t</tr>\n";
	while ($row = mysqli_fetch_assoc($historique)) {
		$nom = $noms[$row['exercice']] ?? $row['exercice'];
		$dt = new DateTime($row['timestamp']);
		$date_str = $dt->format('d.m.Y H:i');
		$m = floor($row['temps'] / 60);
		$s = $row['temps'] % 60;
		$duree_str = ($m > 0 ? $m.'min ' : '').str_pad($s, 2, '0', STR_PAD_LEFT).'s';
		echo "\t\t\t\t<tr style='border-bottom:1px solid #f0f0f3'>\n";
		echo "\t\t\t\t\t<td style='padding:8px 10px;font-size:14px;color:#888'>".$date_str."</td>\n";
		echo "\t\t\t\t\t<td style='padding:8px 10px'>".$nom."</td>\n";
		echo "\t\t\t\t\t<td style='padding:8px 10px;text-align:center'>".$row['reussis']."&nbsp;/&nbsp;".$row['nbcalculs']."</td>\n";
		echo "\t\t\t\t\t<td style='padding:8px 10px;text-align:center'>".$duree_str."</td>\n";
		echo "\t\t\t\t</tr>\n";
	}
	echo "\t\t\t</table></div>\n";
	mysqli_free_result($historique);
}

mysqli_close($con);
?>
		</div>
<?php include 'footer.php'; ?>
