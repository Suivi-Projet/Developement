<?php

session_start();

if (isset($_POST['projetEnCours'])) {
	$_SESSION['projetEnCours'] = $_POST['projetEnCours'];
}

if(isset($_GET["projetEnCours"])) {
	$data = isset($_SESSION["projetEnCours"]) ? $_SESSION["projetEnCours"] : null;
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => $data]);
}

if (isset($_POST['authentify'])) {
	echo "test";
	$login = $_POST['user'];
	$pass = $_POST['pass'];

	if($login == "Ingesup" && $pass == "IngesupIngesup") {
		$_SESSION['login'] = $login;
		echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => "ok"]);
	} else {
		echo json_encode(['codeRetour' => 500, 'result' => null, 'data' => "error"]);
	}
}

if(isset($_GET["authentified"])) {
	$data = isset($_SESSION["login"]) ? $_SESSION["login"] : null;
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => $data]);
}

if(isset($_GET["logout"])) {
	$_SESSION["login"] = null;
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => null]);
}

if(isset($_POST["codeProjet"])) {
	$_SESSION["codeProjet"] = $_POST["codeProjet"];
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => $_SESSION["codeProjet"]]);
}

if(isset($_POST["codeFamille"])) {
	$_SESSION["codeFamille"] = $_POST["codeFamille"];
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => $_SESSION["codeFamille"]]);
}

if(isset($_POST["codeLivrable"])) {
	$_SESSION["codeLivrable"] = $_POST["codeLivrable"];
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => $_SESSION["codeLivrable"]]);
}

if(isset($_POST["codeCategorie"])) {
	$_SESSION["codeCategorie"] = $_POST["codeCategorie"];
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => $_SESSION["codeCategorie"]]);
}

if(isset($_POST["codeRessource"])) {
	$_SESSION["codeRessource"] = $_POST["codeRessource"];
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => $_SESSION["codeRessource"]]);
}
?>