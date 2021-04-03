<!DOCTYPE html>
<html>
<head>
	<title>CoR Ranking Archive</title>
	<link rel="stylesheet" href="./css/css.css"/>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="./js/canvasjs.min.js"></script>
	<script src="./js/helpers.js"></script>
	<script src="./js/js4.js"></script>
	<?php
	require_once("ranking.class.php");
	$ranking = new Ranking();
	?>
</head>
<body class="padding">

<div id="screen1">
	<!-- a href="../../index.php" id="back" tabindex="-1">< Startseite: www.cor-forum.de</a -->
	<h2>Champions of Regnum Ranking - KRP Archiv (Valhalla)</h2>

	<div class="box">
		<div class="padding">
			<h4>Archiv aller Spieler*</h4>
			<input type="text" id="character" placeholder="Charaktername..."/>
			<p id="character_click_notice">Klicke auf einen der Charaktere, um den Graphen zu generieren!</p>
			<ul id="characters"></ul>
			<div id="chartcontainer"></div>
			<div id="share">
				<p>Teilen mit folgendem Link:</p>
				<input type="text" readonly/>
			</div>
		</div>
	</div>

	<br/>
	<div class="box" id="alltimeranking">
		<div class="padding">
			<h4>Top 100 (incl. gebannter / gelöschter) - <a onclick="show_top_x()" id="show_top_x_s">einblenden</a><a onclick="hide_top_x()" id="hide_top_x_s">ausblenden</a></h4>
			<table>
				<tr>
					<th>Name</th>
					<th>Klasse</th>
					<th>Max. KRP</th>
					<th>Am</th>
				</tr>
				<?php $players = $ranking->get_all_time_top(100);
				foreach ($players as $player): ?>
					<tr>
						<td class="padding realm<?= $player["realm"] ?>"><?= $player["name"] ?></td>
						<td class="padding"><?= $player["class"] ?></td>
						<td class="padding"><?= $player["rlmp"] ?></td>
						<td class="padding"><?= $player["date"] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>

	<br/>
	<?php /*
	<div class="box" id="newtorank">
		<div class="padding">
			<h4>Neue Spieler im all-time-Ranking seit letzter Woche:</h4>
			<table>
				<tr>
					<th>Name</th>
					<th>Klasse</th>
					<th>Am</th>
				</tr>
				<?php $players = $ranking->get_players_that_are_new_to_ranking_since_x_days(7);
				foreach ($players as $player): ?>
					<tr>
						<td class="padding realm<?= $player["realm"] ?>"><?= $player["name"] ?></td>
						<td class="padding"><?= $player["class"] ?></td>
						<td class="padding"><?= $player["date"] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
	// ^ funktioniert zwar, aber mega resourceintensiv. vlt query optimieren, use index oder so, kb, einfach rausgenommen
	*/ ?>

</div>

<footer class="padding box" id="footer">
	<p class="unimportant">
		* Nur Charaktere, die in einer der u.a. Datenquellen mindestens einmal im ALL TIME krp-Ranking standen, sind im Archiv enthalten.<br>
		Die einzigen drei Spieler, die seit Anbeginn in den Aufzeichnungen zu finden sind, sind <a href="https://cor-forum.de/regnum/rankingarchive/?wat=Forsal:2012,Belzebub:2012,Anu%20Godire:4,Dirrty:4,Avatar:2012,Lord%20Rajo:4,Themistokles:3,Andrea%20von%20Hude:2012,Ivory:3,Skuld:2012">Forsal, Belzebub und Anu Godire</a>.
	</p>
	<p>Datenquellen:</p>
	<p><a href="https://www.championsofregnum.com/index.php?l=1&sec=19&rank=2&world=1&realm=3&class=1&range=2">www.championsofregnum.com</a>
	<p><a href="./waybackmachine-links.html">Internet Archive Wayback Machine</a> <span class="unimportant">(2007/07 - 2008/05, nur an 6 Tagen)</span></p>
	<p><a href="http://4freax.net/">fop</a> <span class="unimportant">(2008/08 - 2010/03, nur Muspell Syrtis)</span></p>
	<p><a href="http://regnum-fans.de">Flocke</a> <span class="unimportant">(2010/03 - 2013/12)</span></p>
	<p><a href="../../index.php">Blauhirn</a> <span class="unimportant">(2014/02 - heute)</span></p>
	<br><p>Authors:</p>
	<p><a href="https://cor-forum.de/board/index.php?user/2-blauhirn/">Blauhirn <img src="./img/kuerbis.png"/></a> <span class="unimportant">(2014/02 - 2021)</span></p>
	<p><a href="https://cor-forum.de/board/index.php?user/146-joshua2504/">Joshua2504</a> <span class="unimportant">(2021 - today)</span></p>
        <p><img src="./img/git.png" width="16px"> <a href="https://git.treudler.net/CoR-Forum/ranking-archive">source code</a></p>
</footer>
</body>
</html>
