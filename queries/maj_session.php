<?php

session_start();

if (isset($_GET['projetEnCours'])) {
	$_SESSION['projetEnCours'] = $_GET['projetEnCours'];
}
?>