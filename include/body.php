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