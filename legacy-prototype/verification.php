<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification - AfricaMall</title>
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

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .back-btn {
            background: none;
            border: none;
            font-size: 16px;
            color: #555;
            cursor: pointer;
            margin-right: 10px;
        }

        h2 {
            flex: 1;
            text-align: center;
            font-size: 20px;
            margin: 0;
        }

        .info-box {
            margin-bottom: 20px;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 5px;
            font-size: 16px;
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
    </style>

    <!-- Font Awesome pour l'icône -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Vérifiez vos informations</h2>
    </div>

    <?php
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'Non fourni';
        $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : 'Non fourni';
    ?>

    <div class="info-box">
        <strong>Email :</strong> <?php echo $email; ?>
    </div>
    <div class="info-box">
        <strong>Numéro de téléphone :</strong> <?php echo $phone; ?>
    </div>

    <form action="conf.php" method="post">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="phone" value="<?php echo $phone; ?>">
        <button type="submit" class="btn">Continuer</button>
    </form>
</div>

</body>
</html>
