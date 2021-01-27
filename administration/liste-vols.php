<?php session_start(); ?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Liste des vols"; ?>
<?php require '../assets/partials/header-admin.php'; ?>
<?php require '../assets/partials/heading-liste-offres.php'; ?>
<?php require '../assets/config/database.php'; ?>

<!-- LA TABLE LISTE DES OFFRES  -->
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

    <!-- on récupére la liste des vols -->
    <?php
    $req = $db->query('SELECT * FROM vols');
    $vols = $req->fetchAll();
    ?>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped border">

                <!-- header de la table -->
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Départ</th>
                        <th scope="col">Arrivée</th>
                        <th scope="col">Classe</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <!-- fin du header de la table -->

                <!-- body de la table -->
                <?php foreach ($vols as $vol) : ?>
                    <tbody>
                        <tr>
                            <th scope="row"><?= $vol['id_vol']; ?></th>
                            <td class="text-uppercase"><?= $vol['ville_depart']; ?></td>
                            <td class="text-uppercase"><?= $vol['ville_arrivee']; ?></td>
                            <td><?= $vol['classe_voyage']; ?></td>
                            <td><?= $vol['prix_vol']; ?></td>
                            <td class="action-td">
                                <a href="modifier-vol.php?id_vol=<?= $vol['id_vol']; ?>" class="btn btn-outline-primary btn-sm mr-1 btn-table"><i class="far fa-edit"></i></a>
                                <a href="supprimer-vol.php?id_vol=<?= $vol['id_vol']; ?>" class="btn btn-outline-danger btn-sm ml-1 btn-table confirmModalLink"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
                <!-- fin body de la table -->

            </table>
        </div>
    </div>
</div>
<!-- FIN DE LA TABLE LISTE DES OFFRES  -->


<!-- modal suppression -->
<?php require '../assets/partials/modal-suppression.php'; ?>
<!-- footer -->
<?php require '../assets/partials/footer-admin.php'; ?>