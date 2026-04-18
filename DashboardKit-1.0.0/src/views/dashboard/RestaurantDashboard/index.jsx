import { Container, Row, Col } from 'react-bootstrap';
import styled from 'styled-components';
import { 
  reservationData, 
  statisticsData, 
  chartData,
  dishCategoryData,
  dishCategoryBySales,
  popularDishes,
  contactList,
  adminList,
  clientList,
  orderList
} from 'data/reservationData';
import StatCard from 'components/RestaurantStats/StatCard';
import ReservationsList from 'components/RestaurantStats/ReservationsList';
import ReservationChart from 'components/RestaurantStats/ReservationChart';
import DishCategoryChart from 'components/RestaurantStats/DishCategoryChart';
import PopularDishes from 'components/RestaurantStats/PopularDishes';
import DataListTable from 'components/RestaurantStats/DataListTable';

const DashboardWrapper = styled.div`
  padding: 30px 20px;
  background-color: #f5f5f5;
  min-height: 100vh;

  .dashboard-header {
    margin-bottom: 30px;

    h1 {
      color: #333;
      font-size: 28px;
      font-weight: 700;
      margin: 0;
    }

    .date-info {
      color: #999;
      font-size: 14px;
      margin-top: 4px;
    }
  }

  .stats-row {
    margin-bottom: 30px;
  }

  .charts-row {
    margin-bottom: 30px;
  }

  .reservations-section {
    margin-bottom: 30px;
  }
`;

export default function RestaurantDashboard() {
  // Filtrer les réservations pour aujourd'hui (pour la démo)
  const todayReservations = reservationData.filter((res) => res.date === '2026-04-20');
  
  // Réservations futures
  const upcomingReservations = reservationData.filter((res) => res.date >= '2026-04-20').slice(0, 8);

  const today = new Date().toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  return (
    <DashboardWrapper>
      <Container fluid>
        {/* En-tête */}
        <div className="dashboard-header">
          <h1>🍽️ Tableau de Bord Restaurant</h1>
          <div className="date-info">Aujourd'hui : {today}</div>
        </div>

        {/* Statistiques Clés */}
        <Row className="stats-row">
          <Col lg={3} md={6} sm={12} className="mb-3">
            <StatCard
              icon="📅"
              label="Total Réservations"
              value={statisticsData.totalReservations}
              change="+12% cette semaine"
              changeType="positive"
            />
          </Col>
          <Col lg={3} md={6} sm={12} className="mb-3">
            <StatCard
              icon="✅"
              label="Confirmées"
              value={statisticsData.confirmations}
              change="91% du total"
              changeType="positive"
            />
          </Col>
          <Col lg={3} md={6} sm={12} className="mb-3">
            <StatCard
              icon="⏳"
              label="En Attente"
              value={statisticsData.pending}
              change="5% du total"
              changeType="positive"
            />
          </Col>
          <Col lg={3} md={6} sm={12} className="mb-3">
            <StatCard
              icon="👥"
              label="Couverts Prévus"
              value={statisticsData.totalGuests}
              change={`Moyenne: ${statisticsData.averageGuests}`}
              changeType="positive"
            />
          </Col>
        </Row>

        {/* Graphiques */}
        <Row className="charts-row">
          <Col lg={6} md={12} className="mb-3">
            <ReservationChart
              data={chartData.reservationsByDay}
              type="column"
              title="Réservations par Jour (Semaine)"
            />
          </Col>
          <Col lg={6} md={12} className="mb-3">
            <ReservationChart
              data={chartData.peakHours}
              type="line"
              title="Heures de Pointe - Réservations"
            />
          </Col>
        </Row>

        {/* Graphiques Catégories Plats */}
        <Row className="charts-row">
          <Col lg={6} md={12} className="mb-3">
            <DishCategoryChart
              data={dishCategoryData}
              type="donut"
              title="📊 Commandes par Catégorie"
            />
          </Col>
          <Col lg={6} md={12} className="mb-3">
            <DishCategoryChart
              data={dishCategoryBySales}
              type="donut"
              title="💰 Revenus par Catégorie"
            />
          </Col>
        </Row>

        {/* Ré

        {/* Plats Populaires */}
        <Row className="reservations-section">
          <Col lg={12}>
            <PopularDishes
              dishes={popularDishes}
              title="🍽️ Plats Populaires"
            />
          </Col>
        </Row>

        {/* Section Listes */}
        <div style={{ marginTop: '40px' }}>
          <h2 style={{ marginBottom: '30px', color: '#333', fontWeight: '700' }}>📋 Gestion</h2>
        </div>

        {/* Commandes */}
        <Row className="reservations-section">
          <Col lg={12} className="mb-3">
            <DataListTable
              data={orderList}
              columns={[
                { key: 'id', label: 'N° Commande' },
                { key: 'date', label: 'Date' },
                { key: 'table', label: 'Table' },
                { key: 'customer', label: 'Client' },
                { key: 'items', label: 'Articles' },
                { key: 'total', label: 'Total' },
                { key: 'status', label: 'Statut' }
              ]}
              title="📦 Commandes"
            />
          </Col>
        </Row>

        {/* Clients et Réservations côte à côte */}
        <Row className="reservations-section">
          <Col lg={6} className="mb-3">
            <DataListTable
              data={clientList}
              columns={[
                { key: 'name', label: 'Nom' },
                { key: 'email', label: 'Email' },
                { key: 'phone', label: 'Téléphone' },
                { key: 'visits', label: 'Visites' },
                { key: 'status', label: 'Statut' }
              ]}
              title="👥 Clients Réguliers"
            />
          </Col>
          <Col lg={6} className="mb-3">
            <DataListTable
              data={adminList}
              columns={[
                { key: 'name', label: 'Nom' },
                { key: 'email', label: 'Email' },
                { key: 'role', label: 'Rôle' },
                { key: 'phone', label: 'Téléphone' },
                { key: 'status', label: 'Statut' }
              ]}
              title="👔 Administrateurs"
            />
          </Col>
        </Row>

        {/* Contacts */}
        <Row>
          <Col lg={12}>
            <DataListTable
              data={contactList}
              columns={[
                { key: 'name', label: 'Nom' },
                { key: 'type', label: 'Type' },
                { key: 'email', label: 'Email' },
                { key: 'phone', label: 'Téléphone' },
                { key: 'city', label: 'Ville' }
              ]}
              title="📞 Contacts"
            />
          </Col>
        </Row>servations d'aujourd'hui */
        <Row className="reservations-section">
          <Col lg={12} className="mb-3">
            <ReservationsList
              reservations={todayReservations}
              title={`Réservations Aujourd'hui (${todayReservations.length})`}
            />
          </Col>
        </Row>

        {/* Toutes les réservations */}
        <Row>
          <Col lg={12}>
            <ReservationsList
              reservations={upcomingReservations}
              title="Prochaines Réservations"
            />
          </Col>
        </Row>
      </Container>
    </DashboardWrapper>
  );
}
