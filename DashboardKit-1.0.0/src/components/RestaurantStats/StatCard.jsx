import styled from 'styled-components';

const StatCardWrapper = styled.div`
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  text-align: center;

  .stat-icon {
    font-size: 28px;
    margin-bottom: 12px;
    display: inline-block;
  }

  .stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 8px;
  }

  .stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #333;
  }

  .stat-change {
    font-size: 11px;
    color: #4caf50;
    margin-top: 8px;

    &.negative {
      color: #f44336;
    }
  }
`;

export default function StatCard({ icon, label, value, change, changeType = 'positive' }) {
  return (
    <StatCardWrapper>
      <div className="stat-icon">{icon}</div>
      <div className="stat-label">{label}</div>
      <div className="stat-value">{value}</div>
      {change && <div className={`stat-change ${changeType}`}>{change}</div>}
    </StatCardWrapper>
  );
}
