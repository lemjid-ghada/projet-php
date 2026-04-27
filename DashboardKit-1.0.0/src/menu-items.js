// Menu configuration for Restaurant Management
const menuItems = {
  items: [
    {
      id: 'navigation',
      title: 'MENU PRINCIPAL',
      type: 'group',
      icon: 'icon-navigation',
      children: [
        {
          id: 'dashboard',
          title: 'Tableau de Bord',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'home',
          url: '/'
        }
      ]
    },
    {
      id: 'menu-management',
      title: '🍽️ GESTION DU MENU',
      type: 'group',
      icon: 'icon-restaurant-menu',
      children: [
        {
          id: 'plats',
          title: 'Liste des plats',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'restaurant_menu',
          url: '/plats'
        },
        {
          id: 'categories',
          title: 'Catégories',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'category',
          url: '/categories'
        }
      ]
    },
    {
      id: 'restaurant',
      title: 'GESTION RESTAURANT',
      type: 'group',
      icon: 'icon-restaurant',
      children: [
        {
          id: 'reservations',
          title: 'Réservations',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'calendar_today',
          url: '/reservations'
        },
        {
          id: 'orders',
          title: 'Commandes',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'shopping_cart',
          url: '/orders'
        },
        {
          id: 'clients',
          title: 'Clients',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'people',
          url: '/clients'
        },
        {
          id: 'contacts',
          title: 'Messages',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'mail',
          url: '/contacts'
        },
        {
          id: 'admins',
          title: 'Équipe',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'admin_panel_settings',
          url: '/admins'
        }
      ]
    }
  ]
};

export default menuItems;