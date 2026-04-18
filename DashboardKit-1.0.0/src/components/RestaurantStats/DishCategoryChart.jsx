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
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 350px;
  }

  .legend-container {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
  }

  .legend-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 13px;

    .legend-color {
      width: 12px;
      height: 12px;
      border-radius: 2px;
      margin-right: 8px;
    }

    .legend-label {
      flex: 1;
      color: #666;
    }

    .legend-value {
      font-weight: 600;
      color: #333;
      margin-left: 10px;
    }
  }
`;

export default function DishCategoryChart({ data, type = 'donut', title = 'Catégories de Plats' }) {
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

  const series = data.map((item) => item.orders || item.revenue);
  const categories = data.map((item) => item.category);
  const colors = data.map((item) => item.color);

  const options = {
    chart: {
      type: type,
      toolbar: { show: false }
    },
    labels: categories,
    colors: colors,
    legend: {
      show: false
    },
    plotOptions: {
      pie: {
        donut: {
          size: '70%'
        }
      }
    },
    dataLabels: {
      enabled: true,
      formatter: (val) => {
        return Math.round(val) + '%';
      },
      style: {
        fontSize: '12px',
        fontWeight: 600
      }
    },
    tooltip: {
      theme: 'light'
    }
  };

  const isRevenue = data[0].revenue !== undefined;

  return (
    <ChartWrapper>
      <h3>{title}</h3>
      <div className="chart-container">
        <Chart options={options} series={series} type={type} height={300} width="100%" />
      </div>
      <div className="legend-container">
        {data.map((item, index) => (
          <div key={index} className="legend-item">
            <div style={{ display: 'flex', alignItems: 'center', flex: 1 }}>
              <div className="legend-color" style={{ backgroundColor: item.color }}></div>
              <div className="legend-label">{item.category}</div>
            </div>
            <div className="legend-value">
              {isRevenue ? `€${item.revenue}` : `${item.orders}`}
            </div>
          </div>
        ))}
      </div>
    </ChartWrapper>
  );
}
