<?php
require('config.php');
require('security_header.php');
require('security_user.php');
require('security_csrf.php');

$id_utilisateur = $_SESSION['user_id'];

$query = "SELECT e.id_emprunt, l.titre, e.date_emprunt, e.date_retour_prevue, e.date_retour_effective, l.id AS id_livre FROM emprunts e 
          JOIN livres l ON e.id_livre = l.id 
          WHERE e.id_utilisateur = :id_utilisateur";
$stmt = $pdo->prepare($query);
$stmt->execute([':id_utilisateur' => $id_utilisateur]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes emprunts - Librairie XYZ</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .return-button {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>
<header>
    <h1>Mes emprunts</h1>
</header>

<div class="container">
    <?php if ($stmt->rowCount() > 0) : ?>
        <table>
            <tr>
                <th>Titre</th>
                <th>Date d'emprunt</th>
                <th>Date de retour prévue</th>
                <th>Date de retour effective</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['titre']); ?></td>
                    <td><?= htmlspecialchars($row['date_emprunt']); ?></td>
                    <td><?= htmlspecialchars($row['date_retour_prevue']); ?></td>
                    <td><?= $row['date_retour_effective'] ? htmlspecialchars($row['date_retour_effective']) : "Non retourné"; ?></td>
                    <td>
                        <?= $row['date_retour_effective'] ? "Terminé" : "En cours"; ?>
                        <?php if (strtotime($row['date_retour_prevue']) < time() && !$row['date_retour_effective']) : ?>
                            <span style="color: red;">(Retard)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$row['date_retour_effective']) : ?>
                            <!-- Formulaire pour retourner le livre -->
                            <form method="POST" action="return_book.php" style="display: inline-block;">
                                <input type="hidden" name="emprunt_id" value="<?= $row['id_emprunt']; ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <button type="submit" class="return-button">Retourner le livre</button>
                            </form>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>Aucun emprunt en cours.</p>
    <?php endif; ?>
    <button onclick="window.location.href = 'books.php'">Retour aux livres</button>
</div>
</body>
</html>
