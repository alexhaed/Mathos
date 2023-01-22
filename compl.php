<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Mathos - Exercices</title>
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

// SI SELECTION POUR L'EXERCICE DEJA FAITE
if (count($_GET)) {
	echo "		<script type='text/javascript'>\n";

	function erreurSelection($texte) {
		echo '</script><div class="content"><h2>Erreur</h2><p style="line-height: 35px; text-align: center;">Erreur dans la séléction ('.$texte.')<br>';
		echo ' <i class="fa-solid fa-arrow-rotate-left"></i> <a href="javascript:history.back();">Retour</a></p></div></body></html>';
		exit();
	}

	if (isset($_GET['nbcalcul']) && filter_var($_GET['nbcalcul'], FILTER_VALIDATE_INT) && $_GET['nbcalcul'] > 0) {
 		echo "			totalCalcul = ".$_GET['nbcalcul']."\n";
	} else {
		erreurSelection("nombre de calculs");
	}

	if (isset($_GET['duree']) && is_numeric($_GET['duree']) && $_GET['duree'] > 0) {
 		echo "			departMinutes = ".$_GET['duree']."\n";
	} else {
		erreurSelection("durée");
	}

	$compl = "";
	if (isset($_GET['10']) && $_GET['10'] == 1) $compl .= "10, ";
	if (isset($_GET['100']) && $_GET['100'] == 1) $compl .= "100, ";
	if (isset($_GET['1000']) && $_GET['1000'] == 1) $compl .= "1000";
	if ($compl == "") erreurSelection("complément");
 	echo "			compls = [".$compl."]\n";

	echo "		</script>\n";
?>
		<form id="formCalcul" onsubmit="checkReponse();">
			<div class="content">
				<h2>Exercices</h2>
				<p id="pcalcul"><span id="calcul"></span>&nbsp;&nbsp;&nbsp;<input type="submit" id="submit" value="Vérifier"> <span id="corrige"></span></p>
				<p><span id="timer"><i class="fa-solid fa-hourglass-half"></i></span><span id="stats"></span></p>
			</div>
		</form>
<?php
include 'common_functions.php';
echo "\n";
?>
		<script type="text/javascript">
			nbcorrect = 0;
			nbcalcul = 0;
			essai = 0;

			// NOUVEAU CALCUL
			function nouveauCalcul() {
				compl = compls[Math.floor(Math.random() * compls.length)];
				valeur1 = generateRandomNumber(compl);
				correct = compl - valeur1;
				nbcalcul += 1;
				essai = 1;
				document.getElementById('calcul').innerHTML = valeur1 + ' + <input type="text" size="4" name="reponse" placeholder="" id="reponse" required> = ' + compl;
				document.getElementById("reponse").focus();
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponse').value = '';
			}

			nouveauCalcul();
		</script>
<?php 
// SI PAS DE SELECTION POUR L'EXERCICE
} else {
?>
		<form id="formCalcul" method="GET" action="<?php echo basename($_SERVER['PHP_SELF']); ?>">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required autofocus><br /><br />
				<i class="fa-solid fa-calculator"></i> Complément à:&nbsp;<label><input type="checkbox" name="10" value="1" checked>10</label>&nbsp;&nbsp;<label><input type="checkbox" name="100" value="1" checked>100</label>&nbsp;&nbsp;<label><input type="checkbox" name="1000" value="1" checked>1000</label><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="5" id="duree" required> minutes<br /><br /><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
	</body>
</html>