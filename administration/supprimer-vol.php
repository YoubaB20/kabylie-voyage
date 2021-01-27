<?php
session_start();

if (!$_SESSION['admin']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit();
}

elseif (!empty($_GET['id_vol'])) {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM vols WHERE id_vol = ?');
    $req->execute([$_GET['id_vol']]);
    $vol = $req->fetch();
    if ($vol) {
        $req = $db->query('DELETE FROM vols WHERE id_vol = ' . $_GET['id_vol']);
        $_SESSION['flash']['success'] = "Le vol a bien été supprimée ";
        header('location:liste-vols.php');
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucun vol qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le vol que vous voulez supprimer !";
    header('location:index.php');
    exit();
}
