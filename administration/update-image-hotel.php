<?php
session_start();
// SI ADMIN NON CONNECTÉ
if (!isset($_SESSION['admin'])) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit;
    // SI AUCUN ID HOTEL N'EST ENVOYÉ
} elseif (empty($_GET['id_hotel'])) {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner l'hôtel que vous voulez modifier !";
    header('location:index.php');
    exit();
    // ID ENVOYÉ
} else {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM hotels WHERE id_hotel = ?');
    $req->execute([$_GET['id_hotel']]);
    $hotel = $req->fetchObject();
    // SI AUCUN HOTEL N'EST TROUVÉ
    if (!$hotel) {
        $_SESSION['flash']['danger'] = "Aucun hôtel ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Mise à jour image d'un hôtel"; ?>

<?php
if (isset($_POST['updateImgHotel'])) {
    // si les champs ne sont pas vides
    if (!empty($_FILES['image_hotel']['name'])) {

        $extentionValide = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $_FILES['image_hotel']['name'];
        $file_tmp_name = $_FILES['image_hotel']['tmp_name'];
        $extentionUpload = strtolower(substr(strrchr($file_name, '.'), 1));
        $chemin = "../assets/img/offres/hotels/" . $file_name;
        $errors = array();

        require_once('../assets/config/database.php');

        // SI L'IMAGE N'EST PAS AU BON FORMAT
        if (!in_array($extentionUpload, $extentionValide)) {
            $errors[] = "L'image de l'hôtel doit être au format jpg, jpeg, gif ou png !";
        }

        // SI TOUT EST BON
        elseif (empty($errors)) {

            // ON DÉPLACE L'IMAGE DE L'HOTEL VERS LE RÉPERTOIRE DE STOCKAGE
            move_uploaded_file($file_tmp_name, $chemin);

            // ON INSÉRE LES DONNÉES DANS LA BASE DE DONNÉES
            $req = $db->prepare('UPDATE hotels SET image_hotel = :image_hotel WHERE id_hotel = :id_hotel');
            $req->execute(
                [
                'image_hotel' => $file_name,
                'id_hotel' => $_GET['id_hotel']
                ]
            );
            $_SESSION['flash']['success'] = "L'image de l'hôtel a bien été modifiée !";
            header('location:consulter-hotel.php?id_hotel=' . $hotel->id_hotel);
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
        <h4 class="text-uppercase">MISE A JOUR IMAGE DE <?= $hotel->nom_hotel; ?></h4>
    </div>
</div>

<!-- LE FORMULAIRE DE MISE A JOUR IMAGE D'OFFRE -->
<div class="row mt-3 mb-3">
    <div class="col-12">

        <!-- formulaire de modification image d'un hotel -->
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
                        <label for="image_hotel">Nouvelle image de l'hôtel *</label>
                        <input type="file" class="form-control-file" id="image_hotel" name="image_hotel">
                    </div>
                </div>

                <!--boutton du formulaire  -->
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Valider" name="updateImgHotel">
                </div>

            </div>
        </form>
        <!--fin formulaire de modification image d'un hotel -->

    </div>
</div>
<!-- FIN DU FORMULAIRE DE MISE A JOUR IMAGE -->

<?php require '../assets/partials/footer-admin.php'; ?>
