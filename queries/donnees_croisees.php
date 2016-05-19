<?php

include 'config.php';

if (($_SESSION["projetEnCours"]) && !is_nan($_SESSION["projetEnCours"])){
    $req = $db->prepare("SELECT libellefamille, tmp.tpsTotal FROM familleTache f,(
                            SELECT SUM(cons.tempsTotal) as tpsTotal, cons.codeFamille FROM

                                (SELECT SUM(c.tempsPassee) as tempsTotal, t.codeFamille
                                 FROM conso c, taches t
                                 WHERE c.codeTache = t.codeTache AND codeProjet = ?
                                 GROUP BY t.libelleTache) as cons
                            GROUP BY cons.codeFamille) as tmp
                        WHERE f.codeFamille = tmp.codeFamille");

    $req->bindParam(1, $_SESSION["projetEnCours"]);

} else if(isset($_SESSION["projetEnCours"]) && !is_nan($_SESSION["projetEnCours"]) && ($_GET["codeTache"]) && !is_nan($_GET["codeTache"])){
    $req = $db->prepare("SELECT SUM(tempsPassee) FROM `conso`,
                        (SELECT p.codeProjet FROM projets p, taches t
                        WHERE t.codeProjet = p.codeProjet) as proj
                        WHERE codeTache = ? AND proj.codeProjet = ?");

    $req->bindParam(1, $_GET["codeTache"]);
    $req->bindParam(2, $_SESSION["projetEnCours"]);

} else if (isset($_SESSION["projetEnCours"]) && !is_nan($_SESSION["projetEnCours"]) && ($_GET["codeTache"]) && !is_nan($_GET["codeTache"]) && isset($_GET["codeRessource"]) && !is_nan($_GET["codeRessource"])){
    $req = $db->prepare("SELECT t.referenceTache, SUM(c.tempsPassee), r.nomRessource FROM `conso` c, `taches` t, `ressources` r,
                        (SELECT p.codeProjet FROM projets p, taches t
                        WHERE t.codeProjet = p.codeProjet) as proj
                        WHERE c.codeTache = ? AND c.codeRessource = ? AND proj.codeProjet = ?");

    $req->bindParam(1, $_GET["codeTache"]);
    $req->bindParam(2, $_GET["codeRessource"]);
    $req->bindParam(2, $_SESSION["projetEnCours"]);

} else if (isset($_SESSION["projetEnCours"]) && !is_nan($_SESSION["projetEnCours"]) && !is_nan($_GET["codeTache"]) && isset($_GET["codeFamille"]) && !is_nan($_GET["codeFamille"])){
    $req = $db->prepare("SELECT t.referenceTache, SUM(c.tempsPassee), fam.nomFamille FROM conso c, taches t,
                      ( SELECT libelleFamille as nomFamille FROM familletache as f
                      INNER JOIN taches t ON f.codeFamille = t.codeFamille
                      WHERE f.codeFamille = ?) as fam,
                      (SELECT codeProjet from taches t, conso c
                       WHERE t.codeTache = c.codeTache) as proj
                      WHERE c.codeTache = ? AND proj.codeProjet = ?");

    $req->bindParam(2, $_GET["codeFamille"]);
    $req->bindParam(1, $_GET["codeTache"]);
    $req->bindParam(2, $_SESSION["projetEnCours"]);
}
