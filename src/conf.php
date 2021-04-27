<?php
    define('SWAPI','https://swapi.dev/api');
    define('ENGLISH_TO_WOOKIE', [
		'a' => 'ra', 'b' => 'rh', 'c' => 'oa', 'd' => 'wa', 'e' => 'wo', 'f' => 'ww',
		'g' => 'rr', 'h' => 'ac', 'i' => 'ah', 'j' => 'sh', 'k' => 'or', 'l' => 'an',
		'm' => 'sc', 'n' => 'wh', 'o' => 'oo', 'p' => 'ak', 'q' => 'rq', 'r' => 'rc',
		's' => 'c',  't' => 'ao', 'u' => 'hu', 'v' => 'ho', 'w' => 'oh', 'x' => 'k',
		'y' => 'ro', 'z' => 'uf']
	);
	define('MSG_NOT_YET_DEVELOPED','Feature not yet developed');
	define('MSG_NO_AMOUNT','No amount was entered');
	define('MSG_NAN_AMOUNT','The amount is not a number');
	define('MSG_NEGATIVE_AMOUNT','The amount can\'t be negative');
	define('MSG_ZERO_AMOUNT','The amount can\'t be 0 (zero)');
	define('MSG_DECIMAL_AMOUNT','The amount can\'t be a decimal');
	define('MSG_LOWERTHANZERO_AMOUNT','The amount decreased can\'t be higher than the set amount');
	define('MSG_VALID_AMOUNT','The amount is a valid a mount');
	define('MSG_NO_ROWS_AFFECTED','No rows affected, check the ID');
	define('MSG_AMOUNT_SET','Amount set');
	define('MSG_AMOUNT_INCREASED','Amount increased');
	define('MSG_AMOUNT_DECREASED','Amount decreased');
	define('MSG_NOT_FOUND','Not found');
	define('LOCAL_URL','http://localhost/test_LN/public');
	$dbhost = '127.0.0.1';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'swapi_extension';
    $dbcharset = 'utf8';
    
    ?>