<?php

include 'config.php';

if(isset(htmlspecialchars($_GET["idProjet"])) && !is_nan($_GET["idProjet"])) {
	$req = $db->prepare("SELECT temp.codeTache, temp.libelleTache, temp.codeFamille, temp.dateDebutPrevue, temp.dateFinPrevue, temp.tempsPrevu,
	                            temp.coutPrevu, temp.dateDebutReelle, temp.dateFinReelle, temp.tempsConsomme, SUM(cout) as coutTotal , temp.avancement 
	                            FROM (
								SELECT t.*, conso.codeRessource, SUM(tempsPassee) AS tempsConsomme, tauxHoraire, SUM(tempsPassee)*tauxHoraire as cout 
								FROM taches as t, conso 
								INNER JOIN ressources ON (ressources.codeRessource = conso.codeRessource) 
								WHERE codeProjet = ? 
								GROUP BY conso.codeRessource
								) AS temp");
	$req->bindParam(1, htmlspecialchars($_GET["idProjet"]);	

	$result = $req->fetchAll();

	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else {
	echo json_encode(['codeRetour' => 500, 'result' => 'Le paramètre passé n\'est pas valide.');
}

?>