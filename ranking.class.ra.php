<?php

if(!defined('WCF_DIR')) {
	// For when the rankingarchive is opened by user directly via url /regnum/rankingarchive, not as a cronjob from the forum suite where WCF_DIR would be defined
	define('WCF_DIR', "../../forum/");
}
require_once(WCF_DIR . '../regnum/rankingarchive/global.php');
require_once(WCF_DIR . '../regnum/lib/class.connect.ra.php');

class Ranking
{
	public static $REALMS = [2012 => 'Alsius', 4 => 'Ignis', 3 => 'Syrtis']; // values sind
	public static $CLASSES = [1 => 'Warrior', 2 => 'Archer', 3 => 'Mage']; // egal, nur zur übersicht

	private $con;

	public function __construct() {
		$this->con = connecting::connect_rankarchive();
		mysqli_set_charset($this->con, "latin1");
	}

	private function query($sql) {
		$ret = mysqli_query($this->con, $sql);
		if (mysqli_errno($this->con)) {
			dieee("invalid sql: " . mysqli_error($this->con) . ", " . $sql);
		}
		if(!$ret) {
			dieee("sql " . $sql . " is invalid3");
		}
		return $ret;
	}

	/** from all players as yyyy-mm-dd str */
	public function get_last_insert_date() {
		$sql = "SELECT max(date) AS maxdate FROM krp";
		$result = $this->query($sql);
		$last_insert = "";
		while ($row = mysqli_fetch_object($result)) {
			$last_insert = $row->maxdate;
		}
		return $last_insert;
	}

	/** get player id or make new player and return id */
	public function insert_ignore_player($name, $realmId, $class) {
		$name = mysqli_escape_string($this->con, $name);
		$class = mysqli_escape_string($this->con, $class);
		$realmId = intval($realmId);
		if (!is_numeric($realmId) || empty($name) || empty($class) || !array_key_exists($realmId, self::$REALMS)) {
			dieee("row values invalid: $class, $realmId, $name Abc.");
		}
		$sql = "insert ignore into player (name,realm,class) values (\"$name\", $realmId, \"$class\")"; // duplicates: handled by composite unique sql key
		$this->query($sql);
		$player_id = mysqli_insert_id($this->con);
		if ($player_id < 1) {
			// player existiert bereits, get id
			$sql = "select id from player where name = \"$name\"";
			$result = $this->query($sql);
			while ($row = mysqli_fetch_object($result)) {
				$player_id = $row->id;
			}
		}
		if ($player_id < 1) {
			dieee("wtf");
		}
		return $player_id;
	}

	/** for date=today */
	public function insert_ignore_krp($player_id, $rlmp, $date = '') {
		$player_id = intval($player_id);
		$rlmp = intval($rlmp);
		if (!is_numeric($player_id) || !is_numeric($rlmp)) {
			dieee("invalid asioegw4 $player_id --- $rlmp");
		}
		if(empty($date)) {
        		$date = date('Y-m-d'); // für -12h: 2. param ,time()-43200);
	        }
		$sql = "insert ignore into krp (player, date, rlmp) values ($player_id, \"" . $date . "\", $rlmp)"; // SILENTLY fails if already in existence
		$this->query($sql);
	}

	/** return all players with as obj(name,realm,class)[] */
	public function get_players() {
		$players = [];
		$sql = "SELECT name,realm,class FROM player";
		$result = $this->query($sql);
		while ($row = mysqli_fetch_object($result)) {
			$players[] = [
				"name" => utf8_encode($row->name),
				"realm" => $row->realm,
				"class" => utf8_encode($row->class)
			];
		}
		return $players;
	}

	/** get all rlmps by name+realm as obj(time,rlmp)[] */
	public function get_rlmps($name, $realm) {
		$name = mysqli_escape_string($this->con, $name);
		$realm = intval($realm);
		if (!array_key_exists($realm, self::$REALMS)) {
			dieee("get rlmp: invalid realm id: " . $realm);
		}
		if (empty($name)) {
			dieee("get rlmp: char name empty");
		}

		$rlmps = [];
		$sql = "SELECT rlmp,date FROM krp LEFT JOIN player on krp.player = player.id WHERE name = \"$name\" and realm = $realm order by date asc";
		$result = $this->query($sql);
		$last_time = 0;
		while ($row = mysqli_fetch_object($result)) {
			$time = strtotime($row->date);
			if ($last_time != 0) {
				$i = $last_time;
				while (($i += 86400) < $time) {
					// fill missing days (gaps) until next recorded date ($time) with null
					$rlmps[] = [
						"time" => $i,
						"rlmp" => null
					];
				}
			}
			$rlmps[] = [
				"time" => $time,
				"rlmp" => $row->rlmp
			];
			$last_time = $time;
		}

		return $rlmps;
	}

	/** return top x players of all time by their max rp as obj(name,realm,maxrlmp,date)[] */
	public function get_all_time_top($x) {
		$x = intval($x);
		if (!is_numeric($x)) {
			dieee("asdklfjasdf 20170730");
		}
		$players = [];
		$sql = "select max(date) as date, max(rlmp) as rlmp, name, realm, class from krp left join player on player.id = krp.player group by player order by rlmp desc limit $x";
		$result = $this->query($sql);
		while ($row = mysqli_fetch_object($result)) {
			$players[] = [
				"name" => utf8_encode($row->name),
				"realm" => $row->realm,
				"class" => utf8_encode($row->class),
				"date" => utf8_encode(date('Y-m-d', strtotime($row->date))),
				"rlmp" => $row->rlmp
			];
		}
		return $players;
	}

	public function get_players_that_are_new_to_ranking_since_x_days($x) {
		$x = intval($x);
		if (!is_numeric($x)) {
			dieee("ziotkklt 20170730");
		}
		$players = [];
		$sql = "SELECT min(date) as date, id, name, realm, class
 			FROM krp LEFT JOIN player ON player.id = krp.player
 			 GROUP BY player 
 			 HAVING min(date) > now() - INTERVAL $x DAY
 			  ORDER BY min(date) DESC";
		$result = $this->query($sql);
		while ($row = mysqli_fetch_object($result)) {
			$players[] = [
				"name" => utf8_encode($row->name),
				"realm" => $row->realm,
				"class" => utf8_encode($row->class),
				"date" => utf8_encode(date('Y-m-d', strtotime($row->date))),
			];
		}
		return $players;
	}
}
