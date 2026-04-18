import { Container, Row, Col } from 'react-bootstrap';
import { orderList } from 'data/reservationData';
import DataListTable from 'components/RestaurantStats/DataListTable';

export default function OrdersPage() {
  return (
    <Container fluid style={{ padding: '30px 20px' }}>
      <Row className="mb-4">
        <Col>
          <h1 style={{ color: '#333', fontWeight: '700' }}>📦 Commandes</h1>
          <p style={{ color: '#999' }}>Suivi des commandes en cours et terminées</p>
        </Col>
      </Row>
      <Row>
        <Col lg={12}>
          <DataListTable
            data={orderList}
            columns={[
              { key: 'id', label: 'N° Commande' },
              { key: 'date', label: 'Date' },
              { key: 'time', label: 'Heure' },
              { key: 'table', label: 'Table' },
              { key: 'customer', label: 'Client' },
              { key: 'items', label: 'Articles' },
              { key: 'total', label: 'Total' },
              { key: 'status', label: 'Statut' }
            ]}
            title="Liste des Commandes"
          />
        </Col>
      </Row>
    </Container>
  );
}
