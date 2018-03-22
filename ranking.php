<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Classement | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div align="left">
        <font style="font-family: 'Mina'; font-size: 20px;"><a href="index.php"><b>Pronostics CDM 2018</b></a></font>
    </div>
    <div align="right">
        <font style="font-family: 'Open Sans'; font-size: 20px;"><a href="logout.php">Déconnexion</a></font>
    </div><br/><br/>
    <div align="center">
            <?php
            include('connect.php');

            $req = $bdd->query("SELECT login, points FROM `users` ORDER BY points DESC");
            ?>
            <font style="font-family: 'Open Sans'; font-size: 30px;"><b>Tableau d'honneur</b><br/><br/></font>
        </div>
    <table width="50%" align="center">
        <?php
        $i = 1;
        while ($item = $req->fetch()) {?>
            <tr>
                <td width="33%" align="left">
                    <font style="font-family: 'Open Sans'; font-size: 25px;"><?php echo $i . '.'?></font>
                </td>
                <td width="33%" align="left">
                    <font style="font-family: 'Open Sans'; font-size: 25px;"><?php echo $item['login']?></font>
                </td>
                <td width="33%" align="center">
                    <font style="font-family: 'Open Sans'; font-size: 25px;"><?php echo $item['points']?></font>
                </td>
                <?php
                $i = $i + 1;
                ?>
            </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>