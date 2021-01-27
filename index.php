<?php session_start(); ?>
<?php $title = "Accueil"; ?>
<?php require 'assets/partials/header.php'; ?>
<?php require 'assets/config/database.php'; ?>

<!-- LISTE DES OFFRES DE SÉJOURS -->
<div class="row">

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

    <!-- TITRE DE PRESENTATION ACCEUIL INTERFACE UTILISATEUR  -->
    <div class="col-12">
        <div class="presentation-title text-center">
            <h3>Nos idées pour <span class="title-voyager">Voyager</span></h3>
        </div>
    </div>
    <!--FIN DU TITRE DE PRESENTATION ACCEUIL INTERFACE UTILISATEUR -->


    <!-- recupérer les informations sur les séjours -->
    <?php
    $req =  $db->query('SELECT * FROM sejours ORDER BY id_sejour DESC LIMIT 3');
    $sejours = $req->fetchAll();
    ?>

    <!-- Carte sejours -->
    <?php foreach ($sejours as $sejour) : ?>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="card rounded-lg sejour-card mt-4">
                <div class="card-price">
                    <span class="card-montant ml-1"><?= $sejour['prix_sejour']; ?></span><span class="text-uppercase ml-1">DZ</span>
                </div>
                <img src="assets/img/offres/sejours/<?= $sejour['image_sejour']; ?>" class="card-img-top rounded-bottom-0 card-img" alt="image séjour">
                <div class="card-body">
                    <h5 class="card-title text-uppercase"><?= $sejour['nom_sejour']; ?></h5>
                    <h6 class="card-country text-capitalize"><i class="fas fa-map-marker-alt position-icon mr-2"></i><?= $sejour['pays_sejour']; ?></h6>
                    <p class="card-text"><?= $sejour['description_sejour']; ?></p>
                    <div class="card-action">
                        <a href="consulter-sejour.php?id_sejour=<?= $sejour['id_sejour']; ?>" class="btn btn-outline-primary card-btn text-capitalize">Voir Plus...</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Fin Carte sejours -->

    <!-- boutton afficher tout les séjours -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="text-center mt-4 mb-4">
            <a href="sejours.php" class="btn btn-outline-primary text-uppercase btn-afficher">afficher tout les séjours<i class="fas fa-angle-right ml-2"></i></a>
        </div>
    </div>

</div>
<!-- FIN DE LA LISTE DES OFFRES DE SÉJOURS -->

<!-- LISTE DES OFFRES D'HOTELS -->
<div class="row">

    <!-- recupérer les informations sur les hotels -->
    <?php
    $req =  $db->query('SELECT * FROM hotels LIMIT 3');
    $hotels = $req->fetchAll();
    ?>

    <!-- Carte hotels -->
    <?php foreach ($hotels as $hotel) : ?>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="card rounded-lg sejour-card mt-4">
                <div class="card-price">
                    <span class="card-montant ml-1"><?= $hotel['prix_chambre']; ?></span><span class="text-uppercase ml-1">DZ</span>
                </div>
                <img src="assets/img/offres/hotels/<?= $hotel['image_hotel']; ?>" class="card-img-top rounded-bottom-0 card-img" alt="image séjour">
                <div class="card-body">
                    <h5 class="card-title text-uppercase"><?= $hotel['nom_hotel']; ?></h5>
                    <h6 class="card-notation">
                        <?php for ($i = 1; $i <= $hotel['notation_hotel']; $i++) : ?>
                            <span class="etoile text-warning">&#9733</span>
                        <?php endfor; ?>
                        <span class="notation-hotel ml-1">Hôtel <?= $hotel['notation_hotel']; ?> <?= ($hotel['notation_hotel'] == 1) ? "étoile" : "étoiles"; ?></span>
                    </h6>
                    <p class="card-text"><?= $hotel['description_hotel']; ?></p>
                    <div class="card-action">
                        <a href="consulter-hotel.php?id_hotel=<?= $hotel['id_hotel']; ?>" class="btn btn-outline-primary card-btn text-capitalize">Voir Plus...</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Fin Carte hotels -->

    <!-- boutton afficher tout les hôtels -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="text-center mt-4 mb-4">
            <a href="hotels.php" class="btn btn-outline-primary text-uppercase btn-afficher">afficher tout les hôtels<i class="fas fa-angle-right ml-2"></i></a>
        </div>
    </div>

</div>
<!-- FIN DE LA LISTE DES OFFRES D'HOTELS -->


<!-- LISTE DES OFFRES DE VOL -->
<div class="row mb-4">

    <!-- recupérer les informations sur les hotels -->
    <?php
    $req =  $db->query('SELECT * FROM vols LIMIT 3');
    $vols = $req->fetchAll();
    ?>
    <?php foreach ($vols as $vol) : ?>
        <div class="col-12 mb-4">
            <div class="card">

                <!-- header card vol -->
                <div class="card-header d-flex justify-content-between align-items-center">

                    <!-- icon companie de voyage -->
                    <div class="card-companie-icon">
                        <img class="icon-companie-vol" src="assets/img/companies/<?= $vol['companie_voyage']; ?>.png" alt="companie de voyage">
                    </div>

                    <!-- lien voir plus -->
                    <div class="card-vol-voir">
                        <!-- prix du vol -->
                        <a href="#" class="btn btn-outline-primary card-btn card-vol-prix mr-1 disabled" aria-disabled="true"><?= $vol['prix_vol']; ?><span class="ml-1 text-uppercase">DZ</span></a>
                        <a href="consulter-vol.php?id_vol=<?= $vol['id_vol']; ?>" class="btn btn-primary card-btn">Voir Plus...</a>
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

    <!-- boutton afficher tout les vols -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="text-center mt-4 mb-4">
            <a href="vols.php" class="btn btn-outline-primary text-uppercase btn-afficher">afficher tout les vols<i class="fas fa-angle-right ml-2"></i></a>
        </div>
    </div>

</div>
<!-- FIN LISTE DES OFFRES DE VOL -->

<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>