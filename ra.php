<!DOCTYPE html>
<html>
<head>
	<title>CoR Ranking Archive</title>
	<link rel="stylesheet" href="./css/css.css"/>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="./js/canvasjs.min.js"></script>
	<script src="./js/helpers.js"></script>
	<script src="./js/js4_ra.js?v=1"></script>
	<?php
	require_once("ranking.class.ra.php");
	$ranking = new Ranking();
	?>
</head>
<body class="padding">

<div id="screen1">
	<!-- a href="../../index.php" id="back" tabindex="-1">< Startseite: www.cor-forum.de</a -->
	<h2>Champions of Regnum Ranking - KRP Archiv (Ra)</h2>

	<?php include ('./include/body.php') ?>

</div>


</body>
<?php include ('./include/footer.php') ?>

</html>
