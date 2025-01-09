<?php
require('config.php');

if (isset($_GET['id'])) {
    $bookId = intval($_GET['id']); // Sécurisation de l'ID reçu via GET

    // Récupérez les détails du livre depuis la base de données
    $query = "SELECT * FROM livres WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $bookId]);

    if ($stmt->rowCount() == 1) {
        $book = $stmt->fetch();
    } else {
        // Livre non trouvé, gestion de l'erreur
        $_SESSION['errors'][] = "Livre non trouvé.";
        header('Location: books.php');
        exit();
    }
} else {
    $_SESSION['errors'][] = "Aucun livre sélectionné.";
    header('Location: books.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Détails du Livre</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .book-image {
            max-width: 30%;
            height: auto;
            display: block;
            margin: 0 auto; /* Pour centrer l'image */
        }
        .back-button {
            margin-top: 20px;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            color: white;
        }
        .borrow-button {
            background-color: #28a745;
        }
        .back-button button {
            background-color: #007bff;
        }
    </style>
</head>
<body>
<header>
    <h1>Détails du Livre</h1>
</header>
<div class="container">
    <div class="details">
        <?php if (isset($book)) : ?>
            <h3><?= htmlspecialchars($book['titre']); ?></h3>
            <img class="book-image" src="<?= htmlspecialchars($book['photo_url']); ?>" alt="<?= htmlspecialchars($book['titre']); ?>">
            <p>Auteur : <?= htmlspecialchars($book['auteur']); ?></p>
            <p>Année de publication : <?= htmlspecialchars($book['date_publication']); ?></p>
            <p>ISBN : <?= htmlspecialchars($book['isbn']); ?></p>
            <p>Status : <?= htmlspecialchars($book['statut']); ?></p>
        <?php else : ?>
            <p>Livre non trouvé</p>
        <?php endif; ?>
    </div>

    <div class="back-button">
        <button onclick="window.location.href = 'books.php'">Retour à la liste des livres</button>


        <?php if (isset($book) && $book['statut'] === 'disponible') : ?>
            <form method="POST" action="borrow_book.php" style="display: inline-block;">
                <input type="hidden" name="book_id" value="<?= $bookId; ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                <button type="submit" class="borrow-button">Emprunter ce livre</button>
            </form>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
            <!-- Boutons "Modifier" et "Supprimer" pour l'admin -->
            <button onclick="window.location.href = 'edit_book.php?book_id=<?= $bookId; ?>'">Modifier le livre</button>
            <button onclick="showDeleteConfirmation(<?= $bookId; ?>)">Supprimer le livre</button>
        <?php endif; ?>
    </div>
</div>

<script>
    function showDeleteConfirmation(bookId) {
        if (confirm("Êtes-vous sûr de vouloir supprimer ce livre ?")) {
            window.location.href = "delete_book.php?book_id=" + bookId;
        }
    }
</script>
</body>
</html>
