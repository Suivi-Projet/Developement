<?php

include 'config.php';

if(isset($_POST["data"])){

	$json = json_decode($_POST["data"]);

	switch ($json['object']) {
		case 'taches':
		if(!isset($json['codeTache'])) {
			$req = $db->prepare("INSERT INTO taches (libelleTache, codeFamille, dateDebutPrevue, dateFinPrevue, dateDebutReelle, dateFinReelle, coutPrevu, codeLivrable, codeProjet) 
						  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

			$req->bindParam(1, $json["libelleTache"]);
			$req->bindParam(2, $json["codeFamille"]);
			$req->bindParam(3, $json["dateDebutPrevue"]);
			$req->bindParam(4, $json["dateFinPrevue"]);
			$req->bindParam(5, $json["dateDebutReelle"]);
			$req->bindParam(6, $json["dateFinReelle"]);
			$req->bindParam(7, $json["coutPrevu"]);
			$req->bindParam(8, $json["codeLivrable"]);
			$req->bindParam(9, $json["codeProjet"]);

			$done = $req->execute();

			if($done)
				echo json_encode(['codeRetour' => 200, 'result' => null]);
			else echo json_encode(['codeRetour' => 500, 'result' => "La création de la tâche a échouée. Veuillez réessayer dans quelques instants."]);

		} else {
			$req = $db->prepare("UPDATE taches 
								 SET libelleTache = ?, codeFamille = ?, dateDebutPrevue = ?, dateFinPrevue = ?, dateDebutReelle = ?, dateFinReelle = ?, coutPrevu = ?, codeLivrable = ?, codeProjet = ? WHERE codeTache = ?");

			$req->bindParam(1, $json["libelleTache"]);
			$req->bindParam(2, $json["codeFamille"]);
			$req->bindParam(3, $json["dateDebutPrevue"]);
			$req->bindParam(4, $json["dateFinPrevue"]);
			$req->bindParam(5, $json["dateDebutReelle"]);
			$req->bindParam(6, $json["dateFinReelle"]);
			$req->bindParam(7, $json["coutPrevu"]);
			$req->bindParam(8, $json["codeLivrable"]);
			$req->bindParam(9, $json["codeProjet"]);
			$req->bindParam(10, $json["codeTache"]);

			$done = $req->execute();

			if($done)
				echo json_encode(['codeRetour' => 200, 'result' => null]);
			else echo json_encode(['codeRetour' => 500, 'result' => "La modification de la tâche a échouée. Veuillez réessayer dans quelques instants."]);
		}
			break;

		case 'projet':
			if(!isset($json["codeProjet"])) {
				$req = $db->prepare("INSERT INTO taches (libelleProjet) 
						  VALUES (?)");

				$req->bindParam(1, $json["libelleProjet"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La création du projet a échouée. Veuillez réessayer dans quelques instants."]);

			} else {
				$req = $db ->prepare("UPDATE projet SET libelleProjet = ? WHERE codeProjet = ?");
				$req->bindParam(1, $json["libelleProjet"]);
				$req->bindParam(2, $json["codeProjet"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification du projet a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		default:
			# code...
			break;
	}
} else {
	$object = htmlspecialchars($_GET["objet"]);
	switch ($object) {
		case 'taches':
			$req = $db->prepare("SELECT * FROM taches WHERE codeProjet = ?");
			$req->bindParam(1, htmlspecialchars($_GET["codeProjet"]));
			$done = $req->execute();

			if($done) {
				$result = $req->fetch(PDO::FETCH_ASSOC);
				echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			} else {
				echo json_encode(['codeRetour' => 500, 'result' => "Impossible de récupérer les taches du projet. Veuillez réessayer dans quelques instants."]);
			}
			break;
		
		case 'projet':
			$req = $db->prepare("SELECT * FROM projets");
			$done = $req->execute();

			if($done) {
				$result = $req->fetch(PDO::FETCH_ASSOC);
				echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			} else {
				echo json_encode(['codeRetour' => 500, 'result' => "Impossible de récupérer les différents projets. Veuillez réessayer dans quelques instants."]);
			}
			break;

		default:
			# code...
			break;
	}
}

?>