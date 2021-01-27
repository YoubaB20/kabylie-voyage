<?php session_start(); ?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Liste des séjours"; ?>
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

    <!-- on récupére la liste des séjours -->
    <?php
    $req = $db->query('SELECT * FROM sejours');
    $sejours = $req->fetchAll();
    ?>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped border">

                <!-- header de la table -->
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Pays</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <!-- fin du header de la table -->

                <!-- body de la table -->
                <?php foreach ($sejours as $sejour) : ?>
                    <tbody>
                        <tr>
                            <th scope="row"><?= $sejour['id_sejour']; ?></th>
                            <td class="text-uppercase"><?= $sejour['nom_sejour']; ?></td>
                            <td class="text-capitalize"><?= $sejour['pays_sejour']; ?></td>
                            <td><?= $sejour['prix_sejour']; ?></td>
                            <td class="action-td">
                                <a href="modifier-sejour.php?id_sejour=<?= $sejour['id_sejour']; ?>" class="btn btn-outline-primary btn-sm mr-1 btn-table"><i class="far fa-edit"></i></a>
                                <a href="supprimer-sejour.php?id_sejour=<?= $sejour['id_sejour']; ?>" class="btn btn-outline-danger btn-sm ml-1 btn-table confirmModalLink"><i class="far fa-trash-alt"></i></a>
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