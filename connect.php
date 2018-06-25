<?php

if (! file_exists(__DIR__ . '/config.php')) {
   echo 'You must copy config_dist.php to config.php and fill it with your information';
}

include('config.php');
try {
    $bdd = new PDO('mysql:host=' . $addr . ';dbname=' . $db_name, $db_user, $db_pw, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>