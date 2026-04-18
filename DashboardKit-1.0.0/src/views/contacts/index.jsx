import { Container, Row, Col, Card } from 'react-bootstrap';
import { contactList } from 'data/reservationData';
import DataListTable from 'components/RestaurantStats/DataListTable';

export default function ContactsPage() {
  return (
    <Container fluid style={{ padding: '30px 20px' }}>
      <Row className="mb-4">
        <Col>
          <h1 style={{ color: '#333', fontWeight: '700' }}>📞 Gestion des Contacts</h1>
          <p style={{ color: '#999' }}>Fournisseurs, services et agences partenaires</p>
        </Col>
      </Row>
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
            title="Liste des Contacts"
          />
        </Col>
      </Row>
    </Container>
  );
}
