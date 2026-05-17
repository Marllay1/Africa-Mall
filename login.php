<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - AfricaMall</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #FFF7ED;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 60px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .back-btn {
            background: none;
            border: none;
            color: #555;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
            width: 85%;
        }

        .input-group input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #888;
        }

        .btn {
            width: 100%;
            background: #EA580C;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background: #EA580C;
        }

        .bottom-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .bottom-text a {
            color: #007bff;
            text-decoration: none;
        }

        .bottom-text a:hover {
            text-decoration: underline;
        }
    </style>

    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<button type="button" class="back-btn" onclick="history.back();"><i class="fas fa-arrow-left"></i> Retour</button>

<div class="container">

    <form action="verification.php" method="post">


        <h2>Reconnectez-vous</h2>

        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Adresse e-mail" required>
        </div>

        <div class="input-group">
            <i class="fas fa-phone"></i>
            <input type="tel" name="phone" placeholder="Numéro de téléphone" required>
        </div>

        <button type="submit" class="btn">Continuer</button>
    </form>

    <div class="bottom-text">
        Pas encore de compte ? <a href="compte_classique.php">Créez-en un !</a>
    </div>

</div>

</body>
</html>
