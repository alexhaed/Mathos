<?php
if ($mode === 'params') {
	$compl = "";
	if (isset($_GET['10']) && $_GET['10'] == 1) $compl .= "10, ";
	if (isset($_GET['100']) && $_GET['100'] == 1) $compl .= "100, ";
	if (isset($_GET['1000']) && $_GET['1000'] == 1) $compl .= "1000";
	if ($compl == "") erreurSelection("complément");
	echo "\t\t\tcompls = [".$compl."]\n";

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
} elseif ($mode === 'options') {
?>
		<form id="formCalcul" method="GET" action="exercise.php">
			<input type="hidden" name="type" value="compl">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required autofocus><br><br>
				<i class="fa-solid fa-calculator"></i> Complément à:&nbsp;<label><input type="checkbox" name="10" value="1" checked>10</label>&nbsp;&nbsp;<label><input type="checkbox" name="100" value="1" checked>100</label>&nbsp;&nbsp;<label><input type="checkbox" name="1000" value="1" checked>1000</label><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="2" id="duree" required> minutes<br><br><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
