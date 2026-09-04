<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
  <title>Seller Center • Africa Mall</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

  <style>
    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:'Segoe UI',system-ui,-apple-system,sans-serif;
    }

    body{
      background:#faf7f2;
      display:flex;
      min-height:100vh;
      overflow-x:hidden;
    }

    a{
      text-decoration:none;
      color:inherit;
    }

    .sidebar{
      width:270px;
      background:#3e2c1f;
      color:#f3e7d9;
      height:100vh;
      position:fixed;
      left:0;
      top:0;
      overflow-y:auto;
      transition:.3s ease;
      z-index:1000;
      padding:20px;
      box-shadow:2px 0 15px rgba(0,0,0,.08);
    }

    .sidebar.collapsed{
      width:90px;
    }

    .logo{
      display:flex;
      align-items:center;
      gap:12px;
      margin-bottom:30px;
    }

    .logo img{
      width:45px;
      height:45px;
      border-radius:50%;
      object-fit:cover;
      border:2px solid #d9b382;
    }

    .logo h2{
      color:#e7c7a7;
      font-size:20px;
      white-space:nowrap;
      font-weight:600;
      letter-spacing:.5px;
    }

    .sidebar.collapsed h2,
    .sidebar.collapsed span,
    .sidebar.collapsed .submenu,
    .sidebar.collapsed .menu-group-title{
      display:none;
    }

    .menu{
      list-style:none;
      margin-top:10px;
    }

    .menu li{
      margin-bottom:4px;
    }

    .menu a{
      display:flex;
      align-items:center;
      gap:12px;
      padding:11px 14px;
      border-radius:12px;
      transition:all .25s;
      font-size:14px;
      color:#f3e7d9;
      font-weight:500;
    }

    .menu a:hover{
      background:#5e3e2b;
      color:#fff5e6;
    }

    .menu .active{
      background:#b68b5c;
      color:#fffaf2;
      box-shadow:0 4px 12px rgba(140,90,40,.3);
      font-weight:600;
    }

    .sidebar.collapsed .menu a{
      justify-content:center;
      padding:12px;
    }

    .menu-group-title{
      font-size:11px;
      text-transform:uppercase;
      letter-spacing:1.5px;
      color:#b9a087;
      padding:8px 14px 6px;
      font-weight:700;
      margin-top:4px;
    }

    .submenu{
      padding-left:8px;
    }

    .submenu a{
      font-size:13px;
      padding:9px 14px;
    }

    .submenu a i{
      font-size:12px;
      width:16px;
    }

    .menu-divider{
      height:1px;
      background:rgba(255,255,255,.08);
      margin:12px 0;
    }

    .menu-badge{
      background:#c25a3a;
      color:white;
      font-size:10px;
      padding:2px 7px;
      border-radius:10px;
      margin-left:auto;
      font-weight:600;
    }

    .main{
      margin-left:270px;
      width:calc(100% - 270px);
      padding:25px 28px;
      transition:.3s ease;
      background:#faf7f2;
      min-height:100vh;
    }

    .main.expanded{
      margin-left:90px;
      width:calc(100% - 90px);
    }

    .topbar{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:20px;
      margin-bottom:30px;
      flex-wrap:wrap;
    }

    .left-top{
      display:flex;
      align-items:center;
      gap:15px;
      flex:1;
    }

    .toggle-btn{
      width:45px;
      height:45px;
      border:none;
      border-radius:14px;
      background:white;
      cursor:pointer;
      box-shadow:0 6px 16px rgba(110,70,30,.08);
      font-size:18px;
      color:#5e3e2b;
    }

    .search-box{
      background:white;
      padding:12px 20px;
      border-radius:18px;
      display:flex;
      align-items:center;
      gap:12px;
      flex:1;
      box-shadow:0 6px 18px rgba(100,60,20,.06);
      border:1px solid #ede3d3;
    }

    .search-box input{
      border:none;
      outline:none;
      width:100%;
      background:transparent;
    }

    .top-icons{
      display:flex;
      align-items:center;
      gap:18px;
    }

    .icon-btn{
      width:45px;
      height:45px;
      border-radius:14px;
      background:white;
      display:flex;
      align-items:center;
      justify-content:center;
      cursor:pointer;
      box-shadow:0 6px 16px rgba(100,60,20,.06);
      position:relative;
      color:#5e3e2b;
    }

    .notification-count{
      position:absolute;
      top:-5px;
      right:-5px;
      background:#c25a3a;
      color:white;
      width:19px;
      height:19px;
      border-radius:50%;
      font-size:11px;
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:bold;
    }

    .profile{
      display:flex;
      align-items:center;
      gap:10px;
      background:white;
      padding:8px 16px;
      border-radius:18px;
      box-shadow:0 6px 16px rgba(100,60,20,.06);
      border:1px solid #ede3d3;
    }

    .profile img{
      width:45px;
      height:45px;
      border-radius:50%;
      object-fit:cover;
      border:2px solid #d9b382;
    }

    .cards{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
      gap:22px;
      margin-bottom:30px;
    }

    .card{
      background:white;
      padding:24px 20px;
      border-radius:24px;
      box-shadow:0 10px 25px rgba(120,70,30,.08);
      position:relative;
      overflow:hidden;
      border:1px solid #f0e2d0;
    }

    .card h3{
      color:#7b5e47;
      font-size:15px;
      margin-bottom:10px;
    }

    .card h1{
      font-size:32px;
      color:#3e2c1f;
      margin-bottom:10px;
    }

    .card i{
      position:absolute;
      right:20px;
      top:20px;
      font-size:44px;
      color:#d9b382;
      opacity:.25;
    }

    .page{
      display:none;
    }

    .page.active{
      display:block;
    }

    .dashboard-grid{
      display:grid;
      grid-template-columns:2fr 1fr;
      gap:22px;
    }

    .products-section,
    .panel-box{
      background:white;
      border-radius:24px;
      padding:22px;
      box-shadow:0 10px 25px rgba(120,70,30,.07);
      border:1px solid #f0e2d0;
    }

    .section-title{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:22px;
      flex-wrap:wrap;
      gap:15px;
    }

    .section-title h2{
      color:#3e2c1f;
    }

    .btn{
      background:linear-gradient(135deg,#c29a6a,#a7754b);
      color:white;
      border:none;
      padding:12px 20px;
      border-radius:16px;
      cursor:pointer;
      font-weight:600;
    }

    table{
      width:100%;
      border-collapse:collapse;
    }

    th{
      background:#f9f2e7;
      padding:15px;
      text-align:left;
      color:#5e3e2b;
    }

    td{
      padding:14px 15px;
      border-bottom:1px solid #f0e2d0;
      color:#3e2c1f;
    }

    .product-info{
      display:flex;
      align-items:center;
      gap:14px;
    }

    .product-info img{
      width:55px;
      height:55px;
      border-radius:16px;
      object-fit:cover;
    }

    .status{
      padding:6px 14px;
      border-radius:30px;
      font-size:13px;
      font-weight:600;
      display:inline-block;
    }

    .active-status{
      background:#e7f0da;
      color:#4b6b2c;
    }

    .pending-status{
      background:#fdf1db;
      color:#b47b3c;
    }

    .out-status{
      background:#fce8e6;
      color:#b34a3b;
    }

    .action-btn{
      border:none;
      width:36px;
      height:36px;
      border-radius:12px;
      cursor:pointer;
      margin-right:6px;
      font-size:14px;
    }

    .edit-btn{
      background:#efe0d1;
      color:#7b5e47;
    }

    .delete-btn{
      background:#f7e0db;
      color:#b34a3b;
    }

    .simple-page{
      background:white;
      padding:40px;
      border-radius:24px;
      box-shadow:0 10px 25px rgba(120,70,30,.07);
      border:1px solid #f0e2d0;
    }

    .simple-page h2{
      margin-bottom:10px;
      color:#3e2c1f;
    }

    .simple-page p{
      color:#7b5e47;
    }

    .messages-container{
      display:flex;
      height:calc(100vh - 180px);
      background:white;
      border-radius:24px;
      overflow:hidden;
      box-shadow:0 10px 25px rgba(120,70,30,.07);
      border:1px solid #f0e2d0;
    }

    .contacts-list{
      width:350px;
      border-right:1px solid #f0e2d0;
      background:#fffaf4;
      display:flex;
      flex-direction:column;
    }

    .contacts-header{
      padding:20px;
      background:#f9f2e7;
      border-bottom:1px solid #f0e2d0;
    }

    .contacts-search{
      background:white;
      padding:10px 16px;
      border-radius:20px;
      display:flex;
      align-items:center;
      gap:10px;
      border:1px solid #e0cfb5;
      margin-top:12px;
    }

    .contacts-search input{
      border:none;
      outline:none;
      width:100%;
      background:transparent;
    }

    .contact-item{
      display:flex;
      align-items:center;
      gap:12px;
      padding:14px 20px;
      cursor:pointer;
      border-bottom:1px solid #faf0e6;
    }

    .contact-item.active{
      background:#efe0d1;
    }

    .contact-item img{
      width:50px;
      height:50px;
      border-radius:50%;
      object-fit:cover;
    }

    .contact-info{
      flex:1;
    }

    .contact-meta{
      text-align:right;
    }

    .unread{
      background:#b68b5c;
      color:white;
      font-size:11px;
      width:20px;
      height:20px;
      border-radius:50%;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      margin-top:4px;
    }

    .chat-area{
      flex:1;
      display:flex;
      flex-direction:column;
      background:#e8ddd0;
    }

    .chat-header{
      padding:16px 20px;
      background:#f0e2d0;
      display:flex;
      align-items:center;
      gap:12px;
    }

    .chat-header img{
      width:42px;
      height:42px;
      border-radius:50%;
    }

    .chat-messages{
      flex:1;
      padding:20px;
      overflow-y:auto;
      display:flex;
      flex-direction:column;
      gap:8px;
    }

    .message{
      max-width:65%;
      padding:10px 14px;
      border-radius:8px;
      line-height:1.4;
    }

    .received{
      background:white;
      align-self:flex-start;
    }

    .sent{
      background:#dcf8c6;
      align-self:flex-end;
    }

    .chat-input{
      padding:16px 20px;
      background:#f0e2d0;
      display:flex;
      gap:12px;
    }

    .chat-input input{
      flex:1;
      padding:12px 18px;
      border-radius:24px;
      border:none;
      outline:none;
    }

    .chat-input button{
      width:45px;
      height:45px;
      border-radius:50%;
      background:#b68b5c;
      border:none;
      color:white;
      cursor:pointer;
    }

    .modal{
      position:fixed;
      top:0;
      left:0;
      width:100%;
      height:100%;
      background:rgba(30,15,5,.5);
      display:none;
      align-items:center;
      justify-content:center;
      z-index:2000;
      padding:20px;
    }

    .modal-content{
      background:#fffaf4;
      width:100%;
      max-width:500px;
      border-radius:28px;
      padding:28px;
    }

    .form-group{
      margin-bottom:18px;
    }

    .form-group label{
      display:block;
      margin-bottom:8px;
      font-weight:600;
      color:#3e2c1f;
    }

    .form-group input,
    .form-group select{
      width:100%;
      padding:13px 16px;
      border-radius:16px;
      border:1px solid #e0cfb5;
      outline:none;
    }

    .modal-buttons{
      display:flex;
      justify-content:flex-end;
      gap:12px;
      margin-top:24px;
    }

    .cancel-btn{
      background:#e7dbcc;
      color:#3e2c1f;
    }

    @media(max-width:1100px){
      .dashboard-grid{
        grid-template-columns:1fr;
      }
    }

    @media(max-width:800px){
      .sidebar{
        left:-270px;
      }

      .sidebar.mobile-open{
        left:0;
      }

      .main,
      .main.expanded{
        margin-left:0;
        width:100%;
      }

      .messages-container{
        flex-direction:column;
        height:auto;
      }

      .contacts-list{
        width:100%;
      }
    }

  </style>
</head>

<body>

<div class="sidebar" id="sidebar">

  <div class="logo">
    <img src="africa mall logo.png" alt="Logo">
    <h2>AFRICA MALL</h2>
  </div>

  <ul class="menu">

    <div class="menu-group-title">Principal</div>

    <li>
      <a href="#" class="active" data-page="dashboard">
        <i class="fas fa-chart-line"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <div class="menu-group-title">Boutique</div>

    <li>
      <a href="#" data-page="products">
        <i class="fas fa-store"></i>
        <span>Ma Boutique</span>
      </a>
    </li>

    <div class="submenu">
      <li>
        <a href="#" data-page="products">
          <i class="fas fa-box"></i>
          <span>Produits</span>
        </a>
      </li>

      <li>
        <a href="#" data-page="orders">
          <i class="fas fa-shopping-cart"></i>
          <span>Commandes</span>
        </a>
      </li>
    </div>

    <div class="menu-group-title">Finances</div>

    <li>
      <a href="#" data-page="revenues">
        <i class="fas fa-wallet"></i>
        <span>Revenus</span>
      </a>
    </li>

    <div class="submenu">
      <li>
        <a href="#" data-page="statistics">
          <i class="fas fa-chart-pie"></i>
          <span>Statistiques</span>
        </a>
      </li>
    </div>

    <div class="menu-group-title">Communication</div>

    <li>
      <a href="#" data-page="messages">
        <i class="fas fa-comment-dots"></i>
        <span>Messages</span>
        <span class="menu-badge" id="sidebarMessageBadge">2</span>
      </a>
    </li>

    <div class="menu-divider"></div>

    <li>
      <a href="#" data-page="premium">
        <i class="fas fa-star"></i>
        <span>Premium</span>
      </a>
    </li>

    <li>
      <a href="#" data-page="settings">
        <i class="fas fa-cog"></i>
        <span>Paramètres</span>
      </a>
    </li>

    <li>
      <a href="#" onclick="logout()">
        <i class="fas fa-sign-out-alt"></i>
        <span>Déconnexion</span>
      </a>
    </li>

  </ul>

</div>

<div class="main" id="main">

  <div class="topbar">

    <div class="left-top">

      <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
      </button>

      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Rechercher..." onkeyup="searchProducts()">
      </div>

    </div>

    <div class="top-icons">

      <div class="icon-btn" onclick="navigateTo('messages')">
        <i class="fas fa-comment-dots"></i>
        <div class="notification-count" id="messageBadge">2</div>
      </div>

      <div class="icon-btn">
        <i class="fas fa-bell"></i>
        <div class="notification-count">3</div>
      </div>

      <div class="profile">
        <img src="https://i.pravatar.cc/100" alt="">
        <div>
          <strong>Gabriel</strong><br>
          <small>Marchand Premium</small>
        </div>
      </div>

    </div>

  </div>

  <!-- DASHBOARD -->

  <div class="page active" id="dashboard-page">

    <div class="cards">

      <div class="card">
        <h3>Revenus Totaux</h3>
        <h1>2 450 000 FCFA</h1>
        <i class="fas fa-wallet"></i>
      </div>

      <div class="card">
        <h3>Produits Actifs</h3>
        <h1 id="productCount">3</h1>
        <i class="fas fa-box-open"></i>
      </div>

      <div class="card">
        <h3>Commandes</h3>
        <h1>356</h1>
        <i class="fas fa-shopping-cart"></i>
      </div>

      <div class="card">
        <h3>Clients</h3>
        <h1>1 204</h1>
        <i class="fas fa-users"></i>
      </div>

    </div>

    <div class="dashboard-grid">

      <div class="products-section">

        <div class="section-title">
          <h2>Produits Récents</h2>

          <button class="btn" onclick="openModal()">
            <i class="fas fa-plus-circle"></i>
            Ajouter Produit
          </button>
        </div>

        <table>

          <thead>
            <tr>
              <th>Produit</th>
              <th>Prix</th>
              <th>Stock</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody id="productTable">

            <tr>
              <td>
                <div class="product-info">
                  <img src="Iphone15.jpg">
                  <div>
                    <strong>iPhone 15 Pro</strong><br>
                    <small>Electronique</small>
                  </div>
                </div>
              </td>

              <td>950 000 FCFA</td>
              <td>12</td>

              <td>
                <span class="status active-status">Actif</span>
              </td>

              <td>
                <button class="action-btn edit-btn" onclick="editProduct(this)">
                  <i class="fas fa-edit"></i>
                </button>

                <button class="action-btn delete-btn" onclick="deleteProduct(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>

            <tr>
              <td>
                <div class="product-info">
                  <img src="WhatsApp Image.jpg">
                  <div>
                    <strong>Robe Africaine</strong><br>
                    <small>Mode</small>
                  </div>
                </div>
              </td>

              <td>35 000 FCFA</td>
              <td>25</td>

              <td>
                <span class="status pending-status">En attente</span>
              </td>

              <td>
                <button class="action-btn edit-btn" onclick="editProduct(this)">
                  <i class="fas fa-edit"></i>
                </button>

                <button class="action-btn delete-btn" onclick="deleteProduct(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>

            <tr>
              <td>
                <div class="product-info">
                  <img src="WhatsApp Image4.jpg">
                  <div>
                    <strong>Chaussures Cuir</strong><br>
                    <small>Mode</small>
                  </div>
                </div>
              </td>

              <td>45 000 FCFA</td>
              <td>0</td>

              <td>
                <span class="status out-status">Rupture</span>
              </td>

              <td>
                <button class="action-btn edit-btn" onclick="editProduct(this)">
                  <i class="fas fa-edit"></i>
                </button>

                <button class="action-btn delete-btn" onclick="deleteProduct(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>

          </tbody>

        </table>

      </div>

      <div class="panel-box">
        <h3>Dernières Commandes</h3>

        <p>#AFR1024 - 75 000 FCFA</p><br>
        <p>#AFR1025 - 950 000 FCFA</p><br>
        <p>#AFR1026 - 120 000 FCFA</p>
      </div>

    </div>

  </div>

  <!-- PRODUITS -->

  <div class="page" id="products-page">
    <div class="simple-page">
      <h2>Gestion des Produits</h2>
      <p>Ajoutez, modifiez et supprimez vos produits.</p>
    </div>
  </div>

  <!-- COMMANDES -->

  <div class="page" id="orders-page">
    <div class="simple-page">
      <h2>Commandes</h2>
      <p>Liste des commandes clients.</p>
    </div>
  </div>

  <!-- REVENUS -->

  <div class="page" id="revenues-page">
    <div class="simple-page">
      <h2>Revenus</h2>
      <p>Suivi financier et revenus générés.</p>
    </div>
  </div>

  <!-- STATS -->

  <div class="page" id="statistics-page">
    <div class="simple-page">
      <h2>Statistiques</h2>
      <p>Analyse des ventes et performances.</p>
    </div>
  </div>

  <!-- PREMIUM -->

  <div class="page" id="premium-page">
    <div class="simple-page">
      <h2>Premium</h2>
      <p>Boostez votre visibilité avec Premium.</p>
    </div>
  </div>

  <!-- SETTINGS -->

  <div class="page" id="settings-page">
    <div class="simple-page">
      <h2>Paramètres</h2>
      <p>Configurez votre compte vendeur.</p>
    </div>
  </div>

  <!-- MESSAGES -->

  <div class="page" id="messages-page">

    <div class="messages-container">

      <div class="contacts-list">

        <div class="contacts-header">

          <h3>Messages Clients</h3>

          <div class="contacts-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Recherche..." onkeyup="filterContacts(this.value)">
          </div>

        </div>

        <div id="contactsContainer"></div>

      </div>

      <div class="chat-area">

        <div id="noChatSelected" style="margin:auto;">
          Sélectionnez une conversation
        </div>

        <div id="activeChat" style="display:none;flex-direction:column;height:100%;">

          <div class="chat-header">
            <img id="chatAvatar" src="">
            <div>
              <h4 id="chatName"></h4>
              <small id="chatStatus"></small>
            </div>
          </div>

          <div class="chat-messages" id="chatMessages"></div>

          <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Écrire un message..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>

        </div>

      </div>

    </div>

  </div>

</div>

<!-- MODAL -->

<div class="modal" id="productModal">

  <div class="modal-content">

    <h2>Ajouter un Produit</h2>

    <div class="form-group">
      <label>Nom</label>
      <input type="text" id="productName">
    </div>

    <div class="form-group">
      <label>Prix</label>
      <input type="number" id="productPrice">
    </div>

    <div class="form-group">
      <label>Stock</label>
      <input type="number" id="productStock">
    </div>

    <div class="form-group">
      <label>Catégorie</label>

      <select id="productCategory">
        <option>Electronique</option>
        <option>Mode</option>
        <option>Beauté</option>
      </select>
    </div>

    <div class="modal-buttons">

      <button class="btn cancel-btn" onclick="closeModal()">
        Annuler
      </button>

      <button class="btn" onclick="addProduct()">
        Ajouter
      </button>

    </div>

  </div>

</div>

<script>

  // NAVIGATION

  function navigateTo(pageName){

    document.querySelectorAll('.page').forEach(page=>{
      page.classList.remove('active');
    });

    const target=document.getElementById(pageName+'-page');

    if(target){
      target.classList.add('active');
    }

    document.querySelectorAll('.menu a').forEach(link=>{
      link.classList.remove('active');
    });

    const activeLink=document.querySelector(`[data-page="${pageName}"]`);

    if(activeLink){
      activeLink.classList.add('active');
    }

    if(pageName==='messages'){
      renderContacts();
    }

  }

  document.querySelectorAll('.menu a[data-page]').forEach(link=>{

    link.addEventListener('click',function(e){

      e.preventDefault();

      navigateTo(this.dataset.page);

    });

  });

  // SIDEBAR

  function toggleSidebar(){

    const sidebar=document.getElementById('sidebar');
    const main=document.getElementById('main');

    if(window.innerWidth<=800){

      sidebar.classList.toggle('mobile-open');

    }else{

      sidebar.classList.toggle('collapsed');
      main.classList.toggle('expanded');

    }

  }

  // MODAL

  function openModal(){
    document.getElementById('productModal').style.display='flex';
  }

  function closeModal(){
    document.getElementById('productModal').style.display='none';
  }

  window.onclick=function(e){

    if(e.target===document.getElementById('productModal')){
      closeModal();
    }

  }

  // PRODUITS

  function addProduct(){

    const name=document.getElementById('productName').value.trim();
    const price=document.getElementById('productPrice').value.trim();
    const stock=document.getElementById('productStock').value.trim();
    const category=document.getElementById('productCategory').value;

    if(!name || !price || stock===''){
      alert('Veuillez remplir tous les champs.');
      return;
    }

    const table=document.getElementById('productTable');

    let statusClass='active-status';
    let statusText='Actif';

    if(parseInt(stock)===0){
      statusClass='out-status';
      statusText='Rupture';
    }

    const row=document.createElement('tr');

    row.innerHTML=`
      <td>
        <div class="product-info">
          <img src="https://via.placeholder.com/55">
          <div>
            <strong>${name}</strong><br>
            <small>${category}</small>
          </div>
        </div>
      </td>

      <td>${parseInt(price).toLocaleString()} FCFA</td>

      <td>${stock}</td>

      <td>
        <span class="status ${statusClass}">
          ${statusText}
        </span>
      </td>

      <td>
        <button class="action-btn edit-btn" onclick="editProduct(this)">
          <i class="fas fa-edit"></i>
        </button>

        <button class="action-btn delete-btn" onclick="deleteProduct(this)">
          <i class="fas fa-trash"></i>
        </button>
      </td>
    `;

    table.prepend(row);

    updateProductCount();

    closeModal();

    document.getElementById('productName').value='';
    document.getElementById('productPrice').value='';
    document.getElementById('productStock').value='';

  }

  function deleteProduct(button){

    if(confirm('Supprimer ce produit ?')){

      button.closest('tr').remove();

      updateProductCount();

    }

  }

  function editProduct(button){

    const row=button.closest('tr');

    const currentName=row.querySelector('strong').innerText;
    const currentPrice=row.children[1].innerText.replace(/[^\d]/g,'');

    const newName=prompt('Modifier le nom',currentName);

    if(newName===null || newName.trim()===''){
      return;
    }

    const newPrice=prompt('Modifier le prix',currentPrice);

    if(newPrice===null || newPrice.trim()===''){
      return;
    }

    row.querySelector('strong').innerText=newName.trim();

    row.children[1].innerText=parseInt(newPrice).toLocaleString()+' FCFA';

  }

  function searchProducts(){

    const value=document.getElementById('searchInput').value.toLowerCase();

    document.querySelectorAll('#productTable tr').forEach(row=>{

      row.style.display=row.innerText.toLowerCase().includes(value)
      ? ''
      : 'none';

    });

  }

  function updateProductCount(){

    document.getElementById('productCount').innerText=
    document.querySelectorAll('#productTable tr').length;

  }

  // PREMIUM

  function goPremium(){
    navigateTo('premium');
  }

  // LOGOUT

  function logout(){

    const confirmLogout=confirm('Voulez-vous vraiment vous déconnecter ?');

    if(confirmLogout){

      alert('Déconnexion réussie.');

      window.location.href='login.html';

    }

  }

  // MESSAGES

  const currentUser='Gabriel';

  let conversations=[

    {
      id:1,
      clientName:'Marie Koffi',
      avatar:'https://i.pravatar.cc/100?img=1',
      status:'En ligne',
      unread:1,
      lastTime:'10:33',

      messages:[
        {
          sender:'Marie Koffi',
          text:'Bonjour, la robe est disponible ?',
          time:'10:30'
        },

        {
          sender:'Gabriel',
          text:'Oui disponible.',
          time:'10:31'
        }
      ]
    },

    {
      id:2,
      clientName:'Jean Traoré',
      avatar:'https://i.pravatar.cc/100?img=3',
      status:'Hors ligne',
      unread:1,
      lastTime:'09:20',

      messages:[
        {
          sender:'Jean Traoré',
          text:'Le iPhone est disponible ?',
          time:'09:20'
        }
      ]
    }

  ];

  let activeConversationId=null;

  function renderContacts(filter=''){

    const container=document.getElementById('contactsContainer');

    const filtered=conversations.filter(c=>
      c.clientName.toLowerCase().includes(filter.toLowerCase())
    );

    container.innerHTML=filtered.map(c=>`

      <div class="contact-item ${activeConversationId===c.id?'active':''}"
      onclick="openConversation(${c.id})">

        <img src="${c.avatar}">

        <div class="contact-info">
          <h4>${c.clientName}</h4>

          <div>
            ${c.messages[c.messages.length-1].text}
          </div>
        </div>

        <div class="contact-meta">

          <span>${c.lastTime}</span>

          ${c.unread>0
            ? `<div class="unread">${c.unread}</div>`
            : ''
          }

        </div>

      </div>

    `).join('');

    updateAllBadges();

  }

  function filterContacts(query){
    renderContacts(query);
  }

  function openConversation(id){

    activeConversationId=id;

    const conv=conversations.find(c=>c.id===id);

    if(!conv) return;

    conv.unread=0;

    document.getElementById('noChatSelected').style.display='none';

    document.getElementById('activeChat').style.display='flex';

    document.getElementById('chatAvatar').src=conv.avatar;
    document.getElementById('chatName').textContent=conv.clientName;
    document.getElementById('chatStatus').textContent=conv.status;

    renderMessages(id);

    renderContacts();

  }

  function renderMessages(id){

    const conv=conversations.find(c=>c.id===id);

    if(!conv) return;

    const container=document.getElementById('chatMessages');

    container.innerHTML=conv.messages.map(m=>`

      <div class="message ${m.sender===currentUser?'sent':'received'}">

        ${m.text}

        <div style="font-size:10px;margin-top:5px;">
          ${m.time}
        </div>

      </div>

    `).join('');

    container.scrollTop=container.scrollHeight;

  }

  function sendMessage(){

    const input=document.getElementById('messageInput');

    const text=input.value.trim();

    if(!text || !activeConversationId){
      return;
    }

    const conv=conversations.find(c=>c.id===activeConversationId);

    const now=new Date();

    const time=
      now.getHours().toString().padStart(2,'0')
      +':'+
      now.getMinutes().toString().padStart(2,'0');

    conv.messages.push({
      sender:currentUser,
      text:text,
      time:time
    });

    conv.lastTime=time;

    input.value='';

    renderMessages(activeConversationId);

    renderContacts();

  }

  function handleKeyPress(e){

    if(e.key==='Enter'){
      sendMessage();
    }

  }

  function updateAllBadges(){

    const totalUnread=conversations.reduce((sum,c)=>sum+c.unread,0);

    const topBadge=document.getElementById('messageBadge');

    topBadge.textContent=totalUnread;

    topBadge.style.display=totalUnread>0
    ? 'flex'
    : 'none';

    const sideBadge=document.getElementById('sidebarMessageBadge');

    sideBadge.textContent=totalUnread;

    sideBadge.style.display=totalUnread>0
    ? 'inline-flex'
    : 'none';

  }

  renderContacts();
  updateAllBadges();

</script>

</body>
</html>