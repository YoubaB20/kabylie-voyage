<?php session_start(); ?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Liste des clients"; ?>
<?php require '../assets/partials/header-admin.php'; ?>
<?php require '../assets/config/database.php'; ?>

<!-- LE TITRE DE PRESENTATION LISTE DES CLIENTS -->
<div class="row">
    <div class="col-12">
        <h4 class="text-upercase">LISTE DES CLIENTS</h4>
    </div>
</div>
<!-- FIN DU TITRE DE PRESENTATION LISTE DES CLIENTS -->


<!-- LA TABLE LISTE DES CLIENTS  -->
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

    <!-- on récupére la liste des clients -->
    <?php
    $req = $db->query('SELECT * FROM clients');
    $clients = $req->fetchAll();
    ?>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-striped border">

                <!-- header de la table -->
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Date de naissance</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <!-- fin du header de la table -->

                <!-- body de la table -->
                <?php foreach ($clients as $client) : ?>
                    <tbody>
                        <tr>
                            <th scope="row"><?= $client['id_client']; ?></th>
                            <td class="text-uppercase"><?= $client['nom_client']; ?></td>
                            <td class="text-capitalize"><?= $client['prenom_client']; ?></td>
                            <td><?= $client['date_naissance_client']; ?></td>
                            <td class="action-td">
                                <a href="supprimer-client.php?id_client=<?= $client['id_client']; ?>" class="btn btn-outline-danger btn-sm ml-1 btn-table confirmModalLink"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
                <!-- fin body de la table -->

            </table>
        </div>

    </div>
</div>
<!-- FIN DE LA TABLE LISTE DES CLIENTS  -->


<!-- modal suppression -->
<?php require '../assets/partials/modal-suppression-client.php'; ?>
<!-- footer -->
<?php require '../assets/partials/footer-admin.php'; ?>