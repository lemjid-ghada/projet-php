import { Container, Row, Col } from 'react-bootstrap';
import { clientList } from 'data/reservationData';
import DataListTable from 'components/RestaurantStats/DataListTable';

export default function ClientsPage() {
  return (
    <Container fluid style={{ padding: '30px 20px' }}>
      <Row className="mb-4">
        <Col>
          <h1 style={{ color: '#333', fontWeight: '700' }}>👥 Clients Réguliers</h1>
          <p style={{ color: '#999' }}>Gestion de la clientèle et historique</p>
        </Col>
      </Row>
      <Row>
        <Col lg={12}>
          <DataListTable
            data={clientList}
            columns={[
              { key: 'name', label: 'Nom' },
              { key: 'email', label: 'Email' },
              { key: 'phone', label: 'Téléphone' },
              { key: 'visits', label: 'Visites' },
              { key: 'totalSpent', label: 'Total dépensé' },
              { key: 'lastVisit', label: 'Dernière visite' },
              { key: 'status', label: 'Statut' }
            ]}
            title="Liste des Clients"
          />
        </Col>
      </Row>
    </Container>
  );
}
