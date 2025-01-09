<?php
// Vérification de l'existence des erreurs dans la session
if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) : ?>
    <style>
        .error-container {
            display: flex;
            justify-content: center;  /* Centre horizontalement */
            align-items: center;      /* Centre verticalement */
            min-height: 100vh;        /* Prend toute la hauteur de la page */
            text-align: center;       /* Texte centré */
            background-color: #f8d7da; /* Couleur d'arrière-plan pour l'erreur */
        }

        .error-message {
            border: 1px solid #f5c2c7;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .error-message li {
            list-style-type: none; /* Supprime les puces de la liste */
            color: #d9534f; /* Couleur du texte rouge */
        }
    </style>

    <div class="error-container">
        <div class="error-message">
            <ul>
                <?php foreach ($_SESSION['errors'] as $error) : ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li> <!-- Protection XSS -->
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php
    // Supprimer les erreurs après affichage
    unset($_SESSION['errors']);
endif;
