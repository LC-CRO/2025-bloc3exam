<?php
require('config.php');
require('security_header.php');
require('security_csrf.php');
require('security_auth_attempts.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validation de l'email pour éviter des formats incorrects
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']); // Suppression des espaces superflus

    // Vérification que l'email est bien un format valide
    if (!$email || empty($password)) {
       $_SESSION['errors'][] = "Veuillez remplir tous les champs correctement.";
    } else {
        // Préparation de la requête SQL sécurisée pour récupérer l'utilisateur correspondant à l'email
        $query = "SELECT id, mot_de_passe, prenom, role FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        // Si un utilisateur est trouvé et que le mot de passe est vérifié
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Regénération de l'ID de session pour prévenir les attaques de fixation de session
            session_regenerate_id(true);

            // Stockage des informations de l'utilisateur dans la session
            $_SESSION['user'] = $email;
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['created'] = time(); // Ajout du timestamp pour la durée de session

            // Redirection vers la page d'accueil après une connexion réussie
            header('Location: index.php');
            exit;
        } else {
            require('security_auth_failed.php');
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<header>
        <h1>Connexion - Librairie XYZ</h1>
    </header>
    <form method="post" action="">
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
        <p>Vous n'avez pas de compte ? <a href="register.php">S'inscrire</a></p>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    </form>
    <?php require('security_errors.php'); ?>
</body>
</html>
