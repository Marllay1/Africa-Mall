<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover" />
  <title>Africa Mall</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; background: #F9F5EF; color: #2E2418; padding-bottom: 80px; min-height: 100vh; }
    :root { --choco: #5C3A1E; --choco-light: #7B4F2C; --choco-soft: #A8815A; --cream: #F2E8DC; --beige: #E5D7C4; --premium-gold: #D4AF37; --dark: #3A2C1E; --white: #FFFFFF; --whatsapp-green: #075E54; }
    .header { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; background: var(--white); box-shadow: 0 4px 14px rgba(0,0,0,0.04); position: sticky; top: 0; z-index: 1200; }
    .logo { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .logo img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid var(--beige); }
    .logo h1 { font-size: 1.5rem; font-weight: 700; color: var(--choco); }
    .header-icons { display: flex; gap: 20px; align-items: center; }
    .header-icons i { font-size: 1.3rem; color: #5A4636; cursor: pointer; transition: 0.2s; }
    .header-icons i:hover { color: var(--choco-light); }
    .sidebar { position: fixed; top: 0; right: -380px; width: 350px; max-width: 90vw; height: 100%; background: var(--white); z-index: 2500; box-shadow: -8px 0 30px rgba(0,0,0,0.1); overflow-y: auto; transition: right 0.35s ease; padding: 24px 20px; border-left: 1px solid var(--beige); }
    .sidebar.open { right: 0; }
    .sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
    .sidebar-header h2 { color: var(--choco); font-weight: 700; }
    .close-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: #5A4636; }
    .profile-box { display: flex; align-items: center; gap: 14px; background: #F5EDE3; padding: 14px 16px; border-radius: 20px; margin-bottom: 28px; }
    .profile-box .avatar-placeholder { width: 52px; height: 52px; border-radius: 50%; background: #A8815A; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.4rem; overflow: hidden; }
    .profile-box .avatar-placeholder img { width: 100%; height: 100%; object-fit: cover; }
    .menu-section { margin-bottom: 22px; }
    .menu-title { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; color: #A28B72; margin-bottom: 12px; font-weight: 700; }
    .menu-link { display: flex; align-items: center; gap: 14px; padding: 12px 14px; border-radius: 16px; transition: background 0.2s; margin-bottom: 4px; color: #3E2E20; font-weight: 500; cursor: pointer; text-decoration: none; }
    .menu-link i { color: var(--choco); width: 22px; font-size: 1.1rem; }
    .menu-link:hover { background: #F5EDE3; }
    .logout { color: #B85C1A; }
    .page-container { max-width: 1200px; margin: 0 auto; padding: 16px 20px 30px; animation: fade 0.2s ease; }
    @keyframes fade { from { opacity: 0.5; } to { opacity: 1; } }
    .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--choco); font-weight: 600; margin-bottom: 20px; cursor: pointer; background: #F2E8DC; padding: 8px 18px; border-radius: 30px; border: none; font-size: 0.95rem; }
    .product-detail-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; background: white; border-radius: 36px; padding: 28px; box-shadow: 0 12px 28px rgba(0,0,0,0.05); }
    .detail-gallery img { width: 100%; border-radius: 28px; object-fit: cover; aspect-ratio: 1/1; }
    .detail-price { font-size: 2.2rem; font-weight: 800; color: var(--choco); margin: 8px 0 16px; }
    .selector-label { font-weight: 700; margin-top: 22px; display: block; color: #4A3625; }
    .sizes-container { display: flex; flex-wrap: wrap; gap: 10px; margin: 8px 0 12px; }
    .size-btn { background: #F3EDE4; border: 1px solid #DDCEBB; padding: 10px 20px; border-radius: 40px; font-weight: 600; cursor: pointer; font-size: 0.9rem; }
    .size-btn.selected { background: var(--choco); color: white; border-color: var(--choco); }
    .colors-container { display: flex; gap: 12px; margin: 8px 0; }
    .color-circle { width: 36px; height: 36px; border-radius: 50%; cursor: pointer; border: 3px solid transparent; box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    .color-circle.selected { border-color: var(--choco); transform: scale(1.1); box-shadow: 0 0 0 2px white, 0 0 0 5px var(--choco); }
    .quantity-selector { display: flex; align-items: center; gap: 10px; background: #F3EDE4; width: fit-content; border-radius: 60px; padding: 4px 6px; margin: 8px 0; }
    .qty-btn { width: 34px; height: 34px; border-radius: 50%; background: white; border: none; font-size: 1.2rem; font-weight: bold; cursor: pointer; }
    .quantity-value { font-size: 1rem; font-weight: 700; min-width: 36px; text-align: center; }
    .action-buttons { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 8px; }
    .btn-buy, .btn-cart { flex: 1; padding: 12px 16px; border-radius: 40px; font-weight: 700; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-buy { background: var(--choco); color: white; }
    .btn-cart { background: #EFE2D4; color: var(--choco); border: 1px solid var(--choco-soft); }
    .btn-cart.disabled { opacity: 0.5; pointer-events: none; }
    .seller-chat-btn { display: flex; align-items: center; justify-content: center; gap: 8px; background: #075E54; color: white; padding: 14px 22px; border-radius: 50px; font-weight: 700; font-size: 1rem; margin: 18px 0 10px; cursor: pointer; border: none; width: 100%; transition: 0.2s; box-shadow: 0 4px 10px rgba(7,94,84,0.3); }
    .mini-selectors { display: flex; flex-wrap: wrap; gap: 6px; margin: 6px 0; align-items: center; }
    .mini-colors { display: flex; gap: 4px; }
    .mini-color-dot { width: 18px; height: 18px; border-radius: 50%; cursor: pointer; border: 2px solid transparent; }
    .mini-color-dot.selected { border-color: var(--choco); }
    .mini-sizes { display: flex; gap: 4px; }
    .mini-size-btn { padding: 2px 8px; border-radius: 20px; font-size: 0.65rem; background: #F3EDE4; border: 1px solid #DDCEBB; cursor: pointer; font-weight: 600; }
    .mini-size-btn.selected { background: var(--choco); color: white; }
    .mini-qty { display: flex; align-items: center; gap: 4px; }
    .mini-qty button { width: 22px; height: 22px; border-radius: 50%; border: none; background: white; font-weight: bold; cursor: pointer; font-size: 0.8rem; }
    .mini-qty span { font-size: 0.8rem; font-weight: 700; min-width: 20px; text-align: center; }
    .add-btn { width: 28px; height: 28px; border-radius: 50%; background: var(--choco); color: white; border: none; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; }
    .chat-fullscreen { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #ECE5DD; z-index: 3000; display: flex; flex-direction: column; }
    .chat-header { background: #075E54; color: white; padding: 12px 16px; display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .chat-header .back-icon { font-size: 1.3rem; cursor: pointer; padding: 8px; }
    .chat-header .avatar-placeholder { width: 40px; height: 40px; border-radius: 50%; background: #A8815A; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; }
    .chat-messages { flex: 1; padding: 16px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
    .message-bubble { max-width: 80%; padding: 10px 14px; border-radius: 12px; font-size: 0.95rem; line-height: 1.4; word-wrap: break-word; }
    .message-bubble.sent { align-self: flex-end; background: #DCF8C6; }
    .message-bubble.received { align-self: flex-start; background: white; }
    .message-time { font-size: 0.65rem; color: #667; text-align: right; margin-top: 4px; }
    .chat-input-area { display: flex; gap: 8px; padding: 10px 12px; background: #F0F0F0; align-items: center; flex-shrink: 0; }
    .chat-input-area input { flex: 1; padding: 10px 16px; border-radius: 30px; border: none; font-size: 0.95rem; outline: none; }
    .chat-input-area button { background: #075E54; color: white; border: none; width: 42px; height: 42px; border-radius: 50%; font-size: 1rem; cursor: pointer; }
    .subscribe-banner { background: linear-gradient(135deg, #D4AF37, #C59B2E); color: #2E2418; border-radius: 24px; padding: 20px 24px; margin: 16px 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 6px 16px rgba(212,175,55,0.3); }
    .subscribe-banner button { background: white; color: #5C3A1E; border: none; padding: 12px 28px; border-radius: 40px; font-weight: 700; cursor: pointer; font-size: 0.95rem; }
    .search-box { display: flex; align-items: center; background: white; border-radius: 60px; padding: 6px 20px; border: 1px solid var(--beige); margin-bottom: 18px; }
    .search-box input { flex: 1; border: none; padding: 14px 8px; font-size: 1rem; background: transparent; outline: none; }
    .carousel-wrapper { border-radius: 28px; overflow: hidden; margin-bottom: 8px; box-shadow: 0 8px 18px rgba(0,0,0,0.08); }
    .carousel { position: relative; height: 240px; }
    .carousel-container { display: flex; height: 100%; transition: 0.5s; }
    .carousel-slide { min-width: 100%; background-size: cover; background-position: center; position: relative; }
    .carousel-badge { position: absolute; bottom: 16px; left: 16px; background: rgba(92,58,30,0.9); color: white; padding: 6px 18px; border-radius: 40px; }
    .carousel-dots { display: flex; justify-content: center; gap: 8px; margin: 12px 0 6px; flex-wrap: wrap; }
    .dot { width: 8px; height: 8px; background: #CBB59A; border-radius: 50%; cursor: pointer; transition: 0.2s; }
    .dot.active { background: var(--choco); width: 22px; border-radius: 10px; }
    .categories-bar { display: flex; gap: 12px; overflow-x: auto; padding: 8px 0 18px; }
    .category { background: white; border: 1px solid var(--beige); padding: 8px 22px; border-radius: 40px; cursor: pointer; white-space: nowrap; }
    .category.active { background: var(--choco); color: white; }
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(165px, 1fr)); gap: 18px; }
    .product-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 6px 14px rgba(0,0,0,0.04); cursor: pointer; transition: 0.2s; position: relative; padding-bottom: 8px; }
    .product-card:hover { transform: translateY(-5px); }
    .premium-badge { position: absolute; top: 10px; left: 10px; background: #D4AF37; color: #2E2418; font-weight: 800; font-size: 0.7rem; padding: 4px 10px; border-radius: 30px; z-index: 2; }
    .product-card img { width: 100%; height: 160px; object-fit: cover; }
    .product-content { padding: 8px 10px 4px; }
    .product-name { font-weight: 600; font-size: 0.85rem; margin-bottom: 2px; }
    .product-price { color: var(--choco); font-weight: 800; font-size: 0.9rem; }
    .card-actions { display: flex; align-items: center; justify-content: space-between; padding: 4px 10px 8px; gap: 6px; }
    .card-actions .add-btn { flex-shrink: 0; }
    footer { position: fixed; bottom: 0; width: 100%; background: white; border-top: 1px solid #E7D9CC; display: flex; justify-content: space-around; padding: 8px 0 14px; z-index: 1000; }
    footer a { text-align: center; font-size: 0.7rem; color: #8B7355; cursor: pointer; }
    footer i { font-size: 1.3rem; display: block; margin-bottom: 4px; }
    footer a.active { color: var(--choco); font-weight: 700; }
    .toast-msg { position: fixed; bottom: 90px; left: 20px; right: 20px; background: #3A2C1E; color: white; padding: 12px; border-radius: 50px; text-align: center; opacity: 0; z-index: 3000; transition: opacity 0.2s; }
    .avatar-placeholder { width: 40px; height: 40px; border-radius: 50%; background: #A8815A; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.1rem; overflow: hidden; }
    .avatar-placeholder img { width: 100%; height: 100%; object-fit: cover; }
    .notif-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 2800; display: flex; align-items: flex-end; justify-content: center; }
    .notif-panel { background: white; width: 100%; max-width: 500px; border-radius: 28px 28px 0 0; padding: 20px; max-height: 60vh; overflow-y: auto; animation: slideUp 0.3s ease; }
    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .notif-item { display: flex; align-items: center; gap: 14px; padding: 16px; border-bottom: 1px solid #eee; cursor: pointer; }
    .notif-icon { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    .conversation-list { background: white; border-radius: 28px; padding: 8px 0; overflow: hidden; }
    .conversation-item { display: flex; align-items: center; gap: 14px; padding: 14px 20px; cursor: pointer; border-bottom: 1px solid #f0e8dc; }
    .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 50vh; text-align: center; color: #8B7355; }
    .empty-state i { font-size: 3rem; margin-bottom: 16px; color: var(--choco-soft); }
    @media (max-width: 700px) { .product-detail-layout { grid-template-columns: 1fr; } .notif-panel { max-height: 50vh; } }
  </style>
</head>
<body>
<div class="toast-msg" id="toastMsg"></div>

<div class="sidebar" id="sidebar">
  <div class="sidebar-header"><h2>Paramètres</h2><button class="close-btn" onclick="toggleSidebar()">✕</button></div>
  <div class="profile-box" id="sidebarProfileBox">
    <div class="avatar-placeholder" style="width:52px;height:52px;font-size:1.4rem;" id="sidebarAvatar">U</div>
    <div id="sidebarProfileInfo"><strong>Bienvenue</strong><br><small>Utilisateur Africa Mall</small></div>
  </div>
  <div class="menu-section"><div class="menu-title">COMPTE</div>
    <a href="compte.php" class="menu-link"><i class="fas fa-user"></i> Profil</a>
    <a href="mes-commandes.php" class="menu-link"><i class="fas fa-box"></i> Mes commandes</a>
    <a href="favoris.php" class="menu-link"><i class="fas fa-heart"></i> Favoris</a>
    <a href="paiements.php" class="menu-link"><i class="fas fa-credit-card"></i> Paiements</a>
  </div>
  <div class="menu-section"><div class="menu-title">PREFERENCES</div>
    <a href="langue.php" class="menu-link"><i class="fas fa-language"></i> Langue</a>
    <a href="theme.php" class="menu-link"><i class="fas fa-moon"></i> Thème sombre</a>
    <a href="devise.php" class="menu-link"><i class="fas fa-money-bill-wave"></i> Devise</a>
    <a href="notifications.php" class="menu-link"><i class="fas fa-bell"></i> Notifications</a>
  </div>
  <div class="menu-section"><div class="menu-title">BUSINESS</div>
    <a href="compte_commercant.php" class="menu-link"><i class="fas fa-store"></i> Seller Center</a>
  </div>
  <div class="menu-section"><div class="menu-title">SUPPORT</div>
    <a href="support.php" class="menu-link"><i class="fas fa-headset"></i> Support client</a>
    <a href="litiges.php" class="menu-link"><i class="fas fa-exclamation-circle"></i> Litiges & remboursements</a>
  </div>
  <div class="menu-section"><div class="menu-title">SECURITE</div>
    <a href="securite.php" class="menu-link"><i class="fas fa-lock"></i> Sécurité</a>
    <a href="logout.php" class="menu-link logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
  </div>
</div>

<header class="header">
  <div class="logo" onclick="navigateTo('home')">
    <img src="africa mall logo.png" alt="Africa Mall" onerror="this.style.background='#D4AF37'; this.style.display='block';">
    <h1>AFRICA MALL</h1>
  </div>
  <div class="header-icons">
    <i class="fas fa-bell" onclick="openNotifications()"></i>
    <i class="fas fa-cog" onclick="toggleSidebar()"></i>
  </div>
</header>

<main id="appRoot"></main>

<footer>
  <a data-route="home"><i class="fas fa-home"></i>Accueil</a>
  <a data-route="cart"><i class="fas fa-shopping-cart"></i>Panier</a>
  <a data-route="messages"><i class="fas fa-comments"></i>Messages</a>
</footer>

<script>
(function() {
    let isSubscribed = JSON.parse(localStorage.getItem('africamall_subscribed') || 'false');
    let userProfile = JSON.parse(localStorage.getItem('africamall_profile') || '{"name":"Utilisateur","email":"utilisateur@africamall.com","avatar":""}');
    let cart = JSON.parse(localStorage.getItem('africamall_cart') || '[]');
    let conversations = JSON.parse(localStorage.getItem('africamall_chats') || '{}');
    let pendingOrder = null;

    function updateSidebarProfile() {
      const avatarEl = document.getElementById('sidebarAvatar');
      const infoEl = document.getElementById('sidebarProfileInfo');
      if (userProfile.avatar) { avatarEl.innerHTML = `<img src="${userProfile.avatar}" alt="Photo">`; }
      else { avatarEl.textContent = userProfile.name.charAt(0).toUpperCase(); }
      infoEl.innerHTML = `<strong>${userProfile.name}</strong><br><small>${userProfile.email}</small>`;
    }
    function saveCart() { localStorage.setItem('africamall_cart', JSON.stringify(cart)); }
    function saveUserProfile() { localStorage.setItem('africamall_profile', JSON.stringify(userProfile)); }
    function saveConversations() { localStorage.setItem('africamall_chats', JSON.stringify(conversations)); }

    const allProducts = [
      { id:1, name:"Boubou Sénégalaise", price:45, category:"Mode", tier:"premium", rating:4.5, seller:"Amadou Couture", sellerId:"seller1", sellerAvatar:"AC", image:"https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?w=400&h=400&fit=crop", desc:"Boubou wax haut de gamme, brodé main.", sizes:["XS","S","M","L","XL"], colors:["Rouge","Bleu","Vert","Jaune"], colorHex:{Rouge:"#C62828",Bleu:"#1565C0",Vert:"#2E7D32",Jaune:"#F9A825"} },
      { id:2, name:"Smartphone Tecno", price:210, category:"Electronique", tier:"premium", rating:4.2, seller:"Tech Afrique", sellerId:"seller2", sellerAvatar:"TA", image:"https://images.unsplash.com/photo-1592899677977-9e10cb588f9e?w=400&h=400&fit=crop", desc:"6.8 pouces, 128Go.", sizes:null, colors:["Noir","Bleu Nuit"], colorHex:{Noir:"#2C2C2C","Bleu Nuit":"#0D3B66"} },
      { id:3, name:"Crème Karité Bio", price:12, category:"Beauté", tier:"standard", rating:4.0, seller:"Karité Naturel", sellerId:"seller3", sellerAvatar:"KN", image:"https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=400&fit=crop", desc:"200ml pur beurre de karité.", sizes:null, colors:null },
      { id:4, name:"Canapé Rotin", price:320, category:"Mobilier", tier:"premium", rating:4.8, seller:"Artisan Ghana", sellerId:"seller4", sellerAvatar:"AG", image:"https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=400&h=400&fit=crop", desc:"Artisanat africain.", sizes:null, colors:["Naturel","Foncé"], colorHex:{Naturel:"#A1887F",Foncé:"#5D4037"} },
      { id:5, name:"Écharpe Wax", price:28, category:"Mode", tier:"standard", rating:3.9, seller:"Tissus du Mali", sellerId:"seller5", sellerAvatar:"TM", image:"https://images.unsplash.com/photo-1601924582970-9238bcb495d9?w=400&h=400&fit=crop", desc:"Accessoire wax.", sizes:["Unique"], colors:["Multicolore"], colorHex:{Multicolore:"#E67E22"} },
      { id:6, name:"Montre Connectée", price:89, category:"Electronique", tier:"premium", rating:4.4, seller:"Gadget Store", sellerId:"seller6", sellerAvatar:"GS", image:"https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=400&h=400&fit=crop", desc:"Fitness GPS.", sizes:null, colors:["Noir","Argent"], colorHex:{Noir:"#2C2C2C",Argent:"#B0BEC5"} },
      { id:7, name:"Théière Marocaine", price:55, category:"Artisanat", tier:"premium", rating:4.7, seller:"Artisan Fès", sellerId:"seller7", sellerAvatar:"AF", image:"https://images.unsplash.com/photo-1571934811356-5cc061b6821f?w=400&h=400&fit=crop", desc:"Théière en laiton gravé.", sizes:null, colors:["Laiton"], colorHex:{Laiton:"#C5A059"} },
      { id:8, name:"Tissu Kente", price:95, category:"Mode", tier:"premium", rating:4.9, seller:"Kente Ghana", sellerId:"seller8", sellerAvatar:"KG", image:"https://images.unsplash.com/photo-1614051442534-4b1c1c1b1c1b?w=400&h=400&fit=crop", desc:"Tissu traditionnel ghanéen.", sizes:null, colors:["Multicolore"], colorHex:{Multicolore:"#E67E22"} },
      { id:9, name:"Huile d'Argan", price:22, category:"Beauté", tier:"standard", rating:4.3, seller:"Coopérative Argan", sellerId:"seller9", sellerAvatar:"CA", image:"https://images.unsplash.com/photo-1585435557343-3b092031a831?w=400&h=400&fit=crop", desc:"Huile d'argan pure 100ml.", sizes:null, colors:null },
      { id:10, name:"Panier en Osier", price:40, category:"Artisanat", tier:"standard", rating:4.1, seller:"Vannerie Sénégal", sellerId:"seller10", sellerAvatar:"VS", image:"https://images.unsplash.com/photo-1611486210617-9b3f3b3b3b3b?w=400&h=400&fit=crop", desc:"Panier tissé main.", sizes:null, colors:["Naturel"], colorHex:{Naturel:"#A1887F"} },
      { id:11, name:"Tableau Batik", price:75, category:"Artisanat", tier:"premium", rating:4.6, seller:"Batik Art", sellerId:"seller11", sellerAvatar:"BA", image:"https://images.unsplash.com/photo-1578926288207-a90a5366759d?w=400&h=400&fit=crop", desc:"Tableau sur tissu batik.", sizes:null, colors:null },
      { id:12, name:"Lampe Calebasse", price:35, category:"Mobilier", tier:"standard", rating:4.0, seller:"Lumière d'Afrique", sellerId:"seller12", sellerAvatar:"LA", image:"https://images.unsplash.com/photo-1507475380673-1246fa72eeea?w=400&h=400&fit=crop", desc:"Lampe design en calebasse.", sizes:null, colors:["Naturel"], colorHex:{Naturel:"#A1887F"} }
    ];

    const carouselSlides = [
      { img:"https://images.unsplash.com/photo-1607083206968-13611e3d76db?w=800&h=300&fit=crop", text:"Électronique premium" },
      { img:"https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?w=800&h=300&fit=crop", text:"Mode africaine" },
      { img:"https://images.unsplash.com/photo-1556742049-0cf9a6a3245e?w=800&h=300&fit=crop", text:"Livraison panafricaine" },
      { img:"https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&h=300&fit=crop", text:"Artisanat local" },
      { img:"https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=800&h=300&fit=crop", text:"Beauté naturelle" },
      { img:"https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=300&fit=crop", text:"Accessoires uniques" },
      { img:"https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&h=300&fit=crop", text:"Mobilier design" },
      { img:"https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&h=300&fit=crop", text:"Technologie mobile" },
      { img:"https://images.unsplash.com/photo-1583744946564-b52ac1c389c8?w=800&h=300&fit=crop", text:"Tissus wax" },
      { img:"https://images.unsplash.com/photo-1598532163257-ae3c6b2524b6?w=800&h=300&fit=crop", text:"Bijoux africains" },
      { img:"https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800&h=300&fit=crop", text:"Chaussures artisanales" },
      { img:"https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&h=300&fit=crop", text:"Sacs en cuir" },
      { img:"https://images.unsplash.com/photo-1544816155-12df9643f363?w=800&h=300&fit=crop", text:"Épices africaines" },
      { img:"https://images.unsplash.com/photo-1513519245088-0e12902e35ca?w=800&h=300&fit=crop", text:"Décoration intérieure" },
      { img:"https://images.unsplash.com/photo-1590736969955-71cc94901144?w=800&h=300&fit=crop", text:"Instruments musique" },
      { img:"https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800&h=300&fit=crop", text:"Vêtements enfants" },
      { img:"https://images.unsplash.com/photo-1581017332333-fa4da1e3d07e?w=800&h=300&fit=crop", text:"Produits bio" },
      { img:"https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&h=300&fit=crop", text:"Chaussures sport" },
      { img:"https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=800&h=300&fit=crop", text:"Montres tendance" },
      { img:"https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=300&fit=crop", text:"Produits high-tech" }
    ];

    function showToast(msg) { const t = document.getElementById('toastMsg'); t.textContent = msg; t.style.opacity = '1'; setTimeout(() => { t.style.opacity = '0'; }, 2200); }
    window.toggleSidebar = () => { updateSidebarProfile(); document.getElementById('sidebar').classList.toggle('open'); };

    function getOrCreateConversation(sellerId, sellerName, sellerAvatar) {
      if (!conversations[sellerId]) { conversations[sellerId] = { sellerName, sellerAvatar, messages: [{ from: 'seller', type:'text', text: `Bonjour ! Je suis ${sellerName}.`, time: new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) }] }; saveConversations(); }
      return conversations[sellerId];
    }
    function sendMessage(sellerId, text, type='text') {
      const conv = conversations[sellerId]; if (!conv) return;
      conv.messages.push({ from: 'me', type, text, time: new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) }); saveConversations();
      setTimeout(() => { conv.messages.push({ from: 'seller', type:'text', text: "Merci pour votre message !", time: new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) }); saveConversations(); if (window._currentChatSellerId === sellerId) renderChatFullscreen(sellerId); }, 1500);
    }
    function renderChatFullscreen(sellerId) {
      const conv = conversations[sellerId]; if (!conv) return; window._currentChatSellerId = sellerId;
      const chatDiv = document.createElement('div'); chatDiv.className = 'chat-fullscreen'; chatDiv.id = 'chatFullscreen';
      chatDiv.innerHTML = `<div class="chat-header"><i class="fas fa-arrow-left back-icon" id="chatBackBtn"></i><div class="avatar-placeholder">${conv.sellerAvatar||'S'}</div><div style="flex:1"><strong>${conv.sellerName}</strong><br><small style="opacity:0.8;">En ligne</small></div></div><div class="chat-messages" id="chatMessages"></div><div class="chat-input-area"><button id="attachBtn" title="Joindre un fichier"><i class="fas fa-paperclip"></i></button><input type="text" id="chatInput" placeholder="Message..."><button id="voiceBtn" title="Note vocale"><i class="fas fa-microphone"></i></button><button id="cameraBtn" title="Prendre une photo"><i class="fas fa-camera"></i></button><button id="sendChatBtn"><i class="fas fa-paper-plane"></i></button></div>`;
      document.body.appendChild(chatDiv);
      const messagesDiv = document.getElementById('chatMessages');
      messagesDiv.innerHTML = conv.messages.map(m => { if(m.type==='voice') return `<div class="message-bubble ${m.from==='me'?'sent':'received'}"><audio controls src="${m.text}"></audio><div class="message-time">${m.time}</div></div>`; if(m.type==='image') return `<div class="message-bubble ${m.from==='me'?'sent':'received'}"><img src="${m.text}" style="max-width:200px;border-radius:12px;"><div class="message-time">${m.time}</div></div>`; if(m.type==='file') return `<div class="message-bubble ${m.from==='me'?'sent':'received'}"><i class="fas fa-file"></i> <a href="${m.text}" target="_blank">Fichier joint</a><div class="message-time">${m.time}</div></div>`; return `<div class="message-bubble ${m.from==='me'?'sent':'received'}">${m.text}<div class="message-time">${m.time}</div></div>`; }).join('');
      messagesDiv.scrollTop = messagesDiv.scrollHeight;
      document.getElementById('chatBackBtn').onclick = () => { document.getElementById('chatFullscreen').remove(); navigateTo('messages'); };
      document.getElementById('sendChatBtn').onclick = () => { const input = document.getElementById('chatInput'); if(input.value.trim()) { sendMessage(sellerId, input.value.trim()); input.value = ''; } };
      document.getElementById('attachBtn').onclick = () => { const fi = document.createElement('input'); fi.type='file'; fi.accept='*/*'; fi.onchange = e => { if(e.target.files[0]) { const url = URL.createObjectURL(e.target.files[0]); sendMessage(sellerId, url, e.target.files[0].type.startsWith('image/')?'image':'file'); } }; fi.click(); };
      document.getElementById('cameraBtn').onclick = () => { const fi = document.createElement('input'); fi.type='file'; fi.accept='image/*'; fi.capture='environment'; fi.onchange = e => { if(e.target.files[0]) { const url = URL.createObjectURL(e.target.files[0]); sendMessage(sellerId, url, 'image'); } }; fi.click(); };
      let recorder, chunks = [];
      document.getElementById('voiceBtn').onmousedown = () => { navigator.mediaDevices.getUserMedia({audio:true}).then(s => { recorder = new MediaRecorder(s); chunks = []; recorder.ondataavailable = e => chunks.push(e.data); recorder.onstop = () => { const blob = new Blob(chunks, {type:'audio/webm'}); const url = URL.createObjectURL(blob); sendMessage(sellerId, url, 'voice'); }; recorder.start(); showToast('Enregistrement...'); }).catch(() => showToast('Micro inaccessible')); };
      document.getElementById('voiceBtn').onmouseup = () => { if(recorder && recorder.state==='recording') { recorder.stop(); recorder.stream.getTracks().forEach(t=>t.stop()); } };
    }

    function renderMessagesList() {
      const root = document.getElementById('appRoot'); const ids = Object.keys(conversations);
      if(!ids.length) { root.innerHTML = `<div class="page-container"><div class="empty-state"><i class="fas fa-comments"></i><h2>Messages</h2><p>Aucune conversation.</p></div></div>`; return; }
      root.innerHTML = `<div class="page-container"><div class="conversation-list">${ids.map(id => { const c = conversations[id]; const last = c.messages[c.messages.length-1]; return `<div class="conversation-item" data-sid="${id}"><div class="avatar-placeholder">${c.sellerAvatar||'S'}</div><div style="flex:1"><strong>${c.sellerName}</strong><br><small>${last.type==='voice'?'Note vocale':(last.text||'').substring(0,35)}</small></div><small>${last.time}</small></div>`; }).join('')}</div></div>`;
      document.querySelectorAll('.conversation-item').forEach(el => el.onclick = () => renderChatFullscreen(el.dataset.sid));
    }

    function addToCart(product, qty, size, color) { const existing = cart.find(i => i.id === product.id && i.size === size && i.color === color); if (existing) { existing.qty += qty; } else { cart.push({ id: product.id, name: product.name, price: product.price, qty, size, color, image: product.image }); } saveCart(); showToast(`${product.name} ajouté au panier`); }

    function renderProductDetail(pid) {
      const p = allProducts.find(x => x.id === pid); if(!p) return navigateTo('home');
      let sz = p.sizes?.[0]||'', cl = p.colors?.[0]||'', qty=1;
      const root = document.getElementById('appRoot');
      root.innerHTML = `<div class="page-container"><button class="back-link" onclick="navigateTo('home')"><i class="fas fa-arrow-left"></i> Retour</button><div class="product-detail-layout"><div class="detail-gallery"><img src="${p.image}"></div><div><h1>${p.name}</h1><div class="detail-price">${p.price} €</div><p>${p.desc}</p><button class="seller-chat-btn" id="contactSellerBtn"><i class="fab fa-whatsapp"></i> Discuter avec le vendeur · ${p.seller}</button>${p.sizes ? `<div class="selector-label">Taille</div><div class="sizes-container">${p.sizes.map(s=>`<div class="size-btn ${s===sz?'selected':''}" data-sz="${s}">${s}</div>`).join('')}</div>` : ''}${p.colors ? `<div class="selector-label">Couleur</div><div class="colors-container">${p.colors.map(c=>`<div class="color-circle ${c===cl?'selected':''}" style="background:${p.colorHex?.[c]||'#ccc'}" data-cl="${c}" title="${c}"></div>`).join('')}</div>` : ''}<div class="selector-label">Quantité</div><div class="quantity-selector"><button class="qty-btn" id="qtyMinus">−</button><span class="quantity-value" id="qtyValue">1</span><button class="qty-btn" id="qtyPlus">+</button></div><div class="action-buttons"><button class="btn-buy" id="buyNowBtn">Acheter maintenant</button><button class="btn-cart ${!isSubscribed?'disabled':''}" id="addCartBtn">Ajouter au panier</button></div></div></div></div>`;
      document.getElementById('contactSellerBtn').onclick = () => { getOrCreateConversation(p.sellerId, p.seller, p.sellerAvatar); renderChatFullscreen(p.sellerId); };
      if(p.sizes) document.querySelectorAll('.size-btn').forEach(b => b.onclick = () => { document.querySelectorAll('.size-btn').forEach(x=>x.classList.remove('selected')); b.classList.add('selected'); sz=b.dataset.sz; });
      if(p.colors) document.querySelectorAll('.color-circle').forEach(c => c.onclick = () => { document.querySelectorAll('.color-circle').forEach(x=>x.classList.remove('selected')); c.classList.add('selected'); cl=c.dataset.cl; });
      document.getElementById('qtyMinus').onclick = () => { if(qty>1){ qty--; document.getElementById('qtyValue').textContent=qty; } };
      document.getElementById('qtyPlus').onclick = () => { qty++; document.getElementById('qtyValue').textContent=qty; };
      document.getElementById('buyNowBtn').onclick = () => { pendingOrder = { items: [{ product: p, qty, size: sz, color: cl }] }; renderOrderSummary(pendingOrder); };
      const cartBtn = document.getElementById('addCartBtn');
      if (isSubscribed && cartBtn) { cartBtn.onclick = () => { addToCart(p, qty, sz, cl); }; }
    }

    function renderOrderSummary(order) {
      const items = order.items;
      let total = 0; let html = '';
      items.forEach(item => { const { product, qty, size, color } = item; const st = product.price * qty; total += st; html += `<div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid #eee;"><img src="${product.image}" style="width:50px;height:50px;border-radius:12px;object-fit:cover;"><div><strong>${product.name}</strong><br>${product.price}€ x ${qty} ${size ? `· ${size}` : ''} ${color ? `· ${color}` : ''}<br><strong>${st} €</strong></div></div>`; });
      document.getElementById('appRoot').innerHTML = `<div class="page-container"><button class="back-link" onclick="history.back()"><i class="fas fa-arrow-left"></i> Retour</button><div style="background:white;border-radius:36px;padding:32px;box-shadow:0 12px 28px rgba(0,0,0,0.05);"><h2 style="color:var(--choco);margin-bottom:24px;">Récapitulatif de commande</h2>${html}<p style="font-weight:700;color:var(--choco);font-size:1.3rem;margin-top:20px;">Total : ${total} €</p><div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;"><button class="btn-cart" style="flex:1;" onclick="navigateTo('home')">Annuler</button><button class="btn-cart" style="flex:1;" onclick="showToast('Commande enregistrée')">Enregistrer</button><button class="btn-cart" style="flex:1;" onclick="history.back()">Modifier</button><button class="btn-buy" style="flex:2;" onclick="navigateTo('payment')">Continuer vers le paiement</button></div></div></div>`;
    }

    function renderPayment() {
      document.getElementById('appRoot').innerHTML = `<div class="page-container"><button class="back-link" onclick="history.back()"><i class="fas fa-arrow-left"></i> Retour</button><div style="background:white;border-radius:36px;padding:32px;box-shadow:0 12px 28px rgba(0,0,0,0.05);"><h2 style="color:var(--choco);margin-bottom:24px;">Moyen de paiement</h2><p style="margin-bottom:20px;">Choisissez votre mode de paiement :</p><div style="display:flex;flex-direction:column;gap:16px;"><div style="padding:16px;border:2px solid #ddd;border-radius:20px;cursor:pointer;" onclick="completePayment('Mobile Money')"><i class="fas fa-mobile-alt" style="color:var(--choco);margin-right:12px;"></i> Mobile Money</div><div style="padding:16px;border:2px solid #ddd;border-radius:20px;cursor:pointer;" onclick="completePayment('Carte Bancaire')"><i class="fas fa-credit-card" style="color:var(--choco);margin-right:12px;"></i> Carte Bancaire</div><div style="padding:16px;border:2px solid #ddd;border-radius:20px;cursor:pointer;" onclick="completePayment('Paiement à la livraison')"><i class="fas fa-truck" style="color:var(--choco);margin-right:12px;"></i> Paiement à la livraison</div></div></div></div>`;
    }

    window.completePayment = function(method) {
      showToast(`Paiement par ${method} confirmé !`);
      cart = []; saveCart();
      setTimeout(() => navigateTo('home'), 1500);
    };

    function renderCartPage() {
      const root = document.getElementById('appRoot');
      if (!isSubscribed) { root.innerHTML = `<div class="page-container"><div class="empty-state"><i class="fas fa-shopping-cart"></i><h2>Panier vide</h2><p>Créez un compte pour utiliser le panier.</p><button class="btn-buy" style="margin-top:16px;" onclick="window.location.href='compte_classique.php'">S'abonner maintenant</button></div></div>`; return; }
      if (cart.length === 0) { root.innerHTML = `<div class="page-container"><div class="empty-state"><i class="fas fa-shopping-cart"></i><h2>Panier vide</h2><p>Ajoutez des articles à votre panier.</p><button class="btn-buy" style="margin-top:16px;" onclick="navigateTo('home')">Parcourir les articles</button></div></div>`; return; }
      let total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
      root.innerHTML = `<div class="page-container"><div style="background:white;border-radius:28px;padding:24px;"><h2>Mon panier</h2>${cart.map((item, idx) => `<div style="display:flex;gap:12px;padding:14px 0;border-bottom:1px solid #eee;align-items:center;"><img src="${item.image}" style="width:60px;height:60px;border-radius:16px;object-fit:cover;"><div style="flex:1;"><strong>${item.name}</strong><br>${item.price}€ x ${item.qty} ${item.size ? `· ${item.size}` : ''} ${item.color ? `· ${item.color}` : ''}</div><button onclick="window._removeCartItem(${idx})" style="background:#eee;border:none;border-radius:40px;padding:8px 16px;cursor:pointer;">Retirer</button></div>`).join('')}<div style="margin-top:20px;font-weight:bold;font-size:1.2rem;">Total : ${total} €</div><button class="btn-buy" style="margin-top:20px;width:100%;" onclick="navigateTo('cart-summary')">Valider la commande</button></div></div>`;
    }
    window._removeCartItem = (idx) => { cart.splice(idx, 1); saveCart(); renderCartPage(); };

    function renderCartSummary() {
      let total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
      let items = cart.map(item => ({ product: allProducts.find(p=>p.id===item.id), qty: item.qty, size: item.size, color: item.color }));
      pendingOrder = { items };
      let html = cart.map(item => `<div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid #eee;"><img src="${item.image}" style="width:50px;height:50px;border-radius:12px;object-fit:cover;"><div><strong>${item.name}</strong><br>${item.price}€ x ${item.qty} ${item.size ? `· ${item.size}` : ''} ${item.color ? `· ${item.color}` : ''}</div></div>`).join('');
      document.getElementById('appRoot').innerHTML = `<div class="page-container"><button class="back-link" onclick="navigateTo('cart')"><i class="fas fa-arrow-left"></i> Retour</button><div style="background:white;border-radius:36px;padding:32px;box-shadow:0 12px 28px rgba(0,0,0,0.05);"><h2 style="color:var(--choco);margin-bottom:24px;">Récapitulatif du panier</h2>${html}<p style="font-weight:700;color:var(--choco);font-size:1.3rem;margin-top:20px;">Total : ${total} €</p><div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;"><button class="btn-cart" style="flex:1;" onclick="navigateTo('home')">Annuler</button><button class="btn-cart" style="flex:1;" onclick="showToast('Panier enregistré')">Enregistrer</button><button class="btn-cart" style="flex:1;" onclick="navigateTo('cart')">Modifier</button><button class="btn-buy" style="flex:2;" onclick="navigateTo('payment')">Continuer vers le paiement</button></div></div></div>`;
    }

    function renderHome() {
      let cat = "Tous", search = "";
      const root = document.getElementById('appRoot');
      root.innerHTML = `<div class="page-container">${!isSubscribed ? `<div class="subscribe-banner"><div><strong>Accès limité</strong><br><small>Abonnez-vous pour débloquer le panier.</small></div><button onclick="window.location.href='compte_classique.php'">S'abonner maintenant</button></div>` : ''}<div class="search-box"><i class="fas fa-search"></i><input id="homeSearch" placeholder="Rechercher..."></div><div class="carousel-wrapper"><div class="carousel"><div class="carousel-container" id="carouselContainer"></div></div><div class="carousel-dots" id="carouselDots"></div></div><div class="categories-bar" id="categoriesList"></div><h2>Recommandations</h2><div class="products-grid" id="productsGrid"></div></div>`;
      const cc = document.getElementById('carouselContainer'); cc.innerHTML = carouselSlides.map(s => `<div class="carousel-slide" style="background-image:url('${s.img}')"><div class="carousel-badge">${s.text}</div></div>`).join('');
      const dotsDiv = document.getElementById('carouselDots'); dotsDiv.innerHTML = carouselSlides.map((_,i) => `<span class="dot ${i===0?'active':''}" data-idx="${i}"></span>`).join('');
      let idx=0; function updateCarousel() { cc.style.transform = `translateX(-${idx*100}%)`; document.querySelectorAll('#carouselDots .dot').forEach((d,i) => d.classList.toggle('active', i===idx)); }
      setInterval(() => { idx=(idx+1)%carouselSlides.length; updateCarousel(); }, 3000);
      document.querySelectorAll('#carouselDots .dot').forEach(d => d.onclick = () => { idx=parseInt(d.dataset.idx); updateCarousel(); });
      const cats = ["Tous","Electronique","Mode","Beauté","Mobilier","Artisanat","Accessoires"];
      const catBar = document.getElementById('categoriesList');
      function renderCats() { catBar.innerHTML = cats.map(c => `<div class="category ${c===cat?'active':''}" data-cat="${c}">${c}</div>`).join(''); document.querySelectorAll('.category').forEach(el => el.onclick = () => { cat=el.dataset.cat; renderCats(); filterProducts(); }); }
      document.getElementById('homeSearch').oninput = e => { search=e.target.value.toLowerCase(); filterProducts(); };
      function filterProducts() {
        let f = allProducts.filter(p => cat==="Tous"||p.category===cat);
        if(search) f = f.filter(p => p.name.toLowerCase().includes(search));
        const grid = document.getElementById('productsGrid');
        grid.innerHTML = f.map(p => `<div class="product-card" data-id="${p.id}">
          ${p.tier==='premium'?'<div class="premium-badge">Premium</div>':''}
          <img src="${p.image}" onclick="navigateTo('product-${p.id}')">
          <div class="product-content"><div class="product-name" onclick="navigateTo('product-${p.id}')">${p.name}</div><div class="product-price">${p.price} €</div></div>
          <div class="mini-selectors">${p.colors ? `<div class="mini-colors">${p.colors.map(c => `<div class="mini-color-dot" style="background:${p.colorHex?.[c]||'#ccc'}" data-cl="${c}" title="${c}"></div>`).join('')}</div>` : ''}${p.sizes ? `<div class="mini-sizes">${p.sizes.map(s => `<div class="mini-size-btn" data-sz="${s}">${s}</div>`).join('')}</div>` : ''}<div class="mini-qty"><button>−</button><span>1</span><button>+</button></div></div>
          <div class="card-actions"><button class="btn-buy" style="padding:6px 12px;font-size:0.7rem;flex:1;" onclick="event.stopPropagation();directPurchase(${p.id},this)">Acheter</button>${isSubscribed ? `<button class="add-btn" onclick="event.stopPropagation();addToCartFromCard(${p.id},this)" title="Ajouter au panier">+</button>` : ''}</div>
        </div>`).join('');
        document.querySelectorAll('.mini-color-dot').forEach(d => d.onclick = function(e){ e.stopPropagation(); this.parentElement.querySelectorAll('.mini-color-dot').forEach(x=>x.classList.remove('selected')); this.classList.add('selected'); });
        document.querySelectorAll('.mini-size-btn').forEach(b => b.onclick = function(e){ e.stopPropagation(); this.parentElement.querySelectorAll('.mini-size-btn').forEach(x=>x.classList.remove('selected')); this.classList.add('selected'); });
        document.querySelectorAll('.mini-qty button:first-child').forEach(b => b.onclick = function(e){ e.stopPropagation(); const s = this.nextElementSibling; s.textContent = Math.max(1, parseInt(s.textContent)-1); });
        document.querySelectorAll('.mini-qty button:last-child').forEach(b => b.onclick = function(e){ e.stopPropagation(); const s = this.previousElementSibling; s.textContent = parseInt(s.textContent)+1; });
      }
      window.directPurchase = function(pid, btn) { const card = btn.closest('.product-card'); const p = allProducts.find(x=>x.id===pid); const qty = parseInt(card.querySelector('.mini-qty span').textContent)||1; const sz = card.querySelector('.mini-size-btn.selected')?.dataset.sz || p.sizes?.[0]||''; const cl = card.querySelector('.mini-color-dot.selected')?.dataset.cl || p.colors?.[0]||''; pendingOrder = { items: [{ product: p, qty, size: sz, color: cl }] }; renderOrderSummary(pendingOrder); };
      window.addToCartFromCard = function(pid, btn) { const card = btn.closest('.product-card'); const p = allProducts.find(x=>x.id===pid); const qty = parseInt(card.querySelector('.mini-qty span').textContent)||1; const sz = card.querySelector('.mini-size-btn.selected')?.dataset.sz || p.sizes?.[0]||''; const cl = card.querySelector('.mini-color-dot.selected')?.dataset.cl || p.colors?.[0]||''; addToCart(p, qty, sz, cl); };
      renderCats(); filterProducts();
    }

    function openNotifications() {
      const existing = document.getElementById('notifOverlay');
      if (existing) { existing.remove(); return; }
      const overlay = document.createElement('div'); overlay.className = 'notif-overlay'; overlay.id = 'notifOverlay';
      overlay.innerHTML = `<div class="notif-panel"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;"><h2>Notifications</h2><button style="background:none;border:none;font-size:24px;cursor:pointer;" id="closeNotifBtn">✕</button></div><div class="notif-item" id="notifMsg"><div class="avatar-placeholder" style="background:#A8815A;">AC</div><div style="flex:1"><strong>Nouveau message</strong><br><small>Amadou Couture vous a répondu.</small></div><small>10:30</small></div><div class="notif-item" id="notifOrder"><div class="notif-icon" style="background:#FFF3E0;color:#E65100;"><i class="fas fa-box"></i></div><div style="flex:1"><strong>Commande expédiée</strong><br><small>Votre boubou est en route.</small></div><small>Hier</small></div><div class="notif-item" id="notifPremium"><div class="notif-icon" style="background:#FFF8E1;color:#F57F17;"><i class="fas fa-star"></i></div><div style="flex:1"><strong>Offre premium</strong><br><small>Abonnez-vous pour 2€/mois.</small></div><small>Mar</small></div></div>`;
      overlay.onclick = function(e) { if (e.target === overlay) overlay.remove(); };
      document.body.appendChild(overlay);
      document.getElementById('closeNotifBtn').onclick = () => overlay.remove();
      document.getElementById('notifMsg').onclick = () => { overlay.remove(); openChatFromNotification('seller1'); };
      document.getElementById('notifOrder').onclick = () => { overlay.remove(); navigateTo('home'); };
      document.getElementById('notifPremium').onclick = () => { overlay.remove(); window.location.href='compte_classique.php'; };
    }

    window.openChatFromNotification = function(sellerId) {
      const product = allProducts.find(p => p.sellerId === sellerId);
      if (product) { getOrCreateConversation(product.sellerId, product.seller, product.sellerAvatar); renderChatFullscreen(product.sellerId); }
    };

    window.navigateTo = function(route) {
      const ec = document.getElementById('chatFullscreen'); if(ec) ec.remove();
      const notif = document.getElementById('notifOverlay'); if(notif) notif.remove();
      if(route.startsWith('product-')) { renderProductDetail(parseInt(route.split('-')[1])); setActiveFooter('home'); }
      else if(route === 'messages') { renderMessagesList(); setActiveFooter('messages'); }
      else if(route === 'payment') { renderPayment(); setActiveFooter('home'); }
      else if(route === 'cart') { renderCartPage(); setActiveFooter('cart'); }
      else if(route === 'cart-summary') { renderCartSummary(); setActiveFooter('cart'); }
      else { renderHome(); setActiveFooter('home'); }
    };

    function setActiveFooter(route) { document.querySelectorAll('footer a').forEach(a => a.classList.remove('active')); const m = Array.from(document.querySelectorAll('footer a')).find(a => a.dataset.route === route); if(m) m.classList.add('active'); }
    document.querySelectorAll('footer a').forEach(a => a.addEventListener('click', e => { e.preventDefault(); navigateTo(a.dataset.route); }));
    updateSidebarProfile(); navigateTo('home'); window.showToast = showToast;
})();
</script>
</body>
</html>