<?php
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion.php');
    exit;
} elseif (empty($_GET['id_sejour'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le séjour que vous voulez réserver !";
    header('location:index.php');
    exit();
} else {
    require_once 'assets/config/database.php';
    $req = $db->prepare('SELECT * FROM sejours WHERE id_sejour = ? ');
    $req->execute([$_GET['id_sejour']]);
    $sejour = $req->fetchObject();
    if (!$sejour) {
        $_SESSION['flash']['danger'] = "Aucun séjour ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>
<?php $title = "Confirmer la réservation du séjour"; ?>
<!-- TRAITEMENT DU FORMULAIRE DE RÉSERVATION -->
<?php
if (isset($_POST['confirmRessej'])) {
    if (!empty($_POST['id_client']) && !empty($_POST['nom_client']) && !empty($_POST['prenom_client']) && !empty($_POST['id_sejour']) && !empty($_POST['nom_sejour'])) {
        $id_client = htmlspecialchars($_POST['id_client']);
        $nom_client = htmlspecialchars($_POST['nom_client']);
        $prenom_client = htmlspecialchars($_POST['prenom_client']);
        $id_sejour = htmlspecialchars($_POST['id_sejour']);
        $nom_sejour = htmlspecialchars($_POST['nom_sejour']);
        $errors = array();

        $req = $db->prepare("INSERT INTO reservation_sejour(id_client, nom_client, prenom_client, id_sejour, nom_sejour) VALUES (?, ?, ?, ?, ?)");
        $req->execute(
            [
                $id_client,
                $nom_client,
                $prenom_client,
                $id_sejour,
                $nom_sejour
            ]
        );
        $_SESSION['flash']['success'] = "La réservation du séjour a été validée !";
        header('location:consulter-sejour.php?id_sejour=' . $sejour->id_sejour);
        exit();
    }
    // SI Y'A DES CHAMPS VIDES 
    else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>

<?php require 'assets/partials/header.php'; ?>

<!-- LE FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
<div class="confirm-reserve-form mt-3 mb-3">

    <!-- TITRE FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
    <h3 class="mb-3 text-center">Confirmer la réservation du séjour</h3>

    <!-- FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
    <form class="bg-light p-3 border" method="POST">
        <div class="row">

            <!-- message en cas d'erreur -->
            <?php if (!empty($errors)) : ?>
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php foreach ($errors as $error) : ?>
                            <span class="span-alert"><?= $error; ?></span>
                        <?php endforeach; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

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

            <!-- civilité du client -->
            <div class="col-12">
                <div class="form-group">
                    <label for="civilite_client">Civilité</label>
                    <input type="text" readonly class="form-control" id="civilite_client" name="civilite_client" value="<?= $_SESSION['user']['civilite_client']; ?>">
                </div>
            </div>

            <!-- nom du client -->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="nom_client">Nom</label>
                    <input type="text" readonly class="form-control" id="nom_client" name="nom_client" value="<?= $_SESSION['user']['nom_client']; ?>">
                </div>
            </div>

            <!-- prénom du client -->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="prenom_client">Prénom</label>
                    <input type="text" readonly class="form-control" id="prenom_client" name="prenom_client" value="<?= $_SESSION['user']['prenom_client']; ?>">
                </div>
            </div>

            <!-- date de naissance-->
            <div class="col-12">
                <div class="form-group">
                    <label for="date_depart">Date de naissance</label>
                    <input type="text" readonly class="form-control" id="date_naissance_client" name="date_naissance_client" value="<?= $_SESSION['user']['date_naissance_client']; ?>">
                </div>
            </div>

            <!-- nom du séjour -->
            <div class="col-12">
                <div class="form-group">
                    <label for="nom_sejour">Nom du séjour</label>
                    <input type="text" readonly class="form-control" id="nom_sejour" name="nom_sejour" value="<?= $sejour->nom_sejour; ?>">
                </div>
            </div>

            <!-- pays du séjour -->
            <div class="col-12">
                <div class="form-group">
                    <label for="pays_sejour">Pays du séjour</label>
                    <input type="text" readonly class="form-control" id="pays_sejour" name="pays_sejour" value="<?= $sejour->pays_sejour; ?>">
                </div>
            </div>

            <!-- prix du séjour -->
            <div class="col-12">
                <div class="form-group">
                    <label for="prix_sejour">Prix du séjour</label>
                    <input type="text" readonly class="form-control" id="prix_sejour" name="prix_sejour" value="<?= $sejour->prix_sejour; ?>">
                </div>
            </div>

            <!-- durée du séjour -->
            <div class="col-12">
                <div class="form-group">
                    <label for="duree_sejour">Durée du séjour</label>
                    <input type="text" readonly class="form-control" id="duree_sejour" name="duree_sejour" value="<?= $sejour->duree_sejour; ?>">
                </div>
            </div>

            <!-- les id -->
            <input type="hidden" id="id_client" name="id_client" value="<?= $_SESSION['user']['id_client']; ?>">
            <input type="hidden" id="id_sejour" name="id_sejour" value="<?= $sejour->id_sejour; ?>">

            <!--boutton du formulaire  -->
            <div class="col-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Valider" name="confirmRessej">
            </div>

        </div>

    </form>
</div>
<!-- FIN DU FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>