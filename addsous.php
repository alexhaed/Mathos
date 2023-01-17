<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
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
	</head>
	<body class="loggedin">

<?php
include 'navbar.php';

// si séléction déjà faite
if(count($_GET)) {
	echo "		<script>\n";

	function erreurSelection() {
		echo '</script><br><br>Erreur dans la séléction<br><a href="addsous.php">Retour</a>';
		exit();
	}

	if(isset($_GET['nbcalcul']) && is_numeric($_GET['nbcalcul'])) {
 		echo "			totalCalcul = ".$_GET['nbcalcul']."\n";
	} else {
		erreurSelection();
	}

	if(isset($_GET['nbmax']) && is_numeric($_GET['nbmax'])) {
 		echo "			nbmax = ".$_GET['nbmax']."\n";
	} else {
		erreurSelection();
	}

	if(isset($_GET['duree']) && is_numeric($_GET['duree'])) {
 		echo "			departMinutes = ".$_GET['duree']."\n";
	} else {
		erreurSelection();
	}

	$operations = "";

	if(isset($_GET['addition']) && $_GET['addition'] == 1) $operations .= "'addition', ";
	if(isset($_GET['soustraction']) && $_GET['soustraction'] == 1) $operations .= "'soustraction', ";

	if ($operations == "") erreurSelection();

 		echo "			operations = [".$operations."]\n";

	echo "		</script>\n";

?>
		<form id="formCalcul" onsubmit="checkReponse()">
			<div class="content">
				<h2>Exercices</h2>
				<p id="pcalcul"><span id="calcul"></span> = <input type="text" size="4" name="reponse" placeholder="" id="reponse" required autofocus> <input type="submit" id="submit" value="Valider"> <span id="corrige"></span></p>
				<p><span id="timer"><i class="fa-solid fa-hourglass-half"></i></span><span id="stats"></span></p>
			</div>
		</form>
		<script>
			nbcorrect = 0;
			nbcalcul = 0;
			essai = 0;
			op = "";

			function generateRandomInteger(max) {
    			return Math.floor(Math.random() * (max +1)) ;
			}

			// NOUVEAU CALCUL
			function nouveauCalcul() {
				operation = operations[Math.floor(Math.random() * operations.length)];
				valeur1 = generateRandomInteger(nbmax);
				if (operation == 'soustraction') {
					valeur2 = generateRandomInteger(valeur1);
				} else {
					valeur2 = generateRandomInteger(nbmax);
				}
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
				document.getElementById('calcul').innerHTML = valeur1 + op + valeur2;
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponse').value = '';
			}

			function termine(arg) {
				if (arg == "temps") {
					feedback = 'Temps écoulé, dommage! &#128533<br>';
				}
				if (arg == "totalCalcul") {
					feedback = 'Fin des calculs, bravo! &#128526;<br>';
					duree = (departMinutes*60)-temps;
					minutes = parseInt(duree / 60, 10);
					secondes = parseInt(duree % 60, 10);
					minutes = minutes < 10 ? "0" + minutes : minutes;
					secondes = secondes < 10 ? "0" + secondes : secondes;
					document.getElementById("timer").innerHTML = '<i class="fa-solid fa-hourglass-end"></i> Temps écoulé: ' + minutes + ':' + secondes;
				}
				document.getElementById('pcalcul').innerHTML = feedback;
				document.body.innerHTML += '<div style="text-align: center"><i class="fa-solid fa-arrow-rotate-right"></i> <a href="addsous.php">Recommencer</a></div>';
			}

			// VERIFIE LA REPONSE
			function checkReponse() {
				event.preventDefault();
				var reponse = document.getElementById("reponse").value;
				if (reponse == correct) {
					if (essai == 1 ) nbcorrect += 1;
					document.getElementById('corrige').innerHTML = 'Juste! <i class="fa-solid fa-circle-check"></i>';
					document.getElementById('stats').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-fire"></i> Réussi: ' + nbcorrect + ' sur ' + nbcalcul;
					if(nbcalcul < totalCalcul) {
						setTimeout(nouveauCalcul, 300);
					} else {
						termine("totalCalcul");
						clearInterval(intervalID);
					}
				}
				else {
					document.getElementById('corrige').innerHTML = 'Faux! <i class="fa-solid fa-circle-xmark"></i>';
					document.getElementById('reponse').value = '';
					essai += 1;
				}
			return false;
			}

			// COMPTE A REBOURS
			temps = departMinutes * 60;
			timerElement = document.getElementById("timer");

			intervalID = setInterval(() => {
			 	minutes = parseInt(temps / 60, 10);
			 	secondes = parseInt(temps % 60, 10);

			 	minutes = minutes < 10 ? "0" + minutes : minutes;
			 	secondes = secondes < 10 ? "0" + secondes : secondes;

			 	timerElement.innerHTML = '<i class="fa-solid fa-hourglass-half"></i> ';
			 	timerElement.innerHTML += `${minutes}:${secondes}`;

				if (temps == 0) {
					timerElement.innerHTML = '<i class="fa-solid fa-hourglass-end"></i> 00:00';
				  	clearInterval(intervalID);
				  	termine("temps");
				}

				temps = temps <= 0 ? 0 : temps - 1;

			}, 1000);

			nouveauCalcul();
		</script>
<?php 
// si pas de séléection
} else {
?>
		<form id="formCalcul" method="GET" action="addsous.php">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required autofocus><br /><br />
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="100" id="nbmax" required><br /><br />
				<i class="fa-solid fa-calculator"></i> Opérations: <label><input type="checkbox" name="addition" value="1" checked>Addition</label> <label><input type="checkbox" name="soustraction" value="1" checked>Soustraction</label><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="5" id="duree" required> minutes<br /><br /><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>

<?php
}
?>
	</body>
</html>