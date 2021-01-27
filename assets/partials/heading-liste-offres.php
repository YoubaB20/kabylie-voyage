<!-- LE TITRE DE PRESENTATION LISTE DES OFFRES -->
<div class="row">
    <div class="col-12">
        <h4 class="text-upercase">LISTE DES OFFRES</h4>
    </div>
</div>
<!-- FIN DU TITRE DE PRESENTATION LISTE DES OFFRES -->

<!-- LA BARRE DE NAVIGATION LISTE DES OFFRES -->
<div class="row mt-2">
    <div class="col-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "liste-sejours.php") ? "active" : ""; ?>" href="liste-sejours.php">Séjours</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "liste-hotels.php") ? "active" : ""; ?>" href="liste-hotels.php">Hôtels</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "liste-vols.php") ? "active" : ""; ?>" href="liste-vols.php">Vols</a>
            </li>
        </ul>
    </div>
</div>
<!-- FIN DE LA BARRE DE NAVIGATION LISTE DES OFFRES -->