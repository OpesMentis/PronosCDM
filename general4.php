<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Résultats des quarts de finale | Pronostics coupe du monde 2018</title>
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
        <font style="font-family: 'Open Sans'; font-size: 30px;"><b>Voilà ce qui va se passer...</b><br/><br/></font>
    </div>
    <?php
    $req = $bdd->query("SELECT id FROM users WHERE login='" . $_SESSION['login'] . "'");
    $id_perso = $req->fetch()['id'];
    ?>
    <table width="100%" align="center">
        <tr>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="predictions.php">Matchs individuels</a></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><b>Toute la compétition</b></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="">Paris divers</a></font><br/>
            </td>
        </tr>
    </table>
    <table width="90%" align="center">
        <form action="general4.php" method="post">
            <tr>
            <?php
            for ($i = 0; $i < 4; $i+=1) {

                $fin4_1 = $bdd->query("SELECT id_e1, eq1.pays AS e1 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id WHERE grp='H" . (string)(2*$i+1) . "'")->fetch();
                $fin4_2 = $bdd->query("SELECT id_e1, eq1.pays AS e1 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id WHERE grp='H" . (string)(2*$i+2) . "'")->fetch();

                if (!$fin4_1) {
                    $j1 = '0';
                } else {
                    $j1 = $fin4_1['id_e1'];
                    $e_j1 = $fin4_1['e1'];
                }
                if (!$fin4_2) {
                    $j2 = '0';
                } else {
                    $j2 = $fin4_2['id_e1'];
                    $e_j2 = $fin4_2['e1'];
                }

                $slc = '';
                $msg = '';

                $prono = $bdd->prepare("SELECT id_pari, id_e1 FROM paris_0 WHERE id_user=:id AND grp=:grp");
                $prono->execute(array('id' => $id_perso, 'grp' => 'Q' . (string)($i+1)));

                if ($data = $prono->fetch()) {
                    if ($data['id_e1'] != $j1 && $data['id_e1'] != $j2) {
                        $correc = $bdd->prepare("DELETE FROM paris_0 WHERE id_pari=:id");
                        $correc->execute(array('id' => $data['id_pari']));
                    } else {
                        $slc = $data['id_e1'];
                    }
                }

                if (isset($_POST[(string)($i+1)]) && $_POST[(string)($i+1)] != '0' && $j1 != '0' && $j2 != '0') {
                    if ($_POST[(string)($i+1)] == $j1 || $_POST[(string)($i+1)] == $j2) {
                        $slc = $_POST[(string)($i+1)];
                        $msg = 'Votre choix a été pris en compte.';
                        if (!$data) {
                            $inser = $bdd->prepare("INSERT INTO `paris_0` (`id_user`, `grp`, `id_e1`) VALUES (:id, :grp, :id_eq);");
                            $inser->execute(array('id' => $id_perso, 'grp' => 'Q' . (string)($i+1), 'id_eq' => $_POST[(string)($i+1)]));
                        } else {
                            $maj = $bdd->prepare("UPDATE `paris_0` SET `id_e1` = :id_eq WHERE id_pari=:id;");
                            $maj->execute(array('id_eq' => $_POST[(string)($i+1)], 'id' => $data['id_pari']));
                        }
                    } else {
                        $msg = 'Un problème a été rencontré.';
                    }
                }

                ?>
                <td width="20%" align="center">
                    <font style="font-family: 'Open Sans'; font-size: 20px;">Quart de finale n°<?php echo ($i+1);?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 15px;">Vainqueur</font>
                    <select name=<?php echo '"' . ($i+1) . '" ' . ($j1 == '0' || $j2 == '0' ? 'disabled': '');?>>
                        <option value="0">--</option>
                        <?php
                        if ($j1 != '0') {
                            echo '<option value="' . $j1 . '"' . ($slc == $j1 ? ' selected': '') . '>' . $e_j1 . '</option>';
                        }
                        if ($j2 != '0') {
                            echo '<option value="' . $j2 . '"' . ($slc == $j2 ? ' selected': '') . '>' . $e_j2 . '</option>';
                        }
                        ?>
                    </select><br/>
                    <font style="font-family: 'Open Sans'; font-size: 10px;"><?php echo $msg;?></font>
                </td>
                <?php
            }?>
            </tr>
            <tr>
                <td width="10%" align="center">
                    <br/>
                    <input type="submit" value="Valider"/><br/><br/><br/>
                </td><br/>
            </tr>
        </form>
    </table>
    <table width="90%" align="center">
        <tr>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="general.php">La phase de groupes</a></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="general8.php">Les huitièmes de finale</b></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><b>Les quarts de finale</b></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="">Les demi-finales</a></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="">La finale</a></font><br/>
            </td>
</body>
</html>