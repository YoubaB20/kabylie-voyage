<?php session_start(); ?>
<?php $title = "Inscription"; ?>

<!-- TRAITEMENT DU FORMULAIRE D'INSCRIPTION -->
<?php
if (isset($_POST['singIn'])) {

    // si les champs ne sont pas vides
    if (!empty($_POST['civilite_client']) && !empty($_POST['nom_client']) && !empty($_POST['prenom_client']) && !empty($_POST['date_naissance_client']) && !empty($_POST['email_client']) && !empty($_POST['motdepasse_client']) && !empty($_POST['confirm_motdepasse_client'])) {
        $civilite_client = htmlspecialchars($_POST['civilite_client']);
        $nom_client = htmlspecialchars($_POST['nom_client']);
        $prenom_client = htmlspecialchars($_POST['prenom_client']);
        $date_naissance_client = htmlspecialchars($_POST['date_naissance_client']);
        $email_client = htmlspecialchars($_POST['email_client']);
        $motdepasse_client = htmlspecialchars($_POST['motdepasse_client']);
        $confirm_motdepasse_client = htmlspecialchars($_POST['confirm_motdepasse_client']);
        $errors = array();

        require_once('assets/config/database.php');

        // SI LE NOM N'EST PAS VALIDE
        if (!preg_match('/^[a-z ]+$/i', $nom_client)) {
            $errors[] = "Le nom saisi n'est pas valide !";
        }
        // SI LE PRENOM N'EST PAS VALIDE
        elseif (!preg_match('/^[a-z ]+$/i', $prenom_client)) {
            $errors[] = "Le prénom saisi n'est pas valide !";
        }
        // ON VÉRIFIE LA VALIDITÉ DE LA DATE DE NAISSANCE
        elseif (!preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/', $date_naissance_client)) {
            $errors[] = "Le format de la date de naissance n'est pas valide !";
        }
        // SI L'EMAIL N'EST PAS VALIDE
        elseif (!filter_var($email_client, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email saisi n'est pas valide !";
        }
        // SI LE MOT DE PASSE EST FAIBLE
        elseif (iconv_strlen($motdepasse_client, "UTF-8") < 4) {
            $errors[] = "Le mot de passe saisi est faible ! Au minimum 4 caractére !";
        }
        // SI LES DEUX MOTS DE PASSE NE CORRESPONDENT PAS
        elseif ($motdepasse_client != $confirm_motdepasse_client) {
            $errors[] = "Le mot de passe de confirmation ne correspond pas !";
        }

        // SI TOUT EST BON
        elseif (empty($errors)) {
            // ON SECURISE LE MOT DE PASSE
            $motdepasse_client = password_hash($_POST['motdepasse_client'], PASSWORD_BCRYPT);
            // ON ENREGISTRE LES DONNEES DANS LA BASE DE DONNEES
            $req = $db->prepare(" INSERT INTO clients(civilite_client, nom_client, prenom_client, date_naissance_client, email_client, motdepasse_client) VALUES (?, ?, ?, ?, ?, ?) ");
            $req->execute([
                $civilite_client,
                $nom_client,
                $prenom_client,
                $date_naissance_client,
                $email_client,
                $motdepasse_client
            ]);

            $_SESSION['flash']['success'] = "Votre compte a bien été crée ! \r\n Vous pouver maintenant vous connecter avec ce compte";
            header('location: connexion.php');
            exit();
        }
    } else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>

<?php require 'assets/partials/header.php'; ?>
<?php require 'assets/config/database.php'; ?>


<!-- LE FORMULAIRE D'INSCRIPTION -->
<div class="sing-in-form mt-3 mb-3">

    <!-- TITRE FORMULAIRE D'INSCRIPTION -->
    <h3 class="mb-3 text-center">Créer votre compte client</h3>

    <!-- FORMULAIRE D'INSCRIPTION -->
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


            <!-- civilité du client -->
            <div class="col-12">
                <div class="form-group">
                    <label for="civilite_client">Civilité *</label>
                    <select id="civilite_client" name="civilite_client" class="form-control">

                        <!-- SI UNE CIVILITÉ A ÉTAIT SELECTIONÉE ON L'AFFICHE DIRECTEMENT -->
                        <?php if (isset($_POST['civilite_client'])) : ?>
                            <option value="<?= $_POST['civilite_client']; ?>" selected><?= $_POST['civilite_client']; ?></option>
                        <?php endif; ?>

                        <option value="M">M</option>
                        <option value="Mme">Mme</option>
                    </select>
                </div>
            </div>

            <!-- nom du client -->
            <div class="col-12">
                <div class="form-group">
                    <label for="nom_client">Nom *</label>
                    <input type="text" class="form-control" id="nom_client" name="nom_client" value="<?php get_input_posted('nom_client'); ?>" placeholder="Saisir votre nom..." required>
                </div>
            </div>

            <!-- prénom du client -->
            <div class="col-12">
                <div class="form-group">
                    <label for="prenom_client">Prénom *</label>
                    <input type="text" class="form-control" id="prenom_client" name="prenom_client" value="<?php get_input_posted('prenom_client'); ?>" placeholder="Saisir votre prénom..." required>
                </div>
            </div>

            <!-- date de naissance du client-->
            <div class="col-12">
                <div class="form-group">
                    <label for="date_depart">Date de naissance *</label>
                    <input type="text" class="form-control datepicker" id="date_naissance_client" name="date_naissance_client" data-date-format="yyyy/mm/dd" value="<?php get_input_posted('date_naissance_client'); ?>" placeholder="Sélectionner la date de naissance" required>
                </div>
            </div>

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

            <!-- confirmation du mot de passe du client -->
            <div class="col-12">
                <div class="form-group">
                    <label for="confirm_motdepasse_client">Confirmation mot de passe *</label>
                    <input type="password" class="form-control" id="confirm_motdepasse_client" name="confirm_motdepasse_client" placeholder="Confirmer votre mot de passe..." required>
                </div>
            </div>

            <!--boutton du formulaire  -->
            <div class="col-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Valider" name="singIn">
            </div>

            <!-- lien vers incription -->
            <div class="col-12 mt-3">
                <p>Vous disposez d'un compte ? <a href="connexion.php">Connectez-vous...</a></p>
            </div>
        </div>

    </form>
</div>
<!-- FIN DU FORMULAIRE D'INSCRIPTION -->
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>