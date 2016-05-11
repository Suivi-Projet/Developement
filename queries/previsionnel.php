<?php

include 'config.php';

if(isset(htmlspecialchars($_GET["idProjet"])) && !is_nan($_GET["idProjet"])) {

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
						 AND t.codeProjet = ?");
	$reqTaches->bindParam(1, htmlspecialchars($_GET["idProjet"]);	

	$resultTaches = $reqTaches->fetchAll();

	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else {
	echo json_encode(['codeRetour' => 500, 'result' => 'Le paramètre passé n\'est pas valide.');
}

?>