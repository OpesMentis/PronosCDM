<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Tableau de bord | Pronostics coupe du monde 2018</title>
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
        <font style="font-family: 'Open Sans'; font-size: 20px;"><a href="logout.php">Déconnexion</a></font>
    </div><br/><br/>
    <div align="center">
            <?php
            include('connect.php');

            $req = $bdd->prepare("SELECT points FROM `users` WHERE login=:pseudo");
            $req->execute(array(
                'pseudo' => $_SESSION['login']
            ));
            $data = $req->fetch();
            $pts = $data['points']

            ?>
            <font style="font-family: 'Open Sans'; font-size: 30px;"><b>Tableau de bord de <?php echo $_SESSION['login'] . '</b> <i>(' . $pts . ')</i>'?><br/><br/></font>
        </div>
    <table width="100%" align="center">
        <tr>
            <td width="33%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 20px;"><a href="ranking.php">Le classement</a></font>
            </td>
            <td width="33%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 20px;"><a href="predictions.php">Je prédis l'avenir</a></font>
            </td>
            <td width="33%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 20px;"><a href="">Mon profil</a></font>
            </td>
        </tr>
        <?php
        if ($_SESSION['login'] == 'admin') {?>
        <tr>
            <td></td>
            <td width="33%" align="center">
                <br/><br/>
                <font style="font-family: 'Open Sans'; font-size: 30px;"><a href="calcul.php">Mise à jour des matchs et des points</a></font>
            </td>
        </tr><?php
        }?>
    </table>
</body>
</html>