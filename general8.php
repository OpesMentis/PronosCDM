<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Résultats des huitièmes de finale | Pronostics coupe du monde 2018</title>
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
    $grp = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
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
        <form action="general.php" method="post">
            <?php
            for ($i = 0; $i < 8; $i+=2) {
                if ($i == 0 || $i == 4) {
                    echo '<tr>';
                }
                $fin8_1 = $bdd->query("SELECT id_e1, eq1.pays AS e1, id_e2, eq2.pays AS e2 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id JOIN teams eq2 ON paris_0.id_e2 = eq2.id WHERE grp='" . $grp[$i] . "'")->fetch();
                $fin8_2 = $bdd->query("SELECT id_e1, eq1.pays AS e1, id_e2, eq2.pays AS e2 FROM paris_0 JOIN teams eq1 ON paris_0.id_e1 = eq1.id JOIN teams eq2 ON paris_0.id_e2 = eq2.id WHERE grp='" . $grp[$i+1] . "'")->fetch();

                if (!$fin8_1) {
                    $j11 = '0';
                    $j12 = '0';
                } else {
                    $j11 = $fin8_1['id_e1'];
                    $e_j11 = $fin8_1['e1'];
                    $j12 = $fin8_1['id_e2'];
                    $e_j12 = $fin8_1['e2'];
                }
                if (!$fin8_2) {
                    $j21 = '0';
                    $j22 = '0';
                } else {
                    $j21 = $fin8_2['id_e1'];
                    $e_j21 = $fin8_2['e1'];
                    $j22 = $fin8_2['id_e2'];
                    $e_j22 = $fin8_2['e2'];
                }
                ?>
                <td width="20%" align="center">
                    <font style="font-family: 'Open Sans'; font-size: 20px;">Huitième de finale n°<?php echo ($i+1);?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 15px;">Vainqueur</font>
                    <select name=<?php echo '"' . ($i+1) . '" ' . ($j11 == '0' || $j22 == '0' ? 'disabled': '');?>>
                        <option value="0">--</option>
                        <?php
                        if ($j11 != '0') {
                            echo '<option value="' . $j11 . '">' . $e_j11 . '</option>';
                        }
                        if ($j22 != '0') {
                            echo '<option value="' . $j22 . '">' . $e_j22 . '</option>';
                        }
                        ?>
                    </select><br/>
                </td>
                <td width="20%" align="center">
                    <font style="font-family: 'Open Sans'; font-size: 20px;">Huitième de finale n°<?php echo ($i+2);?></font><br/>
                    <font style="font-family: 'Open Sans'; font-size: 15px;">Vainqueur</font>
                    <select name=<?php echo '"' . ($i+2) . '" ' . ($j12 == '0' || $j21 == '0' ? 'disabled': '');?>>
                        <option value="0">--</option>
                        <?php
                        if ($j21 != '0') {
                            echo '<option value="' . $j21 . '">' . $e_j21 . '</option>';
                        }
                        if ($j12 != '0') {
                            echo '<option value="' . $j12 . '">' . $e_j12 . '</option>';
                        }
                        ?>
                    </select><br/>
                </td>
                <?php
                if ($i == 3 || $i == 7) {
                    echo '</tr>';
                }
            }?>
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
                <font style="font-family: 'Open Sans'; font-size: 15px;"><b>Les huitièmes de finale</b></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="">Les quarts de finale</a></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="">Les demi-finales</a></font><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="">La finale</a></font><br/>
            </td>
</body>
</html>