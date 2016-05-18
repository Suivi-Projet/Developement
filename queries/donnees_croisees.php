<?php

include 'config.php';

if(isset($_GET["codeTache"]) && !is_nan($_GET["codeTache"])){
    $req = $db->prepare("SELECT SUM(tempsPassee) FROM `conso` WHERE codeTache = ?");

    $req->bindParam(1, $_GET["codeTache"]);

} else if (isset($_GET["codeTache"]) && !is_nan($_GET["codeTache"]) && isset($_GET["codeRessource"]) && !is_nan($_GET["codeRessource"])){
    $req = $db->prepare("SELECT t.referenceTache, SUM(c.tempsPassee), r.nomRessource FROM `conso` c
                        RIGHT JOIN taches t ON c.codeTache = t.codeTache
                        RIGHT JOIN ressources r ON c.codeRessource = r.codeRessource
                        WHERE c.codeTache = ? AND c.codeRessource = ?");

    $req->bindParam(1, $_GET["codeTache"]);
    $req->bindParam(2, $_GET["codeRessource"]);

} else if (isset($_GET["codeTache"]) && !is_nan($_GET["codeTache"]) && isset($_GET["codeFamille"]) && !is_nan($_GET["codeFamille"])){
    $req = $db->prepare("SELECT t.referenceTache, SUM(c.tempsPassee), f.nomFamille FROM `conso` c
                        RIGHT JOIN taches t ON c.codeTache = t.codeTache
                        RIGHT JOIN familles f ON c.codeFamille = f.codeFamille
                        WHERE c.codeTache = ? AND c.codeFamille = ?");

    $req->bindParam(1, $_GET["codeTache"]);
    $req->bindParam(2, $_GET["codeFamille"]);
}
