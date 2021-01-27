<?php session_start(); ?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Liste des réservations d'hôtels"; ?>
<?php require '../assets/partials/header-admin.php'; ?>
<?php require '../assets/partials/heading-liste-reservations.php'; ?>
<?php require '../assets/config/database.php'; ?>


<!-- LA TABLE LISTE DES RÉSERVATIONS  -->
<div class="row mt-3 mb-3">

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

    <!-- on récupére la liste des séjours -->
    <?php
    $req = $db->query('SELECT * FROM reservation_hotel');
    $hotels = $req->fetchAll();
    ?>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped border">

                <!-- header de la table -->
                <thead>
                    <tr>
                        <th scope="col">Nom du client</th>
                        <th scope="col">Prénom du client</th>
                        <th scope="col">Nom de l'hôtel</th>
                        <th scope="col">Date de début</th>
                        <th scope="col">Date de fin</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <!-- fin du header de la table -->

                <!-- body de la table -->
                <?php foreach ($hotels as $hotel) : ?>
                    <tbody>
                        <tr>
                            <td><?= $hotel['nom_client']; ?></td>
                            <td><?= $hotel['prenom_client']; ?></td>
                            <td><?= $hotel['nom_hotel']; ?></td>
                            <td class="date-reservation"><?= $hotel['date_debut']; ?></td>
                            <td class="date-reservation"><?= $hotel['date_fin']; ?></td>
                            <td class="action-td">
                                <a href="supprimer-reservation-hotel.php?id_reservation_hotel=<?= $hotel['id_reservation_hotel']; ?>" class="btn btn-outline-danger btn-sm ml-1 btn-table confirmModalLink"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
                <!-- fin body de la table -->

            </table>
        </div>
    </div>
</div>
<!-- FIN DE LA TABLE LISTE DES RÉSERVATIONS   -->

<!-- modal suppression -->
<?php require '../assets/partials/modal-suppression-reservation.php'; ?>
<!-- footer -->
<?php require '../assets/partials/footer-admin.php'; ?>