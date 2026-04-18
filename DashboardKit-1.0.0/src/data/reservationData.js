// Données des réservations du restaurant
export const reservationData = [
  {
    id: 1,
    name: 'Pierre Dupont',
    email: 'pierre.dupont@email.com',
    phone: '06 12 34 56 78',
    date: '2026-04-20',
    time: '19:30',
    guests: 4,
    table: 'T5',
    status: 'Confirmée',
    notes: 'Allergie arachides client'
  },
  {
    id: 2,
    name: 'Marie Rousseau',
    email: 'marie.r@email.com',
    phone: '06 23 45 67 89',
    date: '2026-04-20',
    time: '20:00',
    guests: 2,
    table: 'T2',
    status: 'Confirmée',
    notes: 'Occasion spéciale'
  },
  {
    id: 3,
    name: 'Jean Martin',
    email: 'jean.martin@email.com',
    phone: '06 34 56 78 90',
    date: '2026-04-20',
    time: '20:30',
    guests: 6,
    table: 'T7, T8',
    status: 'Confirmée',
    notes: ''
  },
  {
    id: 4,
    name: 'Sophie Bernard',
    email: 'sophie.b@email.com',
    phone: '06 45 67 89 01',
    date: '2026-04-21',
    time: '19:00',
    guests: 3,
    table: 'T3',
    status: 'En attente',
    notes: 'Enfant de 5 ans'
  },
  {
    id: 5,
    name: 'Luc Petit',
    email: 'luc.petit@email.com',
    phone: '06 56 78 90 12',
    date: '2026-04-21',
    time: '19:30',
    guests: 4,
    table: 'T1',
    status: 'Confirmée',
    notes: 'Régime végétalien'
  },
  {
    id: 6,
    name: 'Isabelle Lemoine',
    email: 'isabelle.l@email.com',
    phone: '06 67 89 01 23',
    date: '2026-04-17',
    time: '19:30',
    guests: 2,
    table: 'T4',
    status: 'Complétée',
    notes: ''
  }
];

export const statisticsData = {
  totalReservations: 156,
  todayReservations: 12,
  confirmations: 142,
  pending: 8,
  cancelled: 6,
  totalGuests: 487,
  averageGuests: 3.1
};

export const chartData = {
  reservationsByDay: [
    { day: 'Lun', reservations: 12 },
    { day: 'Mar', reservations: 15 },
    { day: 'Mer', reservations: 10 },
    { day: 'Jeu', reservations: 18 },
    { day: 'Ven', reservations: 28 },
    { day: 'Sam', reservations: 32 },
    { day: 'Dim', reservations: 22 }
  ],
  peakHours: [
    { hour: '18:00', count: 5 },
    { hour: '18:30', count: 8 },
    { hour: '19:00', count: 12 },
    { hour: '19:30', count: 15 },
    { hour: '20:00', count: 14 },
    { hour: '20:30', count: 10 },
    { hour: '21:00', count: 6 }
  ]
};

// Données des catégories de plats
export const dishCategoryData = [
  { category: 'Pâtes', orders: 45, percentage: 22, color: '#FFD700' },
  { category: 'Pizza', orders: 38, percentage: 19, color: '#FF6B6B' },
  { category: 'Salades', orders: 35, percentage: 17, color: '#4ECDC4' },
  { category: 'Viandes', orders: 42, percentage: 20, color: '#8B4513' },
  { category: 'Poissons', orders: 28, percentage: 14, color: '#4169E1' },
  { category: 'Desserts', orders: 18, percentage: 9, color: '#FF69B4' }
];

export const dishCategoryBySales = [
  { category: 'Pâtes', revenue: 1350, color: '#FFD700' },
  { category: 'Pizza', revenue: 1140, color: '#FF6B6B' },
  { category: 'Viandes', revenue: 1680, color: '#8B4513' },
  { category: 'Poissons', revenue: 1120, color: '#4169E1' },
  { category: 'Salades', revenue: 700, color: '#4ECDC4' },
  { category: 'Desserts', revenue: 360, color: '#FF69B4' }
];

export const popularDishes = [
  { name: 'Carbonara', category: 'Pâtes', sales: 28, rating: 4.8 },
  { name: 'Margherita', category: 'Pizza', sales: 25, rating: 4.6 },
  { name: 'Côte de Boeuf', category: 'Viandes', sales: 22, rating: 4.9 },
  { name: 'Saumon Grillé', category: 'Poissons', sales: 18, rating: 4.7 },
  { name: 'Salade César', category: 'Salades', sales: 16, rating: 4.5 },
  { name: 'Tiramisu', category: 'Desserts', sales: 14, rating: 4.9 }
];

// Liste des contacts
export const contactList = [
  { id: 1, name: 'Fournisseur Fruits', type: 'Fournisseur', phone: '04 12 34 56 78', email: 'fruits@supplier.com', city: 'Lyon' },
  { id: 2, name: 'Boulangerie Artisan', type: 'Fournisseur', phone: '04 23 45 67 89', email: 'pain@artisan.fr', city: 'Villeurbanne' },
  { id: 3, name: 'Société Nettoyage', type: 'Service', phone: '04 34 56 78 90', email: 'contact@propre.fr', city: 'Lyon' },
  { id: 4, name: 'Agence Marketing Local', type: 'Agence', phone: '04 45 67 89 01', email: 'contact@marketing.fr', city: 'Lyon' },
  { id: 5, name: 'Maintenance Équipements', type: 'Service', phone: '04 56 78 90 12', email: 'maintenance@pro.fr', city: 'Caluire' }
];

// Liste des administrateurs
export const adminList = [
  { id: 1, name: 'David Martinez', email: 'david.martinez@restaurant.com', phone: '06 12 34 56 78', role: 'Propriétaire', status: 'Actif', joinDate: '2024-01-15' },
  { id: 2, name: 'Sophie Leclerc', email: 'sophie.leclerc@restaurant.com', phone: '06 23 45 67 89', role: 'Gérant', status: 'Actif', joinDate: '2024-02-20' },
  { id: 3, name: 'Antoine Renard', email: 'antoine.renard@restaurant.com', phone: '06 34 56 78 90', role: 'Chef', status: 'Actif', joinDate: '2024-03-10' },
  { id: 4, name: 'Valérie Dumont', email: 'valerie.dumont@restaurant.com', phone: '06 45 67 89 01', role: 'Responsable RH', status: 'Actif', joinDate: '2024-01-20' }
];

// Liste des clients réguliers
export const clientList = [
  { id: 1, name: 'Pierre Dupont', email: 'pierre.dupont@email.com', phone: '06 12 34 56 78', visits: 15, totalSpent: '€450', lastVisit: '2026-04-15', status: 'VIP' },
  { id: 2, name: 'Marie Rousseau', email: 'marie.r@email.com', phone: '06 23 45 67 89', visits: 8, totalSpent: '€240', lastVisit: '2026-04-14', status: 'Régulier' },
  { id: 3, name: 'Jean Martin', email: 'jean.martin@email.com', phone: '06 34 56 78 90', visits: 12, totalSpent: '€380', lastVisit: '2026-04-12', status: 'VIP' },
  { id: 4, name: 'Sophie Bernard', email: 'sophie.b@email.com', phone: '06 45 67 89 01', visits: 3, totalSpent: '€120', lastVisit: '2026-04-10', status: 'Nouveau' },
  { id: 5, name: 'Luc Petit', email: 'luc.petit@email.com', phone: '06 56 78 90 12', visits: 6, totalSpent: '€200', lastVisit: '2026-04-08', status: 'Régulier' }
];

// Liste des commandes
export const orderList = [
  { id: 'CMD001', date: '2026-04-16', table: 'T5', items: 4, status: 'Complétée', total: '€85', customer: 'Pierre Dupont', time: '20:15' },
  { id: 'CMD002', date: '2026-04-16', table: 'T2', items: 2, status: 'En cours', total: '€45', customer: 'Marie Rousseau', time: '20:30' },
  { id: 'CMD003', date: '2026-04-16', table: 'T7', items: 6, status: 'Préparation', total: '€120', customer: 'Jean Martin', time: '20:45' },
  { id: 'CMD004', date: '2026-04-16', table: 'T3', items: 3, status: 'Complétée', total: '€65', customer: 'Sophie Bernard', time: '19:45' },
  { id: 'CMD005', date: '2026-04-16', table: 'T1', items: 4, status: 'En cours', total: '€95', customer: 'Luc Petit', time: '20:00' }
];
