<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Achat Direct</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
  <title>Achat Direct</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
      text-align: center;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
    }

    header h1 {
      color: #F2791D;
      font-size: 18px;
      margin: 0 auto;
    }

    .back-arrow {
      font-size: 24px;
      cursor: pointer;
      color: #F2791D
    }

    .contact {
      font-size: 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
      color:  #F2791D;
    }

    .product-image {
      background-color: #B6A6A6;
      width: 90%;
      max-width: 400px;
      height: 300px;
      margin: 20px auto;
      border-radius: 10px;
    }

    .product-details {
      text-align: left;
      margin: 20px auto;
      max-width: 400px;
      color: #555;
    }

    .product-details h2,
    .product-details p {
      margin: 5px 0;
    }

    select, input[type="number"] {
      margin-top: 5px;
      padding: 5px;
    }

    .total {
      margin-top: 10px;
    }

    button {
      background-color:  #EA580C;
      color: white;
      padding: 10px 20px;
      border: none;
      margin-top: 15px;
      cursor: allowed ;
    }
    .active {
      color: green;
    }
    footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      background: white;
      display: flex;
      justify-content: space-around;
      padding: 0.6rem 0;
      border-top: 1px solid #ddd;
      height: 60px;
      display: flex;
      justify-content: space-around;
      align-items: center;
      z-index: 100;
      display: flex;
      justify-content: space-around;
      background-color: white;
      color: #fff;
      padding: 15px 0;
      bottom: 0;
      width: 100%;
    }

    footer div {
      font-size: 0.8rem;
      color: #a55b0c;
      text-align: center;
      font-size: 14px;
    }

    footer i {
      font-size: 1.2rem;
      display: block;
      font-size: 18px;
      margin-bottom: 2px;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-bottom: 80px; /* Espace pour le footer */
    }

    header {
      position: sticky;
      top: 0;
      background-color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      z-index: 100;
    }

    header h1 {
      color: #F2791D;
      font-size: 18px;
      margin: 0 auto;
    }

    .back-arrow {
      font-size: 24px;
      cursor: pointer;
    }
    .product-image {
      background-color: #B6A6A6;
      width: 90%;
      max-width: 400px;
      height: 300px;
      margin-top: 20px;
      border-radius: 10px;
    }

    .product-details {
      text-align: left;
      width: 90%;
      max-width: 400px;
      margin-top: 20px;
      color: #555;
    }

    .product-details h2,
    .product-details p {
      margin: 5px 0;
    }

    input[type="number"] {
      margin-top: 5px;
      padding: 5px;
      width: 60px;
    }

    .total {
      margin-top: 10px;
    }

    .active {
      color: green;
    }

    html, body {
      height: 100%;
      overflow-y: auto;
    }
  </style>
</head>
<script>
  function getParam(param) {
    const params = new URLSearchParams(window.location.search);
    return params.get(param);
  }

  const nomProduit = getParam("nom");
  const prixProduit = getParam("prix");
  const imageProduit = getParam("image");

  if (nomProduit && prixProduit && imageProduit) {
    document.querySelector(".product-image").style.backgroundImage = `url(${imageProduit})`;
    document.querySelector(".product-image").style.backgroundSize = "cover";
    document.querySelector("h2").textContent = nomProduit;
    document.querySelector(".product-details p strong + text")?.remove(); // Nettoie si doublon
    document.querySelector(".product-details p").innerHTML = `<strong>PRIX</strong> &nbsp;&nbsp; ${prixProduit} XOF`;

    // Mise à jour automatique du total selon quantité
    const quantityInput = document.getElementById("quantity");
    const totalDisplay = document.querySelector(".total p");

    function updateTotal() {
      const quantite = parseInt(quantityInput.value);
      const total = quantite * parseInt(prixProduit);
      totalDisplay.innerHTML = `<strong>TOTAL</strong><br>${total} XOF`;
    }

    quantityInput.addEventListener("input", updateTotal);
    updateTotal();
  }
</script>
<body>
<?php
// Connexion à la base
$db = new PDO("mysql:host=localhost;dbname=AfricaMall", "root", "");

// Sécurisation de l'ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
  $stmt = $db->prepare("SELECT * FROM produits WHERE id = ?");
  $stmt->execute([$id]);
  $produit = $stmt->fetch();
} else {
  die("Produit introuvable.");
}
?>

  <header>
  <h2><?= htmlspecialchars($produit['nom']) ?></h2>
<p><strong>PRIX</strong> &nbsp;&nbsp; <?= $produit['prix'] ?> XOF</p>
<img src="<?= $produit['image_url'] ?>" alt="Image produit" class="product-image">

<!-- Quantité et bouton commande -->
<label>Quantité :</label>
<input type="number" id="quantite" min="1" value="1">
<p><strong>TOTAL :</strong> <span id="total"><?= $produit['prix'] ?></span> XOF</p>
<button onclick="passerCommande()">Commander</button>

<script>
  const prixUnitaire = <?= $produit['prix'] ?>;
  const input = document.getElementById("quantite");
  const total = document.getElementById("total");

  input.addEventListener("input", () => {
    total.textContent = prixUnitaire * parseInt(input.value || 1);
  });

  function passerCommande() {
    alert("Commande passée !");
    // Ici, envoie la commande vers un fichier PHP si nécessaire
  }
</script>

    <button>Passer la commande…</button>
  </div>

  <div style="height: 70px;"></div>
  <footer>
    <div><i class="fas fa-home"></i>Accueil</div>
    <div><i class="fas fa-box-open"></i>Produits</div>
    <div><i class="fas fa-shopping-cart"></i>Catalogue</div>
    <div><i class="fas fa-users"></i>Messagerie</div>
    <div><i class="fas fa-user"></i>Compte</div>
  </footer>

</body>
</html>
