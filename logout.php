<?php
require('security_header.php');

// Vérification que la clé 'attempts' et 'blocked_until' existent avant de les utiliser
if (!isset($_SESSION['attempts'][$_SERVER['REMOTE_ADDR']]['blocked_until']) ||
    $_SESSION['attempts'][$_SERVER['REMOTE_ADDR']]['blocked_until'] <= time()) {
    // Si la session n'existe pas ou si l'utilisateur n'est pas bloqué
    session_unset();  // Supprimer toutes les variables de session
    session_destroy();  // Détruire la session
}

header('Location: index.php');
exit();
?>
