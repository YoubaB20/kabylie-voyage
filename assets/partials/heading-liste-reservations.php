<!-- LE TITRE DE PRESENTATION LISTE DES RÉSERVATIONS -->
<div class="row">
    <div class="col-12">
        <h4 class="text-upercase">LISTE DES RÉSERVATIONS</h4>
    </div>
</div>
<!-- FIN DU TITRE DE PRESENTATION LISTE DES RÉSERVATIONS -->

<!-- LA BARRE DE NAVIGATION LISTE DES RÉSERVATIONS -->
<div class="row mt-2">
    <div class="col-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "liste-reservations-sejours.php") ? "active" : ""; ?>" href="liste-reservations-sejours.php">Séjours</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "liste-reservations-hotels.php") ? "active" : ""; ?>" href="liste-reservations-hotels.php">Hôtels</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "liste-reservations-vols.php") ? "active" : ""; ?>" href="liste-reservations-vols.php">Vols</a>
            </li>
        </ul>
    </div>
</div>
<!-- FIN DE LA BARRE DE NAVIGATION LISTE DES RÉSERVATIONS -->