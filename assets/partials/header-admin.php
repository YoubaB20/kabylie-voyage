<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit;
}
?>
<?php require '../assets/partials/functions.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php
        if (isset($title)) {
            echo $title . " " . "| Administration";
        } else {
            echo "Administration | Kabylie Voyage";
        }
        ?>
    </title>
    <!-- LES STYLES -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/datepicker.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- FIN LES STYLES -->
</head>

<body>
    <!------------------------------- LE HEADER ------------------------------->

    <header class="header">
        <!--------------- LA NAVBAR --------------->

        <nav class="navbar navbar-administration navbar-expand-md fixed-top navbar-dark bg-dark shadow">
            <!-- logo de la navbar -->
            <a class="navbar-brand" href="index.php"><img src="../assets/img/Logo-KabylieVoyage12.png" alt="Logo Kabylie Voyage"></a>
            <!-- logo de la navbar -->

            <!-- boutton toggler -->
            <button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- lien de la navbar collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav d-md-none mr-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "index.php") ? "active" : ""; ?>" href="index.php">Acceuil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "sejours-administration.php") ? "active" : ""; ?>" href="sejours-administration.php">Séjours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "hotels-administration.php") ? "active" : ""; ?>" href="hotels-administration.php">Hôtels</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "vols-administration.php") ? "active" : ""; ?>" href="vols-administration.php">Vols</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == ("ajouter-sejour.php") or basename($_SERVER['PHP_SELF']) == ("ajouter-hotel.php") or basename($_SERVER['PHP_SELF']) == ("ajouter-vol.php")) ? "active" : ""; ?>" href="ajouter-sejour.php">Ajouter une offre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == ("liste-sejours.php") or basename($_SERVER['PHP_SELF']) == ("liste-hotels.php") or basename($_SERVER['PHP_SELF']) == ("liste-vols.php")) ? "active" : ""; ?>" href="liste-sejours.php">Liste des offres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == ("liste-reservations-sejours.php") or basename($_SERVER['PHP_SELF']) == ("liste-reservations-hotels.php") or basename($_SERVER['PHP_SELF']) == ("liste-reservations-vols.php")) ? "active" : ""; ?>" href="liste-reservations-sejours.php">Liste des réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "liste-clients.php") ? "active" : ""; ?>" href="liste-clients.php">Liste des clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-secondary btn-sm text-white" href="deconnexion-admin.php">Se déconnecter</a>
                    </li>
                </ul>
            </div>
            <!-- finboutton toggler -->

            <!-- lien de la navbar -->
            <ul class="navigation-administration navbar-nav ml-auto d-none d-md-block">
                <li class="nav-item">
                    <a class="nav-link link-sing-out mr-2 ml-2 " href="deconnexion-admin.php">
                        <i class="fas fa-sign-out-alt text-white"></i>
                    </a>
                </li>
            </ul>
            <!-- fin lien de la navbar -->

            <!-- fin lien de la navbar collapse-->
        </nav>
        <!--------------- FIN DE LA NAVBAR --------------->

    </header>
    <!------------------------------- FIN DE HEADER ------------------------------->

    <!------------------------------ LE WRAPPER ------------------------------>

    <div class="wrapper d-flex align-items-stretch">

        <!------------ LA SIDEBAR------------>
        <div class="sidebar d-none d-md-block">
            <nav class="sidebar-content bg-light border-right">
                <ul class="nav flex-column">
                    <li class="nav-item mt-2">
                        <a class="nav-link text-capitalize disabled sidebar-link-presentation" href="#" tabindex="-1" aria-disabled="true">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a href="ajouter-sejour.php" class="nav-link link-sidebar <?= (basename($_SERVER['PHP_SELF']) == ("ajouter-sejour.php") or basename($_SERVER['PHP_SELF']) == ("ajouter-hotel.php") or basename($_SERVER['PHP_SELF']) == ("ajouter-vol.php")) ? "active" : ""; ?>"><i class="fas fa-plus-square mr-3"></i>Ajouter une
                            offre</a>
                    </li>
                    <li class="nav-item">
                        <a href="liste-sejours.php" class="nav-link link-sidebar <?= (basename($_SERVER['PHP_SELF']) == ("liste-sejours.php") or basename($_SERVER['PHP_SELF']) == ("liste-hotels.php") or basename($_SERVER['PHP_SELF']) == ("liste-vols.php")) ? "active" : ""; ?>"><i class="fas fa-shopping-cart mr-3"></i><span>Liste
                                des offres</span></a>
                    </li>
                    <li class="nav-item mt-1">
                        <a class="nav-link text-capitalize disabled sidebar-link-presentation" href="#" tabindex="-1" aria-disabled="true">Pages</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link link-sidebar <?= (basename($_SERVER['PHP_SELF']) == "index.php") ? "active" : ""; ?>"><i class="fas fa-home mr-3"></i>Acceuil</a>
                    </li>
                    <li class="nav-item">
                        <a href="sejours-administration.php" class="nav-link link-sidebar <?= (basename($_SERVER['PHP_SELF']) == "sejours-administration.php") ? "active" : ""; ?>"><i class="fas fa-umbrella-beach mr-3"></i>Séjours</a>
                    </li>
                    <li class="nav-item">
                        <a href="hotels-administration.php" class="nav-link link-sidebar <?= (basename($_SERVER['PHP_SELF']) == "hotels-administration.php") ? "active" : ""; ?>"><i class="fas fa-bed mr-3"></i>Hôtels</a>
                    </li>
                    <li class="nav-item">
                        <a href="vols-administration.php" class="nav-link link-sidebar <?= (basename($_SERVER['PHP_SELF']) == "vols-administration.php") ? "active" : ""; ?>"><i class="fas fa-plane mr-3"></i>Vols</a>
                    </li>
                    <li class="nav-item mt-1">
                        <a class="nav-link text-capitalize disabled sidebar-link-presentation" href="#" tabindex="-1" aria-disabled="true">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a href="liste-reservations-sejours.php" class="nav-link link-sidebar <?= (basename($_SERVER['PHP_SELF']) == ("liste-reservations-sejours.php") or basename($_SERVER['PHP_SELF']) == ("liste-reservations-hotels.php") or basename($_SERVER['PHP_SELF']) == ("liste-reservations-vols.php")) ? "active" : ""; ?>"><i class="fas fa-cart-arrow-down mr-3"></i><span>Liste
                                des réservations</span></a>
                    </li>
                    <li class="nav-item mt-1">
                        <a class="nav-link text-capitalize disabled sidebar-link-presentation" href="#" tabindex="-1" aria-disabled="true">Clients</a>
                    </li>
                    <li class="nav-item mb-4">
                        <a href="liste-clients.php" class="nav-link link-sidebar <?= (basename($_SERVER['PHP_SELF']) == "liste-clients.php") ? "active" : ""; ?>"><i class="fas fa-users mr-3"></i>Liste des clients</a>
                    </li>
                </ul>
            </nav>
        </div>
        <!------------ FIN SIDEBAR------------>

        <!------------------------------- LA PAGE CONTAINER ------------------------------->
        <div class="container mb-4">