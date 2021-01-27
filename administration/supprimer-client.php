<?php
session_start();

if (!$_SESSION['admin']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit();
}

elseif (!empty($_GET['id_client'])) {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM clients WHERE id_client = ?');
    $req->execute([$_GET['id_client']]);
    $client = $req->fetch();
    if ($client) {
        $req = $db->query('DELETE FROM clients WHERE id_client = ' . $_GET['id_client']);
        $_SESSION['flash']['success'] = "Le client a bien été supprimé ";
        header('location:liste-clients.php');
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucun client qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le client que vous voulez supprimer !";
    header('location:index.php');
    exit();
}
