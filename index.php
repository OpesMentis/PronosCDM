<?php
session_start();
if (isset($_SESSION['login'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<html>
<head>
<title>Accueil | Pronostics coupe du monde 2018</title>
    <link href='https://fonts.googleapis.com/css?family=Mina'
    rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans'
    rel='stylesheet'>
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>

<body>
    <div align="center">
        <font style="font-family: 'Mina'; font-size: 40px;"><b>PRONOSTICS COUPE DU MONDE 2018</b></font>
    </div><br/><br/>
    <table align="center" border="0">
        <tr>
            <td width="300">
                <font style="font-size: 20px;">
                    <font style="font-size: 30px;"><b>Connexion</b></font><br/><br/>
                    <form method="post" action="index.php">
                        Nom d'utilisateur<br/><input type="text" name="pseudo" <?php echo (isset($_POST['pseudo']) ? 'value="' . $_POST['pseudo'] . '"': '')?>/><br/><br/>
                        Mot de passe<br/><input type="password" name="mdp"/><br/><br/>
                        <input type="submit" value="Connexion"/>
                    </form>
                </font>
            </td>
        </tr>
    <?php
    include('connect.php');

    if (isset($_POST['pseudo']) and isset($_POST['mdp'])) {
        $req = $bdd->prepare("SELECT login, mdp FROM users WHERE LOWER(login)=LOWER(:pseudo)");
        $req->execute(array(
            'pseudo' => $_POST['pseudo']
        ));
        $data = $req->fetch();
        if (!$data) {?>
            <tr>
                <td align="center">
                    <font style="font-size: 15px;">Nom d'utilisateur inconnu</font>
                </td>
            </tr>
            <?php
        } else {
            if (password_verify($_POST['mdp'], $data['mdp'])) {?>
                <tr>
                    <td align="center">
                        <font style="font-size: 15px;">Connexion réussie !<br/>Redirection en cours</font><?php
                        $_SESSION['login'] = $data['login'];
                        header('Location: dashboard.php');
                        exit();
                        ?>
                    </td>
                </tr>
                <?php
            } else {?>
                <tr>
                    <td align="center">
                        <font style="font-size: 15px;">Mot de passe erroné</font>
                    </td>
                </tr>
                <?php
            }
        }
    }
    ?>
        <tr>
            <td align="center">
                <font style="font-size: 20px;">
                    <br/><br/><br/>
                    <a href="inscription.php">Pas encore inscrit ?</a>
                </font>
            </td>
        </tr>
    </table>
</body>
</html>