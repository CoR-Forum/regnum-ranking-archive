<?php

require_once('global.php');
require_once('ranking.class.php');
require_once('simple_html_dom.php');

echo 'Hello, this is the Regnum Rankingarchive Cronjob.';

$today_date = date('Y-m-d');

$ranking = new Ranking();

// already done today?
$last_insert = $ranking->get_last_insert_date();
if(empty($last_insert)) {
	dieee("last insert is empty?");
}
$last_date_utc = date("z", strtotime($last_insert." UTC"));
$this_day_utc = date("z");
if($last_date_utc == $this_day_utc) {
	dieee("Ranking already fetched today. Not fetching again. $last_date_utc, $this_day_utc");
} elseif($last_date_utc > $this_day_utc) {
	mail("cor-forum@waritschlager.de", 'COR RANKING new years eve', 'hi. sollte nur an silvester passieren. ranking insert ausgeführt wie normal. schüs');
}
// todo müsste eig auch checken ob this_hour > 10:15 GMT ist bzw. besser 11:00 oder 15:00 oder so

foreach (Ranking::$REALMS as $realmId => $realmName) {
	foreach (Ranking::$CLASSES as $classId => $className) {
		// world war, als haven noch existierte: valhalla=2. jetzt ist val=1 und ra=0
		$url = "http://www.championsofregnum.com/index.php?l=1&ref=gmg&sec=19&rank=2&world=1&realm=$realmId&class=$classId&range=2";
		$http = file_get_html($url);
		$player_c = 0;
		foreach ($http->find('.ranking-table tr') as $tr) {
			$tds = $tr->find('td');
			if (sizeof($tds) != 5) {
				continue;
			}
			$name = $tds[0]->plaintext;
			$player_class = $tds[1]->plaintext;
			$rlmp = intval($tds[4]->plaintext);

			// insert player or get id of existing player
			$player_id = $ranking->insert_ignore_player($name,$realmId,$player_class);

			// insert krp
			$ranking->insert_ignore_krp($player_id, $rlmp);

			$player_c++;
		}
		if ($player_c < 100) { // in vers. alt ists != 900 am schluss und das klappt iwie trotzdem?? obwohl das auch mehr sein können pro, 101 zb 20170727 bei entwicklung gleich erste kategorie. joa egal
			dieee("player_c is $player_c: $classId,$realmId");
		}
	}
}
echo 'fin';
