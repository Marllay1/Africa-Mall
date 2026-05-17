<?php
// fonctionnalites.php
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <title>Africa Mall - Fonctionnalités</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

     <!-- Redirection automatique après 3 secondes -->
     <script>
      setTimeout(() => {
        window.location.href = "personnalisation.php";
      }, 3000);
    </script>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
      body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(to bottom right, #fffaf0, #fbe6d4);
      }
    </style>
  </head>
  <body class="min-h-screen flex flex-col items-center justify-center text-center px-4 py-8">

    <!-- Titre -->
    <h1 class="text-4xl font-bold text-orange-500 tracking-wider mb-6">AFRICA MALL</h1>

    <!-- Logo -->
    <img src="africa mall logo.png" alt="Logo AfricaMall" class="w-40 h-40 mb-6" />

    <!-- Pagination Dots -->
    <div class="flex space-x-2 mb-4">
      <span class="w-3 h-3 bg-white border border-gray-300 rounded-full"></span>
      <span class="w-3 h-3 bg-gray-500 rounded-full"></span>
      <span class="w-3 h-3 bg-white border border-gray-300 rounded-full"></span>
    </div>

   <!-- Bienvenue -->
     <h2 class="text-xl font-semibold mb-2">Fonctionnalités clés</h2>

    <!-- Message -->
    <div class="text-gray-700 max-w-md text-sm space-y-2">
      <p>Recherche par catégories, nom, prix, popularité</p>
      <p>Filtres avancés (localisation, disponibilité, promotions)</p>
      <p>Suggestions automatiques / Produits similaires</p>
    </div>

  </body>
</html>
