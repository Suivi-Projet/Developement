<?php

session_start();

if (isset($_POST['projetEnCours'])) {
	$_SESSION['projetEnCours'] = $_POST['projetEnCours'];
}

if(isset($_GET["projetEnCours"])) {
	$data = isset($_SESSION["projetEnCours"]) ? $_SESSION["projetEnCours"] : null;
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => $data]);
}
?>