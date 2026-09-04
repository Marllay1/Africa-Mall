# AfricaMall — Mémoire de session

**v2 — 2026-09-04** — + cahier des charges client complet reçu (42 sections) : analyse d'écart faite, feuille de route §6 réécrite en conséquence

> Ce fichier est le journal canonique du projet, organisé en sections numérotées. À chaque session : ajouter dans la bonne section, bumper la ligne de version ci-dessus (v2, v3…). Contient des identifiants de démo (pas de vrais secrets) — voir §4.

---

## 1. Contexte et objectif

Le projet `africamall-web` était un prototype PHP statique sans framework, sans vraie base de données, sans authentification réelle (voir §7 pour le détail de ce qui a été trouvé). Le mémo client demandait une refonte en trois espaces — **Customer**, **Seller**, **Admin** — bâtis sur **un compte utilisateur unique** (Customer → souscription Seller → validation Admin → Seller actif, sur le modèle WhatsApp / WhatsApp Business), avec un backend prêt pour une future séparation Web / PWA (AfricaMall vs AfricaMall Business).

Décision de stack validée avec l'utilisateur : **Laravel** (plutôt que Symfony — bootstrap plus rapide pour un dev solo).

## 1bis. Cahier des charges client (2026-09-04) et écart avec l'existant

Le client a fourni un cahier des charges complet et beaucoup plus détaillé (42 sections) — conservé intégralement dans **[`CAHIER-DES-CHARGES.md`](CAHIER-DES-CHARGES.md)** à la racine du repo. Consigne explicite du client, à respecter avant toute chose :

> « Il ne s'agit pas de recommencer from scratch, mais de continuer le développement selon les fichiers .php qui étaient déjà là. »

Ce cahier des charges **confirme** l'architecture déjà construite (compte unique, Customer→Seller par souscription, statuts pending/active/suspended/rejected, permissions vérifiées côté serveur, jamais deux comptes/deux auth séparés) et **détaille très largement au-delà** de ce qui a été livré. Analyse d'écart, section par section du cahier des charges :

**Déjà couvert (phases 1-3)** : §2/§3/§4 logique de compte unique et statuts Seller (identique, y compris `suspended`) ; §35/§40 permissions serveur uniquement, jamais deux comptes ; §7 sidebar Seller sombre `#3E2C1F` (couleur exacte déjà utilisée) ; §11 tableau produits + CRUD (en pages dédiées, pas en modal) ; §18 carrousel héro Customer (version simple : derniers produits actifs, pas encore promos/sponsorisés/Flash Sales) ; §29 accès invité en lecture (parcourir/rechercher/consulter, mais **pas encore** l'achat immédiat 1-produit sans compte que demande le §29 — actuellement le panier exige une connexion) ; §38 pagination.

**Partiellement couvert** : §13/§24 statuts de commande (j'ai pending/confirmed/shipped/delivered/cancelled — le cahier des charges veut aussi « Préparation » et « Litige ») ; §21 page produit (image unique + prix + stock + description ok, **manquent** galerie multi-images, avis, nombre de ventes, produits similaires, boutons Acheter maintenant/Contacter le vendeur/Partager) ; §22 panier (ok basique, **manquent** frais de livraison, codes promo) ; §27 profil Customer (page Breeze générique, pas le profil enrichi demandé) ; §32 Admin (seulement file de validation Seller + listes utilisateurs/boutiques en lecture seule — le reste du module est à construire).

**Non couvert — à construire** : §8 reste de la sidebar Seller (Ma Boutique, Revenus, Statistiques, Messages, Premium, Paramètres — je n'ai que Dashboard/Produits/Commandes) ; §9 topbar Seller (recherche, messages, notifications, switch de mode) ; §10 dashboard Seller avec cartes chiffrées (le mien est 2 liens statiques) ; §14 Finances Seller (revenus/transactions/retraits/graphiques) ; §15/§26 messagerie temps réel Customer↔Seller (WebSocket, aucune table conversations/messages) ; §16/§31 Premium Seller et Customer ; §17 Paramètres Seller dédiés ; §19 recherche avancée (voix/image/filtres multiples — j'ai juste nom+catégorie) ; §23 paiement réel (aucune méthode intégrée, le checkout crée juste une commande `pending`) ; §25 favoris ; §28 notifications Customer ; reste du §32 Admin (KYC, modération, litiges, publicités, promotions/coupons, paramètres plateforme) ; §33 tables manquantes (`user_roles`, `product_images`, `product_variants`, `inventory` distinct, `transactions`, `withdrawals`, `conversations`, `messages`, `notifications`, `favorites`, `reviews`, `premium_*`, `advertisements`, `promotions`, `coupons`, `disputes`, `reports`, `analytics`, `audit_logs`) ; §34 API REST (rien n'existe — tout est en pages Blade server-rendered ; nécessaire pour les deux futures apps mobiles) ; §37 temps réel (aucun broadcasting configuré) ; §6 séparation PWA/mobile (AfricaMall vs AfricaMall Business).

**Écarts de detail à trancher avec le client plus tard** : le cahier des charges donne une palette (`#faf7f2` fond, `#b68b5c`/`#d9b382`/`#5e3e2b` secondaires) et une police (Segoe UI/system-ui) légèrement différentes de ce qui a été implémenté en phase 3 (fond `#F2E8DC`, police Inter) — la couleur de sidebar `#3E2C1F` correspond exactement, le reste est proche mais pas identique ; catégories seedées (Mode/Électronique/Beauté/Mobilier/Artisanat/Épicerie/Santé/Autre) vs celles du cahier des charges (Electronique/Mode/Beauté/Accessoires) — à harmoniser si besoin.

## 2. Architecture actuelle

- **Framework** : Laravel 13, Breeze (Blade, session-based), Tailwind v3 (config-based), Alpine.js, Vite.
- **Modèle de rôle** (le cœur de la demande) :
  - `users` (name, email, phone, password, `is_admin`)
  - `seller_profiles` (1–1 avec `users`) : infos boutique + paiement, `status` enum(`pending`,`active`,`suspended`,`rejected`). **Pas de ligne = pur Customer.**
  - `shops` (1–1 avec `seller_profiles`, créée auto à l'activation)
  - `categories`, `products` (FK shop/catégorie), `orders`, `order_items`, `payments` (table présente, pas encore utilisée — pas de paiement réel)
- **Routes / espaces** :
  - Customer : `routes/web.php` (défaut, marché public + panier/commandes sous `auth`)
  - Seller : `routes/seller.php`, préfixe `/seller`, middleware `seller.active` (`App\Http\Middleware\EnsureSellerActive`)
  - Admin : `routes/admin.php`, préfixe `/admin`, middleware `admin` (`App\Http\Middleware\EnsureIsAdmin`), layout indépendant (`x-admin-layout`, sombre/neutre par choix délibéré)
- **Piège récurrent à ne jamais réintroduire** : les modèles `Product`, `Order`, `OrderItem`, `SellerProfile`, `Shop` ont des attributs `#[Fillable(...)]` qui **excluent volontairement** les clés étrangères. `Model::create([...])`/`->update([...])` avec ces clés les **droppe silencieusement** (pas d'erreur PHP). Partout où une FK doit être fixée par le contrôleur : `new Model($fillableData); $model->foreign_id = $value; $model->save();` — jamais via le tableau passé à `create()`/`update()`. (Rencontré et corrigé 3 fois en phase 1-2 avant d'être bien intégré.)
- **Ancien prototype** : préservé intact dans `legacy-prototype/` (design de référence : palette choco/cream/or, sidebar Seller, carrousel, chat WhatsApp-style jamais reconstruit).

## 3. État des lieux — livré, phase par phase

| Phase | Commit | Contenu |
|---|---|---|
| 1. Fondations | `45a8404` | Laravel + schéma complet + auth réelle (Breeze) + parcours Customer→souscription Seller→validation Admin→Seller actif + garde-fous de rôle |
| 2. Boucle commerciale | `5e9ae7d` | Seller : CRUD produits scopé à sa boutique + commandes reçues (statut). Customer : marché public (recherche/filtre), fiche produit, panier (session), checkout (Order+OrderItems par boutique, stock décrémenté), historique commandes. Admin : listes utilisateurs/boutiques |
| 3. Passe visuelle | `54f2a47` | Palette choco/cream/or + police Inter (via les composants Blade partagés → propagation automatique), logo AfricaMall réel. Customer : carrousel héro (vrais produits, pas de contenu factice) + nav basse mobile. Seller : nouveau `x-seller-layout` avec sidebar repliable brun/or (seulement Dashboard/Produits/Commandes — pas de liens morts). Admin inchangé (choix délibéré) |
| 4. Déploiement Railway | `c483af6`…`1a25b99` | Voir §5 |

**Hors périmètre explicite (pas encore traité)** : pages Customer restantes (favoris, paiements, notifications, support, litiges, sécurité — dead links dans l'ancien `home.php`), statistiques/promotions Seller, reste de l'Admin (litiges, commissions, gestion catégories/produits, modération boutiques/comptes), chat vendeur, paiement réel, séparation PWA AfricaMall/AfricaMall Business, SMS/OTP réel.

Plan détaillé de chaque phase (fichiers exacts touchés, raisonnement) : `C:\Users\Gabe McLlay\.claude\plans\mutable-bubbling-seal.md` (peut avoir été nettoyé entre deux sessions — ce mémo est la source de vérité durable).

## 4. Accès / identifiants (démo, pas de vrais secrets)

**Local** (XAMPP MySQL, Herd PHP 8.4/8.5, `php artisan serve` sur `http://127.0.0.1:8000`) :
- DB `africamall`, `DB_USERNAME=root`, pas de mot de passe (XAMPP par défaut)
- Admin : `admin@africamall.test` / `password`
- Seller actif (Aminata, boutique "Boutique Aminata Wax") : `aminata@example.com` / `password123`
- Customer simple : `test@example.com` / `password`

**Production (Railway)** : seul le compte Admin a été seedé (`admin@africamall.test` / `password`) — pas de vendeur/produits de démo, la base démarre propre comme un vrai déploiement. Créer un compte normalement pour tester le parcours Customer→Seller.

## 5. Déploiement Railway

- **URL production** : https://africamall-web-production.up.railway.app
- Projet Railway `africamall-web`, workspace `marllay1's Projects`. Services : `africamall-web` (web, lié au repo GitHub `Marllay1/Africa-Mall` branche `main`, auto-deploy à chaque push) + `Postgres` (DB — **pas MySQL**, voir raison ci-dessous).
- CLI Railway installé globalement (`npm i -g @railway/cli`), authentifié (`ambambandi@gmail.com`). Clé SSH locale enregistrée sur Railway (`~/.ssh/id_ed25519`) pour `railway ssh`/`railway connect --tunnel-only`.
- `railway.json` (Config as Code — dépréciée par Railway au profit de `.railway/railway.ts`, mais reste supportée jusqu'au 2026-12-01) : `startCommand` = `php artisan migrate --force && php artisan storage:link || true && php artisan serve --host 0.0.0.0 --port $PORT`.
- Variables clés posées sur le service `africamall-web` : `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, `DB_CONNECTION=pgsql`, `DB_HOST=${{Postgres.PGHOST}}` (et PORT/DATABASE/USERNAME/PASSWORD pareil, référence dynamique — jamais de valeur en dur), `PORT=8080` (doit matcher le port du domaine généré via `railway domain --port 8080`).
- `bootstrap/app.php` : `$middleware->trustProxies(at: '*')` ajouté — nécessaire derrière le reverse proxy Railway pour une détection HTTPS correcte.

### Trois bugs de build rencontrés et corrigés (dans l'ordre) :
1. **PHP 8.3 vs 8.4** — `composer.json` disait `^8.3`, mais Symfony (via Laravel 13.17) utilise en réalité une syntaxe PHP 8.4 (property hooks) → Parse error à la compilation. Invisible en local (Herd tourne en 8.5). Fix : `composer.json` → `"php": "^8.4"` + fichier `.php-version` (`8.4`) pour que Railpack/Nixpacks sélectionne la bonne version Nix.
2. **Node 18 vs Vite 8** — Railway provisionne Node 18 par défaut ; Vite 8/rolldown ont besoin de `node:util#styleText` (Node ≥20) → SyntaxError au `npm run build`. Fix : `.nvmrc` + `package.json` `"engines": {"node": "22.x"}` (attention : `">=22"` fait dériver vers `nodejs_24`, absent de l'instantané nixpkgs utilisé côté Railway → épingler une valeur exacte, pas une plage).
3. **MySQL `caching_sha2_password` incompatible** — Le MySQL fourni par le plugin Railway (v9.4) n'accepte QUE `caching_sha2_password`/`sha256_password` comme méthodes d'auth (pas de `mysql_native_password` disponible en repli). Le PHP 8.4 provisionné par Railpack est lié à `mariadb-connector-c` 3.3.5, qui ne sait pas parler ce protocole côté client. Aucun contournement propre côté client (changer l'utilisateur ne sert à rien, le plugin serveur alternatif n'existe pas). **Solution retenue : basculer la base de production sur PostgreSQL** (le schéma Eloquent était déjà 100% portable, aucun SQL spécifique MySQL). Le service MySQL Railway a été supprimé après validation.

**Si ce projet est un jour redéployé ailleurs (Docker manuel, autre PaaS, etc.), ces trois contraintes de version (PHP ≥8.4, Node ≥20 exact 22.x recommandé, préférer Postgres à MySQL sur un environnement où le client PHP est lié à MariaDB Connector/C) doivent être vérifiées à nouveau — elles sont spécifiques à l'environnement de build Railway (Railpack/Nixpacks) rencontré cette session, pas des vérités universelles.**

## 6. Prochaines étapes possibles (à prioriser avec l'utilisateur)

Issu de l'analyse d'écart §1bis contre `CAHIER-DES-CHARGES.md`. Rien n'est encore priorisé/validé avec l'utilisateur — à trancher en session, ne pas partir bille en tête sur l'ensemble.

**Web Seller Center (§7-17)** : topbar (recherche, messages, notifications, switch de mode) ; dashboard avec vraies cartes chiffrées (revenus/produits/commandes/clients + variations) ; sections manquantes de la sidebar (Ma Boutique, Revenus, Statistiques, Messages, Premium, Paramètres) ; statuts de commande étendus (Préparation, Litige).

**Web Customer (§18-31)** : favoris ; profil enrichi ; notifications ; achat invité 1-produit sans compte (§29) ; page produit enrichie (galerie, avis, produits similaires, boutons manquants) ; recherche avancée/filtres ; Premium Customer.

**Admin (§32)** : le reste des modules (Sellers KYC/niveau, modération produits/contenus, litiges, paiements/commissions/fraude, publicités, promotions/coupons, paramètres plateforme).

**Fondations transverses nécessaires à plusieurs items ci-dessus** : messagerie temps réel Customer↔Seller (§15/26 — tables `conversations`/`messages` + WebSocket, gros morceau, préalable à la messagerie Seller ET Customer) ; paiement réel (§23 — Mobile Money/Orange Money/Moov Money/Wave/carte) ; tables manquantes du §33 selon les features attaquées.

**Architecture mobile/PWA (§1/§6/§34)** : nécessite une vraie API REST (rien n'existe aujourd'hui, tout est Blade server-rendered) avant de pouvoir envisager AfricaMall (Customer) et AfricaMall Business (Seller) comme apps séparées partageant le même compte.

**Harmonisation mineure possible** : palette/police Seller Center pour coller exactement au cahier des charges (§7) ; jeu de catégories (§20).

**Hors scope dev, à part** : §41 livrable documentaire PDF (architecture, diagrammes, user stories...) — à traiter comme une tâche de documentation séparée si demandé, pas du code.

## 7. Notes sur l'ancien prototype (pour mémoire, ne pas reproduire)

Aucune vraie BDD (une seule connexion PDO isolée dans `produit.php`), OTP codé en dur (`$code_attendu = "123456"`), Customer et Seller étaient deux inscriptions totalement séparées et déconnectées (contraire au mémo client), `conf.php` était un doublon accidentel de `confirm.php`. Tout ceci a été remplacé ; le code original reste consultable dans `legacy-prototype/` comme référence de design uniquement.

---

## Prompt de reprise

```
Je reprends le projet AfricaMall (C:\xampp\htdocs\africamall-web). Lis
MEMOIRE-SESSION.md à la racine du repo (canonique, à jour) et
CAHIER-DES-CHARGES.md (spec client complète, 42 sections, fournie
2026-09-04) — le mémo contient en §1bis l'analyse d'écart entre les deux.
Si besoin, C:\Users\Gabe McLlay\.claude\plans\mutable-bubbling-seal.md
donne le détail des phases déjà planifiées/livrées.

Consigne explicite du client, non négociable : il ne s'agit PAS de
recommencer from scratch. Le projet continue depuis l'existant. Ne jamais
créer deux comptes séparés Customer/Seller, ni une deuxième authentification,
ni reconstruire ce qui fonctionne déjà — voir CAHIER-DES-CHARGES.md §42 pour
la démarche d'analyse attendue avant toute modification.

État : Laravel + Breeze, modèle de compte unique Customer→Seller→Admin
livré et fonctionnel (fondations + boucle commerciale + passe visuelle
choco/cream/or), déployé en production sur Railway et vérifié en direct :
https://africamall-web-production.up.railway.app (auto-deploy à chaque
push sur main). Admin de démo : admin@africamall.test / password (voir
§4 du mémo pour les autres comptes de test en local).

Le cahier des charges va très largement au-delà de ce qui est livré
(Seller Center topbar/finances/messagerie/Premium/paramètres, favoris,
paiement réel, messagerie temps réel, Admin complet, API REST pour les
futures apps mobiles, etc.) — voir §6 du mémo pour la liste priorisée par
zone. Ne pas tout attaquer d'un coup : proposer une portion cohérente à
l'utilisateur et confirmer avant de coder, comme fait dans les phases
précédentes (fondations → boucle commerciale → passe visuelle, chacune
validée séparément).

Avant de commencer : vérifie l'état réel du repo (git log, git status) et
du déploiement (railway status / railway logs --service africamall-web)
plutôt que de faire confiance aveuglément à ce résumé — le mémo peut avoir
dérivé depuis sa dernière mise à jour.

Prochaine tâche : [à préciser avec l'utilisateur — voir §6 du mémo pour
les pistes classées par zone (Seller Center, Customer, Admin, fondations
transverses type messagerie/paiement, architecture mobile/API)]
```
