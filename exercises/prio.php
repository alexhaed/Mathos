<?php
if ($mode === 'params') {
	if (isset($_GET['nbmax']) && filter_var($_GET['nbmax'], FILTER_VALIDATE_INT)) {
		echo "\t\t\tnbmax = ".$_GET['nbmax']."\n";
	} else {
		erreurSelection("plus grand nombre");
	}

} elseif ($mode === 'form') {
?>
		<form id="formCalcul" onsubmit="checkReponse();">
			<div class="content">
				<h2>Exercices</h2>
				<div>
					<div class="ex-play" id="pcalcul">
						<div class="ex-calcul"><span id="calcul"></span><span class="ex-eq"> = </span><input type="text" class="ex-input" name="reponse" id="reponse" required autofocus></div>
						<div class="ex-feedback"><span id="corrige"></span></div>
						<input type="submit" id="submit" value="Valider">
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
			operations = ["+", "-", "*", "/"];

			function newValuesDiv() {
				val1 = generateRandomNumber(nbmax);
			  	val2 = generateRandomNumber(val1);
				return [val1,val2];
			 }

			function randomOp() {
				return operations[Math.floor(Math.random() * operations.length)];
			}

			// NOUVEAU CALCUL
			function nouveauCalcul(arg) {
				op1 = randomOp();
				op2 = randomOp();

				if(op1 == "/") {
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

			  	parenthese = Math.floor(Math.random()*(3));

			  	parenthese1 = "";
			  	parenthese2 = "";
			  	parenthese3 = "";
			  	parenthese4 = "";

			  	switch (parenthese) {
			  		case 0:
			  			parenthese1 = "(";
			  			parenthese3 = ")";
			  			break;
			  		case 1:
			  			parenthese2 = "(";
			  			parenthese4 = ")";
			  			break;
			  	}

  				resultat = parenthese1 + valeur1 + op1 + parenthese2 +  valeur2 + parenthese3 + op2 + valeur3 + parenthese4;
  				correct = eval(resultat);
  				calcul = resultat.replaceAll("/", " : ").replaceAll("*", " x ").replaceAll("+", " + ").replaceAll("-"," - ");

			  	if (correct < 0 || !Number.isInteger(correct)) {
			  		nouveauCalcul();
			  		return;
			  	}

				essai = 1;
				nbcalcul += 1;
				document.getElementById('calcul').innerHTML = calcul;
				document.getElementById('corrige').innerHTML = '';
				document.getElementById('reponse').value = '';
			}

			nouveauCalcul();
		</script>
<?php
} elseif ($mode === 'options') {
?>
		<form id="formCalcul" method="GET" action="exercise.php">
			<input type="hidden" name="type" value="prio">
			<div class="content">
				<h2>Options de l'exercice</h2>
				<p><i class="fa-solid fa-list"></i> Nombre de calculs:&nbsp;<input type="text" size="4" name="nbcalcul" value="20" id="nbcalcul" required autofocus><br><br>
				<i class="fa-solid fa-maximize"></i> Plus grand nombre:&nbsp;<input type="text" size="4" name="nbmax" value="12" id="nbmax" required><br><br>
				<i class="fa-solid fa-hourglass-start"></i> Durée: <input type="text" size="4" name="duree" value="2" id="duree" required> minutes<br><br><input type="submit" id="submit" value="C'est parti!"></p>
			</div>
		</form>
<?php
}
?>
