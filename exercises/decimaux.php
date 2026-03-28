<?php
if ($mode === 'params') {
	if (isset($_GET['nbmax']) && filter_var($_GET['nbmax'], FILTER_VALIDATE_INT)) {
		echo "\t\t\tnbmax = ".$_GET['nbmax']."\n";
	} else {
		erreurSelection("plus grand nombre");
	}
	if (isset($_GET['decimal']) && $_GET['decimal'] == 2) {
		echo "\t\t\tdecimal = ".$_GET['decimal']."\n";
	} else {
		echo "\t\t\tdecimal = 1;\n";
	}
	$operations = "";
	if (isset($_GET['addition']) && $_GET['addition'] == 1) $operations .= "'addition', ";
	if (isset($_GET['soustraction']) && $_GET['soustraction'] == 1) $operations .= "'soustraction', ";
	if ($operations == "") erreurSelection("opérations");
	echo "\t\t\toperations = [".$operations."]\n";

} elseif ($mode === 'form') {
?>
		<form id="formCalcul" onsubmit="checkReponse();">
			<div class="content">
				<h2>Exercices</h2>
				<div>
					<div class="ex-play" id="pcalcul">
						<div class="ex-calcul"><span id="calcul"></span><span class="ex-eq"> = </span><input type="text" class="ex-input" name="reponse" id="reponse" required autofocus></div>
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
		<script type="text/javascript">
			nbcorrect = 0;
			nbcalcul = 0;
			essai = 0;
			op = "";

			// NOUVEAU CALCUL
			function nouveauCalcul() {
				operation = operations[Math.floor(Math.random() * operations.length)];
				valeur1 = generateRandomNumber(nbmax,decimal,0);
				if (operation == 'soustraction') {
					valeur2 = generateRandomNumber(valeur1,decimal,0);
				} else {
					valeur2 = generateRandomNumber(nbmax,decimal,0);
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
				correct = correct.toFixed(decimal).replace(/[.,]*0+$/, "");
				nbcalcul += 1;
				essai = 1;
				document.getElementById('calcul').innerHTML = valeur1 + op + valeur2;
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponse').value = '';
			}

			nouveauCalcul();
		</script>
<?php
} elseif ($mode === 'options') {
?>
		<form id="formCalcul" method="GET" action="exercise.php">
			<input type="hidden" name="type" value="decimaux">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required autofocus><br><br>
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="100" id="nbmax" required><br><br>
				<i class="fa-solid fa-terminal"></i> Décimale:&nbsp;<label><input type="checkbox" name="decimal01" value="1" checked disabled>0,1</label>&nbsp;&nbsp;&nbsp;<label><input type="checkbox" name="decimal" value="2">0,01</label><br><br>
				<i class="fa-solid fa-calculator"></i> Opérations:&nbsp;<label><input type="checkbox" name="addition" value="1" checked>Addition</label>&nbsp;<label><input type="checkbox" name="soustraction" value="1" checked>Soustraction</label><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="2" id="duree" required> minutes<br><br><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
