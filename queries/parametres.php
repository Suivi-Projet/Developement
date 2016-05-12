<?php

include 'config.php';

if(isset($_POST["data"])){

	$json = json_decode($_POST["data"]);

	switch ($json['object']) {
		case 'tache':
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
				$req = $db->prepare("INSERT INTO projets (libelleProjet) 
						  VALUES (?)");

				$req->bindParam(1, $json["libelleProjet"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La création du projet a échouée. Veuillez réessayer dans quelques instants."]);

			} else {
				$req = $db ->prepare("UPDATE projets SET libelleProjet = ? WHERE codeProjet = ?");
				$req->bindParam(1, $json["libelleProjet"]);
				$req->bindParam(2, $json["codeProjet"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification du projet a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'familles':
			if(!isset($json['codeFamille'])) {
				$req = $db->prepare("INSERT INTO familletache (libelleFamille) 
							  VALUES (?)");

				$req->bindParam(1, $json["libelleFamille"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La création de la famille de tâche a échouée. Veuillez réessayer dans quelques instants."]);

			} else {
				$req = $db->prepare("UPDATE familletache 
									 SET libelleFamille = ? WHERE codeFamille = ?");

				$req->bindParam(1, $json["libelleFamille"]);
				$req->bindParam(2, $json["codeFamille"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification de la famille de tâche a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'livrables':
			if(!isset($json['codeLivrable'])) {
				$req = $db->prepare("INSERT INTO livrables (libelleLivrable) 
							  VALUES (?)");

				$req->bindParam(1, $json["libelleLivrable"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La création du livrable a échouée. Veuillez réessayer dans quelques instants."]);

			} else {
				$req = $db->prepare("UPDATE familletache 
									 SET libelleLivrable = ? WHERE codeLivrable = ?");

				$req->bindParam(1, $json["libelleLivrable"]);
				$req->bindParam(2, $json["codeLivrable"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification du livrable a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'categs':
			if(!isset($json['codeCategorie'])) {
				$req = $db->prepare("INSERT INTO categoriepersonnel (libelleCategorie) 
							  VALUES (?)");

				$req->bindParam(1, $json["libelleCategorie"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La création de la catégorie a échouée. Veuillez réessayer dans quelques instants."]);

			} else {
				$req = $db->prepare("UPDATE categoriepersonnel 
									 SET libelleCategorie = ? WHERE codeCategorie = ?");

				$req->bindParam(1, $json["libelleCategorie"]);
				$req->bindParam(2, $json["codeCategorie"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification de la catégorie a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'ressources':
			if(!isset($json['codeRessource'])) {
				$req = $db->prepare("INSERT INTO ressources (nomRessource, tauxHoraire, codeCatPerso) 
							  VALUES (?, ?, ?)");

				$req->bindParam(1, $json["nomRessource"]);
				$req->bindParam(2, $json["tauxHoraire"]);
				$req->bindParam(3, $json["codeCatPerso"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La création de la ressource a échouée. Veuillez réessayer dans quelques instants."]);

			} else {
				$req = $db->prepare("UPDATE ressources 
									 SET nomRessource = ?, tauxHoraire = ?, codeCatPerso = ? WHERE codeRessource = ?");

				$req->bindParam(1, $json["nomRessource"]);
				$req->bindParam(2, $json["tauxHoraire"]);
				$req->bindParam(3, $json["codeCatPerso"]);
				$req->bindParam(4, $json["codeRessource"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification de la ressource a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'parametres':
			$reqCount = $db->query("SELECT COUNT(*) FROM parametres");
			$count = $req->fetch();
			if($count != 0) {
				$clear = $db->query("TRUNCATE TABLE parametres");
				$clear->execute();
			}
			$req = $db->prepare("INSERT INTO parametres (dureeLegale)
									 VALUES (?)");
				$req->bindParam(1, $json["dureeLegale"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La création de la durée légale de trvail par jour a échouée. Veuillez réessayer dans quelques instants."]);

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
		
		case 'projets':
			$req = $db->query("SELECT * FROM projets");
			$result = $req->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			break;

		case 'familles':
			$req = $db->query("SELECT * FROM familletache");
			$result = $req->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			break;

		case 'livrables':
			$req = $db->query("SELECT * FROM livrables");
			$result = $req->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			break;

		case 'categs':
			$req = $db->prepare("SELECT * FROM categoriepersonnel");
			$req->execute();
			$result = $req->fetchAll(PDO::FETCH_ASSOC);
			
			echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			break;

		case 'ressources':
			$req = $db->query("SELECT * FROM ressources");
			$result = $req->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			break;

		case 'parametres':
			$req = $db->query("SELECT * FROM parametres");
			$result = $req->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			break;

		default:
			# code...
			break;
	}
}

?>