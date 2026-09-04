<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Félicitations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #FFF7ED; /* bg-orange-50 */
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      margin-top: 40px;
    }
    .modal {
      background-color: #fff;
      border-radius: 16px;
      padding: 30px 20px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      margin-bottom: 200px;
    }
    .modal h2 {
      font-size: 22px;
      color: #16a34a; /* green-600 */
      margin-bottom: 10px;
    }
    .modal p {
      font-size: 16px;
      color: #555;
    }
    .start-btn {
      margin-top: 20px;
      background-color: #F97316; /* orange-500 */
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .start-btn:hover {
      background-color: #EA580C; /* orange-600 */
    }
  </style>
</head>
<body>

  <!-- Logo -->
  <div class="logo">
    <img src="africa mall logo.png" alt="Logo" width="120"> <!-- Remplace "logo.png" par ton image -->
  </div>

  <!-- Modal en bas -->
  <div class="modal">
    <h2>🎉 Félicitations !</h2>
    <p>Votre compte a été créé avec succès.<br>Bienvenue parmi nous !</p>

    <a href="dashboard.php">
      <button class="start-btn">Commencer</button>
    </a>
  </div>

</body>
</html>
