<?php
include 'mysql_login.php';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: ".mysqli_connect_error();
}

$levels = array(1=>0, 2=>10, 3=>20, 4=>50, 5=>100, 6=>150, 7=>200, 8=>250, 9=>300, 10=>350, 11=>400, 12=>450, 13=>500, 14=>1000);
$id = (int)$_SESSION['id'];

$stmt = $con->prepare("SELECT `level` AS B FROM accounts WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$level_scr = $stmt->get_result();
$stmt->close();
if ($level_scr) {
	$row = $level_scr->fetch_assoc();
	$level_old = $row["B"];
}

$level = $level_old;

$stmt = $con->prepare("SELECT SUM(`reussis`) AS C FROM scores WHERE userid = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$reussis = $stmt->get_result();
$stmt->close();
if ($reussis) {
	$row = $reussis->fetch_assoc();
	if ($row["C"] >= $levels[($level_old+1)]) {
		$level = $level_old + 1;
	}
}

if ($level != $level_old) {
	$stmt = $con->prepare("UPDATE accounts SET level = ? WHERE id = ?");
	$stmt->bind_param('ii', $level, $id);
	$stmt->execute();
	$stmt->close();
}

$target = $levels[$level + 1];
$nbtotarget = $target - $row["C"];
$progress = $row["C"] * 100 / $target;

mysqli_close($con);

?>
			<style type="text/css">

			.progesscontent {
				margin: 0px auto;
				text-align: center;
				width: 80%;
			}

			.progress {
			  	margin: 5px 0 10px 0;
			  	padding: 2px;
			  	width: 100%;
			  	background: #fff;
			  	border: 1px solid #000;
			  	border-radius: 20px;
			  	height: 20px;
			  	float: left;
			}

			.progress .progress__bar {
			  	height: 100%;
			  	width: <?php echo $progress; ?>%;
			  	border-radius: 15px;
			  	background: repeating-linear-gradient(
			    	135deg,
			    	#036ffc,
			    	#036ffc 20px,
			    	#1163cf 20px,
			    	#1163cf 40px
			  	);
			}

			@media (prefers-reduced-motion: no-preference) {
			  .progress__bar-animation {
			    animation: fill-bar 3s;
			  }
			}

			@keyframes fill-bar {
			  from {width: 0%;}
			  to {width: <?php echo $progress; ?>%;}
			}

			</style>
			<h2 style="margin-top: 0px">Niveau</h2>
			<div class="progresscontent">
<?php
			if ($level != $level_old) {
				echo '<p style="margin-bottom: 0px; text-align: center;"><i class="fa-solid fa-trophy"></i> ';
				echo "<b>Bravo, tu as passé un niveau !</b>";
				echo ' <i class="fa-solid fa-trophy"></i></p>';
			}
?>
			<p style="margin-bottom: 0px; text-align: center;"><img src="img/niveau_<?php echo $level; ?>.png" height="200"></p>
			<div class="progress"> 
				<div class="progress__bar"></div>
			</div>
			<p style="margin-bottom: 0px; text-align: center;">
<?php
echo "			Plus que ".$nbtotarget;
echo ($nbtotarget > 1) ? ' calculs' : ' calcul';
echo " pour passer au&nbsp;prochain&nbsp;niveau!<br><br>\n";
?>
			<i class="fa-solid fa-ranking-star fa-lg"></i> <a href="score.php">Voir tes scores</a></p>
			</div>
			<script type="text/javascript">
				const observer = new IntersectionObserver(entries => {
				  entries.forEach(entry => {
				    const bar = entry.target.querySelector('.progress__bar');

				    if (entry.isIntersecting) {
				      bar.classList.add('progress__bar-animation');
					  return;
				    }
				    bar.classList.remove('progress__bar-animation');
				  });
				});

				observer.observe(document.querySelector('.progress'));
			</script>