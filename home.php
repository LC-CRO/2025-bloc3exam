<?php
require('config.php');
require('security_header.php');
require('security_user.php');

// Récupérer le nombre total de livres
$queryTotalBooks = "SELECT COUNT(*) as total_books FROM livres";
$stmtTotalBooks = $pdo->prepare($queryTotalBooks);
$stmtTotalBooks->execute();
$resultTotalBooks = $stmtTotalBooks->fetch(PDO::FETCH_ASSOC);

// Récupérer le nombre d'utilisateurs enregistrés
$queryTotalUsers = "SELECT COUNT(*) as total_users FROM utilisateurs";
$stmtTotalUsers = $pdo->prepare($queryTotalUsers);
$stmtTotalUsers->execute();
$resultTotalUsers = $stmtTotalUsers->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur connecté a des emprunts en retard (> 30 jours)
$id_utilisateur = $_SESSION['user_id'];
$queryLateBorrowings = "SELECT COUNT(*) as late_count FROM emprunts WHERE id_utilisateur = :id_utilisateur AND date_retour_effective IS NULL AND DATEDIFF(NOW(), date_emprunt) > 30";
$stmtLateBorrowings = $pdo->prepare($queryLateBorrowings);
$stmtLateBorrowings->execute([':id_utilisateur' => $id_utilisateur]);
$resultLateBorrowings = $stmtLateBorrowings->fetch(PDO::FETCH_ASSOC);
$lateCount = $resultLateBorrowings['late_count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        .alert-late {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<header>
    <h1>Librairie XYZ</h1>
</header>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <ul>
            <?php if (isset($_SESSION['user'])) : ?>
                <li>Bonjour <?= htmlspecialchars($_SESSION['prenom']);  ?> <?= htmlspecialchars($_SESSION['nom']);  ?></li>
                <li><a href="books.php">Voir la liste des livres</a></li>
                <li><a href="profile.php">Mon profil</a></li>
                <li><a href="view_borrowings.php">Voir mes emprunts</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            <?php else : ?>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <div class="container">
            <!-- Affichage du message en cas d'emprunt dépassant 30 jours -->
            <?php if ($lateCount > 0) : ?>
                <div class="alert-late">
                    <strong>Attention !</strong> Vous avez <?= $lateCount; ?> emprunt(s) en retard de plus de 30 jours. Veuillez les retourner dès que possible.
                </div>
            <?php endif; ?>

            <!-- Dashboard principal -->
            <h1>Dashboard</h1>
            <div class="statistic">
                <h3>Total des Livres</h3>
                <p><?= htmlspecialchars($resultTotalBooks['total_books']); ?></p>
            </div>

            <div class="statistic">
                <h3>Utilisateurs Enregistrés</h3>
                <p><?= htmlspecialchars($resultTotalUsers['total_users']); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; <?= date("Y"); ?> Librairie XYZ</p>
    </div>
</footer>
</body>
</html>
