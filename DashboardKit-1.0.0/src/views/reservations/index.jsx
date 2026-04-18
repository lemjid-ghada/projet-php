import { Container, Row, Col } from 'react-bootstrap';
import { reservationData } from 'data/reservationData';
import ReservationsList from 'components/RestaurantStats/ReservationsList';

export default function ReservationsPage() {
  return (
    <Container fluid style={{ padding: '30px 20px' }}>
      <Row className="mb-4">
        <Col>
          <h1 style={{ color: '#333', fontWeight: '700' }}>📅 Réservations</h1>
          <p style={{ color: '#999' }}>Gestion complète des réservations</p>
        </Col>
      </Row>
      <Row>
        <Col lg={12}>
          <ReservationsList
            reservations={reservationData}
            title={`Toutes les Réservations (${reservationData.length})`}
          />
        </Col>
      </Row>
    </Container>
  );
}
