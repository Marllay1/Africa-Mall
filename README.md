# AfricaMall

Marketplace e-commerce multi-rôles (Customer / Seller / Admin) construite avec Laravel.

## Architecture des rôles

Un compte utilisateur unique évolue à travers un cycle de statuts, sans duplication de compte :

```
Customer → souscription Seller → pending → validation Admin → active (+ Shop créé)
```

- **Customer** (`routes/web.php`) : espace par défaut de tout compte, inclut la demande de souscription Seller (`/devenir-vendeur`).
- **Seller** (`routes/seller.php`, préfixe `/seller`) : accessible uniquement si `seller_profiles.status = active` (middleware `EnsureSellerActive`).
- **Admin** (`routes/admin.php`, préfixe `/admin`) : interface indépendante, accessible aux comptes `is_admin` (middleware `EnsureIsAdmin`), avec la file de validation des demandes Seller.

Voir `app/Models/User.php`, `app/Models/SellerProfile.php` pour le modèle de données.

## Démarrage local

```bash
composer install
npm install && npm run build
cp .env.example .env   # puis renseigner DB_* (MySQL local)
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Un compte admin est créé par le seeder `AdminUserSeeder` (`admin@africamall.test` / `password`, à changer avant tout déploiement).

## Historique du projet

L'ancien prototype PHP statique (sans framework, sans base de données réelle) est conservé dans [`legacy-prototype/`](legacy-prototype) à titre de référence de design (palette, structure de menu, parcours Customer → Seller déjà esquissé côté UI).
