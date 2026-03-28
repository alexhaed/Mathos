<?php
if ($mode === 'params') {
	if (isset($_GET['nbmax']) && filter_var($_GET['nbmax'], FILTER_VALIDATE_INT)) {
		echo "\t\t\tnbmax = ".$_GET['nbmax']."\n";
	} else {
		erreurSelection("plus grand nombre");
	}
	$operations = "";
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
		<script type='text/javascript'>
			nbcorrect = 0;
			nbcalcul = 0;
			essai = 0;
			op = "";

			// NOUVEAU CALCUL
			function nouveauCalcul() {
				operation = operations[Math.floor(Math.random() * operations.length)];
				switch (operation) {
					case 'multiplication':
						valeur1 = generateRandomNumber(nbmax);
						valeur2 = generateRandomNumber(nbmax);
						correct = valeur1 * valeur2;
						op = ' x ';
						break;
					case 'division':
						valeur2 = generateRandomNumber(nbmax);
						correct = generateRandomNumber(nbmax);
						valeur1 = correct * valeur2;
						op = ' : ';
						break;
				}
				essai = 1;
				nbcalcul += 1;
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
			<input type="hidden" name="type" value="multidiv">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required autofocus><br><br>
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="12" id="nbmax" required><br><br>
				<i class="fa-solid fa-calculator"></i> Opérations: <label><input type="checkbox" name="multiplication" value="1" checked>Multiplication</label> <label><input type="checkbox" name="division" value="1" checked>Division</label><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="2" id="duree" required> minutes<br><br><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
