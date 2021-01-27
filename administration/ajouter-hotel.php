<?php session_start(); ?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Ajouter un hôtel"; ?>
<?php
if (isset($_POST['ajoutHotel'])) {
    // si les champs ne sont pas vides
    if (!empty($_POST['nom_hotel']) && !empty($_POST['pays_hotel']) && !empty($_FILES['image_hotel']['name']) && !empty($_POST['prix_chambre']) && !empty($_POST['notation_hotel']) && !empty($_POST['adresse_hotel']) && !empty($_POST['description_hotel'])) {

        $nom_hotel = htmlspecialchars($_POST['nom_hotel']);
        $pays_hotel = htmlspecialchars($_POST['pays_hotel']);
        $prix_chambre = htmlspecialchars($_POST['prix_chambre']);
        $notation_hotel = htmlspecialchars($_POST['notation_hotel']);
        $adresse_hotel = htmlspecialchars($_POST['adresse_hotel']);
        $contact_hotel = htmlspecialchars($_POST['contact_hotel']);
        $site_web_hotel = htmlspecialchars($_POST['site_web_hotel']);
        $description_hotel = htmlspecialchars($_POST['description_hotel']);
        $extentionValide = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $_FILES['image_hotel']['name'];
        $file_tmp_name = $_FILES['image_hotel']['tmp_name'];
        $extentionUpload = strtolower(substr(strrchr($file_name, '.'), 1));
        $chemin = "../assets/img/offres/hotels/" . $file_name;
        $errors = array();

        require_once('../assets/config/database.php');

        // SI LE PRIX DE L'HOTEL N'EST PAS VALIDE
        if (!preg_match('/^[0-9 ]+$/', $prix_chambre) && !filter_var($prix_chambre, FILTER_VALIDATE_INT)) {
            $errors[] = "Le prix de l'hôtel doit être des nombres seulement !";
        }

        // ON VÉRIFIE LA VALIDITÉ DU SITE DE L'HOTEL
        elseif (!empty($_POST['site_web_hotel']) && !filter_var($site_web_hotel, FILTER_VALIDATE_URL)) {
            $errors[] = "Le lien du site de l'hôtel n'est pas valide !";
        }

        // SI L'IMAGE N'EST PAS AU BON FORMAT
        elseif (!in_array($extentionUpload, $extentionValide)) {
            $errors[] = "L'image de l'hôtel doit être au format jpg, jpeg, gif ou png !";
        }

        // SI TOUT EST BON
        elseif (empty($errors)) {

            // ON VÉRIFIE SI L'HOTEL CONTIENT DES SERVICES
            if (!empty($_POST['services_hotel'])) {
                $services_hotel = implode(",", $_POST['services_hotel']);
            } else {
                $services_hotel = "Aucun";
            }

            // ON DÉPLACE L'IMAGE DE L'HOTEL VERS LE RÉPERTOIRE DE STOCKAGE
            move_uploaded_file($file_tmp_name, $chemin);

            // ON INSÉRE LES DONNÉES DANS LA BASE DE DONNÉES
            $req = $db->prepare('INSERT INTO hotels(nom_hotel, pays_hotel, image_hotel, prix_chambre, notation_hotel, services_hotel, adresse_hotel, contact_hotel, site_web_hotel, description_hotel ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ');
            $req->execute(array($nom_hotel, $pays_hotel, $file_name, $prix_chambre, $notation_hotel, $services_hotel, $adresse_hotel, $contact_hotel, $site_web_hotel, $description_hotel));
            $_SESSION['flash']['success'] = "L'hôtel a bien été ajoutée !";
            header('location:liste-hotels.php');
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

        <!-- formulaire d'ajout hôtel -->
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

                <!-- nom de l'hotel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="nom_hotel">Nom de l'hôtel *</label>
                        <input type="text" class="form-control" id="nom_hotel" name="nom_hotel" value="<?php get_input_posted('nom_hotel'); ?>" placeholder="Saisir le nom de l'hôtel" required>
                    </div>
                </div>

                <!-- pays de l'hotel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="pays_hotel">Pays de l'hôtel *</label>
                        <select id="pays-hotel" name="pays_hotel" class="form-control">

                            <!-- SI UN PAYS A ÉTAIT SELECTIONÉ -->
                            <?php if (isset($_POST['pays_hotel'])) : ?>
                                <option value="<?= $_POST['pays_hotel']; ?>" selected><?= $_POST['pays_hotel']; ?></option>
                            <?php endif; ?>

                            <!-- On inclue la liste des pays  -->
                            <?php require '../assets/partials/liste-pays.php'; ?>

                        </select>
                    </div>
                </div>

                <!-- image du séjour -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="image_hotel">Image de l'hôtel *</label>
                        <input type="file" class="form-control-file" id="image_hotel" name="image_hotel" required>
                    </div>
                </div>

                <!-- prix d'une chambre de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="prix_sejour">Prix de la chambre *</label>
                        <input type="number" class="form-control" id="prix_chambre" name="prix_chambre" value="<?php get_input_posted('prix_chambre'); ?>" placeholder="Saisir le prix de la chambre / nuit" required>
                    </div>
                </div>

                <!-- notation de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="notation_hotel">Notation de l'hôtel *</label>
                        <select id="notation_hotel" name="notation_hotel" class="form-control">

                            <!-- Si une notation a était faite -->
                            <?php if (isset($_POST['notation_hotel'])) : ?>
                                <?php switch ($_POST['notation_hotel']): case 1: ?>
                                    <option value="1" class="text-warning" selected><span>&#9733</span></option>
                                    <?php break;
                                case 2: ?>
                                    <option value="2" class="text-warning" selected><span>&#9733 &#9733</span></option>
                                    <?php break;
                                case 3: ?>
                                    <option value="3" class="text-warning" selected><span>&#9733 &#9733 &#9733</span></option>
                                    <?php break;
                                case 4: ?>
                                    <option value="4" class="text-warning" selected><span>&#9733 &#9733 &#9733 &#9733</span></option>
                                    <?php break;
                                case 5: ?>
                                    <option value="5" class="text-warning" selected><span>&#9733 &#9733 &#9733 &#9733 &#9733</span></option>
                                    <?php break;
                            endswitch; ?>
                            <?php endif; ?>

                            <option value="1" class="text-warning"><span>&#9733</span></option>
                            <option value="2" class="text-warning"><span>&#9733 &#9733</span></option>
                            <option value="3" class="text-warning"><span>&#9733 &#9733 &#9733</span></option>
                            <option value="4" class="text-warning"><span>&#9733 &#9733 &#9733 &#9733</span></option>
                            <option value="5" class="text-warning"><span>&#9733 &#9733 &#9733 &#9733 &#9733</span></option>
                        </select>
                    </div>
                </div>

                <!-- services de l'hôtel -->
                <div class="col-12">
                    <label>Services de l'hôtel</label>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <ul class="nav flex-column">
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing" name="services_hotel[]" value="Restaurant" <?php get_services_posted("Restaurant"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing"><i class="fas fa-utensils text-dark mr-2"></i>Restaurant</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing2" name="services_hotel[]" value="Centre de fitness" <?php get_services_posted("Centre de fitness"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing2"><i class="fas fa-dumbbell text-dark mr-2"></i>Centre de fitness</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing3" name="services_hotel[]" value="Navette aéroport" <?php get_services_posted("Navette aéroport"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing3"><i class="fas fa-shuttle-van text-dark mr-2"></i>Navette aéroport</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing4" name="services_hotel[]" value="Convient aux enfants" <?php get_services_posted("Convient aux enfants"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing4"><i class="fas fa-baby text-dark mr-2"></i>Convient aux enfants</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing5" name="services_hotel[]" value="Wi-fi" <?php get_services_posted("Wi-fi"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing5"><i class="fas fa-wifi text-dark mr-2"></i>Wi-fi</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <ul class="nav flex-column">
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing6" name="services_hotel[]" value="Service de chambre" <?php get_services_posted("Service de chambre"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing6"><i class="fas fa-concierge-bell text-dark mr-2"></i>Service de chambre</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing7" name="services_hotel[]" value="Piscine" <?php get_services_posted("Piscine"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing7"><i class="fas fa-swimmer text-dark mr-2"></i>Piscine</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing8" name="services_hotel[]" value="Bar" <?php get_services_posted("Bar"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing8"><i class="fas fa-glass-martini-alt text-dark mr-2"></i>Bar</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing9" name="services_hotel[]" value="Spa" <?php get_services_posted("Spa"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing9"><i class="fas fa-spa text-dark mr-2"></i>Spa</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing10" name="services_hotel[]" value="Parking" <?php get_services_posted("Parking"); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing10"><i class="fas fa-parking text-dark mr-2"></i>Parking</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- adresse de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="adresse_hotel">Adresse de l'hôtel *</label>
                        <input type="text" class="form-control" id="adresse_hotel" name="adresse_hotel" value="<?php get_input_posted('adresse_hotel'); ?>" placeholder="Saisir l'adresse de l'hôtel" required>
                    </div>
                </div>

                <!-- contact de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="contact_hotel">Contact de l'hôtel</label>
                        <input type="number" class="form-control" id="contact_hotel" name="contact_hotel" value="<?php get_input_posted('contact_hotel'); ?>" placeholder="Saisir le numéro de l'hôtel">
                    </div>
                </div>

                <!-- site web de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="site_web_hotel">Site web de l'hôtel</label>
                        <input type="text" class="form-control" id="site_web_hotel" name="site_web_hotel" value="<?php get_input_posted_site('site_web_hotel'); ?>">
                    </div>
                </div>

                <!-- a propos de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="text_area">À propos de l'hôtel *</label>
                            <textarea class="form-control" name="description_hotel" id="text_area" rows="5" required><?php get_input_posted('description_hotel'); ?></textarea>
                        </div>
                    </div>
                </div>

                <!--boutton du formulaire  -->
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Valider" name="ajoutHotel">
                </div>

            </div>
        </form>
        <!--fin formulaire d'ajout hôtel -->

    </div>
</div>
<!-- FIN DU FORMULAIRE D'AJOUT D'OFFRE -->



<?php require '../assets/partials/footer-admin.php'; ?>