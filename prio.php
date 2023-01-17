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
		echo '</script><br><br>Erreur dans la séléction<br><a href="asmd.php">Retour</a>';
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
	echo "		</script>\n";
?>
		<form id="formCalcul" onsubmit="checkReponse()">
			<div class="content">
				<h2>Exercices</h2>
				<p id="pcalcul"><span id="calcul"></span> = <input type="text" size="4" name="reponse" placeholder="" id="reponse" required autofocus> <input type="submit" id="submit" value="Valider"> <span id="corrige"></span></p>
				<p><i class="fa-solid fa-hourglass-end"></i> <span id="timer"></span><span id="stats"></span></p>
			</div>
		</form>
		<script>
			nbcorrect = 0;
			nbcalcul = 0;
			essai = 0;
			operations = ["+", "-", "*", "/"];

			function generateRandomInteger(max) {
    			return Math.floor(Math.random() * (max+1));
			}

			function newValuesDiv() {
				val1 = generateRandomInteger(nbmax);
			  	val2 = generateRandomInteger(val1);
				return [val1,val2];
			 }

			function randomOp() {
				return operations[Math.floor(Math.random() * operations.length)];
			}

			// NOUVEAU CALCUL
			function nouveauCalcul() {
				op1 = randomOp();
				op2 = randomOp();
				while (op1 == op2) op2 = randomOp();

				if(op1 == "/" ) {
			    	valeurs = newValuesDiv();
				    while(valeurs[0] % valeurs[1] !== 0) {
				      valeurs = newValuesDiv();
				    }
				    valeur1 = valeurs[0];
				    valeur2 = valeurs[1];
				    valeur3 = Math.floor(Math.random()*(nbmax+1)); 
			  	} else if (op2 == "/") {
				    valeurs = newValuesDiv();
				    while(valeurs[0] % valeurs[1] !== 0) {
				     	valeurs = newValuesDiv();
				    }
				    valeur1 = Math.floor(Math.random()*(nbmax+1));
				    valeur2 = valeurs[0];
				    valeur3 = valeurs[1];
			  	} else {
				    valeur1 = Math.floor(Math.random()*(nbmax+1));
				    valeur2 = Math.floor(Math.random()*(nbmax+1));
				    valeur3 = Math.floor(Math.random()*(nbmax+1)); 
			  	}

  				resultat = valeur1 + op1 + valeur2 + op2 + valeur3;
  				correct = eval(resultat);
  				calcul = resultat.replace("/", " : ").replace("*", " x ").replace("+", " + ").replace("-"," - ");

			  	while (correct < 0) nouveauCalcul();

				essai = 1;
				document.getElementById('calcul').innerHTML = calcul;
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponse').value = '';
			}

			function termine(arg) {
				if (arg == "temps") {
					feedback = 'Temps écoulé, dommage!<br><a href="multidiv.php">Recommencer</a>';
				}
				if (arg == "totalCalcul") {
					feedback = 'Fin des calculs, bravo!<br><a href="multidiv.php">Recommencer</a>';
				}
				document.getElementById('pcalcul').innerHTML = feedback;	
			}

			// VERIFIE LA REPONSE
			function checkReponse() {
				event.preventDefault();
				var reponse = document.getElementById("reponse").value;
				if (reponse == correct) {
					if (essai == 1) nbcorrect += 1;
					nbcalcul += 1;
					document.getElementById('corrige').innerHTML = 'Juste! <i class="fa-solid fa-check"></i>';
					document.getElementById('stats').innerHTML = ' | Réussi: ' + nbcorrect + ' sur ' + nbcalcul;
					if(nbcalcul < totalCalcul) {
						setTimeout(nouveauCalcul, 300);
					} else {
						termine("totalCalcul");
						clearInterval(intervalID);
					}
				}
				else {
					document.getElementById('corrige').innerHTML = 'Faux! <i class="fa-solid fa-xmark"></i>';
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

				timerElement.innerText = `${minutes}:${secondes}`;
				temps = temps <= 0 ? 0 : temps - 1;

				if (temps <= 0) {
					timerElement.innerText = "00:00";
				  	clearInterval(intervalID);
				  	termine("temps");
				}
			}, 1000);

			nouveauCalcul();
		</script>
<?php 
// si pas de séléection
} else {
?>
		<form id="formCalcul" method="GET" action="prio.php">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required autofocus><br /><br />
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="12" id="nbmax" required><br /><br />
				<i class="fa-solid fa-hourglass-end"></i> Durée: <input type="text" size="4" name="duree" value="5" id="duree" required> minutes<br /><br /><input type="submit" id="submit" value="Valider"></p>
			</div>
		</form>
<?php
}
?>
	</body>
</html>