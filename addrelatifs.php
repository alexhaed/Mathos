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

	if (isset($_GET['nbmax']) && filter_var($_GET['nbmax'], FILTER_VALIDATE_INT)) {
 		echo "			nbmax = ".$_GET['nbmax']."\n";
	} else {
		erreurSelection("plus grand nombre");
	}

	if (isset($_GET['duree']) && is_numeric($_GET['duree']) && $_GET['duree'] > 0) {
 		echo "			departMinutes = ".$_GET['duree']."\n";
	} else {
		erreurSelection("durée");
	}

	//$operations = "";
	//if(isset($_GET['addition']) && $_GET['addition'] == 1) $operations .= "'addition', ";
	//if(isset($_GET['soustraction']) && $_GET['soustraction'] == 1) $operations .= "'soustraction', ";
	//if ($operations == "") erreurSelection("opérations");
 	//echo "			operations = [".$operations."]\n";
 	echo "			operations = ['addition', 'soustraction']\n";

	echo "		</script>\n";
?>
		<form id="formCalcul" onsubmit="checkReponse();">
			<div class="content">
				<h2>Exercices</h2>
				<p id="pcalcul"><span id="calcul"></span> = <input type="text" size="4" name="reponse" placeholder="" id="reponse" required autofocus> <input type="submit" id="submit" value="Vérifier"> <span id="corrige"></span></p>
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
			op = "";

			// NOUVEAU CALCUL
			function nouveauCalcul() {
				operation = operations[Math.floor(Math.random() * operations.length)];
				valeur1 = generateRandomNumber(nbmax,0,1);
				valeur2 = generateRandomNumber(nbmax,0,1);
				//if (operation == 'soustraction') {
				//	valeur2 = generateRandomNumber(valeur1);
				//} else {
				//	valeur2 = generateRandomNumber(nbmax);
				//}
				switch (operation) {
					case 'addition':
						correct = valeur1 + valeur2;
						op = ' + ';
						break;
					case 'soustraction':
						correct = valeur1 - valeur2;
						op = ' - ';
						break;
				}
				nbcalcul += 1;
				essai = 1;
				if (valeur1 < 0) valeur1 = "(" + valeur1 + ")";
				if (valeur2 < 0) valeur2 = "(" + valeur2 + ")";
				document.getElementById('calcul').innerHTML = valeur1 + op + valeur2;
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
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="100" id="nbmax" required><br /><br />
				<!--
				<i class="fa-solid fa-calculator"></i> Opérations: <label><input type="checkbox" name="addition" value="1" checked>Addition</label> <label><input type="checkbox" name="soustraction" value="1" checked>Soustraction</label><br><br>
				-->
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="5" id="duree" required> minutes<br /><br /><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
	</body>
</html>