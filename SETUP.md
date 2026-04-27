# 🍽️ BENNA TOUNSIYA - Restaurant Management System Setup Guide

## 📋 Vue d'ensemble

Benna Tounsiya est une application complète de gestion de restaurant avec :
- **Backend PHP**: API REST avec authentification JWT
- **Frontend multiple**: DashboardKit (admin) et My-Restaurant-App (client)
- **Base de données**: MySQL/MariaDB

---

## ⚙️ Configuration Requise

### Prérequis
- **PHP** 7.4+ (8.0+ recommandé)
- **MySQL** 5.7+ ou **MariaDB** 10.2+
- **Node.js** 16+ et **npm** 8+
- **Composer** (optionnel pour PHP)

### Logiciels Recommandés
- **XAMPP** ou **WAMP** (avec Apache et MySQL)
- **Visual Studio Code** avec extensions PHP
- **Postman** ou **Insomnia** pour tester l'API

---

## 🚀 DÉMARRAGE RAPIDE

### 1️⃣ Configuration de la Base de Données

#### Option A: Via XAMPP (Windows)
1. Démarrer **XAMPP** (Apache + MySQL)
2. Accéder à `http://localhost/phpmyadmin`
3. Créer une nouvelle base de données : `benna_tounsiya`
4. Importer le fichier SQL:
```sql
-- Importer le fichier benna_tounsiya.sql à partir de phpMyAdmin
```

#### Option B: Via Terminal/Command Line
```bash
# Se connecter à MySQL
mysql -u root -p

# Créer la base de données
CREATE DATABASE benna_tounsiya;
USE benna_tounsiya;

# Importer le fichier SQL
source /chemin/vers/benna_tounsiya.sql;
```

### 2️⃣ Configuration du Backend PHP

#### Structure des Fichiers
```
api/
├── config/
│   ├── constants.php      # Constantes globales
│   └── database.php       # Configuration MySQL
├── models/
│   ├── BaseModel.php      # Classe de base
│   ├── User.php
│   ├── Order.php
│   ├── Reservation.php
│   ├── Contact.php
│   ├── Dish.php           # ✨ NEW
│   └── DishCategory.php   # ✨ NEW
├── controllers/
│   ├── AuthController.php
│   ├── OrderController.php
│   ├── ReservationController.php
│   ├── ContactController.php
│   ├── UserController.php
│   └── DishController.php # ✨ NEW
├── helpers/
│   ├── Auth.php           # JWT & Password
│   ├── Response.php       # Réponses standardisées
│   └── Validator.php      # Validation
├── middleware/
│   └── AuthMiddleware.php
├── index.php              # Routeur principal
└── .htaccess              # Réécriture URL
```

#### Vérifier la Configuration
Vérifier les paramètres dans `api/config/constants.php`:
```php
define('BASE_URL', 'http://localhost/api');        // URL de l'API
define('FRONTEND_URL', 'http://localhost:5173');   // URL du frontend Vite
define('DB_HOST', 'localhost');                    // Host MySQL
define('DB_USER', 'root');                         // Utilisateur MySQL
define('DB_PASS', '');                             // Motde passe MySQL
define('DB_NAME', 'benna_tounsiya');               // Nom BD
define('JWT_SECRET', 'benna_tounsiya_secret...');  // Secret JWT
```

#### Vérifier la Connexion
Accéder à `http://localhost/api/health` pour vérifier que l'API fonctionne:
```json
{
  "success": true,
  "message": "API Health Check",
  "data": {
    "status": "API is running"
  },
  "timestamp": "2024-01-15 10:30:45"
}
```

### 3️⃣ Configuration du Frontend (DashboardKit)

```bash
cd DashboardKit-1.0.0

# Installer les dépendances
npm install

# Démarrer le serveur de développement
npm start # or npm run dev

# Build pour production
npm run build
```

L'application sera disponible à `http://localhost:5173`

### 4️⃣ Configuration du Frontend (My-Restaurant-App)

```bash
cd doss/my-restaurant-app

# Installer les dépendances
npm install

# Démarrer le serveur de développement
npm run dev

# Build pour production
npm run build
```

---

## 📡 Test de l'API

### Endpoints Disponibles

#### 🔐 Authentification
```
POST   /api/auth/register    - Créer un compte
POST   /api/auth/login       - Connexion
POST   /api/auth/logout      - Déconnexion
POST   /api/auth/verify      - Vérifier le token
POST   /api/auth/refresh     - Renouveler le token
POST   /api/auth/change-password - Changer mot de passe
```

#### 📦 Plats & Catégories
```
GET    /api/dishes          - Tous les plats
GET    /api/dishes/{id}     - Plat spécifique
GET    /api/categories      - Toutes les catégories
GET    /api/categories/{id} - Catégorie spécifique
```

#### 🛒 Commandes
```
GET    /api/orders          - Lister les commandes
GET    /api/orders/{id}     - Détails commande
POST   /api/orders          - Créer une commande
PUT    /api/orders/{id}     - Mettre à jour
DELETE /api/orders/{id}     - Supprimer
```

#### 📅 Réservations
```
GET    /api/reservations    - Lister les réservations
GET    /api/reservations/{id} - Détails réservation
POST   /api/reservations    - Créer une réservation
PUT    /api/reservations/{id} - Mettre à jour
DELETE /api/reservations/{id} - Supprimer
```

#### 📧 Contacts
```
POST   /api/contact         - Envoyer un message
GET    /api/contact         - Tous les messages
GET    /api/contact/{id}    - Message spécifique
POST   /api/contact/{id}/reply - Répondre
POST   /api/contact/{id}/read  - Marquer comme lu
```

#### 👥 Utilisateurs
```
GET    /api/users          - Tous les utilisateurs
GET    /api/users/{id}     - Utilisateur spécifique
POST   /api/users          - Créer utilisateur
PUT    /api/users/{id}     - Mettre à jour
DELETE /api/users/{id}     - Supprimer
POST   /api/users/{id}/role - Changer le rôle
```

### Exemple d'Appel avec cURL

#### Connexion
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123"
  }'
```

#### Récupérer les Plats
```bash
curl -X GET http://localhost/api/dishes \
  -H "Content-Type: application/json"
```

#### Créer une Commande (avec token)
```bash
curl -X POST http://localhost/api/orders \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "client_id": 1,
    "total_amount": 50.00,
    "delivery_address": "123 Rue de la Paix",
    "status": "pending"
  }'
```

---

## 🐛 Troubleshooting

### Problème: "Base de données non trouvée"
**Solution**: 
- Vérifier que MySQL est en cours d'exécution
- Vérifier les paramètres dans `api/config/database.php`
- S'assurer que la BD `benna_tounsiya` existe

### Problème: "Erreur CORS"
**Solution**:
- Vérifier que `FRONTEND_URL` est correctement défini dans `constants.php`
- Ajouter votre domaine dans le header CORS de `api/index.php`

### Problème: "Fichier introuvable" (404)
**Solution**:
- Vérifier que le mod_rewrite d'Apache est activé
- Vérifier que le fichier `.htaccess` existe dans `/api/`
- Tester via `http://localhost/api/health`

### Problème: "Token invalide"
**Solution**:
- Vérifier que le `JWT_SECRET` est identique côté backend et frontend
- S'assurer que le token n'a pas expiré
- Vérifier le header `Authorization: Bearer TOKEN`

---

## 📁 Structure Complète du Projet

```
Restaurant-php/
├── api/                          # Backend PHP REST API
│   ├── config/                   # Configurations
│   ├── controllers/              # Logique métier (5 + 1 nouveau)
│   ├── models/                   # Modèles BD (5 + 2 nouveaux)
│   ├── helpers/                  # Utilitaires
│   ├── middleware/               # Middlewares
│   ├── index.php                 # Routeur
│   ├── .htaccess                 # URL Rewriting
│   └── README.md
│
├── DashboardKit-1.0.0/           # Admin Dashboard (React)
│   ├── src/
│   ├── package.json
│   ├── vite.config.mjs
│   └── ...
│
├── doss/                         # Version principale
│   ├── my-restaurant-app/        # App client (React)
│   ├── package.json
│   └── ...
│
├── benna_tounsiya.sql            # Fichier SQL de la BD
├── IMPLEMENTATION_COMPLETE.md    # Documentation complète
├── API_DOCUMENTATION.md          # API Reference
├── MIGRATION_GUIDE.md            # Guide de migration
└── SETUP.md                      # CE FICHIER
```

---

## ✅ Checklist de Démarrage

- [ ] MySQL est en cours d'exécution
- [ ] Base de données `benna_tounsiya` créée et importée
- [ ] Fichiers PHP testés (`/api/health`)
- [ ] `api/config/constants.php` configuré
- [ ] Node.js et npm installés
- [ ] `npm install` exécuté pour les frontends
- [ ] Frontend démarré sur les bons ports
- [ ] CORS configuré correctement
- [ ] Token JWT généré avec succès

---

## 📞 Support

Pour plus d'informations ou aide:
1. Consulter `API_DOCUMENTATION.md`
2. Consulter `IMPLEMENTATION_COMPLETE.md`
3. Vérifier les logs d'erreur
4. Utiliser Postman pour tester l'API

---

## 📝 Notes Importantes

✨ **Nouveautés Ajoutées**:
- ✅ Modèle `Dish.php` - Gestion des plats
- ✅ Modèle `DishCategory.php` - Gestion des catégories
- ✅ Contrôleur `DishController.php` - Endpoints des plats
- ✅ Routes complètes pour `/api/dishes` et `/api/categories`

🔒 **Sécurité**:
- Tous les mots de passe sont hachés en bcrypt
- JWT pour l'authentification sécurisée
- CORS configuré
- SQL Injection protection via Prepared Statements

🚀 **Prêt pour production** après:
- [ ] Modifier `JWT_SECRET` par une clé forte
- [ ] Remplacer les emails de notification
- [ ] Configurer les variables d'environnement
- [ ] Activer le SSL/HTTPS
- [ ] Ajouter un monitoring

---

**Bon développement! 🎉**
