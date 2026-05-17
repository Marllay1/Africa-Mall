<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue - AfricaMall</title>
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
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .info-box {
            margin-bottom: 20px;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 5px;
            font-size: 16px;
            text-align: left;
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto 20px auto;
            display: block;
            border: 2px solid #EA580C;
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
            background: #d44f09;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Bienvenue</h2>

    <?php
        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : 'Nom non fourni';
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'Email non fourni';
        $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : 'Téléphone non fourni';
        $photo = isset($_FILES['photo']) && $_FILES['photo']['error'] == 0
                    ? 'uploads/' . basename($_FILES['photo']['name'])
                    : 'default.png'; // image par défaut si rien envoyé
    ?>

    <img src="<?php echo $photo; ?>" alt="Photo de profil" class="profile-photo">

    <div class="info-box"><strong>Nom :</strong> <?php echo $name; ?></div>
    <div class="info-box"><strong>Email :</strong> <?php echo $email; ?></div>
    <div class="info-box"><strong>Numéro de téléphone :</strong> <?php echo $phone; ?></div>

    <form action="home.php" method="post">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="phone" value="<?php echo $phone; ?>">
        <input type="hidden" name="photo" value="<?php echo $photo; ?>">
        <button type="submit" class="btn">Continuer</button>
    </form>
</div>

</body>
</html>
