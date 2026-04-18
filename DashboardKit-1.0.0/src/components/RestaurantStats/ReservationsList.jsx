import styled from 'styled-components';

const ListWrapper = styled.div`
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);

  .reservations-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;

    h3 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
    }
  }

  .reservations-table {
    width: 100%;
    border-collapse: collapse;

    thead {
      background-color: #f5f5f5;
    }

    th, td {
      padding: 12px 20px;
      text-align: left;
      border-bottom: 1px solid #eee;
      font-size: 13px;
    }

    th {
      font-weight: 600;
      color: #666;
      text-transform: uppercase;
    }

    tbody tr:hover {
      background-color: #fafafa;
    }
  }

  .status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;

    &.confirmed {
      background-color: #e8f5e9;
      color: #2e7d32;
    }

    &.pending {
      background-color: #fff3e0;
      color: #e65100;
    }

    &.completed {
      background-color: #e3f2fd;
      color: #1565c0;
    }
  }

  .no-data {
    padding: 40px;
    text-align: center;
    color: #999;
  }
`;

export default function ReservationsList({ reservations = [], title = 'Réservations du jour' }) {
  if (!reservations || reservations.length === 0) {
    return (
      <ListWrapper>
        <div className="reservations-header">
          <h3>{title}</h3>
        </div>
        <div className="no-data">Aucune réservation</div>
      </ListWrapper>
    );
  }

  const getStatusClass = (status) => {
    switch (status.toLowerCase()) {
      case 'confirmée':
        return 'confirmed';
      case 'en attente':
        return 'pending';
      case 'complétée':
        return 'completed';
      default:
        return 'confirmed';
    }
  };

  return (
    <ListWrapper>
      <div className="reservations-header">
        <h3>{title}</h3>
        <span style={{ color: '#999', fontSize: '12px' }}>
          {reservations.length} réservation{reservations.length > 1 ? 's' : ''}
        </span>
      </div>
      <table className="reservations-table">
        <thead>
          <tr>
            <th>Heure</th>
            <th>Client</th>
            <th>Couverts</th>
            <th>Table</th>
            <th>Statut</th>
            <th>Contact</th>
          </tr>
        </thead>
        <tbody>
          {reservations.map((res) => (
            <tr key={res.id}>
              <td style={{ fontWeight: 600 }}>{res.time}</td>
              <td>{res.name}</td>
              <td>{res.guests}</td>
              <td>{res.table}</td>
              <td>
                <span className={`status-badge ${getStatusClass(res.status)}`}>
                  {res.status}
                </span>
              </td>
              <td>
                <a href={`tel:${res.phone}`} style={{ color: '#1976d2', textDecoration: 'none' }}>
                  {res.phone}
                </a>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </ListWrapper>
  );
}
