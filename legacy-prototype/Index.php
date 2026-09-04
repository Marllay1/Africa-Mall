<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AfricaMall - Intro</title>

     <!-- Redirection automatique après 3 secondes -->
     <script>
      setTimeout(() => {
        window.location.href = "accueil.php";
      }, 6000);
    </script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font améliorée -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet" />

    <style>
      body {
        font-family: 'Great Vibes', cursive;
        background: linear-gradient(-45deg, #ff9a9e, #fad0c4, #fbc2eb, #a6c1ee);
        background-size: 400% 400%;
        animation: gradientBG 10s ease infinite;
      }

      @keyframes gradientBG {
        0% {
          background-position: 0% 50%;
        }
        50% {
          background-position: 100% 50%;
        }
        100% {
          background-position: 0% 50%;
        }
      }
    </style>
  </head>
  <body class="h-screen flex items-center justify-center overflow-hidden">

    <div id="app" class="relative w-full h-full flex items-center justify-center flex-col">
      <h1 id="introText" class="text-orange-500 text-5xl opacity-0 transition-all duration-1000 tracking-wide drop-shadow-lg">
        AW BISSIMILLAH
      </h1>
      <img
        id="logo"
        src="africa mall logo.png"
        alt="AfricaMall Logo"
        class="w-40 h-40 opacity-0 transition-all duration-1000 translate-y-20 mt-2"
      />
    </div>

    <script>
      const introText = document.getElementById("introText");
      const logo = document.getElementById("logo");

      // Affiche le texte
      setTimeout(() => {
        introText.classList.remove("opacity-0");
        introText.classList.add("opacity-100");
      }, 100);

      // Fait monter le texte + affiche le logo avec un espacement équilibré
      setTimeout(() => {
        introText.classList.add("-translate-y-20", "opacity-0", "transition-all", "duration-1000");
        logo.classList.remove("opacity-0", "translate-y-20");
        logo.classList.add("opacity-100", "translate-y-0", "transition-all", "duration-1000");
      }, 3000);
    </script>
  </body>
</html>
