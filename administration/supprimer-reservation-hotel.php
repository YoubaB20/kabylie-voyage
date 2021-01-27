<?php
session_start();
if (!$_SESSION['admin']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit();
}

elseif (!empty($_GET['id_reservation_hotel'])) {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM reservation_hotel WHERE id_reservation_hotel = ?');
    $req->execute([$_GET['id_reservation_hotel']]);
    $hotel = $req->fetch();
    if ($hotel) {
        $req = $db->query('DELETE FROM reservation_hotel WHERE id_reservation_hotel = ' . $_GET['id_reservation_hotel']);
        $_SESSION['flash']['success'] = "La réservation d'hôtel a bien été supprimée ";
        header('location:liste-reservations-hotels.php');
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucune réservation d'hôtel qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner la réservation d'hôtel que vous voulez supprimer !";
    header('location:index.php');
    exit();
}
