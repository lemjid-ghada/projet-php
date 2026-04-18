import styled from 'styled-components';

const ListWrapper = styled.div`
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);

  .list-header {
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

    .count {
      color: #999;
      font-size: 12px;
    }
  }

  .list-table {
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

    .name-cell {
      font-weight: 600;
      color: #333;
    }

    .email-cell {
      color: #0066cc;
      text-decoration: none;
      font-size: 12px;

      &:hover {
        text-decoration: underline;
      }
    }

    .phone-cell {
      color: #666;
    }

    .status-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;

      &.actif {
        background-color: #e8f5e9;
        color: #2e7d32;
      }

      &.vip {
        background-color: #fff3e0;
        color: #e65100;
      }

      &.regulier {
        background-color: #e3f2fd;
        color: #1565c0;
      }

      &.nouveau {
        background-color: #f3e5f5;
        color: #6a1b9a;
      }

      &.completee {
        background-color: #e8f5e9;
        color: #2e7d32;
      }

      &.en-cours {
        background-color: #fff3e0;
        color: #e65100;
      }

      &.preparation {
        background-color: #fce4ec;
        color: #c2185b;
      }
    }
  }

  .no-data {
    padding: 40px;
    text-align: center;
    color: #999;
  }
`;

export default function DataListTable({ 
  data = [], 
  columns = [], 
  title = 'Liste', 
  renderCell = null 
}) {
  if (!data || data.length === 0) {
    return (
      <ListWrapper>
        <div className="list-header">
          <h3>{title}</h3>
        </div>
        <div className="no-data">Aucune donnée disponible</div>
      </ListWrapper>
    );
  }

  const getStatusClass = (status) => {
    if (!status) return '';
    return status.toLowerCase().replace(/\s+/g, '-').replace('é', 'e');
  };

  return (
    <ListWrapper>
      <div className="list-header">
        <h3>{title}</h3>
        <span className="count">{data.length} enregistrement{data.length > 1 ? 's' : ''}</span>
      </div>
      <table className="list-table">
        <thead>
          <tr>
            {columns.map((col) => (
              <th key={col.key}>{col.label}</th>
            ))}
          </tr>
        </thead>
        <tbody>
          {data.map((item, index) => (
            <tr key={item.id || index}>
              {columns.map((col) => (
                <td key={`${item.id || index}-${col.key}`}>
                  {renderCell
                    ? renderCell(item, col.key)
                    : col.key === 'email' ? (
                      <a href={`mailto:${item[col.key]}`} className="email-cell">
                        {item[col.key]}
                      </a>
                    ) : col.key === 'status' || col.key === 'role' ? (
                      <span className={`status-badge ${getStatusClass(item[col.key])}`}>
                        {item[col.key]}
                      </span>
                    ) : (
                      item[col.key]
                    )}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </ListWrapper>
  );
}
