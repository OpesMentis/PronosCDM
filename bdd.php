<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=PronosCDM;charset=utf8', 'root', 'root1*');
}
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>