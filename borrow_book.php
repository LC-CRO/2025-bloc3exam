<?php
require('config.php');
require('security_header.php');
require('security_user.php');
require('security_csrf.php'); // Protection CSRF

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['book_id']) || !is_numeric($_POST['book_id'])) {
        $_SESSION['errors'][] = "Livre invalide.";
        header('Location: books.php');
        exit();
    }

    $bookId = intval($_POST['book_id']);
    $userId = $_SESSION['user_id'];

    // Vérifier si le livre est disponible
    $query = "SELECT statut FROM livres WHERE id = :id_livre";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id_livre' => $bookId]);
    $book = $stmt->fetch();

    if (!$book || $book['statut'] !== 'disponible') {
        $_SESSION['errors'][] = "Ce livre n'est pas disponible.";
        header('Location: books.php');
        exit();
    }

    // Créer l'emprunt
    $dateEmprunt = date('Y-m-d');
    $dateRetourPrevue = date('Y-m-d', strtotime('+30 days'));

    $query = "INSERT INTO emprunts (id_utilisateur, id_livre, date_emprunt, date_retour_prevue) VALUES (:user_id, :book_id, :date_emprunt, :date_retour_prevue)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':user_id' => $userId,
        ':book_id' => $bookId,
        ':date_emprunt' => $dateEmprunt,
        ':date_retour_prevue' => $dateRetourPrevue
    ]);

    // Mettre à jour le statut du livre
    $query = "UPDATE livres SET statut = 'emprunté' WHERE id = :id_livre";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id_livre' => $bookId]);

    $_SESSION['success'] = "Le livre a été emprunté avec succès.";
    header('Location: view_borrowings.php');
    exit();
} else {
    $_SESSION['errors'][] = "Requête invalide.";
    header('Location: books.php');
    exit();
}
