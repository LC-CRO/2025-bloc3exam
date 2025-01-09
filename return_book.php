<?php
require('config.php');
require('security_header.php');
require('security_user.php');
require('security_csrf.php'); // Protection CSRF

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['emprunt_id']) || !is_numeric($_POST['emprunt_id'])) {
        $_SESSION['errors'][] = "Emprunt invalide.";
        header('Location: view_borrowings.php');
        exit();
    }

    $empruntId = intval($_POST['emprunt_id']);
    $id_utilisateur = $_SESSION['user_id'];

    // Vérification que l'emprunt appartient bien à l'utilisateur
    $query = "SELECT id_livre FROM emprunts WHERE id_emprunt = :id_emprunt AND id_utilisateur = :id_utilisateur AND date_retour_effective IS NULL";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id_emprunt' => $empruntId, ':id_utilisateur' => $id_utilisateur]);
    $emprunt = $stmt->fetch();

    if (!$emprunt) {
        $_SESSION['errors'][] = "Vous ne pouvez pas retourner cet emprunt.";
        header('Location: view_borrowings.php');
        exit();
    }

    // Mettre à jour la date de retour effective
    $dateRetourEffective = date('Y-m-d');
    $query = "UPDATE emprunts SET date_retour_effective = :date_retour_effective WHERE id_emprunt = :id_emprunt";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':date_retour_effective' => $dateRetourEffective,
        ':id_emprunt' => $empruntId
    ]);

    // Remettre le statut du livre à "disponible"
    $query = "UPDATE livres SET statut = 'disponible' WHERE id = :id_livre";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id_livre' => $emprunt['id_livre']]);

    $_SESSION['success'] = "Le livre a été retourné avec succès.";
    header('Location: view_borrowings.php');
    exit();
} else {
    $_SESSION['errors'][] = "Requête invalide.";
    header('Location: view_borrowings.php');
    exit();
}
