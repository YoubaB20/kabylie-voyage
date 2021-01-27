<?php
session_start();

if (!$_SESSION['user']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion.php');
    exit();
} elseif (!empty($_GET['id_hotel']) && !empty($_GET['id_client'])) {
    require_once 'assets/config/database.php';
    $req = $db->prepare("SELECT * FROM reservation_hotel WHERE id_client = ? AND id_hotel = ? ");
    $req->execute(
        [
            $_GET['id_client'],
            $_GET['id_hotel']
        ]
    );
    $res_hotel = $req->fetch();
    if ($res_hotel) {
        $req = $db->prepare("DELETE FROM reservation_hotel WHERE id_client = ? AND id_hotel = ? ");
        $req->execute(
            [
                $_GET['id_client'],
                $_GET['id_hotel']
            ]
        );
        $_SESSION['flash']['success'] = "La réservation d'hôtel a été annulée !";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucune réservation d'hôtel qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner la réservation d'hôtel que vous voulez annuler !";
    header('location:index.php');
    exit();
}
