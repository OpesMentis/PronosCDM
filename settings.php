<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Paramètres | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div align="left">
        <font style="font-family: 'Mina'; font-size: 20px;"><a href="index.php"><b>PRONOSTICS COUPE DU MONDE 2018</b></a></font>
    </div>
    <div align="right">
        <font style="font-size: 20px;"><a href="logout.php">Déconnexion</a></font>
    </div><br/>
    <div align="center">
        <?php
        include('connect.php');

        $req = $bdd->prepare("SELECT id FROM users WHERE login=:pseudo");
        $req->execute(array('pseudo' => $_SESSION['login']));
        $id_perso = $req->fetch()['id'];
        ?>
        <font style="font-size: 30px;"><b>L'arrière-boutique</b><br/><br/></font>
    </div>
    <table width="90%" align="center" style="border-spacing: 10px;">
        <tr>
            <td width="100%" align="left">
                <font style="font-size: 25px;">Rejoindre une tribu</font><br/><br/>
                <font style="font-size: 15px;">En vous rassemblant avec vos proches au sein d'une même tribu, vous apparaîtrez ensemble dans un classement spécifique où il n'y aura que vous. Vous apparaîtrez par ailleurs toujours dans le classement général.</font>
            </td>
        </tr>
        <tr>
            <td width="100%" align="left">
                <font style="font-size: 25px;">Changer de mot de passe</font><br/><br/>
            </td>
        </tr>
        <tr>
            <td width="100%" align="left">
                <font style="font-size: 25px;">Supprimer mon compte</font><br/><br/>
            </td>
        </tr>
    </table>
</body>
</html>