<?php

include 'config.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && !isset(json_decode(file_get_contents('php://input'))->_method)) {

	$json = json_decode(file_get_contents('php://input'), true);

	switch ($json['objet']) {
		case 'tache':
		if(!isset($_SESSION['codeTache'])) {
			$req = $db->prepare("INSERT INTO taches (libelleTache, codeFamille, dateDebutPrevue, dateFinPrevue, codeProjet, referenceTache, tempsPrevu, coutPrevu, codeLivrable) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

			$req->bindParam(1, $json["libelleTache"]);
			$req->bindParam(2, $json["codeFamille"]);
			$req->bindParam(3, $json["dateDebutPrevue"]);
			$req->bindParam(4, $json["dateFinPrevue"]);
			$req->bindParam(5, $json["codeProjet"]);
			$req->bindParam(6, $json["refTache"]);
			$req->bindParam(7, $json["tempsPrevu"]);
			$req->bindParam(8, $json["coutPrevu"]);
			$req->bindParam(9, $json["codeLivrable"]);

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
			if(!isset($_SESSION["codeProjet"])) {
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
				$req->bindParam(2, $_SESSION["codeProjet"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification du projet a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'famille':
			if(!isset($_SESSION['codeFamille'])) {
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
				$req->bindParam(2, $_SESSION["codeFamille"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification de la famille de tâche a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'livrable':
			if(!isset($_SESSION['codeLivrable'])) {
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
				$req->bindParam(2, $_SESSION["codeLivrable"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification du livrable a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'categ':
			if(!isset($_SESSION['codeCategorie'])) {
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
				$req->bindParam(2, $_SESSION["codeCategorie"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification de la catégorie a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'ressource':
			if(!isset($_SESSION['codeRessource'])) {
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
				$req->bindParam(4, $_SESSION["codeRessource"]);

				$done = $req->execute();

				if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La modification de la ressource a échouée. Veuillez réessayer dans quelques instants."]);
			}
			break;

		case 'parametre':
			$reqCount = $db->query("SELECT COUNT(*) FROM parametres");
			$count = $reqCount->fetch();
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
			break;
		default:
			# code...
			break;
	}
} else if(isset($_GET["idProjet"]) && !is_nan($_GET["idProjet"])) {
	
	$req = $db->prepare("SELECT * FROM projets WHERE codeProjet = ?");

	$req->bindParam(1, $_GET["idProjet"]);

	$done = $req->execute();

	$result = $req->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else if ($_SERVER['REQUEST_METHOD'] == "GET"){
	$object = htmlspecialchars($_GET["objet"]);
	switch ($object) {
		case 'taches':
			$req = $db->prepare("SELECT * FROM taches WHERE codeProjet = ?");
			$req->bindParam(1, htmlspecialchars($_GET["codeProjet"]));
			$done = $req->execute();

			if($done) {
				$result = $req->fetch();
				echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			} else {
				echo json_encode(['codeRetour' => 500, 'result' => "Impossible de récupérer les taches du projet. Veuillez réessayer dans quelques instants."]);
			}
			break;
		
		case 'projets':
			$req = $db->query("SELECT * FROM projets");
			$result = $req->fetchAll(PDO::FETCH_ASSOC);
			for($i = 0; $i < count($result); $i++) {
				$reqDates = $db->prepare("SELECT MIN(dateDebutReelle) AS DateDeb, MIN(dateFinReelle) AS DateFin FROM taches WHERE codeProjet = ?");
				$reqDates->bindParam(1, $result[$i]["codeProjet"]);
				$reqDates->execute();
				$resDates = $reqDates->fetch(PDO::FETCH_ASSOC);
				$result[$i]["dateDebutReelle"] = $resDates["DateDeb"];
				$result[$i]["dateFinReelle"] = $resDates["DateFin"];
			}
			
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
			$req = $db->query("SELECT * FROM categoriepersonnel");
			$result = $req->fetchAll(PDO::FETCH_ASSOC);
			
			echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
			break;

		case 'ressources':
			$req = $db->query("SELECT r.codeRessource, r.nomRessource, c.libelleCategorie, r.tauxHoraire FROM ressources r
			 INNER JOIN categoriepersonnel c ON r.codeCatPerso = c.codeCategorie");
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
} else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset(json_decode(file_get_contents('php://input'))->_method)) {
	$object = htmlspecialchars($_GET["objet"]);
	$json = json_decode(file_get_contents('php://input'));
	switch ($object) {
		case 'tache':
			$req = $db->prepare("CALL DELETE_TACHE(?)");
			$req->bindParam(1, $json->idTache);
			$req->execute();
					
			echo json_encode(['codeRetour' => 200, 'result' => null]);
			break;
		case 'projet':
			$sql = "SET @p0=".$json->idProjet."; CALL DELETE_PROJET(@p0);";
			$db->query($sql);					
			echo json_encode(['codeRetour' => 200, 'result' => null]);
			break;
		case 'famille':
			$req = $db->prepare("DELETE FROM familletache WHERE codeFamille = ?");
			$req->bindParam(1, $json["idFamille"]);
			$done = $req->execute();

			if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La suppression de la famille de tâche a échouée. Veuillez réessayer dans quelques instants."]);
			break;
		case 'livrable':
			$req = $db->prepare("DELETE FROM livrables WHERE codeLivrable = ?");
			$req->bindParam(1, $json["idLivrable"]);
			$done = $req->execute();

			if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La suppression du livrable a échouée. Veuillez réessayer dans quelques instants."]);
			break;
		case 'categ':
			$req = $db->prepare("DELETE FROM categoriepersonnel WHERE codeCategorie = ?");
			$req->bindParam(1, $json["idCategorie"]);
			$done = $req->execute();

			if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La suppression de la catégorie de personnel a échouée. Veuillez réessayer dans quelques instants."]);
			break;
		case 'ressource':
			$req = $db->prepare("DELETE FROM ressources WHERE codeRessource = ?");
			$req->bindParam(1, $json["idRessource"]);
			$done = $req->execute();

			if($done)
					echo json_encode(['codeRetour' => 200, 'result' => null]);
				else echo json_encode(['codeRetour' => 500, 'result' => "La suppression de la ressource a échouée. Veuillez réessayer dans quelques instants."]);
			break;
		default:
			break;
	}
}