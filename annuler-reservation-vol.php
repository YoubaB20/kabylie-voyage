<?php
session_start();

if (!$_SESSION['user']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion.php');
    exit();
} elseif (!empty($_GET['id_vol']) && !empty($_GET['id_client'])) {
    require_once 'assets/config/database.php';
    $req = $db->prepare("SELECT * FROM reservation_vol WHERE id_client = ? AND id_vol = ? ");
    $req->execute(
        [
            $_GET['id_client'],
            $_GET['id_vol']
        ]
    );
    $res_vol = $req->fetch();
    if ($res_vol) {
        $req = $db->prepare("DELETE FROM reservation_vol WHERE id_client = ? AND id_vol = ? ");
        $req->execute(
            [
                $_GET['id_client'],
                $_GET['id_vol']
            ]
        );
        $_SESSION['flash']['success'] = "La réservation du vol a été annulée ! ";
        header('Location: ' . $_SERVER['HTTP_REFERER'] );
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucune réservation de vol qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner la réservation du vol que vous voulez annuler !";
    header('location:index.php');
    exit();
}
