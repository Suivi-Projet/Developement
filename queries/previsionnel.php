<?php

include 'config.php';

if(isset($_GET["idProjet"]) && !is_nan($_GET["idProjet"])) {
	
	$reqProjet = $db->prepare("SELECT dateDebutPrevue, dateFinPrevue, SUM(tempsPrevu) as dureePrevu, dateDebutReelle, dateFinReelle,
									  DATEDIFF(dateDebutReelle, dateDebutPrevue) as ecartDateDebut, DATEDIFF(dateFinReelle, dateFinPrevue) as ecartDateFin,
									  temp.tempsConsomme, (tempsPrevu-temp.tempsConsomme) as ecartDuree, temp.montantPrevu, temp.montantReel, (temp.montantPrevu-temp.montantReel) as ecartMontant 
							  FROM taches, (
								SELECT SUM(tempsPassee) as tempsConsomme, SUM(t.coutPrevu) as montantPrevu, 
								SUM(c.tempsPassee*r.tauxHoraire) as montantReel 
								FROM conso as c, taches as t, ressources as r 
								WHERE c.codeTache = t.codeTache 
								AND c.codeRessource = r.codeRessource 
								AND t.codeProjet = ? 
								GROUP BY c.codeTache
                              ) as temp
							  ORDER BY taches.codeTache");
	$reqProjet->bindParam(1, $_GET["idProjet"]);
	$reqProjet->execute();
	$resultProjet = $reqProjet->fetchAll();

	$reqTaches = $db->prepare("SELECT t.codeTache, t.libelleTache, f.libelleFamille, t.dateDebutPrevue, t.dateFinPrevue, t.tempsPrevu, 
								t.dateDebutReelle, t.dateFinReelle, temp.tempsConsomme, temp.cout, ROUND((temp.tempsConsomme/t.tempsPrevu)*100, 1) as ratio,
								t.avancement, t.tempsPrevu-temp.tempsConsomme as tempsRestant
							   FROM taches as t, familletache as f,
							   (
								  SELECT c.codeTache, SUM(c.tempsPassee) as tempsConsomme, SUM(c.tempsPassee*r.tauxHoraire) as cout
								  FROM conso as c, ressources as r
								  WHERE c.codeRessource = r.codeRessource
								  GROUP BY c.codeTache
							   ) AS temp
							   WHERE t.codeTache = temp.codeTache    
							   AND f.codeFamille = t.codeFamille
							   AND t.codeProjet = ?
							   ORDER BY t.codeTache");
	$reqTaches->bindParam(1, $_GET["idProjet"]);	
	$reqTaches->execute();
	$resultTaches = $reqTaches->fetchAll();
	$result = array("projet" => json_encode($resultProjet), "taches" => json_encode($resultTaches));
	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else {
	echo json_encode(['codeRetour' => 500, 'result' => 'Le paramètre passé n\'est pas valide.']);
}

?>