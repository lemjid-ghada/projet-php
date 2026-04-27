# 📡 Documentation API - Benna Tounsiya

## Configuration

Base URL: `http://localhost/api`

### Headers requis
```
Content-Type: application/json
Authorization: Bearer {token} (optionnel)
```

---

## 🔐 Authentication Endpoints

### Register (Créer un compte)
```http
POST /auth/register
Content-Type: application/json

{
  "first_name": "Ahmed",
  "last_name": "Ben Ali",
  "email": "ahmed@example.com",
  "password": "password123",
  "phone": "+216 50 123 456"
}

Response:
{
  "success": true,
  "message": "Utilisateur créé avec succès",
  "data": {
    "id": 1,
    "email": "ahmed@example.com"
  }
}
```

### Login (Se connecter)
```http
POST /auth/login
Content-Type: application/json

{
  "email": "ahmed@example.com",
  "password": "password123"
}

Response:
{
  "success": true,
  "data": {
    "token": "jwt_token_here",
    "user": {
      "id": 1,
      "first_name": "Ahmed",
      "email": "ahmed@example.com",
      "role": "client"
    }
  }
}
```

---

## 👥 Users Endpoints

### Créer un utilisateur
```http
POST /users
Authorization: Bearer {token}

{
  "first_name": "Mohamed",
  "last_name": "Ali",
  "email": "mohamed@example.com",
  "password": "secure_password",
  "phone": "+216 50 456 789",
  "role": "client"
}
```

### Obtenir tous les utilisateurs
```http
GET /users?limit=20&offset=0
GET /users/clients
GET /users/admins
```

### Obtenir un utilisateur
```http
GET /users/{id}
```

### Mettre à jour un utilisateur
```http
PUT /users/{id}
Authorization: Bearer {token}

{
  "first_name": "New Name",
  "email": "newemail@example.com",
  "phone": "+216 50 999 999"
}
```

### Changer le rôle
```http
POST /users/{id}/role
Authorization: Bearer {token}

{
  "role": "admin"
}
```

### Désactiver/Activer
```http
POST /users/{id}/deactivate
POST /users/{id}/activate
Authorization: Bearer {token}
```

### Supprimer
```http
DELETE /users/{id}
Authorization: Bearer {token}
```

### Rechercher
```http
GET /users/search?q=Ahmed
```

### Statistiques
```http
GET /users/statistics
```

---

## 🛒 Orders Endpoints

### Créer une commande
```http
POST /orders
Content-Type: application/json

{
  "client_id": 1,
  "total_amount": 42.99,
  "delivery_address": "123 Rue de la Paix, Tunis",
  "delivery_date": "2024-04-20",
  "delivery_time": "19:00",
  "status": "pending",
  "payment_method": "cash",
  "notes": "Sans oignons s'il vous plaît",
  "items": [
    {
      "dish_id": 1,
      "quantity": 2,
      "unit_price": 7.99,
      "subtotal": 15.98,
      "special_instructions": "Bien cuit"
    },
    {
      "dish_id": 2,
      "quantity": 1,
      "unit_price": 12.99,
      "subtotal": 12.99
    }
  ]
}

Response:
{
  "success": true,
  "message": "Commande créée avec succès",
  "data": {
    "id": 42
  }
}
```

### Obtenir toutes les commandes
```http
GET /orders
GET /orders?limit=20&offset=0
GET /orders?status=pending
```

### Obtenir une commande
```http
GET /orders/{id}

Response:
{
  "success": true,
  "data": {
    "id": 42,
    "client_id": 1,
    "total_amount": 42.99,
    "status": "pending",
    "items": [
      {
        "id": 1,
        "order_id": 42,
        "dish_id": 1,
        "quantity": 2,
        "unit_price": 7.99,
        "subtotal": 15.98,
        "name": "Brik Tunisien"
      }
    ]
  }
}
```

### Mettre à jour une commande
```http
PUT /orders/{id}
Content-Type: application/json

{
  "status": "ready",
  "delivery_address": "123 Rue de la Paix, Tunis",
  "delivery_date": "2024-04-20"
}
```

### Supprimer une commande
```http
DELETE /orders/{id}
```

---

## 🍽️ Dishes Endpoints

### Obtenir tous les plats
```http
GET /dishes

Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Brik Tunisien",
      "name_ar": "برك تونسي",
      "description": "Pâte croustillante farcie d'œuf, fromage et persil",
      "price": 7.99,
      "currency": "DT",
      "image_url": "https://...",
      "icon": "🥟",
      "is_spicy": false,
      "rating": 4.9
    }
  ]
}
```

### Obtenir un plat
```http
GET /dishes/{id}
```

### Obtenir les catégories
```http
GET /categories

Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Entrées",
      "name_ar": "المقبلات",
      "description": "Appetizers - Petits plats pour commencer",
      "icon": "🥟"
    }
  ]
}
```

---

## 📅 Reservations Endpoints

### Créer une réservation
```http
POST /reservations
Content-Type: application/json

{
  "client_id": 1,
  "reservation_date": "2024-04-25",
  "reservation_time": "19:30",
  "number_of_guests": 4,
  "special_requests": "Table près de la fenêtre"
}

Response:
{
  "success": true,
  "message": "Réservation créée avec succès",
  "data": {
    "id": 10
  }
}
```

### Obtenir toutes les réservations
```http
GET /reservations
```

### Obtenir une réservation
```http
GET /reservations/{id}
```

### Mettre à jour une réservation
```http
PUT /reservations/{id}

{
  "status": "confirmed",
  "reservation_time": "19:45"
}
```

### Annuler une réservation
```http
DELETE /reservations/{id}
```

---

## 💬 Contact Endpoints

### Envoyer un message
```http
POST /contact
Content-Type: application/json

{
  "name": "Ahmed Ben Ali",
  "email": "ahmed@example.com",
  "phone": "+216 50 123 456",
  "subject": "Demande d'informations",
  "message": "Je voudrais connaître vos horaires d'ouverture"
}

Response:
{
  "success": true,
  "message": "Message envoyé avec succès"
}
```

---

## Statuts disponibles

### Orders
- `pending` - En attente
- `preparing` - En préparation
- `ready` - Prêt
- `delivered` - Livré
- `cancelled` - Annulé

### Reservations
- `pending` - En attente
- `confirmed` - Confirmée
- `cancelled` - Annulée
- `completed` - Complétée

### User Roles
- `client` - Client
- `staff` - Personnel
- `manager` - Gestionnaire
- `admin` - Administrateur

---

## Codes d'erreur

| Code | Signification |
|------|---------------|
| 200 | OK - Succès |
| 201 | Created - Créé |
| 400 | Bad Request - Données invalides |
| 401 | Unauthorized - Authentification requise |
| 403 | Forbidden - Accès refusé |
| 404 | Not Found - Ressource non trouvée |
| 500 | Server Error - Erreur serveur |

---

## CORS Configuration

L'API accepte les requêtes de:
- `http://localhost:5173`
- `http://localhost:3000`
- `http://localhost`

---

## Exemples avec curl

### Créer une commande
```bash
curl -X POST http://localhost/api/orders \
  -H "Content-Type: application/json" \
  -d '{
    "client_id": 1,
    "total_amount": 42.99,
    "delivery_address": "123 Rue",
    "delivery_date": "2024-04-20",
    "delivery_time": "19:00",
    "items": [{"dish_id": 1, "quantity": 2, "unit_price": 7.99, "subtotal": 15.98}]
  }'
```

### Récupérer les plats
```bash
curl http://localhost/api/dishes
```

### Connecter
```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123"
  }'
```

---

## Notes d'implémentation

1. **Authentification JWT**: Les endpoints protégés nécessitent un token Bearer
2. **CORS**: Les headers CORS sont automatiquement gérés
3. **Validation**: Tous les inputs sont validés côté serveur
4. **Pagination**: Utilisez `limit` et `offset` pour paginer les résultats
5. **Dates**: Format ISO 8601 (YYYY-MM-DD HH:MM:SS)
