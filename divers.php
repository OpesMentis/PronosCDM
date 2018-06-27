<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Paris divers | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
    <style>
        #inputTable {
            border-spacing: 5px 25px;
        }

        #inputTable td:first-child {
            text-align: right;
        }

        #resultTable td {
            text-align: center;
        }

        #resultTable td:nth-child(2) {
            background-color: rgba(0,0,0,0.1);
        }
    </style>
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

        $vals = ['', '', '', ''];
        $items = ['a', 'b', 'c', 'd'];
        $msg = [' ', ' ', ' ', ' '];

        for ($i = 0; $i < 4; $i++) {
            $req = $bdd->prepare("SELECT id_pari, val FROM paris_divers WHERE id_user=:usr AND id_obj=:item");
            $req->execute(array('usr' => $id_perso, 'item' => $items[$i]));
            $pari = $req->fetch();

            if ($pari) {
                $vals[$i] = $pari['val'];
            }

            if (($_SESSION['login'] == 'admin' || strtotime('2018-06-14 15:00:00') > strtotime('now'))) {
                if (isset($_POST[$items[$i]]) && ctype_digit($_POST[$items[$i]]) && strlen($_POST[$items[$i]]) <= 5) {
                    if ($pari && $vals[$i] != $_POST[$items[$i]]) {
                        $req = $bdd->prepare("UPDATE paris_divers SET val=:value WHERE id_pari=:id");
                        $req->execute(array('value' => $_POST[$items[$i]], 'id' => $pari['id_pari']));
                        $msg[$i] = 'Votre choix a été pris en compte.';
                    } elseif (!$pari) {
                        $req = $bdd->prepare("INSERT INTO paris_divers(id_user, id_obj, val) VALUES(:usr, :item, :value)");
                        $req->execute(array('usr' => $id_perso, 'item' => $items[$i], 'value' => $_POST[$items[$i]]));
                        $msg[$i] = 'Votre choix a été pris en compte.';
                    }
                    $vals[$i] = $_POST[$items[$i]];
                } elseif (isset($_POST[$items[$i]]) && $_POST[$items[$i]] == '' && $pari) {
                    $req = $bdd->prepare("DELETE FROM paris_divers WHERE id_pari=:id");
                    $req->execute(array('id' => $pari['id_pari']));
                    $msg[$i] = 'Votre pronostic a été supprimé';
                    $vals[$i] = '';
                } elseif (isset($_POST[$items[$i]])) {
                    $msg[$i] = 'Un problème a été détecté, seules les valeurs inférieures ou égales à 99999 sont acceptées.';
                }
            }
        }

        $stats = $bdd->prepare("SELECT 
            (SELECT SUM(score1 + score2) FROM matchs) as TOT,
            (SELECT SUM(s) from (
                SELECT score1 as s from matchs WHERE team1 = :team union
                SELECT score2 as s from matchs WHERE team2 = :team
            ) as _) as BP,
            (SELECT SUM(s) from (
                SELECT score1 as s from matchs WHERE team2 = :team union
                SELECT score2 as s from matchs WHERE team1 = :team
            ) as _) as BC");
        $stats->execute(array('team' => 9)); // 9 = France
        $stats = $stats->fetch();
        ?>
        <font style="font-size: 30px;"><b><i>« Sarah Connor ? »</i></b><br/><br/></font>
    </div>
    <table width="100%" align="center">
        <tr>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><a href="predictions.php">Matchs individuels</a></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><a href="groupes.php">Toute la compétition</a></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-size: 15px;"><b>Paris divers</b></font><br/><br/>
            </td>
        </tr>
    </table>
    <form method="post" action="divers.php">
        <table width="100%" align="left" id="inputTable">
            <tr>
                <td width="50%" align="right">
                    <font style="font-size: 20px;">Nombre de buts marqués pendant la compétition</font>
                </td>
                <td>
                    <input type="text" name="a" size="5" value=<?php echo '"' . $vals[0] . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-14 15:00:00') < strtotime('now') ? 'disabled': '');?>/>
                    <i><font style="font-size: 15px;"><?php echo $msg[0];?></font></i>
                </td>
                <td width="50%" align="center" rowspan="4">
                    <input type="submit" value="Je valide"/>
                </td>
            </tr>
            <tr>
                <td>
                    <font style="font-size: 20px;">Nombre de buts marqués par la France</font>
                </td>
                <td>
                    <input type="text" name="b" size="5" value=<?php echo '"' . $vals[1] . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-14 15:00:00') < strtotime('now') ? 'disabled': '');?>/>
                    <i><font style="font-size: 15px;"><?php echo $msg[1];?></font></i>
                </td>
            </tr>
            <tr>
                <td>
                    <font style="font-size: 20px;">Nombre de buts encaissés par la France</font>
                </td>
                <td>
                    <input type="text" name="c" size="5" value=<?php echo '"' . $vals[2] . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-14 15:00:00') < strtotime('now') ? 'disabled': '');?>/>
                    <i><font style="font-size: 15px;"><?php echo $msg[2];?></font></i>
                </td>
            <tr>
                <td>
                    <font style="font-size: 20px;">Nombre de cartons pendant la compétition</font>
                </td>
                <td>
                    <input type="text" name="d" size="5" value=<?php echo '"' . $vals[3] . '" ' . ($_SESSION['login'] != 'admin' && strtotime('2018-06-14 15:00:00') < strtotime('now') ? 'disabled': '');?>/>
                    <i><font style="font-size: 15px;"><?php echo $msg[3];?></font></i>
                </td>
            </tr>
        </table>
    </form>
    <table width="90%" align = "center" border="1" id="resultTable">
        <tr>
            <td width="40%"><b>ÉDITIONS</b></td>
            <td><i>2018</i></td>
            <td><i>2014</i></td>
            <td><i>2010</i></td>
            <td><i>2006</i></td>
            <td><i>2002</i></td>
            <td><i>1998</i></td>
        </tr>
        <tr>
            <td><i>Nombre de buts marqués pendant la compétition</i></td>
            <td><i><?php echo $stats['TOT'] ?></i></td>
            <td>171</td>
            <td>143</td>
            <td>147</td>
            <td>161</td>
            <td>171</td>
        </tr>
        <tr>
            <td><i>Nombre de buts marqués par la France</i></td>
            <td><i><?php echo $stats['BP'] ?></i></td>
            <td>10</td>
            <td>1</td>
            <td>9</td>
            <td>0</td>
            <td>15</td>
        </tr>
        <tr>
            <td><i>Nombre de buts encaissés par la France</i></td>
            <td><i><?php echo $stats['BC'] ?></i></td>
            <td>3</td>
            <td>4</td>
            <td>3</td>
            <td>3</td>
            <td>2</td>
        </tr>
        <tr>
            <td><i>Nombre de cartons pendant la compétition</i></td>
            <td><i></i></td>
            <td>188</td>
            <td>270</td>
            <td>373</td>
            <td>289</td>
            <td>280</td>
        </tr>
    </table>
</body>
</html>