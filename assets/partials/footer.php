</div>
<!------------------------------- FIN DU CONTAINER ------------------------------->

<!-- FOOTER -->
<div class="footer bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <h5 class="footer-title">KABYLIE Voyage</h5>
                <p>Réservez votre hôtel, vol et séjour à des conditions avantageuses. Agence émettrice et réceptive en Algérie..</p>
            </div>
            <div class="col-lg-6 col-md-6">
                <h5 class="footer-title">Contact</h5>
                <p><i class="fas fa-envelope text-white mr-2"></i>agence.kabylie.voyage15@gmail.com</p>
                <p><i class="fas fa-phone-square text-white mr-2"></i>+213 559224556</p>
                <p class="text-capitalize"><i class="fas fa-map-marker-alt text-white mr-2"></i>Université Mouloud MAMMERI, Faculté De Génie Électrique et d'informatique, Département informatique. </p>

            </div>
        </div>
    </div>
</div>
<!-- FIN DU FOOTER -->


<!------------------------------- LES SCRIPTS ------------------------------->
<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/bootstrap-datepicker.js"></script>
<script>
    // fonction auto resize text area
    $(document).ready(function() {
        var text = $('#text_area');
        text.on('change drop keydown cut paste', function() {
            text.height('auto');
            text.height(text.prop('scrollHeight'));
        });
    });
    // fin fonction auto resize text area

    // fonction pour cacher la div alert apres un laps de temps
    $().ready(function() {
        $('#alert-with-time').delay(4000);
        $('#alert-with-time').hide(1000);
    });
    // fin de fonction pour cacher la div alert apres un laps de temps

    // le date picker
    $('.datepicker').datepicker();
    // fin du date picker

    // CONFIRMATION DE LA SUPPRESSION AVEC MODAL
    $(document).ready(function() {
        // ON STOCK LE LIEN DE REDIRECTION EN CAS DE CONFIRMATION DE SUPPRESSION DANS LA VARIABLE LienSuppression
        var LienSuppression;

        // LORSQU'ON DETECTE UN CLICK SUR LE LIEN DE SUPPRESSION 
        $(".confirmModalLink").click(function(e) {
            // ON ANNULE LE COMPORTEMENT PAR DEFAUT DU LIEN AVEC e.preventDefault()
            e.preventDefault();
            LienSuppression = $(this).attr("href");
            $("#ConfirmDeleteModal").modal("show");
        });
        // ICI ON ANNULE LA SUPPRESSION 
        $("#confirmModalNo").click(function(e) {
            $("#ConfirmDeleteModal").modal("hide");
        });
        // ICI ON CONTINUE LA SUPPRESSION 
        $("#confirmModalYes").click(function(e) {
            window.location.href = LienSuppression;
        });

    });
</script>
</body>

</html>