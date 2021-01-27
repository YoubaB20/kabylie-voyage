<!-- si il n'y pas de session démmarée , de démmare une nouvelle session -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- on inclut le fichier functions.php -->
<?php require 'assets/partials/functions.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- le titre -->
    <title>
        <?php
        // si un titre a été saisi pour la page courante
        if (isset($title)) {
            echo $title . " " . "| Kabylie Voyage";
        } 
        // si aucun titre n'a été saisi
        else {
            echo "Kabylie Voyage | Agence de voyage en ligne";
        }
        ?>
    </title>
    <!-- LES STYLES -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/datepicker.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FIN LES STYLES -->
</head>

<body>

    <!------------------------------- LE HEADER ------------------------------->

    <header class="header">

        <!--------------- LA NAVBAR --------------->
        <nav class="navbar navbar-administration navbar-expand-lg fixed-top navbar-dark bg-dark shadow">
            <!-- logo de la navbar -->
            <a class="navbar-brand" href="index.php"><img src="assets/img/Logo-KabylieVoyage12.png" alt="Logo Kabylie Voyage"></a>
            <!-- logo de la navbar -->

            <!-- boutton toggler -->
            <button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- lien de la navbar collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto flex-nowrap ">
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "index.php") ? "active" : ""; ?>" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "sejours.php") ? "active" : ""; ?>" href="sejours.php">Séjours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "hotels.php") ? "active" : ""; ?>" href="hotels.php">Hôtels</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "vols.php") ? "active" : ""; ?>" href="vols.php">Vols</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "a-propos.php") ? "active" : ""; ?>" href="a-propos.php">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="administration/index.php">Administration</a>
                    </li>
                    <?php if (isset($_SESSION['user'])) : ?>
                        <!-- si un utilisateur est connecté -->
                        <li class="nav-item">
                            <a class="nav-link btn btn-secondary btn-sm text-white" href="deconnexion.php">Se déconnecter</a>
                        </li>
                        <!-- si aucun utilisateur est connecté -->
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light btn-sm text-dark btn-sing-in-navbar mr-1 <?= (basename($_SERVER['PHP_SELF']) == "inscription.php") ? "active" : ""; ?>" href="inscription.php">S'inscrire</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-sm text-white btn-log-in-navbar <?= (basename($_SERVER['PHP_SELF']) == "connexion.php") ? "active" : ""; ?>" href="connexion.php">Se connecter</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <!-- finboutton toggler -->


            <!-- fin lien de la navbar collapse-->
        </nav>
        <!--------------- FIN DE LA NAVBAR --------------->

    </header>
    <!------------------------------- FIN DE HEADER ------------------------------->

    <!------------------------------- LE CONTAINER ------------------------------->
    <div class="container">