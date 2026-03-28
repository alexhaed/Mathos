<?php
if ($mode === 'params') {
	if (isset($_GET['nbmax']) && filter_var($_GET['nbmax'], FILTER_VALIDATE_INT)) {
		echo "\t\t\tnbmax = ".$_GET['nbmax']."\n";
	} else {
		erreurSelection("plus grand nombre");
	}
	$operations = "";
	if (isset($_GET['double']) && $_GET['double'] == 1) $operations .= "'double', ";
	if (isset($_GET['moitie']) && $_GET['moitie'] == 1) $operations .= "'moitie', ";
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
				double = 1;
				while (double % 2 !== 0) {
					double = generateRandomNumber(nbmax);
				}
				simple = double / 2;
				switch (operation) {
					case 'double':
						valeur = simple;
						correct = double;
						op = ' x ';
						break;
					case 'moitie':
						valeur = double;
						correct = simple;
						op = ' : ';
						break;
				}
				essai = 1;
				nbcalcul += 1;
				document.getElementById('calcul').innerHTML = valeur + op + '2';
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponse').value = '';
			}

			nouveauCalcul();
		</script>
<?php
} elseif ($mode === 'options') {
?>
		<form id="formCalcul" method="GET" action="exercise.php">
			<input type="hidden" name="type" value="doublemoitie">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="10" id="nbcalcul" required autofocus><br><br>
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="200" id="nbmax" required><br><br>
				<i class="fa-solid fa-calculator"></i> Opérations: <label><input type="checkbox" name="double" value="1" checked>Double</label> <label><input type="checkbox" name="moitie" value="1" checked>Moitié</label><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="2" id="duree" required> minutes<br><br><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
