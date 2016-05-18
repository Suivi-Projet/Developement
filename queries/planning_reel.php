<?php

include 'config.php';

if(isset($_GET["idProjet"]) && !is_nan($_GET["idProjet"])) {
	$reqDates = $db->prepare("SELECT CASE WHEN MIN(dateDebutPrevue) < MIN(dateDebutReelle) THEN MIN(dateDebutPrevue) ELSE MIN(dateDebutReelle) END AS dateMin ,
	   								 CASE WHEN MAX(dateFinPrevue) > MAX(dateFinReelle) THEN MAX(dateFinPrevue) ELSE MAX(dateFinReelle) END AS dateMax
							  FROM taches 
							  WHERE codeProjet = ?");
	$reqDates->bindParam(1, $_GET["idProjet"]);
	$reqDates->execute();

	$resultDates = $reqDates->fetch(PDO::FETCH_ASSOC);

	$req = $db->prepare("SELECT referenceTache, libelleTache, dateDebutPrevue, tempsPrevu, dateFinPrevue, dateDebutReelle, dateFinReelle FROM taches WHERE codeProjet = ? ORDER BY referenceTache");
	$req->bindParam(1, $_GET["idProjet"]);
	$req->execute();

	$result = $req->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result), 'dates' => json_encode($resultDates)]);
} else {
	echo json_encode(['codeRetour' => 500, 'result' => 'Le paramètre passé n\'est pas valide.']);
}
?>