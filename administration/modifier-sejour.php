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
<?php $title = "Modifier un séjour"; ?>

<?php
if (isset($_POST['modifSejour'])) {
    if (!empty($_POST['nom_sejour']) && !empty($_POST['pays_sejour']) && !empty($_POST['prix_sejour']) && !empty($_POST['duree_sejour']) && !empty($_POST['description_sejour'])) {

        $nom_sejour = htmlspecialchars($_POST['nom_sejour']);
        $pays_sejour = htmlspecialchars($_POST['pays_sejour']);
        $prix_sejour = htmlspecialchars($_POST['prix_sejour']);
        $duree_sejour = htmlspecialchars($_POST['duree_sejour']);
        $description_sejour = htmlspecialchars($_POST['description_sejour']);
        $errors = array();

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

        // SI TOUT EST BON
        elseif (empty($errors)) {

            $req = $db->prepare('UPDATE sejours SET nom_sejour = :nom_sejour, pays_sejour= :pays_sejour, prix_sejour= :prix_sejour, duree_sejour= :duree_sejour, description_sejour= :description_sejour WHERE id_sejour = :id_sejour');
            $req->execute(
                [
                    'nom_sejour' => $nom_sejour,
                    'pays_sejour' => $pays_sejour,
                    'prix_sejour' => $prix_sejour,
                    'duree_sejour' => $duree_sejour,
                    'description_sejour' => $description_sejour,
                    'id_sejour' => $_GET['id_sejour']
                ]
            );
            $_SESSION['flash']['success'] = "Le séjour a bien été modifié !";
            header('location:consulter-sejour.php?id_sejour=' . $sejour->id_sejour);
            exit();
        }
    } else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>
<?php require '../assets/partials/header-admin.php'; ?>

<!-- LE TITRE DE PRESENTATION DE MODIFICATION D'UNE OFFRE -->
<div class="row">
    <div class="col-12">
        <h4 class="text-uppercase">MODIFIER <?= $sejour->nom_sejour; ?></h4>
    </div>
</div>

<!-- LE FORMULAIRE DE MODIFICATION D'OFFRE -->
<div class="row mt-3 mb-3">
    <div class="col-12">
        <!-- formulaire de modification d'un séjour -->
        <form class="bg-light p-3 border" method="post">
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

                <!-- nom du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="nom_sejour">Nom du séjour *</label>
                        <input type="text" class="form-control" id="nom_sejour" name="nom_sejour" value="<?= (isset($_POST['nom_sejour']) ? $_POST['nom_sejour'] : $sejour->nom_sejour); ?>" placeholder="Saisir le nom du séjour" required>
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
                            <?php else : ?>
                                <option value="<?= $sejour->pays_sejour; ?>" selected><?= $sejour->pays_sejour; ?></option>
                            <?php endif; ?>

                            <!-- On inclue la liste des pays  -->
                            <?php require '../assets/partials/liste-pays.php'; ?>
                        </select>
                    </div>
                </div>

                <!-- prix du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="prix_sejour">Prix du séjour *</label>
                        <input type="number" class="form-control" id="prix_sejour" name="prix_sejour" value="<?= (isset($_POST['prix_sejour']) ? $_POST['prix_sejour'] : $sejour->prix_sejour); ?>" min="50" placeholder="Saisir le prix du séjour" required>
                    </div>
                </div>

                <!-- durée du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="duree_sejour">Durée du séjour *</label>
                        <input type="number" class="form-control" id="duree_sejour" name="duree_sejour" value="<?= (isset($_POST['duree_sejour']) ? $_POST['duree_sejour'] : $sejour->duree_sejour); ?>" min="1" placeholder="Saisir la durée du séjour" required>
                    </div>
                </div>

                <!-- a propos du séjours -->
                <div class="col-12">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="text_area">À propos du séjour *</label>
                            <textarea class="form-control" name="description_sejour" id="text_area" rows="5" required><?= (isset($_POST['description_sejour']) ? $_POST['description_sejour'] : $sejour->description_sejour); ?></textarea>
                        </div>
                    </div>
                </div>

                <!--boutton du formulaire  -->
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Valider" name="modifSejour">
                </div>

            </div>

        </form>
        <!--fin formulaire de séjour -->

    </div>
</div>
<!-- FIN DU FORMULAIRE DE MODIFICATION D'UNE OFFRE -->



<?php require '../assets/partials/footer-admin.php'; ?>