import styled from 'styled-components';
import Chart from 'react-apexcharts';

const ChartWrapper = styled.div`
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);

  h3 {
    margin: 0 0 20px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
  }

  .chart-container {
    min-height: 300px;
  }
`;

export default function ReservationChart({ data, type = 'column', title = 'Réservations' }) {
  if (!data || data.length === 0) {
    return (
      <ChartWrapper>
        <h3>{title}</h3>
        <div style={{ padding: '40px', textAlign: 'center', color: '#999' }}>
          Aucune donnée disponible
        </div>
      </ChartWrapper>
    );
  }

  const series = [
    {
      name: type === 'column' ? 'Réservations' : 'Clients',
      data: data.map((item) => item.reservations || item.count)
    }
  ];

  const options = {
    chart: {
      type: type,
      toolbar: { show: false }
    },
    colors: ['#1976d2'],
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%'
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    xaxis: {
      categories: data.map((item) => item.day || item.hour),
      labels: {
        style: {
          fontSize: '12px',
          color: '#666'
        }
      }
    },
    yaxis: {
      title: {
        text: undefined
      },
      labels: {
        style: {
          fontSize: '12px',
          color: '#666'
        }
      }
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      theme: 'light'
    }
  };

  return (
    <ChartWrapper>
      <h3>{title}</h3>
      <div className="chart-container">
        <Chart options={options} series={series} type={type} height={300} />
      </div>
    </ChartWrapper>
  );
}
