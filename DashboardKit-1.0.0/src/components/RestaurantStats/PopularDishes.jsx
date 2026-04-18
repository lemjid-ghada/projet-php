import styled from 'styled-components';

const PopularDishesWrapper = styled.div`
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);

  .header {
    padding: 20px;
    border-bottom: 1px solid #eee;

    h3 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
    }
  }

  .dishes-table {
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

    .dish-name {
      font-weight: 600;
      color: #333;
    }

    .category-badge {
      display: inline-block;
      padding: 4px 8px;
      background-color: #f0f0f0;
      border-radius: 4px;
      font-size: 11px;
      color: #666;
      font-weight: 500;
    }

    .sales-badge {
      display: inline-block;
      padding: 4px 8px;
      background-color: #e8f5e9;
      border-radius: 4px;
      color: #2e7d32;
      font-weight: 600;
      font-size: 12px;
    }

    .rating {
      color: #ffa500;
      font-weight: 600;
      font-size: 13px;

      .star {
        margin-right: 2px;
      }
    }
  }

  .no-data {
    padding: 40px;
    text-align: center;
    color: #999;
  }
`;

export default function PopularDishes({ dishes = [], title = 'Plats Populaires' }) {
  if (!dishes || dishes.length === 0) {
    return (
      <PopularDishesWrapper>
        <div className="header">
          <h3>{title}</h3>
        </div>
        <div className="no-data">Aucun plat disponible</div>
      </PopularDishesWrapper>
    );
  }

  const renderStars = (rating) => {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    const emptyStars = 5 - Math.ceil(rating);

    return (
      <>
        {[...Array(fullStars)].map((_, i) => (
          <span key={`full-${i}`} className="star">
            ⭐
          </span>
        ))}
        {hasHalfStar && <span className="star">✨</span>}
        {[...Array(emptyStars)].map((_, i) => (
          <span key={`empty-${i}`} className="star">
            ☆
          </span>
        ))}
      </>
    );
  };

  return (
    <PopularDishesWrapper>
      <div className="header">
        <h3>{title}</h3>
      </div>
      <table className="dishes-table">
        <thead>
          <tr>
            <th>Plat</th>
            <th>Catégorie</th>
            <th>Ventes</th>
            <th>Note</th>
          </tr>
        </thead>
        <tbody>
          {dishes.map((dish, index) => (
            <tr key={index}>
              <td className="dish-name">{dish.name}</td>
              <td>
                <span className="category-badge">{dish.category}</span>
              </td>
              <td>
                <span className="sales-badge">{dish.sales} ventes</span>
              </td>
              <td>
                <span className="rating">
                  {renderStars(dish.rating)}
                  <span style={{ marginLeft: '8px', color: '#333' }}>{dish.rating}</span>
                </span>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </PopularDishesWrapper>
  );
}
