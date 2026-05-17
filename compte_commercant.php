<?php
// compte_commercant.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Créer un compte commerçant</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-orange-50 min-h-screen px-6 py-4 flex flex-col justify-start items-center">


  <!-- Titre centré -->
  <h1 class="text-xl font-semibold text-orange-600 mb-6 text-center">Créer votre compte</h1>

  <!-- Image + Bouton Ajouter centrés -->
  <div class="flex flex-col items-center mb-6">
    <div id="preview" class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center text-white text-sm overflow-hidden">
      <span class="material-icons text-gray-700 text-4xl">account_circle</span>
    </div>
    <input type="file" id="photo" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(event)">
    <button type="button" onclick="document.getElementById('photo').click()" class="mt-2 bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-1 rounded transition duration-200">
      Ajouter une photo de profil
    </button>
  </div>

  <!-- Formulaire réduit et centré -->
  <form action="register.php" method="post" class="flex flex-col gap-3 items-center w-full max-w-xs">

    <!-- Nom entreprise -->
    <div class="flex items-center w-full border border-gray-300 rounded-md px-3 py-2 bg-white">
      <span class="material-icons text-gray-400 mr-2">business</span>
      <input type="text" name="entreprise" placeholder="Nom de l'entreprise" class="flex-1 focus:outline-none text-sm" required>
    </div>

    <!-- Email -->
    <div class="flex items-center w-full border border-gray-300 rounded-md px-3 py-2 bg-white">
      <span class="material-icons text-gray-400 mr-2">email</span>
      <input type="email" name="email" placeholder="Email" class="flex-1 focus:outline-none text-sm" required>
    </div>

    <!-- Téléphone -->
    <div class="flex items-center w-full border border-gray-300 rounded-md px-3 py-2 bg-white">
      <span class="material-icons text-gray-400 mr-2">phone</span>
      <input type="tel" name="phone" placeholder="Numéro de téléphone" class="flex-1 focus:outline-none text-sm" required>
    </div>

    <!-- Bouton Continuer réduit et centré -->
    <button type="submit" class="mt-4 bg-orange-500 hover:bg-orange-600 text-white text-sm px-6 py-1.5 rounded">
      Continuer
    </button>
  </form>

  <!-- Message bas -->
  <div class="text-center mt-6 text-xs text-gray-600 px-4">
    <p>Le Compte Commercial est Uniquement fait pour les gens qui disposent de boutiques.</p>
    <p class="mt-2">Avez-Vous déjà Un Compte ? <a href="#" class="text-blue-600 font-semibold">Connectez-Vous</a></p>
  </div>

  <!-- Script de prévisualisation -->
  <script>
    function previewPhoto(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById("preview");
          preview.innerHTML = "";
          const img = document.createElement("img");
          img.src = e.target.result;
          img.className = "object-cover w-full h-full rounded-full";
          preview.appendChild(img);
        };
        reader.readAsDataURL(file);
      }
    }
  </script>

</body>
</html>