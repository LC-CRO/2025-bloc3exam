<?php
const MAX_ATTEMPTS = 5;
const BLOCK_TIME = 300; // 5 minutes (300 secondes)
$ip_address = $_SERVER['REMOTE_ADDR'];

// Initialisation des variables de session pour le suivi des tentatives
if (!isset($_SESSION['attempts'][$ip_address])) {
    $_SESSION['attempts'][$ip_address] = [
        'count' => 0,
        'last_attempt' => time(),
        'blocked_until' => 0
    ];
}


if ($_SESSION['attempts'][$ip_address]['blocked_until'] > time()) {
    $remaining_time = $_SESSION['attempts'][$ip_address]['blocked_until'] - time(); // Temps restant en secondes
    $minutes = floor($remaining_time / 60);
    $seconds = $remaining_time % 60;
   $_SESSION['errors'][] = "Trop de tentatives. Veuillez réessayer dans $minutes minute(s) et $seconds seconde(s).";
}
