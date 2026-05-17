<?php
// register.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Créer votre compte</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CSS + Fonts + Icons -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-orange-50 min-h-screen px-6 py-4 flex flex-col items-center">

  <!-- Flèche retour -->
  <a href="compte_commercant.php" class="self-start text-orange-500 text-2xl mb-4">&#8592;</a>

  <!-- Titre -->
  <h1 class="text-xl font-semibold text-orange-600 mb-6 text-center">Créer votre compte</h1>

  <!-- Formulaire principal -->
  <form action="confirm.php" method="post" class="flex flex-col gap-3 items-center w-full max-w-md bg-white p-6 rounded shadow-md">

    <!-- Régime de l'entreprise -->
    <div class="flex items-center w-full border border-gray-300 rounded-md px-3 py-2">
      <span class="material-icons text-gray-400 mr-2">assignment</span>
      <input type="text" name="regime" placeholder="Régime de l'entreprise" class="flex-1 focus:outline-none text-sm" required>
    </div>

    <!-- Adresse professionnelle -->
    <div class="flex items-center w-full border border-gray-300 rounded-md px-3 py-2">
      <span class="material-icons text-gray-400 mr-2">location_on</span>
      <input type="text" name="adresse" placeholder="Adresse professionnelle" class="flex-1 focus:outline-none text-sm" required>
    </div>
    
    <button type="button" class="text-blue-600 text-xs underline self-start mb-2"
  onclick="window.open('map_localisation.php', '_blank', 'width=800,height=600')">
  Définir via Map
</button>


    <!-- Mode de paiement -->
    <div class="flex items-center w-full border border-gray-300 rounded-md px-3 py-2">
      <span class="material-icons text-gray-400 mr-2">payments</span>
      <select id="modePaiement" name="mode" onchange="afficherChamps()" class="flex-1 focus:outline-none text-sm">
        <option value="">-- Sélectionnez un mode de paiement --</option>
        <option value="orange">Orange Money / MTN Money</option>
        <option value="paypal">PayPal</option>
        <option value="banque">Compte bancaire</option>
      </select>
    </div>

    <!-- Champs dynamiques -->

    <!-- Orange Money -->
    <div id="champOrange" class="w-full" style="display: none;">
      <label class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
      <input type="tel" name="numero_om" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Ex: 07xxxxxxx">
    </div>

    <!-- PayPal -->
    <div id="champPaypal" class="w-full" style="display: none;">
      <label class="block text-sm font-medium text-gray-700">Adresse Email PayPal</label>
      <input type="email" name="email_paypal" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="exemple@paypal.com">
    </div>

    <!-- Compte Bancaire -->
    <div id="champBanque" class="w-full" style="display: none;">
      <label class="block text-sm font-medium text-gray-700">Nom de la banque</label>
      <input type="text" name="nom_banque" class="w-full border border-gray-300 rounded px-3 py-2 mb-2">

      <label class="block text-sm font-medium text-gray-700">Numéro de compte</label>
      <input type="text" name="numero_compte" class="w-full border border-gray-300 rounded px-3 py-2 mb-2">

      <label class="block text-sm font-medium text-gray-700">Nom du titulaire</label>
      <input type="text" name="titulaire_compte" class="w-full border border-gray-300 rounded px-3 py-2">
    </div>

<!-- Devise -->
<div class="flex items-center w-full border border-gray-300 rounded-md px-3 py-2 bg-white">
  <span class="material-icons text-gray-400 mr-2">attach_money</span>
  <select name="devise" class="flex-1 focus:outline-none text-sm">
    <option value="">Choisissez une devise</option>
    <option value="XOF">Franc CFA (XOF)</option>
    <option value="USD">Dollar américain (USD)</option>
    <option value="EUR">Euro (EUR)</option>
    <option value="GBP">Livre sterling (GBP)</option>
    <option value="CAD">Dollar canadien (CAD)</option>
    <option value="CNY">Yuan chinois (CNY)</option>
    <option value="JPY">Yen japonais (JPY)</option>
    <option value="AUD">Dollar australien (AUD)</option>
    <option value="ZAR">Rand sud-africain (ZAR)</option>
    <option value="NGN">Naira nigérian (NGN)</option>
  </select>
</div>


    <!-- Bouton -->
    <button type="submit" class="mt-4 bg-orange-500 hover:bg-orange-600 text-white text-sm px-6 py-2 rounded">
      Continuer
    </button>

    <!-- Infos bas -->
    <div class="text-center mt-6 text-xs text-gray-600 px-4">
      <p>Le Compte Commercial est uniquement destiné aux personnes disposant de boutiques.</p>
      <p class="mt-2">Vous avez déjà un compte ? <a href="#" class="text-blue-600 font-semibold">Connectez-vous</a></p>
    </div>

  </form>

  <!-- Script de gestion des champs dynamiques -->
  <script>
    function afficherChamps() {
      const mode = document.getElementById('modePaiement').value;
      document.getElementById('champOrange').style.display = 'none';
      document.getElementById('champPaypal').style.display = 'none';
      document.getElementById('champBanque').style.display = 'none';

      if (mode === 'orange') {
        document.getElementById('champOrange').style.display = 'block';
      } else if (mode === 'paypal') {
        document.getElementById('champPaypal').style.display = 'block';
      } else if (mode === 'banque') {
        document.getElementById('champBanque').style.display = 'block';
      }
    }
  </script>

</body>
</html>
<?php

// Inclure PHPMailer si utilisé
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Configuration Orange API
define('ORANGE_CLIENT_ID', 'VOTRE_CLIENT_ID');
define('ORANGE_CLIENT_SECRET', 'VOTRE_SECRET');
define('ORANGE_PHONE_SENDER', 'VOTRE_NUMÉRO');

// Twilio (optionnel)
define('TWILIO_SID', 'VOTRE_TWILIO_SID');
define('TWILIO_TOKEN', 'VOTRE_TWILIO_AUTH_TOKEN');
define('TWILIO_NUMBER', 'VOTRE_TWILIO_PHONE');

// Africa’s Talking (optionnel)
define('AFRICASTALKING_USERNAME', 'VOTRE_USERNAME');
define('AFRICASTALKING_API_KEY', 'VOTRE_API_KEY');

// Traitement des données du formulaire
$regime = $_POST['regime'] ?? '';
$adresse = $_POST['adresse'] ?? '';
$mode = $_POST['mode'] ?? '';
$devise = $_POST['devise'] ?? '';

// Champs dynamiques
$numero_om = $_POST['numero_om'] ?? '';
$email_paypal = $_POST['email_paypal'] ?? '';
$nom_banque = $_POST['nom_banque'] ?? '';
$numero_compte = $_POST['numero_compte'] ?? '';
$titulaire_compte = $_POST['titulaire_compte'] ?? '';

// Génération du code de confirmation
$code = random_int(100000, 999999);

// Stockage temporaire
$_SESSION['code_confirmation'] = $code;
$_SESSION['email'] = $email_paypal;
$_SESSION['numero_om'] = $numero_om;

// Fonction pour envoyer un email avec PHPMailer
function envoyerMail($email, $code) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io'; // Ex : smtp.gmail.com
        $mail->SMTPAuth = true;
        $mail->Username = 'VOTRE_SMTP_USERNAME';
        $mail->Password = 'VOTRE_SMTP_PASSWORD';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('noreply@votresite.com', 'Votre Application');
        $mail->addAddress($email);
        $mail->Subject = 'Code de confirmation';
        $mail->Body = "Votre code de confirmation est : $code";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Fonction d’envoi via Orange API
function envoyerSMSOrange($numero, $code) {
    // Authentification
    $ch = curl_init('https://api.orange.com/oauth/v3/token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode(ORANGE_CLIENT_ID . ':' . ORANGE_CLIENT_SECRET),
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tokenData = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (!isset($tokenData['access_token'])) return false;

    $accessToken = $tokenData['access_token'];
    $numeroFormat = 'tel:+'.preg_replace('/[^0-9]/', '', $numero);

    // Envoi SMS
    $messageData = [
        'outboundSMSMessageRequest' => [
            'address' => $numeroFormat,
            'senderAddress' => ORANGE_PHONE_SENDER,
            'outboundSMSTextMessage' => ['message' => "Votre code est : $code"]
        ]
    ];

    $ch = curl_init('https://api.orange.com/smsmessaging/v1/outbound/' . urlencode(ORANGE_PHONE_SENDER) . '/requests');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return strpos($result, 'resourceReference') !== false;
}

// Fonction d’envoi avec Twilio
function envoyerSMSTwilio($numero, $code) {
    $url = 'https://api.twilio.com/2010-04-01/Accounts/' . TWILIO_SID . '/Messages.json';
    $data = [
        'To' => $numero,
        'From' => TWILIO_NUMBER,
        'Body' => "Votre code est : $code"
    ];
    $post = http_build_query($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, TWILIO_SID . ':' . TWILIO_TOKEN);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return strpos($response, '"status"') !== false;
}

// Envoi via Africa’s Talking (optionnel)
function envoyerSMSAfrica($numero, $code) {
    $ch = curl_init("https://api.africastalking.com/version1/messaging");
    $data = [
        'username' => AFRICASTALKING_USERNAME,
        'to' => $numero,
        'message' => "Votre code est : $code"
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apiKey: ' . AFRICASTALKING_API_KEY,
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    curl_close($ch);

    return strpos($res, 'SMSMessageData') !== false;
}

// Décision selon le mode de paiement
$envoiEffectue = false;

if ($mode === 'paypal' && !empty($email_paypal)) {
    $envoiEffectue = envoyerMail($email_paypal, $code);
} elseif ($mode === 'orange' && !empty($numero_om)) {
    $envoiEffectue = envoyerSMSOrange($numero_om, $code);
    if (!$envoiEffectue) {
        // fallback
        $envoiEffectue = envoyerSMSTwilio($numero_om, $code);
    }
} elseif ($mode === 'banque') {
    // Option : envoyer par email si email présent
    if (!empty($email_paypal)) {
        $envoiEffectue = envoyerMail($email_paypal, $code);
    }
}

// Résultat
if ($envoiEffectue) {
    echo "Code de confirmation envoyé avec succès.";
    // Rediriger vers une page de validation ?
    // header("Location: valider_code.php");
} else {
    echo "Échec de l'envoi du code.";
}
?>
