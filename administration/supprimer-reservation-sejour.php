<?php
session_start();
if (!$_SESSION['admin']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit();
}

elseif (!empty($_GET['id_reservation_sejour'])) {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM reservation_sejour WHERE id_reservation_sejour = ?');
    $req->execute([$_GET['id_reservation_sejour']]);
    $sejour = $req->fetch();
    if ($sejour) {
        $req = $db->query('DELETE FROM reservation_sejour WHERE id_reservation_sejour = ' . $_GET['id_reservation_sejour']);
        $_SESSION['flash']['success'] = "La réservation de séjour a bien été supprimée ";
        header('location:liste-reservations-sejours.php');
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucune réservation de séjour qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner la réservation du séjour que vous voulez supprimer !";
    header('location:index.php');
    exit();
}
