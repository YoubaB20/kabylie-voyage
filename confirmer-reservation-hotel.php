<?php
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion.php');
    exit;
} elseif (empty($_GET['id_hotel'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner l'hôtel que vous voulez réserver !";
    header('location:index.php');
    exit();
} else {
    require_once 'assets/config/database.php';
    $req = $db->prepare('SELECT * FROM hotels WHERE id_hotel = ? ');
    $req->execute([$_GET['id_hotel']]);
    $hotel = $req->fetchObject();
    if (!$hotel) {
        $_SESSION['flash']['danger'] = "Aucun hôtel ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>
<?php $title = "Confirmer la réservation de l'hôtel"; ?>
<!-- TRAITEMENT DU FORMULAIRE DE RÉSERVATION -->
<?php
if (isset($_POST['confirmReshot'])) {
    if (!empty($_POST['date_debut']) && !empty($_POST['date_fin']) && !empty($_POST['id_client']) && !empty($_POST['nom_client']) && !empty($_POST['prenom_client']) && !empty($_POST['id_hotel']) && !empty($_POST['nom_hotel'])) {
        $date_debut = htmlspecialchars($_POST['date_debut']);
        $date_fin = htmlspecialchars($_POST['date_fin']);
        $id_client = htmlspecialchars($_POST['id_client']);
        $nom_client = htmlspecialchars($_POST['nom_client']);
        $prenom_client = htmlspecialchars($_POST['prenom_client']);
        $id_hotel = htmlspecialchars($_POST['id_hotel']);
        $nom_hotel = htmlspecialchars($_POST['nom_hotel']);
        // Variables pour calculer la différence entre les deux dates
        $format_date_debut = strtotime($date_debut);
        $format_date_fin = strtotime($date_fin);
        $diff_date = $format_date_fin - $format_date_debut;
        $errors = array();

        // ON VÉRIFIE LA VALIDITÉ DE LA DATE DE DEBUT
        if (!preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/', $date_debut)) {
            $errors[] = "Le format de la date de début n'est pas valide !";
        }

        // ON VÉRIFIE LA VALIDITÉ DE LA DATE DE FIN
        elseif (!preg_match('/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/', $date_fin)) {
            $errors[] = "Le format de la date de fin n'est pas valide !";
        }

        // ON VÉRIFIE QUE LA DATE DE DEBUT N'EST PAS SUPÉRIEUR A LA DATE DE FIN
        elseif ($diff_date < 0) {
            $errors[] = "Le date de début ne peut pas étre supérieure a la date de fin !";
        }

        // SI IL N'Y A AUCUNE ERREURE
        elseif (empty($errors)) {
            // on convertie la duree en jours
            $duree = $diff_date / 86400;

            $req = $db->prepare("INSERT INTO reservation_hotel(id_client, nom_client, prenom_client, id_hotel, nom_hotel, date_debut, date_fin, duree_reservation_hotel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $req->execute(
                [
                    $id_client,
                    $nom_client,
                    $prenom_client,
                    $id_hotel,
                    $nom_hotel,
                    $date_debut,
                    $date_fin,
                    $duree
                ]
            );
            $_SESSION['flash']['success'] = "La réservation de l'hôtel a été validée !";
            header('location:consulter-hotel.php?id_hotel=' . $hotel->id_hotel);
            exit();
        }
    }
    // SI Y'A DES CHAMPS VIDES 
    else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>

<?php require 'assets/partials/header.php'; ?>

<!-- LE FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
<div class="confirm-reserve-form mt-3 mb-3">

    <!-- TITRE FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
    <h3 class="mb-3 text-center">Confirmer la réservation de l'hôtel</h3>

    <!-- FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
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

            <!-- date de début-->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="date_debut">Du</label>
                    <input type="text" class="form-control datepicker" id="date_debut" name="date_debut" data-date-format="yyyy/mm/dd" value="<?php get_input_posted('date_debut'); ?>" placeholder="AAAA/MM/JJ" required>
                </div>
            </div>

            <!-- date de fin-->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="date_fin">Au</label>
                    <input type="text" class="form-control datepicker" id="date_fin" name="date_fin" data-date-format="yyyy/mm/dd" value="<?php get_input_posted('date_fin'); ?>" placeholder="AAAA/MM/JJ" required>
                </div>
            </div>

            <!-- civilité du client -->
            <div class="col-12">
                <div class="form-group">
                    <label for="civilite_client">Civilité</label>
                    <input type="text" readonly class="form-control" id="civilite_client" name="civilite_client" value="<?= $_SESSION['user']['civilite_client']; ?>">
                </div>
            </div>

            <!-- nom du client -->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="nom_client">Nom</label>
                    <input type="text" readonly class="form-control" id="nom_client" name="nom_client" value="<?= $_SESSION['user']['nom_client']; ?>">
                </div>
            </div>

            <!-- prénom du client -->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="prenom_client">Prénom</label>
                    <input type="text" readonly class="form-control" id="prenom_client" name="prenom_client" value="<?= $_SESSION['user']['prenom_client']; ?>">
                </div>
            </div>

            <!-- date de naissance-->
            <div class="col-12">
                <div class="form-group">
                    <label for="date_depart">Date de naissance</label>
                    <input type="text" readonly class="form-control" id="date_naissance_client" name="date_naissance_client" value="<?= $_SESSION['user']['date_naissance_client']; ?>">
                </div>
            </div>

            <!-- nom de l'hotel -->
            <div class="col-12">
                <div class="form-group">
                    <label for="nom_hotel">Nom de l'hôtel</label>
                    <input type="text" readonly class="form-control" id="nom_hotel" name="nom_hotel" value="<?= $hotel->nom_hotel; ?>">
                </div>
            </div>

            <!-- pays du séjour -->
            <div class="col-12">
                <div class="form-group">
                    <label for="pays_hotel">Pays de l'hôtel</label>
                    <input type="text" readonly class="form-control" id="pays_hotel" name="pays_hotel" value="<?= $hotel->pays_hotel; ?>">
                </div>
            </div>

            <!-- prix d'une chambre de l'hôtel -->
            <div class="col-12">
                <div class="form-group">
                    <label for="prix_hotel">Prix de la chambre</label>
                    <input type="text" readonly class="form-control" id="prix_chambre" name="prix_chambre" value="<?= $hotel->prix_chambre; ?>">
                </div>
            </div>

            <!-- les id -->
            <input type="hidden" id="id_client" name="id_client" value="<?= $_SESSION['user']['id_client']; ?>">
            <input type="hidden" id="id_hotel" name="id_hotel" value="<?= $hotel->id_hotel; ?>">

            <!--boutton du formulaire  -->
            <div class="col-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Valider" name="confirmReshot">
            </div>

        </div>

    </form>
</div>
<!-- FIN DU FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>