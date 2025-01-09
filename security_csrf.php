<?php
//creation token si inexistant
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification que le token CSRF est défini
    if (!isset($_SESSION['csrf_token'])) {
        die('CSRF token manquant dans la session.');
    }

    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    } else {
        //si le token est ok ont le regénère pour le prochain
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
