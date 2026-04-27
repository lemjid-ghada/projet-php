# 🚀 GUIDE DE DÉMARRAGE COMPLET - Benna Tounsiya

## 📌 Vue d'ensemble

Bienvenue sur **Benna Tounsiya** - Système Complet de Gestion de Restaurant

```
Restaurant-php/
├── 📱 Backend API (PHP)              → Démarrer/Vérifier
├── 🎨 Dashboard Admin (React)        → http://localhost:5173
├── 🛒 App Client (React)             → http://localhost:3000
└── 💾 Base de Données (MySQL)        → localhost:3306
```

---

## ⚡ DÉMARRAGE EN 5 MINUTES

### Étape 1: Base de Données MySQL

**Windows (XAMPP):**
1. Lancer XAMPP > Démarrer Apache + MySQL
2. Ouvrir http://localhost/phpmyadmin
3. Accéder à `http://localhost/api/setup.php`

**macOS/Linux:**
```bash
# Vérifier MySQL
mysql --version

# Exécuter le script d'installation
mysql -u root -p < /chemin/vers/benna_tounsiya.sql

# Ou via l'interface web
http://localhost/api/setup.php
```

### Étape 2: Vérifier l'API

```bash
# Test rapide
curl http://localhost/api/health

# Résultat attendu:
{
  "success": true,
  "message": "API Health Check",
  "data": {"status": "API is running"}
}
```

### Étape 3: Démarrer Dashboard Admin

```bash
cd DashboardKit-1.0.0
npm install
npm start
# Ouvre: http://localhost:5173
```

### Étape 4: Démarrer App Client (Optionnel)

```bash
cd doss
npm install
npm run dev
# Ouvre: http://localhost:3000
```

---

## 🔑 COMPTES DE TEST

### Admin
- **Email:** admin@bennatounsiya.tn
- **Mot de passe:** admin123456
- **Rôle:** Admin

### Client Exemple
- **Email:** client@example.com
- **Mot de passe:** ExempleClient123
- **Rôle:** Client

> ⚠️ **IMPORTANT:** Changer les mots de passe après première connexion!

---

## 🌐 URLs IMPORTANTES

| URL | Description |
|-----|-------------|
| http://localhost/api/health | Vérifier API |
| http://localhost/api/setup.php | Installation initial |
| http://localhost:5173 | Dashboard Admin |
| http://localhost:3000 | App Client |
| http://localhost/phpmyadmin | Gestion BD |

---

## 📡 PRINCIPALES FONCTIONNALITÉS

### ✅ Authentification
- Inscription / Connexion
- JWT Tokens (7 jours)
- Gestion des rôles (Client, Staff, Manager, Admin)

### ✅ Gestion du Menu
- Catégories de plats
- Plats avec descriptions multi-langue
- Photos et icônes
- Notation et disponibilité

### ✅ Commandes
- Panier dynâmique
- Suivi en temps réel
- Paiement (Cash, Card, Online)
- Livraison ou à emporter

### ✅ Réservations
- Choix de date/heure
- Vérification de disponibilité
- Demandes spéciales
- Statuts (Confirmed, Pending, Completed, Cancelled)

### ✅ Contacts
- Formulaire de contact
- Archive des messages
- Réponses automatisées
- Notifications

### ✅ Dashboard Admin
- Statistiques temps réel
- Gestion utilisateurs
- Gestion commandes
- Gestion réservations

---

## 🛠️ ARCHITECTURE TECHNIQUE

### Backend (PHP)
```
API REST                          Routeur (index.php)
├── Controllers (Logique métier)
├── Models (Accès BD)
├── Helpers (Utilitaires)
├── Middleware (Authentification)
└── Config (Base de données)
```

### Frontend (React)
```
DashboardKit               doss/my-restaurant-app
├── Admin                  ├── Client
├── Utilisateurs           ├── Panier
├── Menu                   ├── Commandes
├── Commandes              ├── Réservations
├── Réservations           └── Tableau de bord
└── Rapports
```

### Base de Données (MySQL)
```
utilisateurs
├── dish_categories
├── dishes
├── orders
├── order_items
├── reservations
└── contacts
```

---

## 🔌 ENDPOINTS API CLÉS

### Authentification
```bash
POST   /api/auth/register          # Créer compte
POST   /api/auth/login             # Connexion
GET    /api/auth/verify            # Vérifier token
POST   /api/auth/refresh           # Renouveler token
POST   /api/auth/change-password   # Changer mot de passe
```

### Menu
```bash
GET    /api/dishes                 # Tous les plats
GET    /api/dishes/{id}            # Détails plat
GET    /api/categories             # Catégories
POST   /api/dishes                 # Ajouter plat (Admin)
```

### Commandes
```bash
POST   /api/orders                 # Créer commande
GET    /api/orders                 # Mes commandes
GET    /api/orders/{id}            # Détails
PUT    /api/orders/{id}            # Modifier statut
```

### Réservations
```bash
POST   /api/reservations           # Créer réservation
GET    /api/reservations           # Mes réservations
PUT    /api/reservations/{id}      # Modifier
```

---

## ⚙️ CONFIGURATION AVANCÉE

### Variables d'Environnement (api/config/constants.php)
```php
// URLs
FRONTEND_URL = 'http://localhost:5173'    # Votre frontend
BASE_URL = 'http://localhost/api'         # URL API

// BD
DB_HOST = 'localhost'                     # MySQL Host
DB_USER = 'root'                          # MySQL User
DB_PASS = ''                              # MySQL Password

// JWT
JWT_SECRET = 'benna_tounsiya_secret...'   # À CHANGER! 🔒
JWT_EXPIRATION = 604800                   # 7 jours

// Email (optionnel)
SMTP_HOST = 'smtp.gmail.com'
SMTP_PORT = 587
SMTP_USER = 'votre-email@gmail.com'
SMTP_PASS = 'app-password'
```

---

## 🐛 TROUBLESHOOTING

### ❌ "Erreur de connexion à la base de données"
```bash
# Solution 1: Vérifier MySQL est démarré
# XAMPP: Cliquer "Start" pour MySQL

# Solution 2: Vérifier config
vi api/config/database.php

# Solution 3: Créer la base
mysql -u root -p < benna_tounsiya.sql
```

### ❌ "API retourne 404"
```bash
# Solution 1: Vérifier .htaccess existe
ls -la api/.htaccess

# Solution 2: Activer mod_rewrite
# Dans XAMPP: apache/conf/httpd.conf
# Décommenter: LoadModule rewrite_module

# Solution 3: Tester health
curl http://localhost/api/health
```

### ❌ "Erreur CORS"
```bash
# Solution: Vérifier FRONTEND_URL dans constants.php
# Elle doit correspond à votre frontend (ex: http://localhost:5173)

# Ou ajouter le domaine:
define('ALLOWED_ORIGINS', ['http://localhost:5173', 'votre-domaine.com']);
```

### ❌ "Token invalide"
```bash
# Solution 1: Vérifier JWT_SECRET est identique
# Backend: api/config/constants.php
# Frontend: .env ou config/config.js

# Solution 2: Token expiré? Utiliser refresh
POST /api/auth/refresh

# Solution 3: Vérifier format header
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 📊 TESTER L'API AVEC POSTMAN

### 1. Importer Collection
- Nouvelle collection: "Benna Tounsiya"

### 2. Variables Globales
```
{{base_url}} = http://localhost/api
{{token}} = [Généré après login]
```

### 3. Requests Exemple

```
POST {{base_url}}/auth/register
Content-Type: application/json

{
  "first_name": "Jean",
  "last_name": "Dupont",
  "email": "jean@example.com",
  "password": "SecurePass123",
  "phone": "+216 20 123 456"
}
```

```
POST {{base_url}}/auth/login
Content-Type: application/json

{
  "email": "jean@example.com",
  "password": "SecurePass123"
}
```

---

## 🚀 DÉPLOIEMENT EN PRODUCTION

### Checklist
- [ ] JWT_SECRET changé (valeur forte)
- [ ] FRONTEND_URL mis à jour (domaine réel)
- [ ] DEBUG = false dans constants.php
- [ ] HTTPS activé (certificat SSL)
- [ ] Base de données backupée
- [ ] Logs configurés et rotés
- [ ] CORS limité aux domaines approuvés
- [ ] Rate limiting implémenté
- [ ] Cache optimisé (Redis)

### Déployer sur VPS
```bash
# 1. Cloner le repo
git clone <votre-repo> /var/www/html/restaurant

# 2. Configuration
cd /var/www/html/restaurant
cp api/config/constants.example.php api/config/constants.php
# Éditer avec les vraies valeurs

# 3. Permissions
chmod 755 api/
chmod 644 api/*.php

# 4. Database
mysql -u root -p < benna_tounsiya.sql

# 5. Frontend build
cd DashboardKit-1.0.0
npm run build
cp -r dist /var/www/html/restaurant/public/dashboard

# 6. Apache config
# Pointer DocumentRoot vers /var/www/html/restaurant
```

---

## 📚 DOCUMENTATION SUPPLÉMENTAIRE

- 📖 [SETUP.md](SETUP.md) - Configuration détaillée
- 📖 [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Endpoints complets
- 📖 [CORRECTIONS.md](CORRECTIONS.md) - Modifications apportées
- 📖 [IMPLEMENTATION_COMPLETE.md](IMPLEMENTATION_COMPLETE.md) - Architecture générale

---

## 💡 CONSEILS

1. **Développement:** Garder DEBUG = true pour voir les erreurs
2. **Base de données:** Faire des backup réguliers
3. **Sécurité:** Changer tous les tokens/secrets par défaut
4. **Performance:** Utiliser un cache (Redis) en production
5. **Code:** Utiliser Git pour versionner

---

## 📞 SUPPORT

Pour des questions ou problèmes:
1. Consulter les fichiers README et documentation
2. Vérifier les logs Apache et MySQL
3. Tester avec Postman ou cURL
4. Checker les CORS et authentification

---

**Bonne chance! Happy coding! 🚀**

---

*Dernière mise à jour: 2024-04-22*
*Version: 2.0 (Avec Dish Model & Controller)*
