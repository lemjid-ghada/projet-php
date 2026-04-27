import { lazy } from 'react';
import { createBrowserRouter } from 'react-router-dom';
import PlatsList from '../views/menu/PlatsList';
import Categories from '../views/menu/Categories';
import ContactsPage from '../views/contacts/ContactsPage';



// project import
import MainRoutes from './MainRoutes';
import AdminLayout from 'layouts/AdminLayout';

// render - landing page
const RestaurantDashboard = lazy(() => import('../views/dashboard/RestaurantDashboard/index'));

// ==============================|| ROUTING RENDER ||============================== //

const router = createBrowserRouter(
  [
    {
      path: '/',
      element: <AdminLayout />,
      children: [
        {
          index: true,
          element: <RestaurantDashboard />
        }
      ]
    },
    {
  path: '/plats',
  element: <PlatsList />
},
{
  path: '/categories',
  element: <Categories />
},
{
  path: '/contacts',
  element: <ContactsPage />
},
    MainRoutes
  ],
  { basename: import.meta.env.VITE_APP_BASE_NAME }
);

export default router;
