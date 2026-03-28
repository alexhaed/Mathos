<?php
if ($mode === 'params') {
	if (isset($_GET['nbmax']) && filter_var($_GET['nbmax'], FILTER_VALIDATE_INT)) {
		echo "\t\t\tnbmax = ".$_GET['nbmax']."\n";
	} else {
		erreurSelection("plus grand nombre");
	}
	$operations = "";
	if (isset($_GET['addition']) && $_GET['addition'] == 1) $operations .= "'addition', ";
	if (isset($_GET['soustraction']) && $_GET['soustraction'] == 1) $operations .= "'soustraction', ";
	if (isset($_GET['multiplication']) && $_GET['multiplication'] == 1) $operations .= "'multiplication', ";
	if (isset($_GET['division']) && $_GET['division'] == 1) $operations .= "'division', ";
	if ($operations == "") erreurSelection("opérations");
	echo "\t\t\toperations = [".$operations."]\n";

} elseif ($mode === 'form') {
?>
		<form id="formCalcul" onsubmit="checkReponse();">
			<div class="content">
				<h2>Exercices</h2>
				<div>
					<div class="ex-play" id="pcalcul">
						<div class="ex-calcul" id="calcul"></div>
						<div class="ex-feedback"><span id="corrige"></span></div>
						<input type="submit" id="submit" value="Vérifier">
					</div>
					<div class="ex-meta">
						<span id="timer"></span>
						<span id="stats"></span>
					</div>
				</div>
			</div>
		</form>
<?php
} elseif ($mode === 'js') {
?>
		<script type='text/javascript'>
			nbcorrect = 0;
			nbcalcul = 0;
			essai = 0;

			// NOUVEAU CALCUL
			function nouveauCalcul(arg) {
				operation = operations[Math.floor(Math.random() * operations.length)];
				trou = Math.floor(Math.random() * 2); // retourne 0 ou 1
				switch (operation) {
					case 'addition':
						valeur1 = generateRandomNumber(nbmax);
						valeur2 = generateRandomNumber(nbmax);
						if (!trou) {
							correct = valeur1;
							calcul = '<input type="text" size="4" name="reponse" placeholder="" id="reponse" required> + ' + valeur2 + ' = ' + (valeur1 + valeur2);
						}
						else {
							correct = valeur2;
							calcul = valeur1 + ' + <input type="text" size="4" name="reponse" placeholder="" id="reponse" required> = ' + (valeur1 + valeur2);
						}
						break;
					case 'soustraction':
						valeur1 = generateRandomNumber(nbmax);
						valeur2 = generateRandomNumber(valeur1);
						if (!trou) {
							correct = valeur1;
							calcul = '<input type="text" size="4" name="reponse" placeholder="" id="reponse" required> - ' + valeur2 + ' = ' + (valeur1-valeur2);
						} else {
							correct = valeur2;
							calcul = valeur1 + ' - <input type="text" size="4" name="reponse" placeholder="" id="reponse" required> = ' + (valeur1-valeur2);
						}
						break;
					case 'multiplication':
						valeur1 = generateRandomNumber(nbmax);
						valeur2 = generateRandomNumber(nbmax);

						// si valeur1 ou valeur2 égale 0 et le résultat est 0, la réponse peut être n'importe quel chiffre -> génère un autre calcul sans incrémenter le nombre de calculs (arg = 1)
						if ((valeur1 * valeur2) === 0 && (valeur1 === 0 || valeur2 === 0)) nouveauCalcul(1);

						if (!trou) {
							correct = valeur1;
							calcul = '<input type="text" size="4" name="reponse" placeholder="" id="reponse" required> x ' + valeur2 + ' = ' + (valeur1 * valeur2);
						} else {
							correct = valeur2;
							calcul = valeur1 + ' x <input type="text" size="4" name="reponse" placeholder="" id="reponse" required> = ' + (valeur1 * valeur2);
						}
						break;
					case 'division':
						valeur2 = generateRandomNumber(nbmax);
						rep = generateRandomNumber(nbmax);
						valeur1 = rep * valeur2;
						if (!trou) {
							correct = valeur1;
							calcul = '<input type="text" size="4" name="reponse" placeholder="" id="reponse" required> : ' + valeur2 + ' = ' + rep;
						} else {
							correct = valeur2;
							calcul = valeur1 + ' : <input type="text" size="4" name="reponse" placeholder="" id="reponse" required> = ' + rep;
						}
						break;
				}
				if (arg != 1) {
					nbcalcul += 1;
					essai = 1;
				}
				document.getElementById('calcul').innerHTML = calcul;
				document.getElementById("reponse").focus();
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponse').value = '';
			}

			nouveauCalcul();
		</script>
<?php
} elseif ($mode === 'options') {
?>
		<form id="formCalcul" method="GET" action="exercise.php">
			<input type="hidden" name="type" value="trous">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required autofocus><br><br>
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="100" id="nbmax" required><br><br>
				<i class="fa-solid fa-calculator"></i> Opérations:<br>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="checkbox" name="addition" value="1" checked>Addition</label><br>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="checkbox" name="soustraction" value="1" checked>Soustraction</label><br>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="checkbox" name="multiplication" value="1">Multiplication</label><br>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="checkbox" name="division" value="1">Division</label><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="2" id="duree" required> minutes<br><br><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
