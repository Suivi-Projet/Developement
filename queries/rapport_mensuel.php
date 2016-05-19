<?php

include 'config.php';

$request = $db->prepare("SELECT MIN(dateDebutReelle) as dateDeb, MAX(dateFinReelle) as dateEnd FROM taches WHERE codeProjet = ?");
$request->bindParam(1, $_GET["idProjet"]);
$request->execute();
$resDate = $request->fetch(PDO::FETCH_ASSOC);

$yearStart = isset($_GET["year"]) ? $_GET["year"] : ($resDate["dateDeb"] == null ? "0000" : explode('-', $resDate["dateDeb"])[0]);
$yearEnd = isset($_GET["year"]) ? $_GET["year"] : ($resDate["dateEnd"] == null ? "9999" : explode('-', $resDate["dateEnd"])[0]);

$monthStart = (isset($_GET["month"]) && $_GET["month"] !== null) ? $_GET["month"] : '01';
$monthEnd = (isset($_GET["month"]) && $_GET["month"] !== null) ? $_GET["month"] : '12';

$dateStart = $yearStart.'-'.$monthStart.'-01';
$dateEnd = $yearEnd.'-'.$monthEnd.'-'.date("t", strtotime($yearEnd.'-'.$monthEnd.'-01'));

if(isset($_GET["idRessource"]) && !isset($_GET["idTache"]) && !is_nan($_GET["idRessource"])) {

	$sqlTaches = "SELECT c.date, t.referenceTache, c.tempsPassee, (c.tempsPassee * r.tauxHoraire) as montantTache, SUM(tempsPassee) as tempsTotTache, dureeLegale,
						 t.tempsPrevu
			FROM parametres as p, conso as c   
			INNER JOIN taches as t ON (t.codeTache = c.codeTache)
			INNER JOIN ressources as r ON (c.codeRessource = r.codeRessource)
			WHERE t.codeProjet = ".$_GET["idProjet"].
		  " AND c.codeRessource = ". $_GET["idRessource"].
		  " AND c.codeRessource = r.codeRessource 
		  	AND t.codeTache = c.codeTache 
		    AND date BETWEEN '".$dateStart."' AND '".$dateEnd."' 
		    GROUP BY c.codeTache, c.date";

	$reqTaches = $db->query($sqlTaches);
	$resultTaches = $reqTaches->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; $i<count($resultTaches); $i++) {
		$resultTaches[$i]["alerteTache"] = $resultTaches[$i]["tempsPrevu"] - $resultTaches[$i]["tempsTotTache"] < 0 ? true : false;
		$resultTaches[$i]["alerteQuota"] = $resultTaches[$i]["tempsPassee"] - $resultTaches[$i]["dureeLegale"] < 0 ? false : true;
	}


	$sqlTotal = "SELECT SUM(tempsPassee) as tempsTotal, SUM(conso.tempsPassee*ressources.tauxHoraire) as montantTotal 
				 FROM conso 
				 INNER JOIN ressources ON (conso.codeRessource = ressources.codeRessource) 
				 INNER JOIN taches as t ON (t.codeTache = conso.codeTache)
				 WHERE t.codeProjet = ".$_GET["idProjet"].
			   " AND conso.codeRessource = ".$_GET["idRessource"]. 
			   " AND date BETWEEN '".$dateStart."' AND '".$dateEnd."'"; 
	$reqTotal = $db->query($sqlTotal);
	$resultTotal = $reqTotal->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode(['codeRetour' => 200, 'result' => null, 'taches' => json_encode($resultTaches), 'total' => json_encode($resultTotal)]);

} else if (!isset($_GET["idRessource"]) && isset($_GET["idTache"]) && !is_nan($_GET["idTache"])){
	
	$sqlRessources = "SELECT c.date, t.referenceTache, r.nomRessource, c.tempsPassee, (c.tempsPassee * r.tauxHoraire) as montantTache,
							 SUM(tempsPassee) as tempsTotTache, dureeLegale, t.tempsPrevu
					  FROM parametres as p, conso as c   
					  INNER JOIN taches as t ON (t.codeTache = c.codeTache)
					  INNER JOIN ressources as r ON (c.codeRessource = r.codeRessource)
					  WHERE t.codeProjet = ".$_GET["idProjet"].
				    " AND c.codeTache = ". $_GET["idTache"].
				    " AND c.codeRessource = r.codeRessource 
				  	  AND t.codeTache = c.codeTache 
				      AND date BETWEEN '".$dateStart."' AND '".$dateEnd."'  
				      GROUP BY c.codeRessource, c.date";

	$reqRessources = $db->query($sqlRessources);
	$resultRessources = $reqRessources->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; $i<count($resultRessources); $i++) {
		$resultRessources[$i]["alerteTache"] = $resultRessources[$i]["tempsPrevu"] - $resultRessources[$i]["tempsTotTache"] < 0 ? true : false;
		$resultRessources[$i]["alerteQuota"] = $resultRessources[$i]["tempsPassee"] - $resultRessources[$i]["dureeLegale"] < 0 ? false : true;
	}

	$sqlTotal = "SELECT SUM(tempsPassee) as tempsTotal, SUM(conso.tempsPassee*ressources.tauxHoraire) as montantTotal 
				 FROM conso 
				 INNER JOIN ressources ON (conso.codeRessource = ressources.codeRessource) 
				 INNER JOIN taches as t ON (t.codeTache = conso.codeTache)
				 WHERE t.codeProjet = ".$_GET["idProjet"].
			   " AND conso.codeTache = ".$_GET["idTache"]. 
			   " AND date BETWEEN '".$dateStart."' AND '".$dateEnd."'";

	$reqTotal = $db->query($sqlTotal);
	$resultTotal = $reqTotal->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode(['codeRetour' => 200, 'result' => null, 'ressources' => json_encode($resultRessources), 'total' => json_encode($resultTotal)]);	
} else if (isset($_GET["anneesProjet"]) && !is_nan($_GET["anneesProjet"])){
	$sqlRessources = "SELECT YEAR(MIN(c.date)) as smallAnnee, YEAR(MAX(c.date)) as bigAnnee FROM conso c
		INNER JOIN
		taches t
		ON t.codeTache = c.codeTache
		WHERE t.codeProjet = " . $_GET["anneesProjet"];

	$reqRessources = $db->query($sqlRessources);
	$result = $reqRessources->fetch(PDO::FETCH_ASSOC);

	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else {
	echo json_encode(['codeRetour' => 500, 'result' => "Parametre invalide !"]);
}