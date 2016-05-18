<?php

include 'config.php';

if(isset($_GET["codeProjet"]) && !is_nan($_GET["codeProjet"]) && ($_GET["codeTache"]) && !is_nan($_GET["codeTache"])){
    $req = $db->prepare("SELECT SUM(tempsPassee) FROM `conso`,
                        (SELECT p.codeProjet FROM projets p, taches t
                        WHERE t.codeProjet = p.codeProjet) as proj
                        WHERE codeTache = ? AND proj.codeProjet = ?");

    $req->bindParam(1, $_GET["codeTache"]);
    $req->bindParam(2, $_GET["codeProjet"]);

} else if (isset($_GET["codeProjet"]) && !is_nan($_GET["codeProjet"]) && ($_GET["codeTache"]) && !is_nan($_GET["codeTache"]) && isset($_GET["codeRessource"]) && !is_nan($_GET["codeRessource"])){
    $req = $db->prepare("SELECT t.referenceTache, SUM(c.tempsPassee), r.nomRessource FROM `conso` c, `taches` t, `ressources` r,
                        (SELECT p.codeProjet FROM projets p, taches t
                        WHERE t.codeProjet = p.codeProjet) as proj
                        WHERE c.codeTache = ? AND c.codeRessource = ? AND proj.codeProjet = ?");

    $req->bindParam(1, $_GET["codeTache"]);
    $req->bindParam(2, $_GET["codeRessource"]);
    $req->bindParam(2, $_GET["codeProjet"]);

} else if (isset($_GET["codeProjet"]) && !is_nan($_GET["codeProjet"]) && ($_GET["codeTache"]) && !is_nan($_GET["codeTache"]) && isset($_GET["codeFamille"]) && !is_nan($_GET["codeFamille"])){
    $req = $db->prepare("SELECT t.referenceTache, SUM(c.tempsPassee), fam.nomFamille FROM conso c, taches t,
                      ( SELECT libelleFamille as nomFamille FROM familletache as f
                      INNER JOIN taches t ON f.codeFamille = t.codeFamille
                      WHERE f.codeFamille = ?) as fam,
                      (SELECT codeProjet from taches t, conso c
                       WHERE t.codeTache = c.codeTache) as proj
                      WHERE c.codeTache = ? AND proj.codeProjet = ?");

    $req->bindParam(2, $_GET["codeFamille"]);
    $req->bindParam(1, $_GET["codeTache"]);
    $req->bindParam(2, $_GET["codeProjet"]);
}
