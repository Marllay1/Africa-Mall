# AfricaMall — Cahier des charges client (conception complète)

> Document fourni tel quel par le client (2026-09-04), conservé verbatim comme référence faisant autorité. Voir `MEMOIRE-SESSION.md` §1bis pour l'analyse des écarts entre ce document et l'état réel du code, et §6 pour la feuille de route qui en découle. **Ce document ne remplace pas le travail déjà livré — il vient le compléter.** Consigne du client répétée explicitement : « Il ne s'agit pas de recommencer from scratch, mais de continuer le développement selon les fichiers .php qui étaient déjà là. »

---

## AFRICAMALL — CONCEPTION COMPLÈTE DE LA PLATEFORME

Customer + Seller + Admin | Web + PWA + Mobile

L'application AfricaMall est une marketplace e-commerce africaine.

Le projet doit être conçu autour d'un principe fondamental :

Un seul compte utilisateur, plusieurs rôles et plusieurs expériences adaptées à chaque usage.

Un utilisateur commence comme Customer. Il peut ensuite souscrire au statut Seller directement depuis son espace Customer.

Une fois Seller activé, il possède donc simultanément les deux capacités :

Customer + Seller

Il ne faut jamais créer deux comptes indépendants pour la même personne.

L'architecture doit être pensée dès maintenant pour supporter :

* une plateforme Web ;
* des expériences PWA ;
* deux applications mobiles distinctes ;
* un espace Admin Web indépendant ;
* un backend et une base de données communs.

### 1. ARCHITECTURE GLOBALE

Architecture cible :

```text
                         AFRICAMALL CORE
                    Backend / API / Services
                              │
                     ┌────────┴────────┐
                     │                 │
                  CUSTOMER           SELLER
                     │                 │
          ┌──────────┴───────┐ ┌──────┴──────────┐
          │                  │ │                 │
        WEB                PWA WEB             PWA
          │                  │ │                 │
          └──────────┬───────┘ └──────┬──────────┘
                     │                 │
                     ▼                 ▼
               AFRICAMALL       AFRICAMALL BUSINESS
                  CLIENT              SELLER
                     │                 │
                     └────────┬────────┘
                              │
                         API commune
                              │
                         Base commune


                     ┌─────────────────┐
                     │ AFRICAMALL ADMIN│
                     │   WEB ONLY      │
                     └────────┬────────┘
                              │
                         API commune
```

Les interfaces Customer, Seller et Admin sont distinctes, mais elles utilisent :

* le même système d'authentification ;
* les mêmes utilisateurs ;
* les mêmes API centrales ;
* la même base de données ;
* les mêmes services de paiement ;
* le même système de notifications ;
* le même système de messagerie ;
* les mêmes événements temps réel.

### 2. LOGIQUE DU COMPTE

Compte initial

Lorsqu'une personne crée un compte :

```text
Utilisateur
     ↓
Compte AfricaMall
     ↓
ROLE = CUSTOMER
```

Elle peut immédiatement :

* parcourir les produits ;
* acheter ;
* gérer son panier ;
* commander ;
* utiliser ses favoris ;
* communiquer avec les vendeurs ;
* etc.

### 3. DEVENIR SELLER

Depuis l'interface Customer, le menu du compte doit contenir une option permettant de devenir vendeur.

Exemple : Menu → Devenir vendeur

Le parcours est :

```text
Customer
   ↓
Devenir vendeur
   ↓
Présentation Seller Center
   ↓
Conditions / avantages / tarifs
   ↓
Souscription
   ↓
Informations vendeur
   ↓
Informations boutique
   ↓
Documents éventuels / KYC
   ↓
Paiement éventuel
   ↓
Demande Seller
   ↓
Validation Admin si nécessaire
   ↓
Seller ACTIVE
```

Le système doit gérer au minimum :

```text
CUSTOMER
SELLER_PENDING
SELLER_ACTIVE
SELLER_SUSPENDED
SELLER_REJECTED
```

Une fois activé :

```text
Utilisateur
│
├── Customer ✓
│
└── Seller ✓
```

Le Customer n'est donc jamais supprimé.

### 4. CHANGEMENT DE MODE

Une fois Seller actif, l'utilisateur peut changer d'espace.

Depuis Customer : Accéder à Seller Center
Depuis Seller : Passer au mode Client

Le changement de mode :

* ne doit pas déconnecter l'utilisateur ;
* doit conserver la session ;
* doit conserver le JWT/session sécurisé ;
* doit vérifier les permissions ;
* doit charger l'interface correspondant au rôle ;
* doit conserver le contexte utilisateur ;
* doit être instantané autant que possible.

Le backend doit contrôler les permissions à chaque requête.

### 5. COMPORTEMENT WEB

Sur Web, AfricaMall doit fonctionner comme une plateforme unifiée.

Entrée principale : AFRICA MALL CUSTOMER

Un nouvel utilisateur ne doit pas arriver directement dans Seller Center. Il commence par l'expérience Customer.

Cependant, après activation Seller, le système peut mémoriser le dernier espace utilisé.

Exemple :

```text
Dernier espace utilisé = Seller
        ↓
Connexion
        ↓
Proposer / ouvrir Seller Center
```

Mais l'utilisateur doit toujours pouvoir revenir au mode Client. Ne jamais supprimer ou bloquer l'accès Customer parce que le compte est devenu Seller.

### 6. APPLICATIONS MOBILES / PWA

Sur mobile, l'expérience doit être séparée en deux applications, sur le modèle : WhatsApp + WhatsApp Business

**APPLICATION 1 — AFRICAMALL** (destinée principalement aux Customers) :

* accueil ; carrousel ; produits ; catégories ; recherche ; panier ; paiement ; commandes ; favoris ; messagerie ; recommandations ; notifications ; profil ; etc.

**APPLICATION 2 — AFRICAMALL BUSINESS** (destinée aux Sellers) :

* dashboard ; boutique ; produits ; commandes ; stocks ; revenus ; statistiques ; clients ; messages ; Premium ; paramètres ; etc.

Les deux applications utilisent le même compte.

```text
              COMPTE GABRIEL
                    │
          ┌─────────┴─────────┐
          │                   │
     AFRICAMALL        AFRICAMALL BUSINESS
      Customer               Seller
          │                   │
          └─────────┬─────────┘
                    │
               Backend commun
```

Un utilisateur peut donc acheter sur AfricaMall, ouvrir AfricaMall Business, gérer sa boutique, revenir sur AfricaMall, acheter à nouveau — sans créer de second compte.

### 7. WEB SELLER CENTER

L'application possède actuellement un Seller Center avec une interface moderne. Cette interface doit être conservée et améliorée.

**Thème visuel — couleurs :**
- Fond principal : `#faf7f2`
- Sidebar : `#3e2c1f`
- Couleurs secondaires : `#b68b5c`, `#d9b382`, `#5e3e2b`

**Style :** moderne, élégant, premium, inspiration marketplace africaine, cartes arrondies, ombres douces, responsive, mobile-first, excellente lisibilité, hiérarchie visuelle claire.

**Typographie :** Segoe UI, system-ui, sans-serif.

### 8. SIDEBAR SELLER

Sections :
- **PRINCIPALE** : Dashboard
- **BOUTIQUE** : Ma Boutique, Produits, Commandes
- **FINANCES** : Revenus, Statistiques
- **COMMUNICATION** : Messages
- **AUTRES** : Premium, Paramètres, Déconnexion

Règles : un seul menu Messages ; chaque onglet possède sa propre page ; page active clairement mise en évidence ; sidebar rétractable Desktop ; sidebar ouvrable sur mobile ; navigation responsive ; permissions contrôlées côté backend. Décrire précisément le comportement de chaque menu.

### 9. TOPBAR SELLER

Contient : bouton menu ; barre de recherche ; icône Messages + compteur non lus ; icône Notifications + compteur ; photo du profil ; nom du vendeur ; statut vendeur ; bouton de changement de mode (« Passer au mode Client », bascule sans déconnexion).

### 10. DASHBOARD SELLER

Cartes :
- **Revenus** : revenu actuel, variation, période, comparaison période précédente.
- **Produits actifs** : nombre, variation, produits en attente, produits en rupture.
- **Commandes** : nombre, variation, commandes en attente, commandes à expédier.
- **Clients** : nombre, nouveaux clients, variation.

Décrire : calculs, API, cache, mises à jour dynamiques, période sélectionnée, gestion des données absentes.

### 11. PRODUITS

Tableau : Produit ; Prix ; Stock ; Statut ; Actions.

Produits d'exemple (Nom / Catégorie / Prix / Stock / Statut) :
1. [Nom produit] / Electronique / [Prix] / [Stock] / Actif
2. Robe Africaine / Mode / [Prix] / [Stock] / En attente
3. Chaussures Cuir / Mode / [Prix] / [Stock] / Rupture

Actions : Modifier ; Supprimer ; Ajouter produit.

Décrire précisément :
- **Ajout** : ouverture modal, validations, envoi API, sauvegarde, ajout dynamique au tableau, mise à jour des compteurs.
- **Modification** : ouverture formulaire, modification nom/prix/stock/catégorie/images/description, sauvegarde.
- **Suppression** : confirmation, suppression, mise à jour interface, gestion erreur API.
- **Recherche** : filtrage dynamique par nom, catégorie, statut, stock.

### 12. MODAL AJOUT PRODUIT

Champs : nom ; description ; prix ; prix promotionnel ; stock ; catégorie ; sous-catégorie ; images ; variantes ; poids ; dimensions ; informations livraison.

Catégories : Electronique ; Mode ; Beauté ; Accessoires.

Décrire : validations, champs obligatoires, formats, limites, messages d'erreur, règles métier, upload images, compression, aperçu, suppression image, sauvegarde brouillon si nécessaire.

### 13. COMMANDES SELLER

Page complète. Fonctions : afficher commandes ; rechercher ; filtrer ; voir détails ; changer statut ; annuler lorsque permis ; préparer commande ; confirmer expédition ; suivre livraison.

Statuts Seller : En attente ; Confirmée ; Préparation ; Expédiée ; Livrée ; Annulée ; Litige.

Décrire les transitions autorisées et interdites.

### 14. FINANCES SELLER

- **Revenus** : revenus totaux, revenus disponibles, revenus en attente, commissions, remboursements, retraits.
- **Transactions** : historique, paiements, commissions, remboursements.
- **Retraits** : demande de retrait, montant disponible, statut, historique.
- **Statistiques** : graphiques ventes, revenus, commandes, produits, clients.

### 15. MESSAGERIE SELLER

Messagerie complète inspirée de WhatsApp. Conversations d'exemple : Oumou Famanta ; Jean Traoré ; Amina Diallo.

Fonctions : liste conversations ; recherche ; messages non lus ; ouverture conversation ; envoi/réception ; entrée clavier ; bouton envoyer ; emojis ; images ; pièces jointes ; audio si prévu ; statut en ligne/hors ligne ; accusé réception ; accusé lecture.

Backend : table conversations ; table messages ; WebSocket ; API REST ; notifications temps réel ; statuts message ; synchronisation multi-appareils.

### 16. PREMIUM SELLER

Objectif : augmenter la visibilité du vendeur.

Fonctions : produits sponsorisés ; mise en avant ; visibilité accrue ; abonnements ; paiements ; statistiques Premium ; campagnes promotionnelles.

Décrire : offres, tarifs, souscription, renouvellement, expiration, paiement, activation, désactivation, statistiques.

### 17. PARAMÈTRES SELLER

Profil ; Boutique ; Sécurité ; Notifications ; Préférences ; Mot de passe ; Moyens de paiement ; Moyens de retrait ; Informations commerciales.

### 18. EXPÉRIENCE CUSTOMER — ACCUEIL

L'écran principal contient obligatoirement un **carrousel dynamique principal** présentant : promotions ; offres spéciales ; produits sponsorisés ; Flash Sales ; nouveautés ; annonces ; événements ; campagnes marketing.

Décrire : défilement automatique/manuel, indicateurs, vitesse, responsive, chargement, API, priorités, règles Admin.

Autres éléments : catégories ; produits populaires ; produits recommandés ; vendeurs mis en avant ; recherche ; notifications ; raccourcis.

### 19. RECHERCHE CUSTOMER

Fonctions : recherche texte ; recherche vocale ; recherche image ; filtres avancés.

Filtres : prix ; catégorie ; localisation ; vendeur ; note ; popularité ; date ; promotion.

Décrire : tri, résultats, suggestions, historique, aucun résultat, recommandations alternatives.

### 20. CATÉGORIES

Exemples : Electronique ; Mode ; Beauté ; Maison ; Accessoires ; Sport ; Automobile ; Alimentation ; Téléphones ; Ordinateurs.

Prévoir : sous-catégories, navigation, filtres, tri, pagination/lazy loading.

### 21. PAGE PRODUIT

Contient : galerie images ; nom ; prix ; prix réduit ; description ; stock ; disponibilité ; boutique ; vendeur ; note ; avis ; nombre de ventes ; produits similaires ; produits recommandés.

Boutons : Ajouter au panier ; Acheter maintenant ; Ajouter aux favoris ; Contacter le vendeur ; Partager.

### 22. PANIER

Fonctions : ajout ; suppression ; quantité ; calcul automatique ; frais livraison ; codes promo ; total ; disponibilité ; produits indisponibles.

### 23. PAIEMENT

Méthodes : carte bancaire ; Mobile Money ; Orange Money ; Moov Money ; Wave ; PayPal ; paiement à la livraison si disponible.

Décrire : validation, sécurité, échec, succès, expiration, remboursement, confirmation.

### 24. COMMANDES CUSTOMER

Fonctions : historique ; détails ; suivi ; annulation ; retour ; remboursement.

Statuts : En attente ; Confirmée ; Préparation ; Expédiée ; Livrée ; Annulée ; Remboursée.

### 25. FAVORIS

Fonctions : ajout ; suppression ; partage ; notification baisse de prix ; disponibilité.

### 26. MESSAGERIE CUSTOMER

Communication Customer ↔ Seller. Fonctions : messages ; images ; pièces jointes ; audio ; emojis ; notifications temps réel ; accusés réception ; accusés lecture. Le Seller doit recevoir le message dans Seller Center / AfricaMall Business.

### 27. PROFIL CUSTOMER

Photo ; nom ; email ; numéro ; adresses ; moyens de paiement ; préférences ; sécurité ; historique.

Menu doit contenir : « Devenir vendeur / Seller Center » (si pas encore Seller) ou « Accéder à Seller Center » (si Seller actif).

### 28. NOTIFICATIONS CUSTOMER

Types : commandes ; messages ; promotions ; livraison ; paiement ; favoris ; baisse de prix ; recommandations ; compte.

### 29. UTILISATEUR NON CONNECTÉ

Autorisé : parcourir produits ; rechercher ; catégories ; consulter produit ; **acheter immédiatement un seul produit**.

Interdit : panier multi-produits ; favoris ; messagerie ; recommandations personnalisées ; historique ; commandes personnalisées ; avis ; notifications ; suivi personnalisé.

Afficher : « Connectez-vous pour accéder à cette fonctionnalité. »

### 30. UTILISATEUR CONNECTÉ

Autorisé : panier ; favoris ; messagerie ; recommandations ; commandes ; historique ; avis ; suivi ; notifications.

Personnalisation basée sur : navigation ; recherches ; achats ; favoris ; catégories ; interactions ; historique de commandes.

### 31. PREMIUM CUSTOMER

Avantages possibles : réductions exclusives ; livraison prioritaire ; offres réservées ; cashback ; ventes privées ; récompenses fidélité ; promotions personnalisées ; support prioritaire.

Décrire : abonnement, paiement, renouvellement, expiration, suspension, annulation, notifications, API, base de données.

### 32. ADMIN — BACKOFFICE WEB

Accessible exclusivement via AFRICAMALL ADMIN — backoffice Web indépendant, vision globale de la marketplace.

Modules :
- **Dashboard** : utilisateurs, vendeurs, produits, produits en attente, commandes, revenus plateforme, transactions, signalements, litiges, ventes, activité temps réel.
- **Utilisateurs** : rechercher, filtrer, bloquer, débloquer, supprimer, modifier, historique, permissions.
- **Sellers** : demandes, validation, rejet, suspension, KYC, historique, niveau, Premium.
- **Produits** : validation, rejet, modification, suppression, masquage, signalement.
- **Commandes** : suivi, litiges, annulation, remboursement.
- **Paiements** : transactions, commissions, retraits, paiements échoués, fraudes.
- **Litiges** : dossier, messages, preuves, décision, remboursement, sanctions.
- **Modération** : produits, images, messages, comptes, signalements, contenus interdits.
- **Publicités** : campagnes, ciblage, budget, durée, statistiques.
- **Promotions** : coupons, codes promo, offres spéciales, Flash Sales, réductions.
- **Paramètres** : plateforme, API, paiements, emails, SMS, sécurité, permissions, logs, sauvegardes.

### 33. BASE DE DONNÉES

Tables à concevoir, notamment :

```text
users
user_roles
stores
seller_profiles
seller_subscriptions
products
product_categories
product_images
product_variants
inventory
orders
order_items
payments
transactions
withdrawals
conversations
messages
notifications
favorites
reviews
premium_subscriptions
premium_campaigns
advertisements
promotions
coupons
disputes
reports
analytics
audit_logs
```

Pour chaque table : nom, champ, type, clé primaire, clés étrangères, index, contraintes, relations, valeurs par défaut, règles métier. Le modèle doit éviter les duplications inutiles.

### 34. API

Architecture API REST complète. Exemples :

```text
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout

GET    /api/products
POST   /api/products
GET    /api/products/{id}
PUT    /api/products/{id}
DELETE /api/products/{id}

GET    /api/orders
POST   /api/orders
PATCH  /api/orders/{id}/status

GET    /api/messages
POST   /api/messages

POST   /api/seller/apply
GET    /api/seller/profile
POST   /api/seller/store

GET    /api/notifications

POST   /api/payments
GET    /api/payments

GET    /api/admin/users
GET    /api/admin/sellers
PATCH  /api/admin/sellers/{id}/approve
PATCH  /api/admin/sellers/{id}/reject
```

Décrire : authentification, autorisation, validation, réponses, codes HTTP, erreurs, pagination, filtres, tri, rate limiting.

### 35. AUTHENTIFICATION ET PERMISSIONS

Système sécurisé basé sur : JWT ou mécanisme de session sécurisé ; refresh token si nécessaire ; expiration ; rotation ; permissions ; rôles ; middleware d'autorisation.

Rôles : CUSTOMER, SELLER, ADMIN — mais un utilisateur peut posséder CUSTOMER + SELLER simultanément. L'Admin reste un rôle séparé.

**Ne jamais faire confiance au rôle transmis uniquement par le frontend. Toutes les permissions doivent être vérifiées côté serveur.**

### 36. SÉCURITÉ

Protections contre : XSS ; CSRF ; SQL Injection ; brute force ; session hijacking ; vol JWT ; escalade de privilèges ; upload malveillant ; abus API ; spam ; fraude paiement.

Prévoir : rate limiting, logs, audit logs, sauvegardes, monitoring, chiffrement, contrôle d'accès.

### 37. TEMPS RÉEL

WebSocket ou équivalent pour : messagerie ; notifications ; statut en ligne ; commandes ; changements de statut ; événements Seller ; alertes Admin.

Décrire : événements, reconnexion, synchronisation, gestion offline, accusés réception.

### 38. PERFORMANCE

Pagination ; lazy loading ; cache ; compression ; CDN ; optimisation images ; WebP/AVIF si pertinent ; indexation base de données ; requêtes optimisées ; chargement progressif ; code splitting ; service worker PWA.

### 39. RELATIONS ENTRE LES TROIS ESPACES

```text
             CUSTOMER
             ↙      ↘
            ↙        ↘
       SELLER ←────→ ADMIN
```

- **Customer ↔ Seller** : produits, commandes, messages, avis, boutiques, paiements, livraison.
- **Customer ↔ Admin** : signalements, support, paiements, litiges, notifications, modération.
- **Seller ↔ Admin** : validation, KYC, produits, commandes, paiements, commissions, litiges, Premium, sanctions.

### 40. RÈGLE FONDAMENTALE D'ARCHITECTURE

Ne jamais considérer Customer et Seller comme deux comptes indépendants.

```text
                  USER
                   │
          ┌────────┴────────┐
          │                 │
       CUSTOMER           SELLER
          │                 │
     AfricaMall      AfricaMall Business
```

Le compte reste unique. Les expériences sont séparées. Les données sont communes. Les permissions sont différentes.

### 41. LIVRABLE FINAL (documentation)

Documentation professionnelle PDF : couverture, table des matières, pagination, architectures (globale/Web/PWA/mobile/Admin), diagrammes, schémas, wireframes textuels, user stories, parcours (Customer/Seller/Admin/Customer→Seller), flux (changement de mode/commande/paiement/messagerie/validation Seller), cas d'erreur, règles métier, modèle de données, relations, doc API, sécurité, WebSocket, permissions, performance, checklist finale, annexes techniques.

*(Ce point 41 est un livrable documentaire distinct — pas une tâche de développement. À traiter séparément si/quand demandé.)*

### 42. CONSIGNE FINALE

Avant toute modification du projet existant :

1. analyser complètement le code ;
2. analyser les routes ;
3. analyser les interfaces existantes ;
4. analyser le Seller Center actuel ;
5. analyser le menu Customer existant ;
6. identifier le mécanisme actuel de compte utilisateur ;
7. identifier l'authentification ;
8. identifier les modèles de données ;
9. identifier les fonctionnalités déjà implémentées ;
10. identifier les incohérences ;
11. proposer l'architecture cible ;
12. puis effectuer les modifications.

Ne pas reconstruire inutilement ce qui existe déjà. Ne supprimer aucune fonctionnalité sans analyser son impact. Ne pas créer de comptes Customer et Seller séparés. Ne pas créer une deuxième base utilisateur pour AfricaMall Business. Ne pas créer une deuxième authentification indépendante.

AfricaMall, AfricaMall Business et AfricaMall Admin doivent être trois expériences distinctes reposant sur une infrastructure centrale commune.

**Principe directeur : ONE ACCOUNT — MULTIPLE ROLES — MULTIPLE EXPERIENCES**

- AfricaMall = Customer Experience
- AfricaMall Business = Seller Experience
- AfricaMall Admin = Platform Administration

Le passage de Customer à Seller doit être une évolution du même compte, et non une création de compte séparé.
