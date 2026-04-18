import { Container, Row, Col } from 'react-bootstrap';
import { adminList } from 'data/reservationData';
import DataListTable from 'components/RestaurantStats/DataListTable';

export default function AdminsPage() {
  return (
    <Container fluid style={{ padding: '30px 20px' }}>
      <Row className="mb-4">
        <Col>
          <h1 style={{ color: '#333', fontWeight: '700' }}>👔 Administrateurs</h1>
          <p style={{ color: '#999' }}>Équipe de gestion du restaurant</p>
        </Col>
      </Row>
      <Row>
        <Col lg={12}>
          <DataListTable
            data={adminList}
            columns={[
              { key: 'name', label: 'Nom' },
              { key: 'email', label: 'Email' },
              { key: 'phone', label: 'Téléphone' },
              { key: 'role', label: 'Rôle' },
              { key: 'status', label: 'Statut' },
              { key: 'joinDate', label: 'Date d\'adhésion' }
            ]}
            title="Liste des Administrateurs"
          />
        </Col>
      </Row>
    </Container>
  );
}
