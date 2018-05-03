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
    </div><br/>
    <div align="center">
        <?php
        include('connect.php');
        ?>
        <font style="font-family: 'Open Sans'; font-size: 30px;"><b>Dans la cabane de Madame Irma</b><br/><br/></font>
    </div>
    <table width="100%" align="center">
        <tr>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><b>Matchs individuels</b></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="groupes.php">Toute la compétition</a></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="divers.php">Paris divers</a></font><br/><br/>
            </td>
        </tr>
    </table>
    <table width="90%" align="center" style="border-spacing: 10px;">
        <tr>
            <td width="20%" align="left">
                <font style="font-family: 'Open Sans'; font-size: 25px;">Les matchs à venir</font><br/><br/>
            </td>
        </tr>
        <?php
        $req = $bdd->query("SELECT matchs_q.id AS id_match, DATE_FORMAT(date, '%d/%m, %Hh%i') AS date, date AS dt, matchs_q.groupe, eq1.pays AS e1, eq2.pays AS e2 FROM matchs_q JOIN teams eq1 ON eq1.id = matchs_q.team1 JOIN teams eq2 ON eq2.id = matchs_q.team2 WHERE date > NOW() AND played = 0 ORDER BY date ASC");

        $i = 0;
        while ($item = $req->fetch()) {
            $pari = $bdd->prepare("SELECT score1, score2 FROM paris_match JOIN users ON users.id = paris_match.id_user WHERE id_match=:play AND users.login=:usr");
            $pari->execute(array('play' => $item['id_match'], 'usr' => $_SESSION['login']));
            $res = $pari->fetch();
            if ($i % 4 == 0) {?>
            <tr>
            <?php
            }?>
                <td width="20%" align="center" style="border: 1px solid black;"><br/>
                    <font style="font-family: 'Open Sans'; font-size: 15px;"><?php echo $item['e1'] . ' — ' . $item['e2'];?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;">-- / --</font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><?php echo $item['date'];?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><b>
                        <?php
                        if ($_SESSION['login'] == 'admin') {
                            echo 'LE MATCH N\'EST PAS ENCORE PASSÉ';
                        } else {
                            if (!$res) {
                                echo '<a href="match.php?id=' . $item['id_match'] . '">AUCUN PARI POUR L\'INSTANT</a>';
                            } else {
                                echo '<a href="match.php?id=' . $item['id_match'] . '">VOUS PRÉVOYEZ : ' . $res['score1'] . '-' . $res['score2'] . '</a>';
                            }
                        }
                        ?>
                    </b></font><br/><br/>
                </td><?php
            $i += 1;
        }
        ?>
        <tr>
            <td width="20%" align="left">
                <font style="font-family: 'Open Sans'; font-size: 25px;">Les matchs en attente du résultat</font><br/><br/>
            </td>
        </tr>
        <?php
        $req = $bdd->query("SELECT matchs_q.id AS id_match, DATE_FORMAT(date, '%d/%m, %Hh%i') AS date, matchs_q.groupe, eq1.pays AS e1, eq2.pays AS e2 FROM matchs_q JOIN teams eq1 ON eq1.id = matchs_q.team1 JOIN teams eq2 ON eq2.id = matchs_q.team2 WHERE date < NOW() AND played = 0 ORDER BY date ASC");

        $i = 0;
        while ($item = $req->fetch()) {
            $pari = $bdd->prepare("SELECT score1, score2 FROM paris_match JOIN users ON users.id = paris_match.id_user WHERE id_match=:play AND users.login=:usr");
            $pari->execute(array('play' => $item['id_match'], 'usr' => $_SESSION['login']));
            $res = $pari->fetch();
            if ($i % 4 == 0) {?>
            <tr>
            <?php
            }?>
                <td width="20%" align="center" style="border: 1px solid black;"><br/>
                    <font style="font-family: 'Open Sans'; font-size: 15px;"><?php echo $item['e1'] . ' — ' . $item['e2'];?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;">-- / --</font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><?php echo $item['date'];?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><b>
                        <?php
                        if ($_SESSION['login'] == 'admin') {
                            echo '<a href="match.php?id=' . $item['id_match'] . '">RENSEIGNER LE RÉSULTAT DU MATCH</a>';
                        } else {
                            if (!$res) {
                                echo 'TROP TARD POUR PARIER';
                            } else {
                                echo 'VOUS PRÉVOYEZ : ' . $res['score1'] . '-' . $res['score2'];
                            }
                        }
                        ?>
                    </b></font><br/><br/>
                </td><?php
            $i += 1;
        }
        ?>
        <tr>
            <td width="20%" align="left">
                <font style="font-family: 'Open Sans'; font-size: 25px;">Les matchs passés</font><br/><br/>
            </td>
        </tr>
        <?php
        $req = $bdd->query("SELECT matchs_q.id AS id_match, DATE_FORMAT(date, '%d/%m, %Hh%i') AS date, matchs_q.groupe, eq1.pays AS e1, eq2.pays AS e2, score1, score2 FROM matchs_q JOIN teams eq1 ON eq1.id = matchs_q.team1 JOIN teams eq2 ON eq2.id = matchs_q.team2 WHERE played = 1 ORDER BY date ASC");

        $i = 0;
        while ($item = $req->fetch()) {
            $pari = $bdd->prepare("SELECT score1, score2 FROM paris_match JOIN users ON users.id = paris_match.id_user WHERE id_match=:play AND users.login=:usr");
            $pari->execute(array('play' => $item['id_match'], 'usr' => $_SESSION['login']));
            $res = $pari->fetch();
            if ($i % 4 == 0) {?>
            <tr>
            <?php
            }?>
                <td width="20%" align="center" style="border: 1px solid black;"><br/>
                    <font style="font-family: 'Open Sans'; font-size: 15px;"><?php echo $item['e1'] . ' — ' . $item['e2'];?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><b><?php echo $item['score1'] . ' / ' . $item['score2'];?></b></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><?php echo $item['date'];?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;">
                        <?php
                        if (!$res) {
                            echo 'VOUS N\'AVIEZ PAS PARIÉ';
                        } else {
                            echo 'VOUS PRÉVOYIEZ : ' . $res['score1'] . '-' . $res['score2'];
                        }
                        ?>
                    </font><br/><br/>
                </td><?php
            $i += 1;
        }
        ?>
    </table>
</body>
</html>