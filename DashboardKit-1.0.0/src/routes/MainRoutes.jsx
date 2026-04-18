import { lazy } from 'react';

import AdminLayout from 'layouts/AdminLayout';
import GuestLayout from 'layouts/GuestLayout';

const ContactsPage = lazy(() => import('../views/contacts/index'));
const AdminsPage = lazy(() => import('../views/admins/index'));
const ClientsPage = lazy(() => import('../views/clients/index'));
const ReservationsPage = lazy(() => import('../views/reservations/index'));
const OrdersPage = lazy(() => import('../views/orders/index'));

const Login = lazy(() => import('../views/auth/login'));
const Register = lazy(() => import('../views/auth/register'));

const MainRoutes = {
  path: '/',
  children: [
    {
      path: '/',
      element: <AdminLayout />,
      children: [
        {
          path: '/contacts',
          element: <ContactsPage />
        },
        {
          path: '/admins',
          element: <AdminsPage />
        },
        {
          path: '/clients',
          element: <ClientsPage />
        },
        {
          path: '/reservations',
          element: <ReservationsPage />
        },
        {
          path: '/orders',
          element: <OrdersPage />
        },
        {
          path: '*',
          element: <h1>404 - Page non trouvée</h1>
        }
      ]
    },
    {
      path: '/',
      element: <GuestLayout />,
      children: [
        {
          path: '/login',
          element: <Login />
        },
        {
          path: '/register',
          element: <Register />
        }
      ]
    }
  ]
};

export default MainRoutes;
