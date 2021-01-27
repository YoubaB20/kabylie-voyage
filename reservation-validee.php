<?php
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion.php');
    exit;
} elseif (empty($_GET['id_vol'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le vol que vous voulez réserver !";
    header('location:index.php');
    exit();
} else {
    require_once 'assets/config/database.php';
    $req = $db->prepare('SELECT * FROM vols WHERE id_vol = ?');
    $req->execute([$_GET['id_vol']]);
    $vol = $req->fetchObject();
    if (!$vol) {
        $_SESSION['flash']['danger'] = "Aucun vol ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>

<?php $title = "Réservation validée"; ?>
<?php require 'assets/partials/header.php'; ?>

<!-- LE FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
<div class="validate-reserve-form mt-3 mb-3">

    <!-- TITRE FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
    <h3 class="mb-3 text-center">Réservation validée</h3>

    <!-- FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
    <form class="bg-light p-3 border">
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

            <!-- nom de l'utilisateur -->
            <div class="col-12">
                <div class="form-group">
                    <label for="nom_utilisateur">Nom *</label>
                    <input type="text" readonly class="form-control-plaintext" id="nom_utilisateur" name="nom_utilisateur" value="<?= $_SESSION['user']['nom_utilisateur']; ?>">
                </div>
            </div>

            <!-- prénom de l'utilisateur -->
            <div class="col-12">
                <div class="form-group">
                    <label for="prenom_utilisateur">Prénom *</label>
                    <input type="text" readonly class="form-control-plaintext" id="prenom_utilisateur" name="prenom_utilisateur" value="<?= $_SESSION['user']['prenom_utilisateur']; ?>">
                </div>
            </div>

            <!-- nom du vol -->
            <div class="col-12">
                <div class="form-group">
                    <label for="email_utilisateur">Nom du vol</label>
                    <input type="text" readonly class="form-control-plaintext" id="nom_vol" name="nom_vol" value="<?= $vol->ville_depart . "->" . $vol->ville_depart; ?>">
                </div>
            </div>

    </form>
</div>
<!-- FIN DU FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>