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

        $req = $bdd->query("SELECT id FROM users WHERE login='" . $_SESSION['login'] . "'");
        $id_perso = $req->fetch()['id'];

        $vals = ['', '', '', ''];
        $items = ['1', '2', '3', '4'];

        for ($i = 1; $i <= 4; $i++) {
            $req = $bdd->prepare("SELECT id_pari, val FROM paris_divers WHERE id_user=:usr AND id_obj=:item");
            $req->execute(array('usr' => $id_perso, 'item' => $i));
            $pari = $req->fetch();

            if ($pari) {
                $vals[$i-1] = $pari['val'];
            }

            if (strtotime('2018-06-18 17:00:00') > strtotime('now') && isset($_POST[$items[$i-1]])) {
                if (ctype_digit($_POST[$items[$i-1]])) {
                    if ($pari) {
                        $req = $bdd->prepare("UPDATE paris_divers SET val=:value WHERE id_pari=:id");
                        $req->execute(array('value' => $_POST[$items[$i-1]], 'id' => $pari['id_pari']));
                    } else {
                        $req = $bdd->prepare("INSERT INTO paris_divers(id_user, id_obj, val) VALUES(:usr, :item, :value)");
                        $req->execute(array('usr' => $id_perso, 'item' => $i, 'value' => $_POST[$items[$i-1]]));
                    }
                    $vals[$i-1] = $_POST[(string)($i)];
                }
            }
        }
        ?>
        <font style="font-family: 'Open Sans'; font-size: 30px;"><b>Considérations sportives autres</b><br/><br/></font>
    </div>
    <table width="100%" align="center">
        <tr>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="predictions.php">Matchs individuels</a></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><a href="groupes.php">Toute la compétition</a></font><br/><br/>
            </td>
            <td width="20%" align="center">
                <font style="font-family: 'Open Sans'; font-size: 15px;"><b>Paris divers</b></font><br/><br/>
            </td>
        </tr>
    </table>
    <table width="100%" align="left">
        <tr>
            <form method="post" action="divers.php">
                <td width="50%" align="right">
                    <br/>
                    <font style="font-family: 'Open Sans'; font-size: 20px;">Nombre de buts marqués pendant la compétition</font>
                    <input type="text" name="1" size="5" value=<?php echo '"' . $vals[0] . '"';?>/><br/><br/>
                    <font style="font-family: 'Open Sans'; font-size: 20px;">Nombre de buts marqués par la France</font>
                    <input type="text" name="2" size="5" value=<?php echo '"' . $vals[1] . '"';?>/><br/><br/>
                    <font style="font-family: 'Open Sans'; font-size: 20px;">Nombre de buts encaissés par la France</font>
                    <input type="text" name="3" size="5" value=<?php echo '"' . $vals[2] . '"';?>/><br/><br/>
                    <font style="font-family: 'Open Sans'; font-size: 20px;">Nombre de cartons pendant la compétition</font>
                    <input type="text" name="4" size="5" value=<?php echo '"' . $vals[3] . '"';?>/><br/><br/>
                </td>
                <td width="50%" align="center">
                    <input type="submit" value="Je valide"/>
                </td>
            </form>
        </tr>
    </table>
</body>
</html>