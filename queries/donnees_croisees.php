<?php

include 'config.php';

if (isset($_GET["famTpsTotal"])){
    $req = $db->prepare("SELECT libellefamille, tmp.tpsTotal as tmpsTotal FROM familleTache f,(
                            SELECT SUM(cons.tempsTotal) as tpsTotal, cons.codeFamille FROM
                                (SELECT SUM(c.tempsPassee) as tempsTotal, t.codeFamille
                                 FROM conso c, taches t
                                 WHERE c.codeTache = t.codeTache AND codeProjet = ?
                                 GROUP BY t.libelleTache) as cons
                            GROUP BY cons.codeFamille) as tmp
                        WHERE f.codeFamille = tmp.codeFamille");

    $req->bindParam(1, $_GET["famTpsTotal"]);
    $done = $req->execute();

    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);

} else if (isset($_GET["livTpsTotal"])) {
    $req = $db->prepare("SELECT l.libelleLivrable as liv, tmp.tpsTotal as tpsLivTotal FROM livrables l,(
                            SELECT SUM(cons.tempsTotal) as tpsTotal, cons.codeLivrable FROM

                                (SELECT SUM(c.tempsPassee) as tempsTotal, t.codeLivrable
                                 FROM conso c, taches t
                                 WHERE c.codeTache = t.codeTache AND t.codeLivrable IS NOT NULL AND codeProjet = ?
                                 GROUP BY t.libelleTache) as cons
                            GROUP BY cons.codeLivrable) as tmp
                        WHERE l.codeLivrable = tmp.codeLivrable");

    $req->bindParam(1, $_GET["livTpsTotal"]);
    $done = $req->execute();

    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else if (isset($_GET["resTpsTotal"])) {
    $req = $db->prepare("SELECT SUM(c.tempsPassee) as tpsResTotal, r.nomRessource as ressource FROM conso c
                        INNER JOIN ressources r ON (c.codeRessource = r.codeRessource)
                        INNER JOIN (
                            SELECT t.codeProjet, t.codeTache FROM taches t
                            WHERE t.codeProjet = ?
                        ) res ON (c.codeTache = res.codeTache)
                        GROUP BY c.codeRessource");

    $req->bindParam(1, $_GET["resTpsTotal"]);
    $done = $req->execute();

    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else if (isset($_GET["tpsTache"])) {
    $req = $db->prepare("SELECT SUM(c.tempsPassee) as TpsTache, res.referenceTache as tache FROM conso c
                        INNER JOIN
                        (
                                SELECT t.codeProjet, t.codeTache, t.codeFamille, t.referenceTache FROM taches t
                           WHERE t.codeProjet = ?
                        ) res ON (c.codeTache = res.codeTache)
                        INNER JOIN familletache f ON (res.codeFamille = f.codeFamille)
                        GROUP BY res.codeTache");

    $req->bindParam(1, $_GET["tpsTache"]);
    $done = $req->execute();

    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
} else if (isset($_GET["coutResMois"])) {
    $req = $db->prepare("SELECT (c.tempsPassee * r.tauxHoraire) as prixConso, DATE_FORMAT(c.date, '%m-%Y') as mois, r.nomRessource as nomRes FROM conso c
                        INNER JOIN ressources r ON (c.codeRessource = r.codeRessource)
                        INNER JOIN
                        (
                            SELECT t.codeTache FROM taches t
                            WHERE t.codeProjet = ?
                        ) res
                        ON (res.codeTache = c.codeTache)
                        GROUP BY mois, r.nomRessource
                        ORDER BY mois");

    $req->bindParam(1, $_GET["coutResMois"]);
    $done = $req->execute();

    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['codeRetour' => 200, 'result' => null, 'data' => json_encode($result)]);
}
