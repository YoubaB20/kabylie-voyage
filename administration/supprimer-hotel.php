<?php
session_start();

if (!$_SESSION['admin']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit();
}

elseif (!empty($_GET['id_hotel'])) {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM hotels WHERE id_hotel = ?');
    $req->execute([$_GET['id_hotel']]);
    $hotel = $req->fetch();
    if ($hotel) {
        $req = $db->query('DELETE FROM hotels WHERE id_hotel = ' . $_GET['id_hotel']);
        $_SESSION['flash']['success'] = "L'hotel a bien été supprimé ";
        header('location:liste-hotels.php');
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucun hôtel qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner l'hôtel que vous voulez supprimer !";
    header('location:index.php');
    exit();
}
