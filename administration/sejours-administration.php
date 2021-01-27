<?php session_start(); ?>
<?php $title = "Séjours"; ?>
<?php require '../assets/partials/header-admin.php'; ?>
<?php require '../assets/config/database.php'; ?>

<!-- LISTE DES OFFRES DE SÉJOURS -->
<div class="row">

    <!-- TITRE DE PRESENTATION PAGE SÉJOURS -->
    <div class="col-12">
        <h4 class="text-uppercase">SÉJOURS</h4>
    </div>
    <!--FIN DU TITRE DE PRESENTATION PAGE SÉJOURS -->

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

    <!-- recupérer les informations sur les séjours -->
    <?php
    $req =  $db->query('SELECT * FROM sejours ORDER BY id_sejour DESC');
    $sejours = $req->fetchAll();
    ?>

    <!-- Carte sejours -->
    <?php foreach ($sejours as $sejour) : ?>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="card rounded-lg sejour-card mt-4">
                <div class="card-price">
                    <span class="card-montant ml-1"><?= $sejour['prix_sejour']; ?></span><span class="text-uppercase ml-1">DZ</span>
                </div>
                <img src="../assets/img/offres/sejours/<?= $sejour['image_sejour']; ?>" class="card-img-top rounded-bottom-0 card-img" alt="image séjour">
                <div class="card-body">
                    <h5 class="card-title text-uppercase"><?= $sejour['nom_sejour']; ?></h5>
                    <h6 class="card-country text-capitalize"><i class="fas fa-map-marker-alt position-icon mr-2"></i><?= $sejour['pays_sejour']; ?></h6>
                    <p class="card-text"><?= $sejour['description_sejour']; ?></p>
                    <div class="card-action">
                        <a href="consulter-sejour.php?id_sejour=<?= $sejour['id_sejour']; ?>" class="btn btn-outline-primary card-btn text-capitalize">Voir Plus...</a>
                        <a href="modifier-sejour.php?id_sejour=<?= $sejour['id_sejour']; ?>" class="btn btn-light card-btn"><i class="far fa-edit"></i></a>
                        <a href="supprimer-sejour.php?id_sejour=<?= $sejour['id_sejour']; ?>" class="btn btn-light card-btn confirmModalLink" data-toggle="modal" data-target="#ConfirmDeleteModal"><i class="far fa-trash-alt"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Fin Carte sejours -->
</div>
<!-- FIN DE LA LISTE DES OFFRES DE SÉJOURS -->

<!-- modal suppression -->
<?php require '../assets/partials/modal-suppression.php'; ?>
<!-- footer -->
<?php require '../assets/partials/footer-admin.php'; ?>