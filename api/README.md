# 🍽️ Benna Tounsiya - API REST (PHP)

## 📋 Structure du Projet

```
api/
├── config/
│   ├── database.php      # Connexion MySQL
│   └── constants.php     # Constantes globales
├── controllers/          # Contrôleurs (logique)
├── models/              # Modèles (BD)
├── middleware/          # Middleware (authentification)
├── helpers/             # Fonctions utilitaires
│   ├── Response.php     # Réponses API standardisées
│   ├── Validator.php    # Validation des données
│   └── Auth.php         # JWT et hachage
├── index.php            # Point d'entrée API
└── .htaccess           # Configuration Apache
```

## 🚀 Installation & Configuration

### 1. **Prérequis**
- XAMPP (Apache + PHP + MySQL)
- PHP 7.4+ 
- MySQL 5.7+

### 2. **Configuration XAMPP**

**Placer le dossier `api` dans `htdocs`:**
```
C:\xampp\htdocs\api\
```

### 3. **Base de Données**

1. Ouvrez `phpMyAdmin`: http://localhost/phpmyadmin
2. Importez le fichier `benna_tounsiya.sql`
3. La base de données est créée avec les tables et données initiales

### 4. **Configuration PHP**

Modifiez `config/database.php` si nécessaire:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Vide pour XAMPP défaut
define('DB_NAME', 'benna_tounsiya');
```

## 🔌 API Endpoints

### Auth
- `POST /api/auth/register` - Inscription client
- `POST /api/auth/login` - Connexion client
- `POST /api/auth/logout` - Déconnexion

### Dishes (Menu)
- `GET /api/dishes` - Tous les plats
- `GET /api/dishes/{id}` - Détail d'un plat
- `GET /api/categories` - Catégories

### Reservations
- `GET /api/reservations` - Mes réservations
- `GET /api/reservations/{id}` - Détail réservation
- `POST /api/reservations` - Créer réservation
- `PUT /api/reservations/{id}` - Modifier réservation
- `DELETE /api/reservations/{id}` - Annuler réservation

### Orders
- `GET /api/orders` - Mes commandes
- `GET /api/orders/{id}` - Détail commande
- `POST /api/orders` - Créer commande
- `PUT /api/orders/{id}` - Modifier commande
- `DELETE /api/orders/{id}` - Annuler commande

### Contact
- `POST /api/contact` - Envoyer message

### Health
- `GET /api/health` - Vérifier l'API

## 🧪 Test de l'API

**Ouvrez Postman ou cURL:**

```bash
# Vérifier l'API
curl http://localhost/api/health

# Obtenir tous les plats
curl http://localhost/api/dishes

# Inscription
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Ahmed",
    "last_name": "Ben",
    "email": "ahmed@example.com",
    "phone": "+216 71 123 456",
    "password": "password123"
  }'

# Connexion
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "ahmed@example.com",
    "password": "password123"
  }'
```

## 🔐 Sécurité

- Mots de passe: **Bcrypt** (password_hash)
- Tokens: **JWT** (JsonWebToken)
- CORS: Activé pour frontend
- SQL Injection: **Prepared Statements**

## 📝 Notes

- **Frontend URL:** http://localhost:5173
- **API URL:** http://localhost/api
- **Environnement Dev:** Erreurs affichées en détail
- **Environnement Prod:** Erreurs génériques

## 👨‍💻 Prochaines Étapes

1. Créer les **Controllers** (AuthController, DishController, etc.)
2. Créer les **Models** (Client, Dish, Reservation, etc.)
3. Implémenter la **logique métier**
4. Tester l'API avec Postman
5. Intégrer les endpoints au **Frontend React**

---

**Créé pour Benna Tounsiya - Restaurant Tunisien** 🇹🇳
