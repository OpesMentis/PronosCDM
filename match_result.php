<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
    <title>Pronostic des autres joueurs | Pronostics coupe du monde 2018</title>
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
    ?>
    <font style="font-size: 30px;"><b>Quand je me regarde, je me désole ; quand je me compare, je me console.</b><br/><br/></font>
</div>
<div align="center">
    <font style="font-size: 15px;"><a href=<?php echo "predictions.php#" . (isset($_GET['id']) ? $_GET['id'] : '') ?>>Revenir à la liste des matchs</a></font><br/><br/>

    <?php
    include('connect.php');

    if (!isset($_GET['id'])) {
        echo 'Hum... Il semblerait que vous ayez touché à un truc que vous n\'auriez pas dû toucher...';
    } else {
        $commu = $bdd->prepare("SELECT id_commu, nom FROM users JOIN commus ON users.id_commu=commus.id WHERE login=:pseudo");
        $commu->execute(array('pseudo' => $_SESSION['login']));
        $data = $commu->fetch();
        $num_commu = $data['id_commu'];
        $id_play = $_GET['id'];
        $req = $bdd->prepare("SELECT matchs.groupe AS groupe, eq1.pays AS e1, eq2.pays AS e2, matchs.score1, matchs.score2, DATE_FORMAT(date + INTERVAL '2' HOUR, '%d/%m, %Hh%i') AS date, date AS dt 
          FROM matchs JOIN teams eq1 ON eq1.id = matchs.team1 
          INNER JOIN teams eq2 ON eq2.id = matchs.team2 
          WHERE matchs.id=:id_match AND matchs.played = 1");
        $req->execute(array('id_match' => $id_play));
        $match = $req->fetch();
        if (!$match) {
            echo 'Résultat non disponible.';
        } else {
            $grp = $match['groupe'];
            if (strlen($grp) == 1) {
                $entete = 'GROUPE ' . $grp;
            } else {
                $n = (int)$grp[1];
                if ($n <= 4) {
                    $nb = (string)(2*$n-1);
                } else {
                    $nb = (string)(2*($n-4));
                }
                if ($grp[0] == 'H') {
                    $entete = 'HUITIÈME DE FINALE N°' . $nb;
                } elseif ($grp[0] == 'Q') {
                    $entete = 'QUART DE FINALE N°' . $nb;
                } elseif ($grp[0] == 'D') {
                    $entete = 'DEMI-FINALE N°' . $nb;
                } elseif ($grp == 'F0') {
                    $entete = 'FINALE';
                } elseif ($grp == 'F1') {
                    $entete = 'PETITE FINALE';
                }
            }
            ?>
            <font style="font-size: 20px;"><?php echo $entete . ' &sdot; ' . $match['date'];?><br/></font>
            <font style="font-size: 35px;"><?php echo $match['e1'] . ' &mdash; ' . $match['e2'];?></font><br/>
            <font style="font-size: 20px;"><?php echo $match['score1'] . ' &mdash; ' . $match['score2'];?><br/><br/></font>

            <table width="75%" align="center" style='border-collapse: collapse;'>
                <tr>
                    <td style='padding: 10px;'>
                        <font style="font-size: 30px;">Les membres de ma communauté</font>
                    </td>
                </tr>
                <?php
                $req = $bdd->prepare("SELECT u.login, pm.score1, pm.score2 
                  FROM paris_match AS pm
                  JOIN users AS u ON u.id = pm.id_user
                  WHERE id_match = :id_match AND u.login != 'admin' AND u.id_commu = :comm");
                $req->execute(array('id_match' => $id_play, 'comm' => $num_commu));
                while ($item = $req->fetch()) { ?>
                    <tr <?php echo ($item['login'] == $_SESSION['login'] ? 'style="border: 1px solid black;"': '')?>>
                        <td width="50%" align="center">
                            <font style="font-size: 25px;"><?php echo $item['login']?></font>
                        </td>
                        <td width="50%" align="center">
                            <font style="font-size: 25px;"><?php echo $item['score1']?> &mdash; <?php echo $item['score2']?> </font>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td style='padding: 10px;'>
                        <font style="font-size: 30px;">Les <i>autres</i></font>
                    </td>
                </tr>
                <?php
                $req = $bdd->prepare("SELECT u.login, pm.score1, pm.score2 
                  FROM paris_match AS pm
                  JOIN users AS u ON u.id = pm.id_user
                  WHERE id_match = :id_match AND u.login != 'admin' AND u.id_commu != :comm");
                $req->execute(array('id_match' => $id_play, 'comm' => $num_commu));
                while ($item = $req->fetch()) { ?>
                    <tr <?php echo ($item['login'] == $_SESSION['login'] ? 'style="border: 1px solid black;"': '')?>>
                        <td width="50%" align="center">
                            <font style="font-size: 25px;"><?php echo $item['login']?></font>
                        </td>
                        <td width="50%" align="center">
                            <font style="font-size: 25px;"><?php echo $item['score1']?> &mdash; <?php echo $item['score2']?> </font>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <?php
        }
    }
    ?>
</div>
</body>
</html>