<?php
session_start();

if (!$_SESSION['user']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion.php');
    exit();
} elseif (!empty($_GET['id_sejour']) && !empty($_GET['id_client'])) {
    require_once 'assets/config/database.php';
    $req = $db->prepare("SELECT * FROM reservation_sejour WHERE id_client = ? AND id_sejour = ? ");
    $req->execute(
        [
            $_GET['id_client'],
            $_GET['id_sejour']
        ]
    );
    $res_sejour = $req->fetch();
    if ($res_sejour) {
        $req = $db->prepare("DELETE FROM reservation_sejour WHERE id_client = ? AND id_sejour = ? ");
        $req->execute(
            [
                $_GET['id_client'],
                $_GET['id_sejour']
            ]
        );
        $_SESSION['flash']['success'] = "La réservation du séjour a été annulée !";
        header('Location: ' . $_SERVER['HTTP_REFERER'] );
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucune réservation de séjour qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner la réservation du séjour que vous voulez annuler !";
    header('location:index.php');
    exit();
}
