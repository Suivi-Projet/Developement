<?php

include 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$json = json_decode(file_get_contents('php://input'), true);

	$req = $db->prepare("INSERT INTO conso (codeRessource, codeTache, date, tempsPassee) VALUES (?, ?, ?, ?)");
	$req->bindParam(1, $json["codeRessource"]);
	$req->bindParam(2, $json["codeTache"]);
	$req->bindParam(3, date("Y-m-d"));
	$req->bindParam(4, $json["tempsPassee"]);

	$done = $req->execute();
	if(!done)
		echo json_encode(['codeRetour' => 500, 'result' => "La création de la journée de travail a échouée. Veuillez réessayer dans quelques instants."]);
	else {
		$updAvancement = $db->prepare("UPDATE taches SET avancement = ?, dateFinReelle = null WHERE codeTache = ?");
		$updAvancement->bindParam(1, $json["avancement"]);
		$updAvancement->bindParam(2, $json["codeTache"]);
		$doneAvancement = $updAvancement->execute();

		if($doneAvancement) {
			$reqExistDateDeb = $db->prepare("SELECT dateDebutReelle FROM taches WHERE codeTache = ?");
			$reqExistDateDeb->bindParam(1, $json["codeTache"]);
			$reqExistDateDeb->execute();
			$resultDateDeb = $reqExistDateDeb->fetchAll(PDO::FETCH_ASSOC);

			if(is_null($resultDateDeb["dateDebutReelle"])) {
				$updTache = $db->prepare("UPDATE taches SET dateDebutReelle = ? WHERE codeTache = ?");
				$updTache->bindParam(1, date("Y-m-d"));
				$updTache->bindParam(2, $json["codeTache"]);
				$doneDateDeb = $updTache->execute();

				if(!$doneDateDeb)
					echo json_encode(['codeRetour' => 500, 'result' => "La mise à jour de la date de début réelle de la tâche a échouée. Veuillez réessayer dans quelques instants."]);

			}

			if($json["avancement"] == 100) {
				$updTacheEnd = $db->prepare("UPDATE taches SET dateFinReelle = ? WHERE codeTache = ?");
				$updTacheEnd->bindParam(1, date("Y-m-d"));
				$updTacheEnd->bindParam(2, $json["codeTache"]);
				$doneUpTacheEnd = $updTacheEnd->execute();
				if(!$doneUpTacheEnd)
					echo json_encode(['codeRetour' => 500, 'result' => "La mise à jour de la date de fin réelle de la tâche a échouée. Veuillez réessayer dans quelques instants."]);
			}


			echo json_encode(['codeRetour' => 200, 'result' => null]);
		}
		
	}
}else if(isset($_GET["idProjet"]) && !is_nan($_GET["idProjet"])) {
	
	$req = $db->prepare("SELECT a.nbTaches, b.nbDepassement, c.nbLate FROM (SELECT count(t.codeTache) as nbTaches FROM taches t WHERE codeProjet = ?) a, (SELECT COUNT(r.nbTaches) as nbDepassement FROM (SELECT t.codeTache as nbTaches FROM taches t WHERE codeProjet = ? AND t.tempsPrevu < (SELECT SUM(c.tempsPassee) FROM conso c WHERE c.codeTache = t.codeTache)) r) b, (SELECT COUNT(codeTache) as nbLate FROM (SELECT t.codeTache FROM taches t WHERE codeProjet = ? AND (t.dateDebutPrevue < t.dateDebutReelle OR (t.dateDebutPrevue < NOW()) OR (t.dateFinPrevue < NOW()) OR ( t.dateFinReelle > t.dateFinPrevue))) r) c");

	$req->bindParam(1, $_GET["idProjet"]);
	$req->bindParam(2, $_GET["idProjet"]);
	$req->bindParam(3, $_GET["idProjet"]);

	$done = $req->execute();

	$result = $req->fetch(PDO::FETCH_ASSOC);
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
}

?>