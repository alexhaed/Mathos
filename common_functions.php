		<script type="text/javascript">
			// GENERE UN NOMBRE
			function generateRandomNumber(max, nbdecimal, relatif) {
				if (nbdecimal > 0) {
					numb = Math.random() * max;
	      			return numb.toFixed(nbdecimal);
				} else if (relatif == 1) {
					return Math.floor((Math.random() - 0.5) * (max + max));
				} else {
					return Math.floor(Math.random() * (max + 1));
				}
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

			// ENREGISTREMENT DES RESULTATS
			async function saveResult() {
				const data = { userid: <?php echo $_SESSION['id']; ?>, exercice: "<?php echo basename($_SERVER['PHP_SELF'], ".php"); ?>", nbcalculs: nbcalcul, reussis: nbcorrect, duree: duree };
				const rep = await fetch('saveresult.php', {
				  method: 'POST',
				  headers: {
				    'Content-Type': 'application/json'
				  },
				  body: JSON.stringify(data)
				});
				//const retour = await rep.text();
	  			//console.log(retour);
			}

			// FIN DE L'EXERCICE
			function termine(arg) {
				if (arg == "temps") {
					feedback = 'Temps écoulé, dommage! &#128533<br>';
					duree = departMinutes*60;
				}
				if (arg == "totalCalcul") {
					feedback = 'Fin des calculs, bravo! &#128526;<br>';
					duree = (departMinutes*60)-temps;
				}
				minutes = parseInt(duree / 60, 10);
				secondes = parseInt(duree % 60, 10);
				minutes = minutes < 10 ? "0" + minutes : minutes;
				secondes = secondes < 10 ? "0" + secondes : secondes;
				document.getElementById("timer").innerHTML = '<i class="fa-solid fa-hourglass-end"></i> Temps écoulé: ' + minutes + ':' + secondes;
				document.getElementById('pcalcul').innerHTML = feedback;
				document.body.innerHTML += '<div style="text-align: center"><i class="fa-solid fa-arrow-rotate-right"></i> <a href="<?php echo basename($_SERVER['PHP_SELF']); ?>">Recommencer</a></div>';
				saveResult();
			}

			// VERIFIE LA REPONSE
			function checkReponse() {
				event.preventDefault();
				var reponse = document.getElementById("reponse").value;
				if (reponse == correct) {
					if (essai == 1 ) nbcorrect += 1;
					document.getElementById('corrige').innerHTML = '&nbsp;Juste! <i class="fa-solid fa-circle-check"></i>';
					document.getElementById('stats').innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-fire"></i> Réussi: ' + nbcorrect + ' sur ' + nbcalcul;
					if(nbcalcul < totalCalcul) {
						setTimeout(nouveauCalcul, 300);
					} else {
						termine("totalCalcul");
						clearInterval(intervalID);
					}
				}
				else {
					document.getElementById('corrige').innerHTML = '&nbsp;Faux! <i class="fa-solid fa-circle-xmark"></i>';
					document.getElementById('reponse').value = '';
					essai += 1;
				}
				return false;
			}
		</script>