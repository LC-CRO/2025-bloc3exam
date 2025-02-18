<?php
require('config.php');

require('security_header.php');
require('security_user.php');
require('security_csrf.php');
require('security_admin.php');

// Assurez-vous que l'ID du livre que vous souhaitez supprimer est passé en tant que paramètre (par exemple, dans l'URL).
if (!isset($_GET['book_id'])) {
    header('Location: books.php'); // Redirigez l'utilisateur vers la liste des livres ou une autre page appropriée.
    exit();
}

$book_id = $_GET['book_id'];

// Supprimez le livre de la base de données en utilisant l'ID du livre
$deleteQuery = "DELETE FROM livres WHERE id = :book_id";
$deleteStmt = $pdo->prepare($deleteQuery);
$deleteStmt->execute(array(':book_id' => $book_id));

// Redirigez l'utilisateur vers la liste des livres ou une autre page appropriée.
header('Location: books.php');
exit();
?>
