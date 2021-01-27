<?php
session_start();
if (!$_SESSION['admin']) {
    $_SESSION['flash']['danger'] = "Vous devez vous connecter pour accéder à cette page !";
    header('location:connexion-admin.php');
    exit();
}

elseif (!empty($_GET['id_sejour'])) {
    require_once '../assets/config/database.php';
    $req = $db->prepare('SELECT * FROM sejours WHERE id_sejour = ?');
    $req->execute([$_GET['id_sejour']]);
    $sejour = $req->fetch();
    if ($sejour) {
        $req = $db->query('DELETE FROM sejours WHERE id_sejour = ' . $_GET['id_sejour']);
        $_SESSION['flash']['success'] = "Le séjour a bien été supprimée ";
        header('location:liste-sejours.php');
        exit();
    } else {
        $_SESSION['flash']['danger'] = "Il n'y a aucun séjour qui correspond à cet id !";
        header('location:index.php');
        exit();
    }
} else {
    $_SESSION['flash']['warning'] = "Vous devez sélectionner le séjour que vous voulez supprimer !";
    header('location:index.php');
    exit();
}
