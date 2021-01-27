<?php session_start(); ?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Ajouter Vol"; ?>
<?php
// SI DES DONNÉES ONT ETAIENT POSTÉES 
if (isset($_POST['ajoutVol'])) {

    // SI LES CHAMPS NE SONT PAS VIDES 
    if (!empty($_POST['ville_depart']) && !empty($_POST['ville_arrivee']) && !empty($_POST['date_depart']) && !empty($_POST['date_arrivee']) && !empty($_POST['heure_depart']) && !empty($_POST['heure_arrivee']) && !empty($_POST['companie_voyage']) && !empty($_POST['type_vol']) && !empty($_POST['aeroport_depart']) && !empty($_POST['aeroport_arrivee']) && !empty($_POST['classe_voyage']) && !empty($_POST['prix_vol'])) {

        // ON ÉCHAPPE LES CHAMPS SAISI PAR L'ADMINISTRATEUR
        $ville_depart = htmlspecialchars($_POST['ville_depart']);
        $ville_arrivee = htmlspecialchars($_POST['ville_arrivee']);
        $date_depart = htmlspecialchars($_POST['date_depart']);
        $date_arrivee = htmlspecialchars($_POST['date_arrivee']);
        $heure_depart = htmlspecialchars($_POST['heure_depart']);
        $heure_arrivee = htmlspecialchars($_POST['heure_arrivee']);
        $companie_voyage = htmlspecialchars($_POST['companie_voyage']);
        $type_vol = htmlspecialchars($_POST['type_vol']);
        $aeroport_depart = htmlspecialchars($_POST['aeroport_depart']);
        $aeroport_arrivee = htmlspecialchars($_POST['aeroport_arrivee']);
        $classe_voyage = htmlspecialchars($_POST['classe_voyage']);
        $prix_vol = htmlspecialchars($_POST['prix_vol']);
        // Variables pour calculer la différence entre les deux dates
        $format_date_depart = strtotime($date_depart . " " . $heure_depart);
        $format_date_arrivee = strtotime($date_arrivee . " " . $heure_arrivee);
        $diff_date = $format_date_arrivee - $format_date_depart;
        // Tableau des erreurs
        $errors = array();

        require_once('../assets/config/database.php');

        // ON VÉRIFIE LA VALIDITÉ DE LA DATE DE DÉPART
        if (!preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/', $date_depart)) {
            $errors[] = "Le format de la date de départ n'est pas valide !";
        }

        // ON VÉRIFIE LA VALIDITÉ DE LA DATE D'ARRIVÉE
        elseif (!preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/', $date_arrivee)) {
            $errors[] = "Le format de la date d'arrivée n'est pas valide !";
        }

        // ON VÉRIFIE LA VALIDITÉ DE L'HEURE DE DÉPART
        elseif (!preg_match('/^([0-1]?[0-9]|[2][0-3]):([0-5][0-9])(:[0-5][0-9])?$/', $heure_depart)) {
            $errors[] = "Le format de l'heure de départ n'est pas valide !";
        }

        // ON VÉRIFIE LA VALIDITÉ DE L'HEURE D'ARRIVÉE
        elseif (!preg_match('/^([0-1]?[0-9]|[2][0-3]):([0-5][0-9])(:[0-5][0-9])?$/', $heure_arrivee)) {
            $errors[] = "Le format de l'heure d'arrivée n'est pas valide !";
        }

        // ON VÉRIFIE QUE LA DATE DE DÉPART N'EST PAS SUPÉRIEUR A LA DATE D'ARRIVÉE
        elseif ($diff_date < 0) {
            $errors[] = "Le date de départ ne peut pas étre supérieure a la date d'arrivée !";
        }

        // ON VÉRIFIE LA VALIDITÉ DU PRIX DU VOL
        elseif (!preg_match('/^[0-9 ]+$/', $prix_vol) && !filter_var($prix_vol, FILTER_VALIDATE_INT)) {
            $errors[] = "Le prix du vol doit être des nombres seulement !";
        }

        // SI IL N'Y A AUCUNE ERREURE
        elseif (empty($errors)) {

            // ON FAIT UNE REQUETTE PRÉPARÉ A LA BDD POUR L'INSERTION DES DONNEES 
            $req = $db->prepare("INSERT INTO vols(ville_depart, ville_arrivee, date_depart, date_arrivee, heure_depart, heure_arrivee, companie_voyage, type_vol, aeroport_depart, aeroport_arrivee, classe_voyage, prix_vol) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
            $req->execute([$ville_depart, $ville_arrivee, $date_depart, $date_arrivee, $heure_depart, $heure_arrivee, $companie_voyage, $type_vol, $aeroport_depart, $aeroport_arrivee, $classe_voyage, $prix_vol]);
            $_SESSION['flash']['success'] = "Le vol a bien été ajouté !";
            header('location:liste-vols.php');
            exit();
        }
    } else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>

<?php require '../assets/partials/header-admin.php'; ?>
<?php require '../assets/partials/heading-ajout-offre.php'; ?>

<!-- LE FORMULAIRE D'AJOUT D'OFFRE -->
<div class="row mt-3 mb-3">
    <div class="col-12">
        <!-- formulaire d'ajout vol -->
        <form class="bg-light p-3 border" method="post">
            <div class="row">

                <!-- message en cas d'erreur -->
                <?php if (!empty($errors)) : ?>
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <!-- On affiche les erreurs -->
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

                <!-- ville de départ -->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="ville_depart">Ville de départ *</label>
                        <input type="text" class="form-control" id="ville_depart" name="ville_depart" value="<?php get_input_posted('ville_depart'); ?>" placeholder="Saisir la ville de départ" required>
                    </div>
                </div>

                <!-- ville d'arrivée-->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="ville_arrivee">Ville d'arrivée *</label>
                        <input type="text" class="form-control" id="ville_arrivee" name="ville_arrivee" value="<?php get_input_posted('ville_arrivee'); ?>" placeholder="Saisir la ville d'arrivée" required>
                    </div>
                </div>

                <!-- date de départ-->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="date_depart">Date de départ *</label>
                        <input type="text" class="form-control datepicker" id="date_depart" name="date_depart" data-date-format="yyyy/mm/dd" value="<?php get_input_posted('date_depart'); ?>" placeholder="AAAA/MM/JJ" required>
                    </div>
                </div>

                <!-- date d'arrivée-->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="date_arrivee">Date d'arrivée *</label>
                        <input type="text" class="form-control datepicker" id="date_arrivee" name="date_arrivee" data-date-format="yyyy/mm/dd" value="<?php get_input_posted('date_arrivee'); ?>" placeholder="AAAA/MM/JJ" required>
                    </div>
                </div>

                <!-- heure de départ-->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="heure_depart">Heure de départ *</label>
                        <input type="time" class="form-control" id="heure_depart" name="heure_depart" value="<?php get_input_posted('heure_depart'); ?>" placeholder="Saisir l'heure de départ" required>
                    </div>
                </div>

                <!-- heure d'arrivée-->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="heure_arrivee">Heure d'arrivée *</label>
                        <input type="time" class="form-control" id="heure_arrivee" name="heure_arrivee" value="<?php get_input_posted('heure_arrivee'); ?>" placeholder="Saisir l'heure d'arrivée" required>
                    </div>
                </div>

                <!-- companie de voyage -->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="companie_voyage">Companie du voyage *</label>
                        <select id="companie_voyage" name="companie_voyage" class="form-control">

                            <!-- SI UNE COMPANIE A ÉTAIT SELECTIONÉE ON L'AFFICHE DIRECTEMENT -->
                            <?php if (isset($_POST['companie_voyage'])) : ?>
                                <option value="<?= $_POST['companie_voyage']; ?>" selected><?= $_POST['companie_voyage']; ?></option>
                            <?php endif; ?>

                            <option value="Air Algerie">Air Algerie</option>
                            <option value="Air France">Air France</option>
                            <option value="Aigle Azur">Aigle Azur</option>
                            <option value="Tassili Airlines">Tassili Airlines</option>
                        </select>
                    </div>
                </div>

                <!-- type du vol -->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="type_vol">Type du vol *</label>
                        <select id="type_vol" name="type_vol" class="form-control">

                            <!-- SI UN TYPE A ÉTAIT SELECTIONÉ ON L'AFFICHE DIRECTEMENT -->
                            <?php if (isset($_POST['type_vol'])) : ?>
                                <option value="<?= $_POST['type_vol']; ?>" selected><?= $_POST['type_vol']; ?></option>
                            <?php endif; ?>

                            <option value="Vol Direct">Vol Direct</option>
                            <option value="Avec Escale">Avec Escale</option>
                        </select>
                    </div>
                </div>

                <!-- aéroport de départ -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="aeroport_depart">Aéroport de départ *</label>
                        <select id="aeroport_depart" name="aeroport_depart" class="form-control">

                            <!-- SI UN AEROPORT DE DÉPART A ÉTAIT SELECTIONÉ ON L'AFFICHE DIRECTEMENT -->
                            <?php if (isset($_POST['aeroport_depart'])) : ?>
                                <option value="<?= $_POST['aeroport_depart']; ?>" selected><?= $_POST['aeroport_depart']; ?></option>
                            <?php endif; ?>

                            <!-- on inclue la liste des aéroports -->
                            <?php require '../assets/partials/liste-aeroports.php'; ?>
                        </select>
                    </div>
                </div>

                <!-- aéroports d'arrivée-->
                <div class="col-12">
                    <div class="form-group">
                        <label for="aeroport_arrivee">Aéroport d'arrivée *</label>
                        <select id="aeroport_arrivee" name="aeroport_arrivee" class="form-control">

                            <!-- SI UN AEROPORT DE DÉPART A ÉTAIT SELECTIONÉ ON L'AFFICHE DIRECTEMENT -->
                            <?php if (isset($_POST['aeroport_arrivee'])) : ?>
                                <option value="<?= $_POST['aeroport_arrivee']; ?>" selected><?= $_POST['aeroport_arrivee']; ?></option>
                            <?php endif; ?>

                            <!-- on inclue la liste des aéroports -->
                            <?php require '../assets/partials/liste-aeroports.php'; ?>
                        </select>
                    </div>
                </div>

                <!-- classe du vol -->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="classe_voyage">Classe du vol *</label>
                        <select id="classe_voyage" name="classe_voyage" class="form-control">

                            <!-- SI UNE CLASSE A ÉTAIT SELECTIONÉE ON L'AFFICHE DIRECTEMENT -->
                            <?php if (isset($_POST['classe_voyage'])) : ?>
                                <option value="<?= $_POST['classe_voyage']; ?>" selected><?= $_POST['classe_voyage']; ?></option>
                            <?php endif; ?>

                            <option value="Économique">Économique</option>
                            <option value="Premium Économique">Premium Économique</option>
                            <option value="Affaires">Affaires</option>
                            <option value="Première">Première</option>
                        </select>
                    </div>
                </div>

                <!-- prix du vol-->
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="prix_vol">Prix du vol *</label>
                        <input type="number" class="form-control" id="prix_vol" name="prix_vol" value="<?php get_input_posted('prix_vol'); ?>" placeholder="Saisir le prix du vol" required>
                    </div>
                </div>

                <!--boutton du formulaire  -->
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Valider" name="ajoutVol">
                </div>



            </div>

        </form>
        <!--fin formulaire d'ajout vol -->

    </div>
</div>
<!-- FIN DU FORMULAIRE D'AJOUT D'OFFRE -->



<?php require '../assets/partials/footer-admin.php'; ?>