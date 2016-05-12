<?php

include 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$json = json_decode(file_get_contents('php://input'), true);
	switch ($json["objet"]) {
		case 'tache':
			# code...
			break;
		
		default:
			# code...
			break;
	}
}
else if(isset($_GET["idProjet"]) && !is_nan($_GET["idProjet"])) {
	
	$req = $db->prepare("SELECT t.referenceTache, t.libelleTache, f.libelleFamille, t.dateDebutPrevue, t.dateFinPrevue, t.tempsPrevu, t.coutPrevu
		FROM taches t INNER JOIN familletache f ON t.codeFamille = f.codeFamille WHERE t.codeProjet = ?");

	$req->bindParam(1, $_GET["idProjet"]);

	$done = $req->execute();

	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
}
else {
	echo json_encode(['codeRetour' => 500, 'result' => 'Le paramètre passé n\'est pas valide.']);
}

?>