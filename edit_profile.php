<?php

require('security_header.php');
require('security_user.php');
require('security_csrf.php');

?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier le Profil</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<header>
        <h1>Modifier votre profil - Librairie XYZ</h1>
    </header>
    <form method="post" action="update_profile.php">
        <label for="new_name">Nouveau Nom :</label>
        <input type="text" name="new_name" required>
        <br>
        <label for="new_email">Nouvel Email :</label>
        <input type="email" name="new_email" required>
        <br>
        <!-- Ajoutez d'autres champs à mettre à jour ici -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <button type="submit">Enregistrer les Modifications</button>
    </form>
   <button onclick="window.location.href ='profile.php'">Retour au Profil</button>
    <button onclick="window.location.href ='index.php'">Retour à l'accueil</button>
</body>
</html>
