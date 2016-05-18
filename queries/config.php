
<?php

$host = 'sql7.freesqldatabase.com:3306';
$dbname = 'sql7119210';
$user = 'sql7119210';
$pass = 'VDcmxGmYV9';

try {
	$db = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass, array(
		PDO::ATTR_PERSISTENT => true
	));

} catch(PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}