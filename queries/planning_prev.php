<?php

include 'config.php';

if(isset($_GET["idProjet"]) && !is_nan($_GET["idProjet"])) {
	$req = $db->prepare("SELECT libelleTache, tempsPrevu, dateDebutPrevue, dateFinPrevue
						 FROM taches 
						 WHERE codeProjet = ?");
	$req->bindParam(1, $_GET["idProjet"]);
	$req->execute();
	$result = $req->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else {
	echo json_encode(['codeRetour' => 500, 'result' => "Parametre invalide !"]);
}
?>