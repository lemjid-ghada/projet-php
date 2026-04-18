import { useState } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import styled from 'styled-components';
import { Card, Row, Col, Button, Form, InputGroup, Container } from 'react-bootstrap';
import FeatherIcon from 'feather-icons-react';

const RegisterWrapper = styled.div`
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;

  .register-card {
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    border: none;
    border-radius: 12px;

    .card-body {
      padding: 50px 40px;
    }

    .logo-section {
      text-align: center;
      margin-bottom: 40px;

      .logo-icon {
        font-size: 48px;
        margin-bottom: 15px;
      }

      h2 {
        color: #333;
        font-weight: 700;
        margin-bottom: 5px;
      }

      p {
        color: #999;
        font-size: 14px;
      }
    }

    .form-group {
      margin-bottom: 20px;
    }

    .input-group {
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      overflow: hidden;
      background: #f9f9f9;

      .input-group-text {
        background: transparent;
        border: none;
        color: #667eea;
      }

      .form-control {
        border: none;
        background: transparent;
        padding: 12px;
        font-size: 14px;

        &:focus {
          box-shadow: none;
        }
      }
    }

    .btn-register {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 12px;
      font-weight: 600;
      border-radius: 8px;
      margin-top: 20px;
      width: 100%;

      &:hover {
        opacity: 0.9;
        color: white;
      }
    }

    .footer-links {
      text-align: center;
      margin-top: 30px;

      p {
        margin: 0;
        font-size: 13px;
        color: #666;

        a {
          color: #667eea;
          text-decoration: none;
          font-weight: 600;

          &:hover {
            text-decoration: underline;
          }
        }
      }
    }
  }
`;

export default function RestaurantRegister() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    restaurant: '',
    agree: false
  });

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleRegister = (e) => {
    e.preventDefault();
    if (formData.password !== formData.confirmPassword) {
      alert('Les mots de passe ne correspondent pas');
      return;
    }
    if (formData.password && formData.email && formData.name) {
      localStorage.setItem('isLoggedIn', 'true');
      localStorage.setItem('userEmail', formData.email);
      localStorage.setItem('restaurantName', formData.restaurant);
      navigate('/');
    }
  };

  return (
    <RegisterWrapper>
      <Container style={{ maxWidth: '450px' }}>
        <Row>
          <Col xs={12}>
            <Card className="register-card">
              <Card.Body className="card-body">
                <div className="logo-section">
                  <div className="logo-icon">🍽️</div>
                  <h2>Restaurant Manager</h2>
                  <p>Créez votre compte</p>
                </div>

                <Form onSubmit={handleRegister}>
                  <Form.Group className="form-group">
                    <InputGroup>
                      <InputGroup.Text>
                        <FeatherIcon icon="user" width={18} height={18} />
                      </InputGroup.Text>
                      <Form.Control
                        type="text"
                        placeholder="Nom complet"
                        name="name"
                        value={formData.name}
                        onChange={handleChange}
                        required
                      />
                    </InputGroup>
                  </Form.Group>

                  <Form.Group className="form-group">
                    <InputGroup>
                      <InputGroup.Text>
                        <FeatherIcon icon="mail" width={18} height={18} />
                      </InputGroup.Text>
                      <Form.Control
                        type="email"
                        placeholder="Adresse email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                        required
                      />
                    </InputGroup>
                  </Form.Group>

                  <Form.Group className="form-group">
                    <InputGroup>
                      <InputGroup.Text>
                        <FeatherIcon icon="home" width={18} height={18} />
                      </InputGroup.Text>
                      <Form.Control
                        type="text"
                        placeholder="Nom du restaurant"
                        name="restaurant"
                        value={formData.restaurant}
                        onChange={handleChange}
                        required
                      />
                    </InputGroup>
                  </Form.Group>

                  <Form.Group className="form-group">
                    <InputGroup>
                      <InputGroup.Text>
                        <FeatherIcon icon="lock" width={18} height={18} />
                      </InputGroup.Text>
                      <Form.Control
                        type="password"
                        placeholder="Mot de passe"
                        name="password"
                        value={formData.password}
                        onChange={handleChange}
                        required
                      />
                    </InputGroup>
                  </Form.Group>

                  <Form.Group className="form-group">
                    <InputGroup>
                      <InputGroup.Text>
                        <FeatherIcon icon="lock" width={18} height={18} />
                      </InputGroup.Text>
                      <Form.Control
                        type="password"
                        placeholder="Confirmer le mot de passe"
                        name="confirmPassword"
                        value={formData.confirmPassword}
                        onChange={handleChange}
                        required
                      />
                    </InputGroup>
                  </Form.Group>

                  <Form.Group>
                    <Form.Check
                      type="checkbox"
                      name="agree"
                      label="J'accepte les conditions d'utilisation"
                      checked={formData.agree}
                      onChange={handleChange}
                      required
                    />
                  </Form.Group>

                  <Button type="submit" className="btn-register">
                    Créer un compte
                  </Button>
                </Form>

                <div className="footer-links">
                  <p>
                    Vous avez déjà un compte?{' '}
                    <NavLink to="/login">Se connecter</NavLink>
                  </p>
                </div>
              </Card.Body>
            </Card>
          </Col>
        </Row>
      </Container>
    </RegisterWrapper>
  );
}
