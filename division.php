<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php?redirect='.urlencode(basename($_SERVER['REQUEST_URI'])));
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
		echo '</script><div class="content"><h2>Erreur</h2><p style="line-height: 35px; text-align: center;">Erreur dans la séléction ('.$texte.') &#128579;<br>';
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

	echo "		</script>\n";
?>
		<form id="formCalcul" onsubmit="checkReponseDiv();">
			<div class="content">
				<h2>Exercices</h2>
				<p id="pcalcul"><span id="calcul"></span><br><br>Quotient:&nbsp;<input type="text" size="4" name="reponseQuot" placeholder="" id="reponseQuot" required autofocus> &nbsp;Reste:&nbsp;<input type="text" size="4" name="reponseReste" placeholder="" id="reponseReste" required><br><br><input type="submit" id="submit" value="Vérifier"> <span id="corrige"></span></p>
				<p><span id="timer"></span><span id="stats"></span></p>
			</div>
		</form>
<?php
include 'common_functions.php';
echo "\n";
?>
		<script type='text/javascript'>
			nbcorrect = 0;
			nbcalcul = 0;
			essai = 0;

			function checkReponseDiv() {
				event.preventDefault();
				var reponseQuot = document.getElementById("reponseQuot").value;
				var reponseReste = document.getElementById("reponseReste").value;
				if (reponseQuot == quotient && reponseReste == reste) {
					if (essai == 1 ) nbcorrect += 1;
					document.getElementById('corrige').innerHTML = '&nbsp;Juste! <i class="fa-solid fa-circle-check"></i>';
					document.getElementById('stats').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-fire"></i> Réussi: ' + nbcorrect + ' sur ' + nbcalcul;
					if(nbcalcul < totalCalcul) {
						setTimeout(nouveauCalcul, 300);
					} else {
						termine("totalCalcul");
						clearInterval(intervalID);
					}
					document.getElementById('reponseQuot').focus();
				}
				else {
					document.getElementById('corrige').innerHTML = '&nbsp;Faux! <i class="fa-solid fa-circle-xmark"></i>';
					document.getElementById('reponseQuot').value = '';
					document.getElementById('reponseReste').value = '';
					document.getElementById('reponseQuot').focus();
					essai += 1;
				}
				return false;
			}

			// NOUVEAU CALCUL
			function nouveauCalcul(arg) {
				dividende = generateRandomNumber(nbmax);
				diviseur = generateRandomNumber(dividende);
				if (diviseur === 0) nouveauCalcul(1);
				quotient = Math.floor(dividende / diviseur);
				reste = dividende % diviseur;				
				essai = 1;
				if (arg != 1) nbcalcul += 1;
				document.getElementById('calcul').innerHTML = dividende + ' : ' + diviseur;
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponseQuot').value = '';
				document.getElementById('reponseReste').value = '';
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
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required><br><br>
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="12" id="nbmax" required><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="2" id="duree" required> minutes<br><br><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
	</body>
</html>