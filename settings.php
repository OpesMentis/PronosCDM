<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: index.php');
    exit();
}
?>

<html>
<head>
<title>Paramètres | Pronostics coupe du monde 2018</title>
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

        $req = $bdd->prepare("SELECT id, id_commu FROM users WHERE login=:pseudo");
        $req->execute(array('pseudo' => $_SESSION['login']));
        $data = $req->fetch();
        $id_perso = $data['id'];
        $num_com = $data['id_commu'];

        $msg = '';

        if (isset($_POST['new_commu'])) {
            $req = $bdd->prepare("SELECT id FROM commus WHERE nom=:name");
            $req->execute(array('name' => $_POST['new_commu']));
            if ($req->fetch()) {
                $msg = 'Une communauté porte déjà ce nom.';
            } else {
                $req = $bdd->prepare("INSERT INTO commus(nom) VALUES(:name)");
                $req->execute(array('name' => $_POST['new_commu']));
                $req = $bdd->prepare("SELECT id FROM commus WHERE nom=:name");
                $req->execute(array('name' => $_POST['new_commu']));

                $num_com = $req->fetch()['id'];

                $req = $bdd->prepare("UPDATE users SET id_commu=:id_c WHERE id=:id_u");
                $req->execute(array('id_c' => $num_com, 'id_u' => $id_perso));
            }
        } elseif (isset($_POST['set_commu'])) {
            if ($_POST['set_commu'] != 0) {
                $req = $bdd->prepare("SELECT id, nom FROM commus WHERE id=:id_c");
                $req->execute(array('id_c' => $_POST['set_commu']));
            
                $com = $req->fetch();

                if (!$com) {
                    $msg = 'Une erreur a été rencontré.';
                } else {
                    $num_com = $com['id'];
                    $req = $bdd->prepare("UPDATE users SET id_commu=:id_c WHERE id=:id_u");
                    $req->execute(array('id_c' => $num_com, 'id_u' => $id_perso));
                }
            } else {
                $req = $bdd->prepare("UPDATE users SET id_commu=0 WHERE id=:id_u");
                $req->execute(array('id_u' => $id_perso));
                $num_com = 0;
            }
        }
        ?>
        <font style="font-size: 30px;"><b>L'arrière-boutique</b><br/><br/></font>
    </div>
    <table width="90%" align="center" style="border-spacing: 10px;">
        <tr>
            <td width="100%" align="left">
                <font style="font-size: 25px;">Rejoindre une communauté</font><br/>
                <font style="font-size: 15px;">En vous rassemblant avec vos proches au sein d'une même communauté, vous apparaîtrez ensemble dans un classement spécifique où il n'y aura que vous. Vous apparaîtrez par ailleurs toujours dans le classement général.</font><br/><br/>
                <?php
                if ($num_com == 0) {?>
                    <font style="font-size: 15px;">Vous n'appartenez à aucune communauté. Vous pouvez mettre un terme à cet état de fait en créant une communauté ou en en sélectionnant une ci-dessous.</font><br/><br/>
                    <?php
                } else {
                    $commu = $bdd->prepare("SELECT nom FROM commus WHERE id=:id_commu");
                    $commu->execute(array('id_commu' => $num_com));
                    $nom_commu = $commu->fetch()['nom'];?>
                    <font style="font-size: 15px;">Vous êtes membre de la communauté <b><?php echo $nom_commu; ?></b>. Vous pouvez la quitter pour en rejoindre une autre ou même en créer une nouvelle grâce au menu ci-dessous. Notez qu'une communauté qui perd tous ses membres disparaît.</font><br/><br/>
                    <?php
                }
                ?>
                <form method="post" action="settings.php">
                    <input type="text" name="new_commu"/>
                    <input type="submit" value="Créer et rejoindre une communauté"/>
                </form>
                <?php echo $msg ?><br/>

                <?php
                $commus = $bdd->query("SELECT * FROM commus");
                ?>
            
                <form method="post" action="settings.php">
                    <select name="set_commu">
                        <option value=0>--</option>
                        <?php
                        while ($com = $commus->fetch()) {
                            echo '<option value=' . $com['id'] . ($com['id'] == $num_com ? ' selected': '') . '>' . $com['nom'] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" value="Changer de crémerie"/>
                </form>
            </td>
        </tr>
        <tr>
            <td width="100%" align="left">
                <font style="font-size: 25px;">Changer de mot de passe</font><br/><br/>
            </td>
        </tr>
        <tr>
            <td width="100%" align="left">
                <font style="font-size: 25px;">Supprimer mon compte</font><br/><br/>
            </td>
        </tr>
    </table>
</body>
</html>