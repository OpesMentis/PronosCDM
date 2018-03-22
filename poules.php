<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Parier sur un match | Pronostics coupe du monde 2018</title>
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

        if (!isset($_GET['id'])) {
            echo 'Hum... Il semblerait que vous ayez touché à un truc que vous n\'auriez pas dû toucher...';
        } else {
            $req = $bdd->prepare("SELECT matchs_q.groupe AS groupe, eq1.pays AS e1, eq2.pays AS e2, DATE_FORMAT(date, '%d/%m, %Hh%i') AS date FROM matchs_q JOIN teams eq1 ON eq1.id = matchs_q.team1 JOIN teams eq2 ON eq2.id = matchs_q.team2 WHERE matchs_q.id=:id_match");
            $req->execute(array('id_match' => $_GET['id']));
            $match = $req->fetch();
            if (!$match) {
                echo 'Alors comme ça on veut jouer les hackers ?';
            } else {?>
                <font style="font-family: 'Open Sans'; font-size: 20px;"><?php echo 'GROUPE ' . $match['groupe'] . ' ⋅ ' . $match['date'];?><br/><br/></font>
                <font style="font-family: 'Open Sans'; font-size: 35px;"><?php echo $match['e1'] . ' — ' . $match['e2'];?><br/></font>
                <form method="post" action=<?php echo '"poules.php?id=' . $_GET['id'] . '"';?>>
                    <input type="text" name="score1" id="score1" size="2"/> <input type="text" name="score2" id="score2" size="2"/><br/>
                    <input type="submit" value="Telle est mon opinion"/>
                </form>
            <?php
            }
        }
        ?>
    </div>
</body>
</html>