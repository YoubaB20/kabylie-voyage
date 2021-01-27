<?php
session_start();
if (isset($_SESSION['admin'])) {
    $_SESSION['flash']['danger'] = "Vous êtes déjà connecté avec la session admin !";
    header('location:index.php');
    exit();
}
?>

<!-- Traitement du formulaire de connexion admin-->
<?php
// ON VERIFIE SI DES DONNÉES ONT ÉTAIENT POSTÉES
if (isset($_POST['connexion'])) {
    // ON VERIFIE SI TOUT LES CHAMPS NE SONT PAS VIDES
    if (!empty($_POST['pseudo_admin']) && !empty($_POST['motdepasse_admin'])) {

        // ON EXTRAIT LES DONNEES ET ON LES ECHAPPES
        $pseudo_admin = htmlspecialchars($_POST['pseudo_admin']);
        require('../assets/config/database.php');
        $req = $db->prepare("SELECT * FROM admin where pseudo_admin = :pseudo_admin ");
        $req->execute(['pseudo_admin' => $_POST['pseudo_admin']]);

        $admin = $req->fetch();
        if (password_verify($_POST['motdepasse_admin'], $admin['motdepasse_admin'])) {
            session_start();
            $_SESSION['admin'] = $admin;
            $_SESSION['flash']['info'] = "Bienvenue sur l'interface d'administration du site";
            header('location: index.php');
            exit();
        } else {
            $errors[] = "Pseudo ou mot de passe incorrect !";
        }
    } else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexion Admin | Kabylie Voyage</title>
    <!-- LES STYLES -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- FIN LES STYLES -->
</head>

<body class="bg-light text-center">
    <div class="form-signin mt-5">

        <!-- LOGO FORMULAIRE DE CONNEXION ADMINISTRATION  -->
        <a href="../index.php">
            <img class="mb-4" src="../assets/img/Kabylie-voyage-icone2.png" alt="Loge Kabylie Voyage" width="72" height="72">
        </a>
        <!-- TITRE FORMULAIRE DE CONNEXION ADMINISTRATION -->
        <h1 class="h3 mb-3">Se connecter à l’administration</h1>

        <!-- FORMULAIRE DE CONNEXION ADMINISTRATION -->
        <form method="POST">

            <!-- message flash -->
            <?php if (isset($_SESSION['flash'])) : ?>
                <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
                    <div class="alert alert-<?= $type; ?> alert-dismissible fade show" role="alert">
                        <span class="span-alert"><?= $message; ?></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <!-- Message d'erreur du formulaire de connexion administration -->
            <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php foreach ($errors as $error) : ?>
                        <span class="span-alert"><?= $error; ?></span>
                    <?php endforeach; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>


            <!-- Champ pseudo -->
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                </div>
                <input type="text" class="form-control" placeholder="Pseudo" name="pseudo_admin" aria-label="Pseudo" aria-describedby="basic-addon1" required>
            </div>

            <!-- Champ mot de passe -->
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-lock"></i></span>
                </div>
                <input type="password" class="form-control" name="motdepasse_admin" placeholder="Mot de passe" aria-label="password" aria-describedby="basic-addon2" required>
            </div>

            <!-- Boutton de connexion -->
            <input class="btn btn-primary w-100 mt-2 connexion-admin" type="submit" name="connexion" value="Connexion">

        </form>
    </div>

    <!-- LES SCRIPTS -->
    <script src="../assets/js/jquery-3.3.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
</body>

</html>