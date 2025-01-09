<?php
// Vérifiez si l'utilisateur est authentifié et a le rôle approprié (par exemple, "admin" ou "gestionnaire") pour accéder à cette fonctionnalité.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
