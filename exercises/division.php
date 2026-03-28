<?php
if ($mode === 'params') {
	if (isset($_GET['nbmax']) && filter_var($_GET['nbmax'], FILTER_VALIDATE_INT)) {
		echo "\t\t\tnbmax = ".$_GET['nbmax']."\n";
	} else {
		erreurSelection("plus grand nombre");
	}

} elseif ($mode === 'form') {
?>
		<form id="formCalcul" onsubmit="checkReponseDiv();">
			<div class="content">
				<h2>Exercices</h2>
				<div>
					<div class="ex-play" id="pcalcul">
						<div class="ex-calcul"><span id="calcul"></span></div>
						<div class="ex-div-inputs">
							<label class="ex-div-label">Quotient<input type="text" class="ex-input" name="reponseQuot" id="reponseQuot" required autofocus></label>
							<label class="ex-div-label">Reste<input type="text" class="ex-input" name="reponseReste" id="reponseReste" required></label>
						</div>
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

			// VERIFICATION DE LA REPONSE
			function checkReponseDiv() {
				event.preventDefault();
				var reponseQuot = document.getElementById("reponseQuot").value;
				var reponseReste = document.getElementById("reponseReste").value;
				if (reponseQuot == quotient && reponseReste == reste) {
					if (essai == 1 ) nbcorrect += 1;
					document.getElementById('corrige').innerHTML = '&nbsp;Juste! <i class="fa-solid fa-circle-check"></i>';
					document.getElementById('stats').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-trophy"></i> Réussi: ' + nbcorrect + ' sur ' + nbcalcul;
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
				if (diviseur === 0) {
					nouveauCalcul(1);
					return;
			  	}
				quotient = Math.floor(dividende / diviseur);
				reste = dividende % diviseur;
				if (arg != 1) {
					nbcalcul += 1;
					essai = 1;
				}
				document.getElementById('calcul').innerHTML = dividende + ' : ' + diviseur;
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponseQuot').value = '';
				document.getElementById('reponseReste').value = '';
			}

			nouveauCalcul();
		</script>
<?php
} elseif ($mode === 'options') {
?>
		<form id="formCalcul" method="GET" action="exercise.php">
			<input type="hidden" name="type" value="division">
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
