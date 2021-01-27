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
    // ON TRANSFORME LA CHAINE DE CARACTÉRES EN UN TABLEAU 
    $services_hotel = explode(",", $hotel->services_hotel);
    // SI AUCUN HOTEL N'EST TROUVÉ
    if (!$hotel) {
        $_SESSION['flash']['danger'] = "Aucun hôtel ne correspond à cet id !";
        header('location:index.php');
        exit();
    }
}
?>
<!-- TITRE DE LA PAGE -->
<?php $title = "Modifier un hôtel"; ?>

<?php
// SI DES DONNEES ONT ETAIENT ENVOYÉES 
if (isset($_POST['modifhotel'])) {
    // SI CERTAINS CHAMPS NE SONT PAS VIDES
    if (!empty($_POST['nom_hotel']) && !empty($_POST['pays_hotel']) && !empty($_POST['prix_chambre']) && !empty($_POST['notation_hotel']) && !empty($_POST['adresse_hotel']) && !empty($_POST['description_hotel'])) {

        $nom_hotel = htmlspecialchars($_POST['nom_hotel']);
        $pays_hotel = htmlspecialchars($_POST['pays_hotel']);
        $prix_chambre = htmlspecialchars($_POST['prix_chambre']);
        $notation_hotel = htmlspecialchars($_POST['notation_hotel']);
        $adresse_hotel = htmlspecialchars($_POST['adresse_hotel']);
        $contact_hotel = htmlspecialchars($_POST['contact_hotel']);
        $site_web_hotel = htmlspecialchars($_POST['site_web_hotel']);
        $description_hotel = htmlspecialchars($_POST['description_hotel']);
        $errors = array();

        require_once('../assets/config/database.php');

        // SI LE PRIX DE L'HOTEL N'EST PAS VALIDE
        if (!preg_match('/^[0-9 ]+$/', $prix_chambre)  && !filter_var($prix_chambre, FILTER_VALIDATE_INT)) {
            $errors[] = "Le prix de la chambre de l'hôtel doit étre des nombres seulement !";
        }

        // ON VÉRIFIE LA VALIDITÉ DU SITE DE L'HOTEL
        elseif (!empty($_POST['site_web_hotel']) && !filter_var($site_web_hotel, FILTER_VALIDATE_URL)) {
            $errors[] = "Le lien du site de l'hôtel n'est pas valide !";
        }

        // SI TOUT EST BON
        elseif (empty($errors)) {

            // ON VÉRIFIE SI L'HOTEL CONTIENT DES SERVICES
            if (!empty($_POST['services_hotel'])) {
                $services_hotel = implode(",", $_POST['services_hotel']);
            } else {
                $services_hotel = "Aucun";
            }

            // ON FAIT UNE MISE A JOUR DES DONNEES DANS LA BDD POUR L'HOTEL DEFINIE
            $req = $db->prepare('UPDATE hotels SET nom_hotel =:nom_hotel, pays_hotel =:pays_hotel, prix_chambre =:prix_chambre, notation_hotel =:notation_hotel, services_hotel =:services_hotel, adresse_hotel =:adresse_hotel, contact_hotel =:contact_hotel, site_web_hotel =:site_web_hotel, description_hotel = :description_hotel WHERE id_hotel = :id_hotel ');
            $req->execute(array(
                'nom_hotel' => $nom_hotel,
                'pays_hotel' => $pays_hotel,
                'prix_chambre' => $prix_chambre,
                'notation_hotel' => $notation_hotel,
                'services_hotel' => $services_hotel,
                'adresse_hotel' => $adresse_hotel,
                'contact_hotel' => $contact_hotel,
                'site_web_hotel' => $site_web_hotel,
                'description_hotel' => $description_hotel,
                'id_hotel' => $_GET['id_hotel'],
            ));
            $_SESSION['flash']['success'] = "L'hotel a bien été modifiée !";
            header('location:consulter-hotel.php?id_hotel=' . $hotel->id_hotel);
            exit();
        }
    }
    // SI Y'A DES CHAMPS VIDES 
    else {
        $errors[] = "Veuillez remplir tout les champs !";
    }
}
?>
<!-- ON INCLUE LE HEADER -->
<?php require '../assets/partials/header-admin.php'; ?>

<!-- LE TITRE DE PRESENTATION DE MODIFICATION D'UNE OFFRE -->
<div class="row">
    <div class="col-12">
        <h4 class="text-uppercase">MODIFIER <?= $hotel->nom_hotel; ?></h4>
    </div>
</div>

<!-- LE FORMULAIRE DE MODIFICATION D'UN HOTEL -->
<div class="row mt-3 mb-3">
    <div class="col-12">

        <!-- formulaire d'ajout hôtel -->
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

                <!-- nom de l'hotel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="nom_hotel">Nom de l'hôtel *</label>
                        <input type="text" class="form-control" id="nom_hotel" name="nom_hotel" value="<?= (isset($_POST['nom_hotel']) ? $_POST['nom_hotel'] : $hotel->nom_hotel); ?>" placeholder="Saisir le nom de l'hôtel" required>
                    </div>
                </div>

                <!-- pays de l'hotel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="pays_hotel">Pays de l'hôtel *</label>
                        <select id="pays-hotel" name="pays_hotel" class="form-control">

                            <!-- SI UN PAYS A ÉTAIT SELECTIONÉ -->
                            <option selected value="<?= (isset($_POST['pays_hotel']) ? $_POST['pays_hotel'] : $hotel->pays_hotel); ?>"><?= (isset($_POST['pays_hotel']) ? $_POST['pays_hotel'] : $hotel->pays_hotel); ?></option>

                            <!-- On inclue la liste des pays  -->
                            <?php require '../assets/partials/liste-pays.php'; ?>

                        </select>
                    </div>
                </div>

                <!-- prix d'une chambre de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="prix_sejour">Prix de la chambre *</label>
                        <input type="number" class="form-control" id="prix_chambre" name="prix_chambre" value="<?= (isset($_POST['prix_chambre']) ? $_POST['prix_chambre'] : $hotel->prix_chambre); ?>" placeholder="Saisir le prix de la chambre / nuit" required>
                    </div>
                </div>

                <!-- notation de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="notation_hotel">Notation de l'hôtel *</label>
                        <select id="notation_hotel" name="notation_hotel" class="form-control">

                            <!-- On inclue modification de la notation de l'hotel  -->
                            <?php require '../assets/partials/modification-notation-hotel.php'; ?>

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
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing" name="services_hotel[]" value="Restaurant" <?php get_services_modify("Restaurant", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing"><i class="fas fa-utensils text-dark mr-2"></i>Restaurant</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing2" name="services_hotel[]" value="Centre de fitness" <?php get_services_modify("Centre de fitness", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing2"><i class="fas fa-dumbbell text-dark mr-2"></i>Centre de fitness</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing3" name="services_hotel[]" value="Navette aéroport" <?php get_services_modify("Navette aéroport", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing3"><i class="fas fa-shuttle-van text-dark mr-2"></i>Navette aéroport</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing4" name="services_hotel[]" value="Convient aux enfants" <?php get_services_modify("Convient aux enfants", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing4"><i class="fas fa-baby text-dark mr-2"></i>Convient aux enfants</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing5" name="services_hotel[]" value="Wi-fi" <?php get_services_modify("Wi-fi", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing5"><i class="fas fa-wifi text-dark mr-2"></i>Wi-fi</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <ul class="nav flex-column">
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing6" name="services_hotel[]" value="Service de chambre" <?php get_services_modify("Service de chambre", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing6"><i class="fas fa-concierge-bell text-dark mr-2"></i>Service de chambre</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing7" name="services_hotel[]" value="Piscine" <?php get_services_modify("Piscine", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing7"><i class="fas fa-swimmer text-dark mr-2"></i>Piscine</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing8" name="services_hotel[]" value="Bar" <?php get_services_modify("Bar", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing8"><i class="fas fa-glass-martini-alt text-dark mr-2"></i>Bar</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing9" name="services_hotel[]" value="Spa" <?php get_services_modify("Spa", $services_hotel); ?>>
                                        <label class="custom-control-label" for="customControlAutosizing9"><i class="fas fa-spa text-dark mr-2"></i>Spa</label>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing10" name="services_hotel[]" value="Parking" <?php get_services_modify("Parking", $services_hotel); ?>>
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
                        <input type="text" class="form-control" id="adresse_hotel" name="adresse_hotel" value="<?= (isset($_POST['adresse_hotel']) ? $_POST['adresse_hotel'] : $hotel->adresse_hotel); ?>" placeholder="Saisir l'adresse de l'hôtel" required>
                    </div>
                </div>

                <!-- contact de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="contact_hotel">Contact de l'hôtel</label>
                        <input type="number" class="form-control" id="contact_hotel" name="contact_hotel" value="<?= (isset($_POST['contact_hotel']) ? $_POST['contact_hotel'] : $hotel->contact_hotel); ?>" placeholder="Saisir le numéro de l'hôtel">
                    </div>
                </div>

                <!-- site web de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="site_web_hotel">Site web de l'hôtel</label>
                        <input type="text" class="form-control" id="site_web_hotel" name="site_web_hotel" value="<?= (isset($_POST['site_web_hotel']) ? $_POST['site_web_hotel'] : $hotel->site_web_hotel); ?>" placeholder="Saisir le site web de l'hôtel">
                    </div>
                </div>

                <!-- a propos de l'hôtel -->
                <div class="col-12">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="text_area">À propos de l'hôtel *</label>
                            <textarea class="form-control" name="description_hotel" id="text_area" rows="5" required><?= (isset($_POST['description_hotel']) ? $_POST['description_hotel'] : $hotel->description_hotel); ?></textarea>
                        </div>
                    </div>
                </div>

                <!--boutton du formulaire  -->
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Valider" name="modifhotel">
                </div>

            </div>
        </form>
        <!--fin formulaire de modification d'un hotel -->

    </div>
</div>
<!-- FIN DU FORMULAIRE DE MODIFICATION D'OFFRE -->

<?php require '../assets/partials/footer-admin.php'; ?>