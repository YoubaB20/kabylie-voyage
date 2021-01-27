<?php
session_start();
if (empty($_GET['id_sejour'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le séjour que vous voulez consulter !";
    header('location:index.php');
    exit();
} else {
    require_once 'assets/config/database.php';
    $req = $db->prepare('SELECT * FROM sejours WHERE id_sejour = ?');
    $req->execute([$_GET['id_sejour']]);
    $sejour = $req->fetchObject();
    if (!$sejour) {
        $_SESSION['flash']['danger'] = "Aucun séjour ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>
<?php $title = "Consulter séjour"; ?>
<?php require 'assets/partials/header.php'; ?>

<!-- Consultation d'une offre -->

<div class="row mt-3 mb-3 bg-light border p-2 m-2 details-offre">

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

    <!-- image du séjour -->
    <div class="col-lg-6 col-md-12">
        <div class="img-consultation-content mt-2">
            <img class="img-consultation rounded shadow" src="assets/img/offres/sejours/<?= $sejour->image_sejour; ?>">
        </div>
    </div>

    <!-- content détail du séjour -->
    <div class="col-lg-6 col-md-12">

        <!-- les détails du séjour -->
        <div class="details-sejour">

            <!-- nom du séjour -->
            <h5 class="mt-3 mb-3 text-uppercase"><?= $sejour->nom_sejour; ?></h5>

            <!-- pays du séjour -->
            <h6>Pays</h6>
            <p class="pays-consultation"><i class="fas fa-map-marker-alt position-icon mr-2"></i><?= $sejour->pays_sejour; ?></p>

            <!-- prix du séjour -->
            <h6>Prix</h6>
            <p class="prix-consultation"><?= $sejour->prix_sejour; ?><span class="dz-span ml-1">DZ</span></p>

            <!-- durée du séjour -->
            <h6>Durée</h6>
            <p class="prix-consultation"><?= $sejour->duree_sejour; ?><span class="dz-span ml-1">JOUR(S)</span></p>

            <!-- date d'ajout du séjour -->
            <h6>Date d'ajout</h6>
            <p class="date-ajout-consultation"><?= $sejour->date_ajout; ?></span></p>
        </div>

        <!-- les bouttons action -->
        <div class="action-buttons">
            <!-- condition pour la réservation -->
            <?php
            $findsejour = 0;
            if (isset($_SESSION['user'])) {
                $q = $db->prepare("SELECT * FROM reservation_sejour WHERE id_client = ? AND id_sejour = ? ");
                $q->execute(
                    [
                        $_SESSION['user']['id_client'],
                        $sejour->id_sejour
                    ]
                );
                $findsejour = $q->rowCount();
            }
            ?>
            <?php if ($findsejour == 0) : ?>
                <a href="confirmer-reservation-sejour.php?id_sejour=<?= $sejour->id_sejour; ?>" class="btn btn-primary mr-1">Réserver</a>
            <?php else : ?>
                <a href="annuler-reservation-sejour.php?id_sejour=<?= $sejour->id_sejour; ?>&id_client=<?= $_SESSION['user']['id_client']; ?>" class="btn btn-secondary mr-1 confirmModalLink" data-toggle="modal" data-target="#ConfirmDeleteModal">Annuler la réservation</a>
            <?php endif; ?>
        </div>

    </div>

    <!-- a propos du séjour -->
    <div class="col-12 mt-4">
        <h6>À propos</h6>
        <p><?= $sejour->description_sejour; ?></p>
    </div>


</div>
<!-- fin Consultation d'une offre -->

<!-- modal annulation -->
<?php require 'assets/partials/modal-annulation-reservation.php'; ?>
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>