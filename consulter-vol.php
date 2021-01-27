<?php
session_start();
if (empty($_GET['id_vol'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le vol que vous voulez consulter !";
    header('location:index.php');
    exit();
} else {
    require_once 'assets/config/database.php';
    $req = $db->prepare('SELECT * FROM vols WHERE id_vol = ?');
    $req->execute([$_GET['id_vol']]);
    $vol = $req->fetchObject();
    if (!$vol) {
        $_SESSION['flash']['danger'] = "Aucun vol ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>
<?php $title = "Consulter un vol"; ?>
<?php require 'assets/partials/header.php'; ?>

<!-- message flash -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="row pl-2 pr-2">
        <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
            <div class="col-12">
                <div class="alert alert-<?= $type; ?> alert-dismissible fade show mt-1" role="alert">
                    <span class="span-alert"><?= $message; ?></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    </div>
<?php endif; ?>

<!-- Consultation d'un vol -->
<div class="row mb-3 bg-light border p-2 m-2 details-offre">

    <!--header consultation vol -->
    <div class="col-12 border-bottom pb-2">
        <div class="vol-consultation-header d-flex justify-content-between align-items-center">

            <!-- icon companie de voyage -->
            <div class="card-companie-icon">
                <img class="icon-companie-vol" src="assets/img/companies/<?= $vol->companie_voyage; ?>.png" alt="companie de voyage">
            </div>

            <!-- lien voir plus -->
            <div class="card-vol-voir">
                <!-- prix du vol -->
                <a href="#" class="btn btn-outline-primary card-vol-prix mr-1 disabled" aria-disabled="true"><?= $vol->prix_vol; ?><span class="ml-1 text-uppercase">DZ</span></a>

                <!-- condition pour la réservation -->
                <?php
                $find = 0;
                if (isset($_SESSION['user'])) {
                    $q = $db->prepare("SELECT * FROM reservation_vol WHERE id_client = ? AND id_vol = ? ");
                    $q->execute(
                        [
                            $_SESSION['user']['id_client'],
                            $vol->id_vol
                        ]
                    );
                    $find = $q->rowCount();
                }
                ?>
                <?php if ($find == 0) : ?>
                    <a href="confirmer-reservation-vol.php?id_vol=<?= $vol->id_vol; ?>" class="btn btn-primary mr-1">Réserver</a>
                <?php else : ?>
                    <a href="annuler-reservation-vol.php?id_vol=<?= $vol->id_vol; ?>&id_client=<?= $_SESSION['user']['id_client']; ?>" class="btn btn-secondary mr-1 confirmModalLink" data-toggle="modal" data-target="#ConfirmDeleteModal">Annuler la réservation</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--header consultation vol -->

    <!-- consultation vol départ -->
    <div class="col-lg-6 col-md-12 mt-4">
        <!-- ville de départ -->
        <h6>Ville de départ</h6>
        <p class="ville-depart-consultation"><i class="fas fa-city mr-2"></i><?= $vol->ville_depart; ?></p>

        <!-- date de départ -->
        <h6>Date de départ</h6>
        <p class="ville-depart-consultation"><i class="far fa-calendar-alt mr-2"></i><?= $vol->date_depart; ?></p>

        <!-- heure de départ -->
        <h6>Heure de départ</h6>
        <p class="heure-depart-consultation"><i class="far fa-clock mr-2"></i><?= $vol->heure_depart; ?></p>

        <!-- aéroport de départ -->
        <h6>Aéroport de départ</h6>
        <p class="aeroport-depart-consultation"><i class="fas fa-map-pin mr-2"></i><?= $vol->aeroport_depart; ?></p>

        <!-- companie du voyage -->
        <h6>Companie du voyage</h6>
        <p class="companie-consultation"><i class="fas fa-plane mr-2"></i><?= $vol->companie_voyage; ?></p>

        <!-- Prix du vol -->
        <h6>Prix du vol</h6>
        <p class="prix-vol-consultation"><i class="fas fa-coins mr-2"></i><?= $vol->prix_vol; ?><span class="dz-vol-consultation text-uppercase ml-1">DZ</span></p>
    </div>
    <!-- fin consultation vol départ -->

    <!-- consultation vol arrivée -->

    <div class="col-lg-6 col-md-12 mt-4">
        <!-- ville d'arrivée -->
        <h6>Ville d'arrivée</h6>
        <p class="ville-arrivee-consultation"><i class="fas fa-city mr-2"></i><?= $vol->ville_arrivee; ?></p>

        <!-- date d'arrivée -->
        <h6>Date d'arrivée</h6>
        <p class="ville-arrivee-consultation"><i class="far fa-calendar-alt mr-2"></i><?= $vol->date_arrivee; ?></p>

        <!-- heure d'arrivée -->
        <h6>Heure d'arrivée</h6>
        <p class="heure-arrivee-consultation"><i class="far fa-clock mr-2"></i><?= $vol->heure_arrivee; ?></p>

        <!-- aéroport d'arrivée -->
        <h6>Aéroport d'arrivée</h6>
        <p class="aeroport-arrivee-consultation"><i class="fas fa-map-pin mr-2"></i><?= $vol->aeroport_arrivee; ?></p>

        <!-- type du vol -->
        <h6>Type du vol</h6>
        <p class="type-vol-consultation"><i class="fas fa-route mr-2"></i><?= $vol->type_vol; ?></p>

        <!-- classe du vol -->
        <h6>Classe du vol</h6>
        <p class="classe-vol-consultation"><i class="fas fa-chair mr-2"></i><?= $vol->classe_voyage; ?></p>
    </div>
    <!-- fin consultation vol arrivée -->
</div>
<!-- fin Consultation d'un vol -->

<!-- modal annulation -->
<?php require 'assets/partials/modal-annulation-reservation.php'; ?>
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>