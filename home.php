<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Africa Mall</title>

  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>

    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
    }

    body{
      font-family:Arial, sans-serif;
      background:#fafafa;
      color:#333;
      padding-bottom:80px;
    }

    a{
      text-decoration:none;
      color:inherit;
    }

    /* ================= HEADER ================= */

    .header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:15px 20px;
      background:white;
      box-shadow:0 2px 6px rgba(0,0,0,0.08);
      position:sticky;
      top:0;
      z-index:1000;
    }

    .logo{
      display:flex;
      align-items:center;
      gap:10px;
    }

    .logo img{
      width:40px;
      height:40px;
      border-radius:50%;
    }

    .logo h1{
      font-size:22px;
      color:#EA580C;
    }

    .header-icons{
      display:flex;
      align-items:center;
      gap:15px;
    }

    .header-icons i{
      font-size:18px;
      cursor:pointer;
      transition:0.2s ease;
    }

    .header-icons i:hover{
      color:#EA580C;
      transform:scale(1.1);
    }

    /* ================= SEARCH ================= */

    .search-container{
      padding:15px 20px;
    }

    .search-container input{
      width:100%;
      padding:14px 18px;
      border-radius:14px;
      border:1px solid #ddd;
      outline:none;
      font-size:15px;
    }

    /* ================= CAROUSEL ================= */

    .carousel{
      width:95%;
      height:300px;
      margin:auto;
      overflow:hidden;
      border-radius:18px;
      position:relative;
    }

    .carousel-container{
      display:flex;
      width:100%;
      height:100%;
      transition:transform 0.5s ease-in-out;
    }

    .carousel-container img{
      width:100%;
      height:100%;
      object-fit:cover;
      flex-shrink:0;
    }

    /* ================= CATEGORIES ================= */

    .categories-bar{
      display:flex;
      gap:10px;
      overflow-x:auto;
      padding:15px 20px;
    }

    .categories-bar::-webkit-scrollbar{
      height:6px;
    }

    .categories-bar::-webkit-scrollbar-thumb{
      background:#ccc;
      border-radius:10px;
    }

    .category{
      background:#eee;
      padding:10px 18px;
      border-radius:30px;
      white-space:nowrap;
      cursor:pointer;
      transition:0.2s ease;
      font-size:14px;
    }

    .category:hover,
    .category.active{
      background:#EA580C;
      color:white;
      transform:scale(1.03);
    }

    /* ================= BANNER ================= */

    .banner{
      margin:20px;
      background:#ffe8d8;
      border-radius:20px;
      padding:25px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:20px;
    }

    .banner h2{
      color:#a55b0c;
      line-height:1.5;
    }

    .banner img{
      width:180px;
      border-radius:14px;
      object-fit:cover;
    }

    /* ================= SECTION ================= */

    .section-title{
      padding:0 20px;
      margin:25px 0 15px;
      color:#a55b0c;
      font-size:22px;
    }

    .products-grid{
      display:grid;
      grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
      gap:20px;
      padding:0 20px;
    }

    .product-card{
      background:white;
      border-radius:18px;
      overflow:hidden;
      box-shadow:0 2px 8px rgba(0,0,0,0.08);
      transition:0.25s ease;
    }

    .product-card:hover{
      transform:translateY(-5px);
    }

    .product-card img{
      width:100%;
      height:180px;
      object-fit:cover;
    }

    .product-content{
      padding:15px;
    }

    .product-name{
      font-weight:bold;
      margin-bottom:8px;
    }

    .product-price{
      color:green;
      font-weight:bold;
      margin-bottom:10px;
    }

    .stars{
      color:#EA580C;
      font-size:14px;
    }

    /* ================= SIDEBAR ================= */

    .sidebar{
      position:fixed;
      top:0;
      right:-340px;
      width:320px;
      height:100%;
      background:white;
      z-index:2000;
      box-shadow:-3px 0 10px rgba(0,0,0,0.1);
      overflow-y:auto;
      transition:0.3s ease;
      padding:20px;
    }

    .sidebar.open{
      right:0;
    }

    .sidebar-header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:25px;
    }

    .sidebar-header h2{
      color:#a55b0c;
    }

    .close-btn{
      background:none;
      border:none;
      font-size:22px;
      cursor:pointer;
    }

    .profile-box{
      display:flex;
      align-items:center;
      gap:15px;
      background:#f5f5f5;
      padding:15px;
      border-radius:15px;
      margin-bottom:25px;
    }

    .profile-box img{
      width:55px;
      height:55px;
      border-radius:50%;
    }

    .menu-section{
      margin-bottom:25px;
    }

    .menu-title{
      font-size:12px;
      color:#999;
      margin-bottom:10px;
      font-weight:bold;
      letter-spacing:1px;
    }

    .menu-link{
      display:flex;
      align-items:center;
      gap:12px;
      padding:12px;
      border-radius:12px;
      transition:0.2s ease;
      margin-bottom:8px;
    }

    .menu-link:hover{
      background:#f3f3f3;
      transform:translateX(3px);
    }

    .menu-link i{
      color:#EA580C;
      width:20px;
    }

    .seller-center{
      background:#fff2e8;
      color:#EA580C;
      font-weight:bold;
    }

    .logout{
      color:#dc2626;
    }

    .logout i{
      color:#dc2626;
    }

    /* ================= FOOTER ================= */

    footer{
      position:fixed;
      bottom:0;
      width:100%;
      background:white;
      border-top:1px solid #ddd;
      display:flex;
      justify-content:space-around;
      align-items:center;
      height:70px;
      z-index:1000;
    }

    footer a{
      text-align:center;
      font-size:13px;
      color:#a55b0c;
    }

    footer i{
      display:block;
      font-size:18px;
      margin-bottom:4px;
    }

    /* ================= RESPONSIVE ================= */

    @media(max-width:768px){

      .banner{
        flex-direction:column;
        text-align:center;
      }

      .banner img{
        width:100%;
        max-width:250px;
      }

      .products-grid{
        grid-template-columns:repeat(auto-fill,minmax(150px,1fr));
      }
    }

  </style>
</head>
<body>

<!-- ================= SIDEBAR ================= -->

<div class="sidebar" id="sidebar">

  <div class="sidebar-header">
    <h2>Paramètres</h2>
    <button class="close-btn" onclick="toggleSidebar()">✕</button>
  </div>

  <div class="profile-box">
    <img src="africa mall logo.png" alt="Profil">

    <div>
      <strong>Bienvenue 👋</strong><br>
      <small>Utilisateur Africa Mall</small>
    </div>
  </div>

  <!-- COMPTE -->

  <div class="menu-section">
    <div class="menu-title">COMPTE</div>

    <a href="compte.php" class="menu-link">
      <i class="fas fa-user"></i>
      Profil
    </a>

    <a href="mes-commandes.php" class="menu-link">
      <i class="fas fa-box"></i>
      Mes commandes
    </a>

    <a href="favoris.php" class="menu-link">
      <i class="fas fa-heart"></i>
      Favoris
    </a>

    <a href="paiements.php" class="menu-link">
      <i class="fas fa-credit-card"></i>
      Paiements
    </a>
  </div>

  <!-- PREFERENCES -->

  <div class="menu-section">
    <div class="menu-title">PREFERENCES</div>

    <a href="langue.php" class="menu-link">
      <i class="fas fa-language"></i>
      Langue
    </a>

    <a href="theme.php" class="menu-link">
      <i class="fas fa-moon"></i>
      Thème sombre
    </a>

    <a href="devise.php" class="menu-link">
      <i class="fas fa-money-bill-wave"></i>
      Devise
    </a>

    <a href="notifications.php" class="menu-link">
      <i class="fas fa-bell"></i>
      Notifications
    </a>
  </div>

  <!-- SELLER CENTER -->

  <div class="menu-section">
    <div class="menu-title">BUSINESS</div>

    <a href="compte_commerçant.php" class="menu-link">
      <i class="fas fa-store"></i>
      Seller Center
    </a>
  </div>

  <!-- SUPPORT -->

  <div class="menu-section">
    <div class="menu-title">SUPPORT</div>

    <a href="support.php" class="menu-link">
      <i class="fas fa-headset"></i>
      Support client
    </a>

    <a href="litiges.php" class="menu-link">
      <i class="fas fa-exclamation-circle"></i>
      Litiges & remboursements
    </a>
  </div>

  <!-- SECURITE -->

  <div class="menu-section">
    <div class="menu-title">SECURITE</div>

    <a href="securite.php" class="menu-link">
      <i class="fas fa-lock"></i>
      Sécurité
    </a>

    <a href="logout.php" class="menu-link logout">
      <i class="fas fa-sign-out-alt"></i>
      Déconnexion
    </a>
  </div>

</div>

<!-- ================= HEADER ================= -->

<header class="header">

  <div class="logo">
    <img src="africa mall logo.png" alt="Logo">
    <h1>AFRICA MALL</h1>
  </div>

  <div class="header-icons">
    <i class="fas fa-search"></i>
    <i class="fas fa-bell"></i>
    <i class="fas fa-shopping-cart"></i>
    <i class="fas fa-cog" onclick="toggleSidebar()"></i>
  </div>

</header>

<!-- ================= SEARCH ================= -->

<div class="search-container">
  <input type="text" placeholder="Rechercher un produit...">
</div>

<!-- ================= CAROUSEL ================= -->

<div class="carousel">

  <div class="carousel-container" id="carouselContainer">

    <img src="Iphone15.jpg" alt="slide">
    <img src="WhatsApp Image2.jpg" alt="slide">
    <img src="WhatsApp Image3.jpg" alt="slide">

  </div>

</div>

<!-- ================= CATEGORIES ================= -->

<div class="categories-bar">

  <div class="category active">Tous</div>
  <div class="category">Electronique</div>
  <div class="category">Mode</div>
  <div class="category">Beauté</div>
  <div class="category">Maison</div>
  <div class="category">Alimentation</div>
  <div class="category">Mobilier</div>
  <div class="category">Accessoires</div>

</div>

<!-- ================= BANNER ================= -->

<div class="banner">

  <div>
    <h2>
      Faites vos achats<br>
      chez les meilleurs<br>
      fournisseurs africains
    </h2>
  </div>

  <img src="https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=100&q=80">

</div>

<!-- ================= PRODUITS ================= -->

<h2 class="section-title">Produits en vedette</h2>

<div class="products-grid">

  <a href="produit.php" class="product-card">

    <img src="WhatsApp Image.jpg">

    <div class="product-content">

      <div class="product-name">Robe Africaine</div>

      <div class="product-price">35 €</div>

      <div class="stars">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
        <i class="far fa-star"></i>
      </div>

    </div>

  </a>

  <a href="#" class="product-card">

    <img src="Iphone15.jpg">

    <div class="product-content">

      <div class="product-name">iPhone 15</div>

      <div class="product-price">1200 €</div>

      <div class="stars">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>

    </div>

  </a>

  <a href="#" class="product-card">

    <img src="WhatsApp Image5.jpg">

    <div class="product-content">

      <div class="product-name">Collier Africain</div>

      <div class="product-price">18 €</div>

      <div class="stars">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
        <i class="far fa-star"></i>
      </div>

    </div>

  </a>

</div>

<!-- ================= FOOTER ================= -->

<footer>

  <a href="home.php">
    <i class="fas fa-home"></i>
    Accueil
  </a>

  <a href="produits.php">
    <i class="fas fa-box-open"></i>
    Produits
  </a>

  <a href="catalogue.php">
    <i class="fas fa-shopping-cart"></i>
    Panier
  </a>

  <a href="messagerie.php">
    <i class="fas fa-comments"></i>
    Messages
  </a>

  <a href="compte.php">
    <i class="fas fa-user"></i>
    Compte
  </a>

</footer>

<!-- ================= JS ================= -->

<script>

  function toggleSidebar(){

    const sidebar = document.getElementById('sidebar');

    sidebar.classList.toggle('open');
  }

  // ================= CAROUSEL =================

  const carousel = document.getElementById('carouselContainer');

  const totalSlides = carousel.children.length;

  let index = 0;

  setInterval(()=>{

    index = (index + 1) % totalSlides;

    carousel.style.transform = `translateX(-${index * 100}%)`;

  },3000);

</script>

</body>
</html>