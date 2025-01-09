<?php


$ip_address = $_SERVER['REMOTE_ADDR'];

// Incrémentation du compteur de tentatives échouées
$_SESSION['attempts'][$ip_address]['count']++;
$_SESSION['attempts'][$ip_address]['last_attempt'] = time();

if ($_SESSION['attempts'][$ip_address]['count'] >= MAX_ATTEMPTS) {
    // Bloquer l'utilisateur pour un certain temps après plusieurs échecs
    $_SESSION['attempts'][$ip_address]['blocked_until'] = time() + BLOCK_TIME;
   $_SESSION['errors'][] = "Trop de tentatives de connexion. Réessayez dans 5 minutes.";
} else {
    $remaining_attempts = MAX_ATTEMPTS - $_SESSION['attempts'][$ip_address]['count'];
   $_SESSION['errors'][] = "Identifiants incorrects. Il vous reste $remaining_attempts tentatives.";
}