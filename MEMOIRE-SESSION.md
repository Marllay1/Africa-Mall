# AfricaMall — Mémoire de session

**v1 — 2026-09-04** — Fondations Laravel + boucle commerciale + passe visuelle + déploiement Railway (production live)

> Ce fichier est le journal canonique du projet, organisé en sections numérotées. À chaque session : ajouter dans la bonne section, bumper la ligne de version ci-dessus (v2, v3…). Contient des identifiants de démo (pas de vrais secrets) — voir §4.

---

## 1. Contexte et objectif

Le projet `africamall-web` était un prototype PHP statique sans framework, sans vraie base de données, sans authentification réelle (voir §7 pour le détail de ce qui a été trouvé). Le mémo client demandait une refonte en trois espaces — **Customer**, **Seller**, **Admin** — bâtis sur **un compte utilisateur unique** (Customer → souscription Seller → validation Admin → Seller actif, sur le modèle WhatsApp / WhatsApp Business), avec un backend prêt pour une future séparation Web / PWA (AfricaMall vs AfricaMall Business).

Décision de stack validée avec l'utilisateur : **Laravel** (plutôt que Symfony — bootstrap plus rapide pour un dev solo).

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

- Pages Customer manquantes (favoris, paiements, notifications, support, litiges, sécurité)
- Statistiques et promotions côté Seller
- Reste de l'Admin (litiges, commissions, gestion catégories/produits, modération)
- Séparation PWA AfricaMall / AfricaMall Business (manifest, service worker, éventuellement builds distincts)
- Paiement réel, chat vendeur, SMS/OTP réel

## 7. Notes sur l'ancien prototype (pour mémoire, ne pas reproduire)

Aucune vraie BDD (une seule connexion PDO isolée dans `produit.php`), OTP codé en dur (`$code_attendu = "123456"`), Customer et Seller étaient deux inscriptions totalement séparées et déconnectées (contraire au mémo client), `conf.php` était un doublon accidentel de `confirm.php`. Tout ceci a été remplacé ; le code original reste consultable dans `legacy-prototype/` comme référence de design uniquement.

---

## Prompt de reprise

```
Je reprends le projet AfricaMall (C:\xampp\htdocs\africamall-web). Lis
MEMOIRE-SESSION.md à la racine du repo (canonique, à jour) et si besoin
C:\Users\Gabe McLlay\.claude\plans\mutable-bubbling-seal.md pour le détail
des phases déjà planifiées/livrées.

État : Laravel + Breeze, modèle de compte unique Customer→Seller→Admin
livré et fonctionnel (fondations + boucle commerciale + passe visuelle
choco/cream/or), déployé en production sur Railway et vérifié en direct :
https://africamall-web-production.up.railway.app (auto-deploy à chaque
push sur main). Admin de démo : admin@africamall.test / password (voir
§4 du mémo pour les autres comptes de test en local).

Avant de commencer : vérifie l'état réel du repo (git log, git status) et
du déploiement (railway status / railway logs --service africamall-web)
plutôt que de faire confiance aveuglément à ce résumé — le mémo peut avoir
dérivé depuis sa dernière mise à jour.

Prochaine tâche : [à préciser — voir §6 du mémo pour les pistes : pages
Customer manquantes, back-office Seller (stats/promos), reste de l'Admin,
séparation PWA, paiement réel, etc.]
```
