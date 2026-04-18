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
          title: 'Contacts Partenaires',
          type: 'item',
          icon: 'material-icons-two-tone',
          iconname: 'contacts',
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
