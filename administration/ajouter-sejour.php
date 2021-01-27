<?php session_start(); ?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Ajouter un séjour"; ?>
<?php
if (isset($_POST['ajoutSejour'])) {
    // si les champs ne sont pas vides
    if (!empty($_POST['nom_sejour']) && !empty($_POST['pays_sejour']) && !empty($_FILES['image_sejour']['name']) && !empty($_POST['prix_sejour']) && !empty($_POST['duree_sejour']) && !empty($_POST['description_sejour'])) {

        // on échape les champs saisis par l'utilisateur
        $nom_sejour = htmlspecialchars($_POST['nom_sejour']);
        $pays_sejour = htmlspecialchars($_POST['pays_sejour']);
        $prix_sejour = htmlspecialchars($_POST['prix_sejour']);
        $duree_sejour = htmlspecialchars($_POST['duree_sejour']);
        $description_sejour = htmlspecialchars($_POST['description_sejour']);
        $file_name = $_FILES['image_sejour']['name'];
        $extentionValide = array('jpg', 'jpeg', 'png', 'gif');
        $file_tmp_name = $_FILES['image_sejour']['tmp_name'];
        $extentionUpload = strtolower(substr(strrchr($file_name, '.'), 1));
        $chemin = "../assets/img/offres/sejours/" . $file_name;
        $errors = array();

        require_once('../assets/config/database.php');

        // SI LE PRIX DU SÉJOUR N'EST PAS VALIDE
        if (!preg_match('/^[0-9 ]+$/', $prix_sejour) && !filter_var($prix_sejour, FILTER_VALIDATE_INT)) {
            $errors[] = "Le prix du séjour doit être des nombres seulement !";
        }

        // SI LA DURÉE DU SÉJOUR N'EST PAS VALIDE
        elseif (!preg_match('/^[0-9 ]+$/', $duree_sejour) && !filter_var($duree_sejour, FILTER_VALIDATE_INT)) {
            $errors[] = "Le durée du séjour doit être des nombres seulement !";
        }

        // SI LA DURÉE DU SÉJOUR EST INFÉRIEURE A 0
        elseif ($duree_sejour < 0) {
            $errors[] = "Le durée du séjour ne doit être inférieure a 1 jour !";
        }
        
        // SI L'IMAGE DU SÉJOUR N'EST PAS AU BON FORMAT
        elseif (!in_array($extentionUpload, $extentionValide)) {
            $errors[] = "L'image du séjour doit être au format jpg, jpeg, gif ou png !";
        }

        // SI TOUT EST BON
        elseif (empty($errors)) {

            move_uploaded_file($file_tmp_name, $chemin);
            $req = $db->prepare('INSERT INTO sejours(nom_sejour, pays_sejour, image_sejour, prix_sejour, duree_sejour, description_sejour) VALUES (?, ?, ?, ?, ?, ?) ');
            $req->execute(array($nom_sejour, $pays_sejour, $file_name, $prix_sejour, $duree_sejour, $description_sejour));
            $_SESSION['flash']['success'] = "Le séjour a bien été ajoutée !";
            header('location:liste-sejours.php');
            exit();
        }
    } else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>


<?php require '../assets/partials/header-admin.php'; ?>
<?php require '../assets/partials/heading-ajout-offre.php'; ?>

<!-- LE FORMULAIRE D'AJOUT D'OFFRE -->
<div class="row mt-3 mb-3">
    <div class="col-12">
        <!-- formulaire d'ajout séjour -->
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

                <!-- nom du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="nom_sejour">Nom du séjour *</label>
                        <input type="text" class="form-control" id="nom_sejour" name="nom_sejour" value="<?php get_input_posted('nom_sejour'); ?>" placeholder="Saisir le nom du séjour" required>
                    </div>
                </div>

                <!-- pays du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="pays_sejour">Pays du séjour *</label>
                        <select id="pays_sejour" name="pays_sejour" class="form-control">

                            <!-- Si un pays du séjour a était selectionné on l'affiche -->
                            <?php if (isset($_POST['pays_sejour'])) : ?>
                                <option value="<?= $_POST['pays_sejour']; ?>" selected><?= $_POST['pays_sejour']; ?></option>
                            <?php endif; ?>

                            <!-- On inclue la liste des pays  -->
                            <?php require '../assets/partials/liste-pays.php'; ?>
                        </select>
                    </div>
                </div>

                <!-- image du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="image_sejour">Image du séjour *</label>
                        <input type="file" class="form-control-file" id="image_sejour" name="image_sejour" required>
                    </div>
                </div>

                <!-- prix du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="prix_sejour">Prix du séjour *</label>
                        <input type="number" class="form-control" id="prix_sejour" name="prix_sejour" value="<?php get_input_posted('prix_sejour'); ?>" min="50" placeholder="Saisir le prix du séjour" required>
                    </div>
                </div>

                <!-- durée du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="duree_sejour">Durée du séjour *</label>
                        <input type="number" class="form-control" id="duree_sejour" name="duree_sejour" value="<?php get_input_posted('duree_sejour'); ?>" min="1" placeholder="Saisir la durée du séjour" required>
                    </div>
                </div>

                <!-- a propos du séjours -->
                <div class="col-12">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="text_area">À propos du séjour *</label>
                            <textarea class="form-control" name="description_sejour" id="text_area" rows="5" required><?php get_input_posted('description_sejour'); ?></textarea>
                        </div>
                    </div>
                </div>

                <!--boutton du formulaire  -->
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Valider" name="ajoutSejour">
                </div>

            </div>

        </form>
        <!--fin formulaire d'ajout séjour -->

    </div>
</div>
<!-- FIN DU FORMULAIRE D'AJOUT D'OFFRE -->



<?php require '../assets/partials/footer-admin.php'; ?>