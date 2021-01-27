<?php
session_start();
if (!$_SESSION['admin']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit();
}

elseif (!empty($_GET['id_reservation_vol'])) {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM reservation_vol WHERE id_reservation_vol = ?');
    $req->execute([$_GET['id_reservation_vol']]);
    $vol = $req->fetch();
    if ($vol) {
        $req = $db->query('DELETE FROM reservation_vol WHERE id_reservation_vol = ' . $_GET['id_reservation_vol']);
        $_SESSION['flash']['success'] = "La réservation de vol a bien été supprimée ";
        header('location:liste-reservations-vols.php');
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucune réservation de vol qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner la réservation de vol que vous voulez supprimer !";
    header('location:index.php');
    exit();
}
