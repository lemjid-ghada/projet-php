# 🔗 Guide d'Intégration Front-End et Back-End

## 📋 Vue d'ensemble

Ce guide explique comment les différentes parties du projet sont connectées:

```
┌─────────────────────────────────────────────────────────────────┐
│                   Frontend (React + TypeScript)                  │
│                     http://localhost:5173                        │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Pages & Components                                      │  │
│  │  - OrderPage.tsx (Commandes)                            │  │
│  │  - ReservationPage.tsx (Réservations)                   │  │
│  │  - HomePage.tsx (Accueil)                              │  │
│  └──────────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Services API (ApiService.ts)                           │  │
│  │  - Crée et gère toutes les requêtes                    │  │
│  │  - Gère l'authentification (tokens)                    │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                              ↓ HTTP
                   API Gateway (CORS enabled)
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    Backend (PHP + MySQL)                         │
│                      http://localhost/api                        │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Router (api/index.php)                                 │  │
│  │  - Répartit les requêtes aux contrôleurs              │  │
│  │  - Gère CORS et authentification                      │  │
│  └──────────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Controllers                                             │  │
│  │  - UserController.php                                  │  │
│  │  - OrderController.php                                 │  │
│  │  - ReservationController.php                           │  │
│  │  - DishController.php                                  │  │
│  └──────────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Models                                                  │  │
│  │  - User.php                                            │  │
│  │  - Order.php                                           │  │
│  │  - Reservation.php                                     │  │
│  └──────────────────────────────────────────────────────────┘  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Database (MySQL)                                       │  │
│  │  - benna_tounsiya                                       │  │
│  │  Table: utilisateurs (avec champ role)                │  │
│  │  Tables: orders, reservations, dishes, etc.           │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🚀 Flux de données - Créer une commande

### 1. Frontend - OrderPage.tsx
```typescript
// L'utilisateur remplit le formulaire et clique sur "Confirmer"
const handleSubmit = async (e: React.FormEvent) => {
  // 1. Récupérer les données
  const orderData = {
    client_id: 1,
    total_amount: 42.99,
    delivery_address: "123 Rue",
    delivery_date: "2024-04-20",
    delivery_time: "19:00",
    items: [
      { dish_id: 1, quantity: 2, unit_price: 7.99, subtotal: 15.98 }
    ]
  };

  // 2. Appeler le service API
  const response = await ApiService.createOrder(orderData);
  
  // 3. Traiter la réponse
  if (response.success) {
    setSubmitted(true);
  } else {
    setError(response.message);
  }
};
```

### 2. Service API - ApiService.ts
```typescript
// Le service envoie la requête HTTP
static async createOrder(orderData: any): Promise<ApiResponse<any>> {
  return this.request("/orders", {
    method: "POST",
    body: JSON.stringify(orderData),
  });
}

// Requête HTTP générée
POST http://localhost/api/orders
Content-Type: application/json
{...orderData...}
```

### 3. Backend - API Router (api/index.php)
```php
// Le router reçoit et analyse la requête
$path = "/orders";
$method = "POST";

// Le router dirige vers le bon contrôleur
case 'orders':
  require_once CONTROLLERS_PATH . '/OrderController.php';
  $orderController = new OrderController();
  
  if ($method === 'POST') {
    $orderController->create();
  }
```

### 4. Controller - OrderController.php
```php
// Le contrôleur traite les données
public function create() {
  // 1. Récupérer les données JSON
  $data = json_decode(file_get_contents('php://input'), true);
  
  // 2. Valider les données
  if (!isset($data['client_id']) || !isset($data['items'])) {
    return Response::error('Données incomplètes', 400);
  }
  
  // 3. Créer la commande via le modèle
  $orderId = $this->orderModel->create([
    'client_id' => $data['client_id'],
    'total_amount' => $data['total_amount'],
    // ...
  ]);
  
  // 4. Ajouter les items
  foreach ($data['items'] as $item) {
    $this->orderModel->addItem($orderId, $item);
  }
  
  // 5. Retourner la réponse
  return Response::success(['id' => $orderId], 201);
}
```

### 5. Model - Order.php
```php
// Le modèle gère la base de données
public function create($data) {
  $query = "INSERT INTO orders (...) VALUES (...)";
  $stmt = $this->db->prepare($query);
  // Exécuter et retourner l'ID
  return $this->db->insert_id;
}

public function addItem($orderId, $data) {
  $query = "INSERT INTO order_items (order_id, ...) VALUES (?, ...)";
  // Insérer l'item
}
```

### 6. Database - MySQL
```sql
-- Les données sont stockées
INSERT INTO orders 
(client_id, total_amount, delivery_date, delivery_time, status) 
VALUES (1, 42.99, '2024-04-20', '19:00', 'pending');

INSERT INTO order_items 
(order_id, dish_id, quantity, unit_price, subtotal) 
VALUES (42, 1, 2, 7.99, 15.98);
```

### 7. Response - Retour au Frontend
```php
// Le backend envoie la réponse JSON
{
  "success": true,
  "message": "Commande créée avec succès",
  "data": {
    "id": 42
  }
}

// HTTP: 201 Created
```

### 8. Frontend - Affichage du résultat
```typescript
// Le frontend affiche le succès
if (response.success) {
  setSubmitted(true);  // Affiche le message "Commande Confirmée"
  // Réinitialise le formulaire après 3 secondes
}
```

---

## 📦 Structure des fichiers

### Frontend (React)
```
doss/src/app/
├── pages/
│   ├── OrderPage.tsx          ← Page des commandes
│   ├── ReservationPage.tsx    ← Page des réservations
│   ├── HomePage.tsx            ← Page d'accueil
│   └── ...
├── components/
│   ├── Navbar.tsx             ← Navigation
│   ├── Footer.tsx
│   └── ...
├── services/
│   └── ApiService.ts          ← Service API unifié
├── routes/
│   └── routes.tsx             ← Définition des routes
└── ...
```

### Backend (PHP)
```
api/
├── index.php                  ← Point d'entrée API
├── config/
│   ├── constants.php          ← Configuration globale
│   └── database.php           ← Connexion BD
├── controllers/
│   ├── OrderController.php    ← Gère les commandes
│   ├── UserController.php     ← Gère les utilisateurs
│   ├── ReservationController.php
│   └── ...
├── models/
│   ├── Order.php              ← Modèle commandes
│   ├── User.php               ← Modèle utilisateurs
│   ├── Reservation.php
│   └── BaseModel.php
├── middleware/
│   ├── AuthMiddleware.php
│   └── ...
└── helpers/
    ├── Response.php           ← Formatage des réponses
    ├── Validator.php
    └── Auth.php
```

---

## 🔑 Configuration requise

### Frontend (.env)
```env
VITE_API_URL=http://localhost/api
VITE_APP_NAME=Benna Tounsiya
VITE_APP_URL=http://localhost:5173
```

### Backend (api/config/constants.php)
```php
define('FRONTEND_URL', 'http://localhost:5173');
define('BASE_URL', 'http://localhost/api');
define('JWT_SECRET', 'benna_tounsiya_secret_key_2026');
```

---

## 🔐 Authentification

### Flux d'authentification

1. **Inscription (Register)**
   ```
   Frontend: POST /auth/register {email, password, ...}
   → Backend: Crée utilisateur, retourne token JWT
   → Frontend: Stocke token dans localStorage
   ```

2. **Connexion (Login)**
   ```
   Frontend: POST /auth/login {email, password}
   → Backend: Valide credentials, retourne token JWT
   → Frontend: Stocke token dans localStorage
   ```

3. **Requêtes authentifiées**
   ```
   Frontend: 
   - Récupère token de localStorage
   - L'ajoute à chaque requête: Header Authorization: Bearer {token}
   
   → Backend:
   - Valide le token
   - Exécute la requête si valide
   ```

### Code Frontend
```typescript
// ApiService.ts - Ajoute automatiquement le token
private static async request<T>(
  endpoint: string,
  options: RequestInit = {}
): Promise<T> {
  const token = localStorage.getItem("token");
  if (token) {
    headers.Authorization = `Bearer ${token}`;
  }
  // ... faire la requête
}
```

---

## 📡 Endpoints principaux

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/auth/register` | Créer un compte |
| POST | `/auth/login` | Se connecter |
| POST | `/orders` | Créer une commande |
| GET | `/orders/{id}` | Obtenir une commande |
| POST | `/reservations` | Créer une réservation |
| GET | `/dishes` | Obtenir les plats |
| POST | `/users` | Créer un utilisateur |
| GET | `/users/{id}` | Obtenir un utilisateur |

---

## 🧪 Tester l'intégration

### 1. Test avec Postman/Thunder Client

```bash
# Créer une commande
POST http://localhost/api/orders
{
  "client_id": 1,
  "total_amount": 42.99,
  "delivery_address": "123 Rue",
  "delivery_date": "2024-04-20",
  "delivery_time": "19:00",
  "items": [{"dish_id": 1, "quantity": 2, "unit_price": 7.99, "subtotal": 15.98}]
}
```

### 2. Test depuis le navigateur

```javascript
// Ouvrir la console (F12) et lancer :
fetch('http://localhost/api/orders', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    client_id: 1,
    total_amount: 42.99,
    delivery_address: "123 Rue",
    delivery_date: "2024-04-20",
    delivery_time: "19:00",
    items: [{dish_id: 1, quantity: 2, unit_price: 7.99, subtotal: 15.98}]
  })
})
.then(r => r.json())
.then(d => console.log(d))
```

### 3. Vérifier dans la base de données

```sql
-- Connectez-vous à MySQL et vérifiez
SELECT * FROM orders WHERE id = 42;
SELECT * FROM order_items WHERE order_id = 42;
SELECT * FROM utilisateurs WHERE id = 1;
```

---

## ⚠️ Dépannage courant

### Erreur: "CORS error"
```
Solution: Vérifier que FRONTEND_URL est correct dans api/config/constants.php
define('FRONTEND_URL', 'http://localhost:5173');
```

### Erreur: "404 Not Found"
```
Solution: 
1. Vérifier que l'API est accessible: http://localhost/api/health
2. Vérifier l'URL du endpoint
3. Vérifier le routeur dans api/index.php
```

### Erreur: "Database connection failed"
```
Solution:
1. Vérifier que MySQL est démarré
2. Vérifier les credentials dans api/config/database.php
3. Vérifier que la base benna_tounsiya existe
```

### Le formulaire ne s'envoie pas
```
Solution:
1. Ouvrir la console du navigateur (F12)
2. Vérifier les erreurs de validation
3. Vérifier que les champs requis sont remplis
4. Vérifier la réponse du serveur
```

---

## 🚀 Déploiement

### Pour la production :

1. Mettre à jour la configuration
```php
// api/config/constants.php
define('ENVIRONMENT', 'production');
define('DEBUG', false);
define('FRONTEND_URL', 'https://bennatounsiya.tn');
define('BASE_URL', 'https://bennatounsiya.tn/api');
```

2. Mettre à jour le .env frontend
```env
VITE_API_URL=https://bennatounsiya.tn/api
VITE_APP_URL=https://bennatounsiya.tn
```

3. Build frontend
```bash
cd doss
npm run build
```

---

## 📚 Ressources supplémentaires

- API_DOCUMENTATION.md - Documentation détaillée des endpoints
- MIGRATION_GUIDE.md - Guide de migration de la base de données
- Backend: api/README.md (si présent)
- Frontend: doss/README.md (si présent)
