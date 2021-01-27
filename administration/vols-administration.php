<?php session_start(); ?>
<?php $title = "Vols"; ?>
<?php require '../assets/partials/header-admin.php'; ?>
<?php require '../assets/config/database.php'; ?>


<!-- LISTE DES OFFRES DE VOL -->
<div class="row">

    <!-- TITRE DE PRESENTATION PAGE VOLS -->
    <div class="col-12">
        <h4 class="text-uppercase">VOLS</h4>
    </div>
    <!--FIN DU TITRE DE PRESENTATION PAGE VOLS -->

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

    <!-- recupérer les informations sur les vols -->
    <?php
    $req =  $db->query('SELECT * FROM vols');
    $vols = $req->fetchAll();
    ?>
    <?php foreach ($vols as $vol) : ?>
        <div class="col-12 mt-4">
            <div class="card">

                <!-- header card vol -->
                <div class="card-header d-flex justify-content-between align-items-center">

                    <!-- icon companie de voyage -->
                    <div class="card-companie-icon">
                        <img class="icon-companie-vol" src="../assets/img/companies/<?= $vol['companie_voyage']; ?>.png" alt="companie de voyage">
                    </div>

                    <!-- lien voir plus -->
                    <div class="card-vol-voir">
                        <!-- prix du vol -->
                        <a href="#" class="btn btn-outline-primary card-btn card-vol-prix mr-1 disabled" aria-disabled="true"><?= $vol['prix_vol']; ?><span class="ml-1 text-uppercase">DZ</span></a>
                        <a href="consulter-vol.php?id_vol=<?= $vol['id_vol']; ?>" class="btn btn-primary card-btn">Voir Plus...</a>
                        <a href="modifier-vol.php?id_vol=<?= $vol['id_vol']; ?>" class="btn btn-light card-btn d-none d-md-inline-block"><i class="far fa-edit"></i></a>
                        <a href="supprimer-vol.php?id_vol=<?= $vol['id_vol']; ?>" class="btn btn-light card-btn d-none d-md-inline-block confirmModalLink" data-toggle="modal" data-target="#ConfirmDeleteModal"><i class="far fa-trash-alt"></i></a>
                    </div>

                </div>
                <!-- fin du header card vol -->

                <!-- body card vol -->
                <div class="card-body pb-0">

                    <!-- départ -->
                    <div class="row">
                        <!-- ville de départ -->
                        <div class="col-lg-3">
                            <h6 class="card-vol-ville text-uppercase"><i class="fas fa-plane-departure mr-2"></i><?= $vol['ville_depart']; ?></h6>
                        </div>

                        <!-- date de départ -->
                        <div class="col-lg-4">
                            <span class="card-vol-date mr-1"><i class="far fa-calendar-alt mr-1"></i><?= $vol['date_depart']; ?></span>
                            <span class="card-vol-time"><i class="far fa-clock mr-1"></i><?= $vol['heure_depart']; ?></span>
                        </div>

                        <!-- aeroport de départ -->
                        <div class="col-lg-5">
                            <span><i class="fas fa-map-pin mr-2"></i><?= $vol['aeroport_depart']; ?></span>
                        </div>

                    </div>
                    <!-- fin de départ -->


                    <!-- arrivée -->
                    <div class="row mt-3">
                        <!-- ville d'arrivée -->
                        <div class="col-lg-3">
                            <h6 class="card-vol-ville text-uppercase"><i class="fas fa-plane-arrival mr-2"></i><?= $vol['ville_arrivee']; ?></h6>
                        </div>

                        <!-- date d'arrivée -->
                        <div class="col-lg-4">
                            <span class="card-vol-date mr-1"><i class="far fa-calendar-alt mr-1"></i><?= $vol['date_arrivee']; ?></span>
                            <span class="card-vol-time"><i class="far fa-clock mr-1"></i><?= $vol['heure_arrivee']; ?></span>
                        </div>

                        <!-- aeroport d'arrivée -->
                        <div class="col-lg-5">
                            <span><i class="fas fa-map-pin mr-2"></i><?= $vol['aeroport_arrivee']; ?></span>
                        </div>
                    </div>
                    <!-- fin d'arrivée -->

                    <!-- classe du vol -->
                    <div class="row mt-2">
                        <div class="col-12">
                            <p class="text-muted text-capitalize">Classe<span class="card-span-classe ml-1"><?= $vol['classe_voyage']; ?></span></p>
                        </div>
                    </div>
                </div>
                <!-- fin body card vol -->
            </div>
        </div>
    <?php endforeach; ?>

</div>
<!-- FIN LISTE DES OFFRES DE VOL -->

<!-- modal suppression -->
<?php require '../assets/partials/modal-suppression.php'; ?>
<!-- footer -->
<?php require '../assets/partials/footer-admin.php'; ?>