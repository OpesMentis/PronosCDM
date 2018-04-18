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
    </div><br/>
    <div align="center">
        <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="predictions.php">Revenir à la liste des matchs</a></font><br/><br/>

        <?php
        include('connect.php');

        $msg = '';

        if (!isset($_GET['id'])) {
            echo 'Hum... Il semblerait que vous ayez touché à un truc que vous n\'auriez pas dû toucher...';
        } else {
            $req = $bdd->prepare("SELECT matchs_q.groupe AS groupe, eq1.pays AS e1, eq2.pays AS e2, DATE_FORMAT(date, '%d/%m, %Hh%i') AS date FROM matchs_q JOIN teams eq1 ON eq1.id = matchs_q.team1 JOIN teams eq2 ON eq2.id = matchs_q.team2 WHERE matchs_q.id=:id_match" AND date > NOW());
            $req->execute(array('id_match' => $_GET['id']));
            $match = $req->fetch();
            if (!$match) {
                echo 'Alors comme ça on veut jouer les hackers ?';
            } else {
                if (isset($_POST['score1']) && isset($_POST['score2'])) {
                    if (ctype_digit($_POST['score1']) && ctype_digit(($_POST['score2']))) {
                        $req = $bdd->query("SELECT id FROM users WHERE login='" . $_SESSION['login'] . "'");
                        $id_perso = $req->fetch()['id'];
                        $req = $bdd->prepare("SELECT id_pari FROM paris_q WHERE id_user=:usr AND id_match=:play");
                        $req->execute(array(
                            'usr' => $id_perso,
                            'play' => $_GET['id']
                        ));
                        $pari = $req->fetch();
                        if (!$pari && $id_perso) {
                            $req = $bdd->prepare("INSERT INTO paris_q(id_user, id_match, score1, score2) VALUES(:usr, :play, :s1, :s2)");
                            $req->execute(array(
                                'usr' => $id_perso,
                                'play' => $_GET['id'],
                                's1' => $_POST['score1'],
                                's2' => $_POST['score2']
                            ));
                            $msg = 'Votre pronostic a été pris en compte !';
                        } elseif ($id_perso) {
                            $req = $bdd->prepare("UPDATE paris_q SET score1=:s1, score2=:s2 WHERE id_user=:usr AND id_match=:play");
                            $req->execute(array(
                                's1' => $_POST['score1'],
                                's2' => $_POST['score2'],
                                'usr' => $id_perso,
                                'play' => $_GET['id']
                            ));
                            $msg = 'Votre pronostic a été pris en compte !';
                        } else {
                            $msg = 'Une erreur est survenu, veuillez vous déconnecter puis vous reconnecter.';
                        }
                    } else {
                        $msg = 'Un problème a été détecté dans les valeurs proposées.';
                    }
                }
                $pari = $bdd->prepare("SELECT score1, score2 FROM paris_q JOIN users ON users.id = paris_q.id_user WHERE id_match=:play AND users.login=:usr");
                $pari->execute(array('play' => $_GET['id'], 'usr' => $_SESSION['login']));
                $res = $pari->fetch();
                if (!$res) {
                    $s1 = '';
                    $s2 = '';
                } else {
                    $s1 = $res['score1'];
                    $s2 = $res['score2'];
                }
                ?>
                <font style="font-family: 'Open Sans'; font-size: 20px;"><?php echo 'GROUPE ' . $match['groupe'] . ' ⋅ ' . $match['date'];?><br/><br/></font>
                <font style="font-family: 'Open Sans'; font-size: 35px;"><?php echo $match['e1'] . ' — ' . $match['e2'];?><br/></font>
                <form method="post" action=<?php echo '"match.php?id=' . $_GET['id'] . '"';?>>
                    <input type="text" name="score1" size="2" value=<?php echo '"' . $s1 . '"';?>/> <input type="text" name="score2" size="2" value=<?php echo '"' . $s2 . '"';?>/><br/>
                    <input type="submit" value="Telle est mon opinion"/>
                </form><br/>
                <font style="font-family: 'Open Sans'; font-size: 20px;"><?php echo $msg;?></font>
            <?php
            }
        }
        ?>
    </div>
</body>
</html>