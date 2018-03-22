<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Les matchs | Pronostics coupe du monde 2018</title>
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

            $req = $bdd->query("SELECT poules.id AS id_match, DATE_FORMAT(date, '%d/%m, %Hh%i') AS date, poules.groupe, eq1.pays AS e1, eq2.pays AS e2 FROM poules JOIN teams eq1 ON eq1.id = poules.team1 JOIN teams eq2 ON eq2.id = poules.team2 WHERE date > NOW() ORDER BY date ASC");
            ?>
            <font style="font-family: 'Open Sans'; font-size: 30px;"><b>Dans la cabane de Madame Irma</b><br/><br/></font>
        </div>
    <table width="100%" align="center">
        <tr>
            <td width="10%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 25px;">Les matchs à venir</font><br/><br/>
            </td>
        </tr>
        <?php
        $i = 0;
        while ($item = $req->fetch()) {
            if ($i % 4 == 0) {?>
            <tr>
            <?php
            }?>
                <td width="20%" align="center">
                    <font style="font-family: 'Open Sans'; font-size: 15px;"><?php echo $item['e1'] . ' — ' . $item['e2'];?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;">-- / --</font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><?php echo $item['date'];?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><a href=<?php echo '"poules.php?id=' . $item['id_match'] . '"';?>>PARIER</font><br/><br/><br/>
                </td><?php
            if ($i % 4 == 2) {?>
            <?php
            }
            $i += 1;
        }
        ?>
    </table>
</body>
</html>