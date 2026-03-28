<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php?redirect='.urlencode(basename($_SERVER['REQUEST_URI'])));
	exit;
}

$valid_types = ['addsous', 'compl', 'trous', 'multidiv', 'division', 'prio', 'relatifs', 'decimaux', 'doublemoitie'];
$type = $_GET['type'] ?? '';
if (!in_array($type, $valid_types)) {
	header('Location: index.php');
	exit;
}

$title = 'Mathos - Exercices';
$body_class = 'loggedin';
include 'header.php';
echo "\n";
include 'navbar.php';
echo "\n";

function erreurSelection($texte) {
	echo '</script><div class="content"><h2>Erreur</h2><p style="line-height: 35px; text-align: center;">Erreur dans la séléction ('.$texte.') &#128579;<br>';
	echo ' <i class="fa-solid fa-arrow-rotate-left"></i> <a href="javascript:history.back();">Retour</a></p></div></body></html>';
	exit();
}

$params = $_GET;
unset($params['type']);

if (count($params)) {
	echo "\t\t<script type='text/javascript'>\n";

	if (isset($_GET['nbcalcul']) && filter_var($_GET['nbcalcul'], FILTER_VALIDATE_INT) && $_GET['nbcalcul'] > 0) {
		echo "\t\t\ttotalCalcul = ".$_GET['nbcalcul']."\n";
	} else {
		erreurSelection("nombre de calculs");
	}

	if (isset($_GET['duree']) && is_numeric($_GET['duree']) && $_GET['duree'] > 0) {
		echo "\t\t\tdepartMinutes = ".$_GET['duree']."\n";
	} else {
		erreurSelection("durée");
	}

	$mode = 'params';
	include "exercises/{$type}.php";

	echo "\t\t</script>\n";

	$mode = 'form';
	include "exercises/{$type}.php";

	$exercise_type = $type;
	include 'common_functions.php';
	echo "\n";

	$mode = 'js';
	include "exercises/{$type}.php";

} else {
	$mode = 'options';
	include "exercises/{$type}.php";
}

include 'footer.php';
?>
