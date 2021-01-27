<?php session_start(); ?>
<?php $title = "Hôtels"; ?>
<?php require '../assets/partials/header-admin.php'; ?>
<?php require '../assets/config/database.php'; ?>

<!-- LISTE DES OFFRES DE SÉJOURS -->
<div class="row">

    <!-- TITRE DE PRESENTATION PAGE Hôtels -->
    <div class="col-12">
        <h4 class="text-uppercase">Hôtels</h4>
    </div>
    <!--FIN DU TITRE DE PRESENTATION PAGE Hôtels -->

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

    <!-- recupérer les informations sur les hotels -->
    <?php
    $req =  $db->query('SELECT * FROM hotels');
    $hotels = $req->fetchAll();
    ?>

    <!-- Carte hotels -->
    <?php foreach ($hotels as $hotel) : ?>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <div class="card rounded-lg sejour-card mt-4">
                <div class="card-price">
                    <span class="card-montant ml-1"><?= $hotel['prix_chambre']; ?></span><span class="text-uppercase ml-1">DZ</span>
                </div>
                <img src="../assets/img/offres/hotels/<?= $hotel['image_hotel']; ?>" class="card-img-top rounded-bottom-0 card-img" alt="image séjour">
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
                        <a href="consulter-hotel.php?id_hotel=<?= $hotel['id_hotel']; ?>" class="btn btn-outline-primary card-btn text-capitalize">Voir Plus..</a>
                        <a href="modifier-hotel.php?id_hotel=<?= $hotel['id_hotel']; ?>" class="btn btn-light card-btn"><i class="far fa-edit"></i></a>
                        <a href="supprimer-hotel.php?id_hotel=<?= $hotel['id_hotel']; ?>" class="btn btn-light card-btn confirmModalLink" data-toggle="modal" data-target="#ConfirmDeleteModal"><i class="far fa-trash-alt"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Fin Carte hotels -->

</div>

<!-- modal suppression -->
<?php require '../assets/partials/modal-suppression.php'; ?>
<!-- footer -->
<?php require '../assets/partials/footer-admin.php'; ?>