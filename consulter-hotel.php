<?php
session_start();
if (empty($_GET['id_hotel'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner l'hôtel que vous voulez consulter !";
    header('location:index.php');
    exit();
} else {
    require_once 'assets/config/database.php';
    $req = $db->prepare('SELECT * FROM hotels WHERE id_hotel = ?');
    $req->execute([$_GET['id_hotel']]);
    $hotel = $req->fetchObject();
    $services_hotel = explode(",", $hotel->services_hotel);
    if (!$hotel) {
        $_SESSION['flash']['danger'] = "Aucun hôtel ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>
<?php $title = "Consulter un hôtel"; ?>
<?php require 'assets/partials/header.php'; ?>

<!-- Consultation d'un hôtel -->
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

    <!-- image de l'hôtel -->
    <div class="col-lg-6 col-md-12">
        <div class="img-consultation-content mt-2">
            <img class="img-consultation rounded shadow" src="assets/img/offres/hotels/<?= $hotel->image_hotel; ?>">
        </div>
    </div>

    <!-- content détail l'hôtel -->
    <div class="col-lg-6 col-md-12">

        <!-- les détails l'hôtel -->
        <div class="details-hotel">

            <!-- nom de l'hôtel -->
            <h5 class="mt-3 mb-3 text-uppercase"><?= $hotel->nom_hotel; ?></h5>

            <!-- pays de l'hôtel -->
            <h6>Pays</h6>
            <p class="pays-consultation"><i class="fas fa-map-marker-alt position-icon mr-2"></i><?= $hotel->nom_hotel; ?></p>

            <!-- prix de l'hôtel -->
            <h6>Prix de la chambre</h6>
            <p class="prix-consultation"><?= $hotel->prix_chambre; ?><span class="dz-span ml-1">DZ</span></p>

            <!-- notation de l'hôtel -->
            <h6>Notation</h6>
            <p class="notation-consultation">
                <?php for ($i = 1; $i <= $hotel->notation_hotel; $i++) : ?>
                    <span class="etoile text-warning">&#9733</span>
                <?php endfor; ?>
            </p>

            <!-- date d'ajout de l'hôtel-->
            <h6>Date d'ajout</h6>
            <p class="date-ajout-consultation"><?= $hotel->date_ajout; ?></span></p>
        </div>

        <!-- les bouttons action -->
        <div class="action-buttons">

            <!-- condition pour la réservation -->
            <?php
            $find = 0;
            if (isset($_SESSION['user'])) {
                $q = $db->prepare("SELECT * FROM reservation_hotel WHERE id_client = ? AND id_hotel = ? ");
                $q->execute(
                    [
                        $_SESSION['user']['id_client'],
                        $hotel->id_hotel
                    ]
                );
                $find = $q->rowCount();
            }
            ?>
            <?php if ($find == 0) : ?>
                <a href="confirmer-reservation-hotel.php?id_hotel=<?= $hotel->id_hotel; ?>" class="btn btn-primary mr-1">Réserver</a>
            <?php else : ?>
                <a href="annuler-reservation-hotel.php?id_hotel=<?= $hotel->id_hotel; ?>&id_client=<?= $_SESSION['user']['id_client']; ?>" class="btn btn-secondary mr-1 confirmModalLink" data-toggle="modal" data-target="#ConfirmDeleteModal">Annuler la réservation</a>
            <?php endif; ?>
        </div>

    </div>

    <div class="col-12 mt-4">

        <!-- a propos de l'hôtel -->
        <h6>À propos</h6>
        <p><?= $hotel->description_hotel; ?></p>

        <!-- services de l'hôtel -->
        <h6>Services</h6>
        <div class="row mt-3 mb-3">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <ul class="nav flex-column">
                    <?php if (in_array("Restaurant", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-utensils mr-2"></i>Restaurant</span></li>
                    <?php endif; ?>
                    <?php if (in_array("Centre de fitness", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-dumbbell mr-2"></i>Centre de fitness</span></li>
                    <?php endif; ?>
                    <?php if (in_array("Navette aéroport", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-shuttle-van mr-2"></i>Navette aéroport</span></li>
                    <?php endif; ?>
                    <?php if (in_array("Convient aux enfants", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-baby mr-2"></i>Convient aux enfants</span></li>
                    <?php endif; ?>
                    <?php if (in_array("Wi-fi", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-wifi mr-2"></i>Wi-fi</span></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <ul class="nav flex-column">
                    <?php if (in_array("Service de chambre", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-concierge-bell mr-2"></i>Service de chambre</span></li>
                    <?php endif; ?>
                    <?php if (in_array("Piscine", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-swimmer mr-2"></i>Piscine</span></li>
                    <?php endif; ?>
                    <?php if (in_array("Bar", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-glass-martini-alt mr-2"></i>Bar</span></li>
                    <?php endif; ?>
                    <?php if (in_array("Spa", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-spa mr-2"></i>Spa</span></li>
                    <?php endif; ?>
                    <?php if (in_array("Parking", $services_hotel)) : ?>
                        <li class="mb-2"><span><i class="fas fa-parking mr-2"></i>Parking</span></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- adresse de l'hôtel -->
        <h6><i class="fas fa-map-marked mr-2"></i>Adresse</h6>
        <p><?= $hotel->adresse_hotel; ?></p>

        <!-- contact de l'hôtel -->
        <h6><i class="fas fa-phone mr-2"></i>Contact</h6>
        <p><?= $hotel->contact_hotel; ?></p>

        <!-- site web de l'hôtel -->
        <p>
            <a href="<?= $hotel->site_web_hotel; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-globe mr-2"></i>Site web</a>
        </p>

    </div>

</div>
<!-- FIN DU FORMULAIRE D'AJOUT D'OFFRE -->

<!-- modal annulation -->
<?php require 'assets/partials/modal-annulation-reservation.php'; ?>
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>