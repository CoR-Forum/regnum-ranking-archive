<?php

require_once('global.php');
require_once('ranking.class.php');

$wat = isset($_GET['wat']) ? $_GET['wat'] : null;
if (empty($wat)) {
	dieee("wat is empty");
}

$ranking = new Ranking();
if ($wat == 'players') {
	echo json_encode($ranking->get_players());
} elseif ($wat == 'rlmp') {
	$name = isset($_GET['name']) ? $_GET['name'] : null;
	$realm = isset($_GET['realm']) ? $_GET['realm'] : null;
	if (empty($name) || empty($realm)) {
		dieee("get rlmp: name/realm is empty");
	}

	echo json_encode(['name'=>$name, 'realm'=>$realm, 'rlmps'=>
		$ranking->get_rlmps($name, $realm)
	]);

	// log
	$LOGFILE = './logs/rankingLogValhalla.log';
	file_put_contents($LOGFILE, "\n" . date('c') . " " . $name, FILE_APPEND);
}

?>