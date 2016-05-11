<?php

$host = 'localhost';
$dbname = 'suiviprojet';
$user = 'root';
$pass = 'root';

try {
	$db = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass, array(
    	PDO::ATTR_PERSISTENT => true
	));

} catch(PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>