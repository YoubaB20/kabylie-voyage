<?php
session_start();
if (!isset($_SESSION['admin'])) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit;
} elseif (empty($_GET['id_sejour'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le séjour que vous voulez modifier !";
    header('location:index.php');
    exit();
} else {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM sejours WHERE id_sejour = ?');
    $req->execute([$_GET['id_sejour']]);
    $sejour = $req->fetchObject();
    if (!$sejour) {
        $_SESSION['flash']['danger'] = "Aucune séjour ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Mise à jour image d'un séjour"; ?>
<?php
if (isset($_POST['updateImgSejour'])) {
    // si les champs ne sont pas vides
    if (!empty($_FILES['image_sejour']['name'])) {

        $extentionValide = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $_FILES['image_sejour']['name'];
        $file_tmp_name = $_FILES['image_sejour']['tmp_name'];
        $extentionUpload = strtolower(substr(strrchr($file_name, '.'), 1));
        $chemin = "../assets/img/offres/sejours/" . $file_name;
        $errors = array();

        require_once('../assets/config/database.php');

        // SI L'IMAGE N'EST PAS AU BON FORMAT
        if (!in_array($extentionUpload, $extentionValide)) {
            $errors[] = "L'image du séjour doit être au format jpg, jpeg, gif ou png !";
        }

        // SI TOUT EST BON
        elseif (empty($errors)) {

            // ON DÉPLACE L'IMAGE DU SÉJOUR VERS LE RÉPERTOIRE DE STOCKAGE
            move_uploaded_file($file_tmp_name, $chemin);

            // ON INSÉRE LES DONNÉES DANS LA BASE DE DONNÉES
            $req = $db->prepare('UPDATE sejours SET image_sejour = :image_sejour WHERE id_sejour = :id_sejour');
            $req->execute(
                [
                    'image_sejour' => $file_name,
                    'id_sejour' => $_GET['id_sejour']
                ]
            );
            $_SESSION['flash']['success'] = "L'image du séjour a bien été modifiée !";
            header('location:consulter-sejour.php?id_sejour=' . $sejour->id_sejour);
            exit();
        }
    } else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>
<!-- ON INCLUE LE HEADER -->
<?php require '../assets/partials/header-admin.php'; ?>

<!-- LE TITRE DE PRESENTATION DE MODIFICATION IMAGE D'UNE OFFRE -->
<div class="row">
    <div class="col-12">
        <h4 class="text-uppercase">MISE A JOUR IMAGE DE <?= $sejour->nom_sejour; ?></h4>
    </div>
</div>

<!-- LE FORMULAIRE DE MISE A JOUR IMAGE D'OFFRE -->
<div class="row mt-3 mb-3">
    <div class="col-12">

        <!-- formulaire de modification image d'un séjour -->
        <form class="bg-light p-3 border" method="post" enctype="multipart/form-data">
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

                <!--nouvelle image d'un hotel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="image_sejour">Nouvelle image du séjour *</label>
                        <input type="file" class="form-control-file" id="image_sejour" name="image_sejour">
                    </div>
                </div>

                <!--boutton du formulaire  -->
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Valider" name="updateImgSejour">
                </div>

            </div>
        </form>
        <!--fin formulaire de modification image d'un séjour -->

    </div>
</div>
<!-- FIN DU FORMULAIRE DE MISE A JOUR IMAGE -->

<?php require '../assets/partials/footer-admin.php'; ?>
