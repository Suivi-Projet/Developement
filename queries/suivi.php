<?php

include 'config.php';

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
	$json = json_decode(file_get_contents('php://input'), true);
	if($_GET["objet"] != "consos") {
		$req = $db->prepare("INSERT INTO conso (codeRessource, codeTache, date, tempsPassee) VALUES (?, ?, ?, ?)");
		$req->bindParam(1, $json["codeRessource"]);
		$req->bindParam(2, $json["codeTache"]);
		$neoDate = is_null($json["date"]) ? date("Y-m-d") : $json["date"];
		$req->bindParam(3, $neoDate);
		$req->bindParam(4, $json["tempsPassee"]);
	} else {
		$req = $db->prepare("UPDATE conso SET codeRessource = ?, codeTache = ?, date = ?, tempsPassee = ? WHERE codeConso = ?");
		$req->bindParam(1, $json["codeRessource"]);
		$req->bindParam(2, $json["codeTache"]);
		$neoDate = is_null($json["date"]) ? date("Y-m-d") : $json["date"];
		$req->bindParam(3, $neoDate);
		$req->bindParam(4, $json["tempsPassee"]);
		$req->bindParam(5, $_SESSION["codeConso"]);
	}

	$done = $req->execute();
	if(!$done)
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
			$resultDateDeb = $reqExistDateDeb->fetch(PDO::FETCH_ASSOC);

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
	
	$req = $db->prepare("SELECT a.nbTaches, b.nbDepassement, c.nbLate FROM
							(SELECT count(t.codeTache) as nbTaches FROM taches t WHERE codeProjet = ?) a,
							(SELECT COUNT(r.nbTaches) as nbDepassement FROM
							(SELECT t.codeTache as nbTaches FROM taches t WHERE codeProjet = ? AND t.tempsPrevu <
							(SELECT SUM(c.tempsPassee) FROM conso c WHERE c.codeTache = t.codeTache)) r) b,
							(SELECT COUNT(codeTache) as nbLate FROM
							(SELECT t.codeTache FROM taches t WHERE codeProjet = ? AND (t.dateDebutPrevue < t.dateDebutReelle OR (t.dateDebutPrevue < NOW()) OR (t.dateFinPrevue < NOW()) OR ( t.dateFinReelle > t.dateFinPrevue))) r) c");

	$req->bindParam(1, $_GET["idProjet"]);
	$req->bindParam(2, $_GET["idProjet"]);
	$req->bindParam(3, $_GET["idProjet"]);

	$done = $req->execute();

	$result = $req->fetch(PDO::FETCH_ASSOC);
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
}else if(isset($_GET["infoProjet"]) && !is_nan($_GET["infoProjet"])) {
	$req = $db->prepare("SELECT MIN(dateDebutPrevue) as dateDebutPrevue, MAX(dateFinPrevue) as dateFinPrevue, SUM(tempsPrevu) as dureePrevu, MIN(dateDebutReelle) as dateDebutReelle, MAX(dateFinReelle) as dateFinReelle,
									  DATEDIFF(MIN(dateDebutReelle), MIN(dateDebutPrevue)) as ecartDateDebut, DATEDIFF(MAX(dateFinReelle), MAX(dateFinPrevue)) as ecartDateFin,
									  temp.tempsConsomme, (SUM(tempsPrevu) - temp.tempsConsomme) as ecartDuree, temp.montantPrevu, temp.montantReel, (temp.montantPrevu-temp.montantReel) as ecartMontant 
							  FROM taches, (
								SELECT SUM(t.coutPrevu) as montantPrevu, res.tempsConsomme, res.montantReel
								FROM taches t, 
								(SELECT SUM(cons.tempsPassee) as tempsConsomme, SUM(cons.montantConso) as montantReel
								FROM taches t
								INNER JOIN
								(SELECT c.tempsPassee, (c.tempsPassee * res.tauxHoraire) montantConso, c.codeTache FROM conso c
								INNER JOIN 
								(SELECT r.codeRessource, r.tauxHoraire FROM ressources r) res
								ON (res.codeRessource = c.codeRessource)
								) cons
								ON (cons.codeTache = t.codeTache)
								WHERE t.codeProjet = ?) res
								WHERE t.codeProjet = ?
                              ) as temp
							  WHERE codeProjet = ?");

	$req->bindParam(1, $_GET["infoProjet"]);
	$req->bindParam(2, $_GET["infoProjet"]);
	$req->bindParam(3, $_GET["infoProjet"]);

	$done = $req->execute();

	$lastReq = $db->prepare("SELECT codeTache FROM taches WHERE dateFinReelle IS NULL AND codeProjet = ?");

	$lastReq->bindParam(1, $_GET["infoProjet"]);

	$lastReq->execute();
	if($done) {
		$result = $req->fetch(PDO::FETCH_ASSOC);
		$lastResult = $lastReq->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result), 'unfinished' => json_encode($lastResult)]);
	}
} else if(isset($_GET["recapProjet"]) && !is_nan($_GET["recapProjet"])) {
	$req = $db->prepare("SELECT t.referenceTache, t.libelleTache, t.dateDebutReelle, t.dateFinReelle, t.tempsPrevu, SUM(res.tempsPassee) as tempsConsomme, 
		SUM(res.tempsPassee * res.tauxHoraire) as cout,
		(SUM(res.tempsPassee)/t.tempsPrevu) as ratioTemps,
		t.avancement, t.dateFinPrevue, t.dateDebutPrevue, p.dureeLegale
		FROM parametres as p, taches as t
		LEFT JOIN
		(
			SELECT c.codeTache, c.tempsPassee, r.tauxHoraire FROM conso c
			INNER JOIN ressources r
			ON (c.codeRessource = r.codeRessource)
		) res
	ON
	(res.codeTache = t.codeTache)
	WHERE t.codeProjet = ?
	GROUP BY t.codeTache");
	$req->bindParam(1, $_GET["recapProjet"]);

	$done = $req->execute();
	if($done) {
		$result = $req->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
	}
} else if(isset($_GET["consosProjet"]) && !is_nan($_GET["consosProjet"])) {
	$req = $db->prepare("SELECT c.date, t.libelleTache, r.nomRessource, c.tempsPassee, t.avancement 
						 FROM conso c
						 INNER JOIN taches t ON (c.codeTache = t.codeTache)
						 INNER JOIN ressources r ON (r.codeRessource = c.codeRessource)
						 WHERE t.codeProjet = ?");
	$req->bindParam(1, $_GET["consosProjet"]);

	$done = $req->execute();

	if($done) {
		$result = $req->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
	} else echo json_encode(['codeRetour' => 500, 'result' => 'Une erreur est survenue lors de la récupération des consos.']);
} else echo json_encode(['codeRetour' => 500, 'result' => 'Appel invalide !!!']);
