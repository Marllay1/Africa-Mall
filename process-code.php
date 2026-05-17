<?php
session_start();

// Exemple de code attendu (dans un vrai cas, ça devrait venir d'une base de données ou être stocké temporairement dans $_SESSION)
$code_attendu = "123456";

// Récupération du code envoyé (via AJAX par exemple)
$code_saisi = isset($_POST['code']) ? $_POST['code'] : '';

// Vérification
if ($code_saisi === $code_attendu) {
    echo json_encode([
        'success' => true,
        'message' => "Code correct",
        'redirect' => 'congrats.php'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => "Le code saisi est incorrect."
    ]);
}
