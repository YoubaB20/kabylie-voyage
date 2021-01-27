<?php
session_start();
if (!isset($_SESSION['admin'])) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit;
} elseif (empty($_GET['id_sejour'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le séjour que vous voulez consulter !";
    header('location:index.php');
    exit();
} else {
    require_once '../assets/config/database.php';
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
<?php $title = "Consulter Séjour"; ?>
<?php require '../assets/partials/header-admin.php'; ?>


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
            <img class="img-consultation rounded shadow" src="../assets/img/offres/sejours/<?= $sejour->image_sejour; ?>">
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
            <a href="modifier-sejour.php?id_sejour=<?= $sejour->id_sejour; ?>" class="btn btn-primary btn-sm mt-1 mr-2 modifier">Modifier</a>
            <a href="supprimer-sejour.php?id_sejour=<?= $sejour->id_sejour; ?>" class="btn btn-danger btn-sm mt-1 mr-2 confirmModalLink" data-toggle="modal" data-target="#ConfirmDeleteModal">Supprimer</a>
            <a href="update-image-sejour.php?id_sejour=<?= $sejour->id_sejour; ?>" class="btn btn-secondary btn-sm mt-1 changer-image">Modifier l'image</a>
        </div>

    </div>

    <!-- a propos du séjour -->
    <div class="col-12 mt-4">
        <h6>À propos</h6>
        <p><?= $sejour->description_sejour; ?></p>
    </div>

    <!-- modal suppression -->
    <?php require '../assets/partials/modal-suppression.php'; ?>

</div>
<!-- fin Consultation d'une offre -->

<?php require '../assets/partials/footer-admin.php'; ?>