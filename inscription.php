<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<html>
<head>
<title>Inscription | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <div align="left">
        <font style="font-family: 'Mina'; font-size: 20px;"><b><a href="index.php"><b>Pronostics CDM 2018</b></a></font>
    </div><br/><br/>
    <table border="0" align="center">
        <tr>
            <td width="300">
                <font style="font-family: 'Open Sans'; font-size: 20px;">
                    <font style="font-size: 30px;"><b>Inscription</b></font><br/><br/>
                    <form method="post" action="inscription.php">
                        Pseudo<br/><input type="text" name="pseudo"
                        <?php
                        if (isset($_POST['pseudo'])) {
                            echo 'value="' . $_POST['pseudo'] . '" ';
                        }
                        ?>/><br/><br/>
                        Mot de passe<br/><input type="password" name="mdp"/><br/><br/>
                        Confirmation<br/><input type="password" name="mdp2"/><br/><br/>
                        <input type="submit" value="C'est parti !"/>
                    </form>
                </font>
            </td>
        </tr>
    </table>
    <?php
    include('connect.php');

    if (isset($_POST['pseudo']) and isset($_POST['mdp'])) {
        if (!ctype_alnum($_POST['pseudo'])) {?>
            <center><font style="font-size: 20px;">Seuls les caractères alphanumériques sont autorisés dans le nom d'utilisateur !</font></center>
            <?php
        } elseif ($_POST['mdp'] != $_POST['mdp2']) {?>
            <center><font style="font-size: 20px;">Les deux mots de passe diffèrent !</font></center>
            <?php
        } elseif (strlen($_POST['mdp']) < 8) {?>
            <center><font style="font-size: 20px;">Le mot de passe doit faire 8 caractères minimum !</font></center>
            <?php
        } else {
            $req = $bdd->prepare("SELECT COUNT(*) AS cnt FROM `users` WHERE login=:pseudo");
            $req->execute(array(
                'pseudo' => $_POST['pseudo']
            ));
            $num = $req->fetch();
            if ($num['cnt'] != '0') {?>
                <center><font style="font-size: 20px;">Nom d'utilisateur déjà pris !</font></center>
                <?php
            } else {
                $req = $bdd->prepare("INSERT INTO users(login, mdp) VALUES(:pseudo, :mdp)");
                $req->execute(array(
                    'pseudo' => $_POST['pseudo'],
                    'mdp' => password_hash($_POST['mdp'], PASSWORD_DEFAULT)
                ));?>
                <center><font style="font-size: 20px;">Inscription réussie !<br/>Redirection en cours</font></center>
                <?php
                $_SESSION['login'] = $_POST['pseudo'];
                header('Location: dashboard.php');
                exit();
            }
        }
    }
    ?>
</body>
</html>