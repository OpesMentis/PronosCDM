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
        <font style="font-family: 'Mina'; font-size: 20px;"><a href="index.php"><b>PRONOSTICS COUPE DU MONDE 2018</b></a></font>
    </div>
    <div align="right">
        <font style="font-family: 'Open Sans'; font-size: 20px;"><a href="logout.php">Déconnexion</a></font>
    </div><br/><br/>
    <div align="center">
            <?php
            include('connect.php');

            $req = $bdd->query("SELECT login, points FROM users WHERE login!='admin' ORDER BY points DESC");
            ?>
            <font style="font-family: 'Open Sans'; font-size: 30px;"><b>Tableau d'honneur</b><br/><br/></font>
        </div>
    <table width="50%" align="center" style='border-collapse: collapse;'>
        <?php
        $i = 0;
        $pts = -1;
        while ($item = $req->fetch()) {
            if ($pts != $item['points']) {
                $i = $i + 1;
                $pts = $item['points'];
            }?>
            <tr <?php echo ($item['login'] == $_SESSION['login'] ? 'style="border: 1px solid black;"': '')?>>
                <td width="33%" align="center">
                    <font style="font-family: 'Open Sans'; font-size: 25px;"><?php echo $i . '.'?></font>
                </td>
                <td width="33%" align="center">
                    <font style="font-family: 'Open Sans'; font-size: 25px;"><?php echo $item['login']?></font>
                </td>
                <td width="33%" align="center">
                    <font style="font-family: 'Open Sans'; font-size: 25px;"><?php echo $item['points']?></font>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>