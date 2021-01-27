<!-- LE TITRE DE PRESENTATION D'AJOUT D'OFFRE -->
<div class="row">
    <div class="col-12">
        <h4 class="text-uppercase">AJOUTER UNE OFFRE</h4>
    </div>
</div>
<!-- FIN DU TITRE DE PRESENTATION D'AJOUT D'OFFRE-->

<!-- LA BARRE DE NAVIGATION D'AJOUT D'OFFRE -->
<div class="row mt-2">
    <div class="col-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "ajouter-sejour.php") ? "active" : ""; ?>" href="ajouter-sejour.php">Séjours</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "ajouter-hotel.php") ? "active" : ""; ?>" href="ajouter-hotel.php">Hôtels</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == "ajouter-vol.php") ? "active" : ""; ?>" href="ajouter-vol.php">Vols</a>
            </li>
        </ul>
    </div>
</div>
<!-- FIN DE LA BARRE DE NAVIGATION D'AJOUT D'OFFRE -->