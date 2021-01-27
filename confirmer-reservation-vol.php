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
<?php $title = "Confirmer la réservation du vol"; ?>
<!-- TRAITEMENT DU FORMULAIRE DE RÉSERVATION -->
<?php
if (isset($_POST['confirmResvol'])) {
    if (!empty($_POST['id_client']) && !empty($_POST['nom_client']) && !empty($_POST['prenom_client']) && !empty($_POST['id_vol']) && !empty($_POST['ville_depart']) && !empty($_POST['ville_arrivee'])) {
        $id_client = htmlspecialchars($_POST['id_client']);
        $nom_client = htmlspecialchars($_POST['nom_client']);
        $prenom_client = htmlspecialchars($_POST['prenom_client']);
        $id_vol = htmlspecialchars($_POST['id_vol']);
        $ville_depart = htmlspecialchars($_POST['ville_depart']);
        $ville_arrivee = htmlspecialchars($_POST['ville_arrivee']);
        $nom_vol = $ville_depart . " -> " . $ville_arrivee;
        $errors = array();

        $req = $db->prepare("INSERT INTO reservation_vol(id_client, nom_client, prenom_client, id_vol, nom_vol) VALUES (?, ?, ?, ?, ?)");
        $req->execute(
            [
                $id_client,
                $nom_client,
                $prenom_client,
                $id_vol,
                $nom_vol
            ]
        );
        $_SESSION['flash']['success'] = "La réservation du vol a été validée !";
        header('location:consulter-vol.php?id_vol=' . $vol->id_vol);
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
    <h3 class="mb-3 text-center">Confirmer la réservation du vol</h3>

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

            <!-- ville de départ -->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="ville_depart">Ville de départ</label>
                    <input type="text" readonly class="form-control" id="ville_depart" name="ville_depart" value="<?= $vol->ville_depart; ?>">
                </div>
            </div>

            <!-- ville d'arrivée-->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="ville_arrivee">Ville d'arrivée</label>
                    <input type="text" readonly class="form-control" id="ville_arrivee" name="ville_arrivee" value="<?= $vol->ville_arrivee; ?>">
                </div>
            </div>

            <!-- date de départ-->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="date_depart">Date de départ</label>
                    <input type="text" readonly class="form-control" id="date_depart" name="date_depart" value="<?= $vol->date_depart; ?>">
                </div>
            </div>

            <!-- date d'arrivée-->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="date_arrivee">Date d'arrivée</label>
                    <input type="text" readonly class="form-control" id="date_arrivee" name="date_arrivee" value="<?= $vol->date_arrivee; ?>">
                </div>
            </div>

            <!-- heure de départ-->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="heure_depart">Heure de départ</label>
                    <input type="time" readonly class="form-control" id="heure_depart" name="heure_depart" value="<?= $vol->heure_depart; ?>">
                </div>
            </div>

            <!-- heure d'arrivée-->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="heure_arrivee">Heure d'arrivée</label>
                    <input type="time" readonly class="form-control" id="heure_arrivee" name="heure_arrivee" value="<?= $vol->heure_arrivee; ?>">
                </div>
            </div>

            <!-- companie de voyage -->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="companie_voyage">Companie du voyage</label>
                    <input type="text" readonly class="form-control" id="companie_voyage" name="companie_voyage" value="<?= $vol->companie_voyage; ?>">
                </div>
            </div>

            <!-- type du vol -->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="type_vol">Type du vol</label>
                    <input type="text" readonly class="form-control" id="type_vol" name="type_vol" value="<?= $vol->type_vol; ?>">
                </div>
            </div>

            <!-- prix du vol-->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="prix_vol">Prix du vol</label>
                    <input type="text" readonly class="form-control" id="prix_vol" name="prix_vol" value="<?= $vol->prix_vol; ?>">
                </div>
            </div>

            <!-- classe du vol -->
            <div class="col-lg-6 col-md-12">
                <div class="form-group">
                    <label for="classe_voyage">Classe du vol</label>
                    <input type="text" readonly class="form-control" id="classe_voyage" name="classe_voyage" value="<?= $vol->classe_voyage; ?>">
                </div>
            </div>

            <!-- aéroport de départ -->
            <div class="col-12">
                <div class="form-group">
                    <label for="aeroport_depart">Aéroport de départ</label>
                    <input type="text" readonly class="form-control" id="aeroport_depart" name="aeroport_depart" value="<?= $vol->aeroport_depart; ?>">
                </div>
            </div>

            <!-- aéroport de départ -->
            <div class="col-12">
                <div class="form-group">
                    <label for="aeroport_depart">Aéroport d'arrivée</label>
                    <input type="text" readonly class="form-control" id="aeroport_arrivee" name="aeroport_arrivee" value="<?= $vol->aeroport_arrivee; ?>">
                </div>
            </div>

            <!-- les id -->
            <input type="hidden" id="id_client" name="id_client" value="<?= $_SESSION['user']['id_client']; ?>">
            <input type="hidden" id="id_vol" name="id_vol" value="<?= $vol->id_vol; ?>">

            <!--boutton du formulaire  -->
            <div class="col-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Valider" name="confirmResvol">
            </div>

        </div>

    </form>
</div>
<!-- FIN DU FORMULAIRE DE VALIDATION DE LA RÉSERVATION -->
<!-- footer -->
<?php require 'assets/partials/footer.php'; ?>