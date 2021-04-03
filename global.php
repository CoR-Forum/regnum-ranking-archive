<?php

set_error_handler("error_");
set_exception_handler("exception_");

//error_reporting(E_ALL);
//ini_set('display_errors', 1);


function dieee($msg) {    // exits the script AND SENDS THE ERROR MESSAGE VIA EMAIL @ADMIN_MAIL
	mail("cor-forum@waritschlager.de", 'COR RANKING error', $msg);
	echo("rip in peaces script. :( error message: <---" . $msg . "--->");
	die();
}

function error_($error_level,$error_message) {
	dieee($error_level.":".$error_message);
}
function exception_($exception) {
	dieee($exception->getMessage());
}
