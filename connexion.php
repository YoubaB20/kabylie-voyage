<?php
session_start();
if (isset($_SESSION['user'])) {
    $_SESSION['flash']['danger'] = "Vous êtes déjà connecté avec la session user !";
    header('location:index.php');
    exit();
}
?>
<?php $title = "Connexion"; ?>
<?php
// ON VERIFIE SI DES DONNEES ONT ETAIS POSTES
if (isset($_POST['logIn'])) {
    // ON VERIFIE SI TOUT LES CHAMPS NE SONT PAS VIDES
    if (!empty($_POST['email_client']) && !empty($_POST['motdepasse_client'])) {
        // ON EXTRAIT LES DONNES ET ON LES ECHAPPE
        $email_client = htmlspecialchars($_POST['email_client']);
        $errors = array();

        require('assets/config/database.php');
        $req = $db->prepare(" SELECT * FROM clients where email_client = :email_client ");
        $req->execute(['email_client' => $_POST['email_client']]);

        $user = $req->fetch();
        if (password_verify($_POST['motdepasse_client'], $user['motdepasse_client'])) {
            session_start();
            $_SESSION['user'] = $user;
            header('location: index.php');
        } else {
            $errors[] = "Pseudo ou mot de passe incorrect !";
        }
    } else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>

<?php require 'assets/partials/header.php'; ?>

<!-- LE FORMULAIRE DE CONNEXION -->
<div class="log-in-form mt-3 mb-3">

    <!-- TITRE FORMULAIRE DE CONNEXION -->
    <h3 class="mb-3 text-center">Connexion au compte client</h3>
    <!-- FORMULAIRE DE CONNEXION -->
    <form class="bg-light p-3 border" method="POST">
        <div class="row">
            <!-- message en cas d'erreur -->
            <?php if (!empty($errors)) : ?>
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php foreach ($errors as $error) : ?>
                            <span class="span-alert"><?= $error; ?></span>
                        <?php endforeach; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- message flash -->
            <?php if (isset($_SESSION['flash'])) : ?>
                <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
                    <div class="col-12">
                        <div class="alert alert-<?= $type; ?> alert-dismissible fade show" role="alert">
                            <span class="span-alert"><?= $message; ?></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <!-- email du client -->
            <div class="col-12">
                <div class="form-group">
                    <label for="email_client">Email *</label>
                    <input type="email" class="form-control" id="email_client" name="email_client" value="<?php get_input_posted('email_client'); ?>" placeholder="Saisir votre adresse email..." required>
                </div>
            </div>

            <!-- mot de passe du client -->
            <div class="col-12">
                <div class="form-group">
                    <label for="motdepasse_client">Mot de passe *</label>
                    <input type="password" class="form-control" id="motdepasse_client" name="motdepasse_client" placeholder="Saisir votre mot de passe..." required>
                </div>
            </div>

            <!--boutton du formulaire  -->
            <div class="col-12 mt-3">
                <input type="submit" class="btn btn-primary w-100" value="Connexion" name="logIn">
            </div>

            <!-- lien vers incription -->
            <div class="col-12 mt-3">
                <p>Vous ne disposez pas de compte ? <a href="inscription.php">Inscrivez-vous...</a></p>
            </div>

        </div>
    </form>
</div>
<!-- FIN DU FORMULAIRE DE CONNEXION -->
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>