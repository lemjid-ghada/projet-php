import { useState } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import styled from 'styled-components';
import { Card, Row, Col, Button, Form, InputGroup, Container } from 'react-bootstrap';
import FeatherIcon from 'feather-icons-react';

const LoginWrapper = styled.div`
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;

  .login-card {
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

    .btn-login {
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
      display: flex;
      flex-direction: column;
      gap: 15px;

      p {
        margin: 0;
        font-size: 13px;

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

export default function RestaurantLogin() {
  const navigate = useNavigate();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [rememberMe, setRememberMe] = useState(false);

  const handleLogin = (e) => {
    e.preventDefault();
    if (email && password) {
      localStorage.setItem('isLoggedIn', 'true');
      localStorage.setItem('userEmail', email);
      navigate('/');
    }
  };

  return (
    <LoginWrapper>
      <Container style={{ maxWidth: '450px' }}>
        <Row>
          <Col xs={12}>
            <Card className="login-card">
              <Card.Body className="card-body">
                <div className="logo-section">
                  <div className="logo-icon">🍽️</div>
                  <h2>Restaurant Manager</h2>
                  <p>Gestion complète de votre restaurant</p>
                </div>

                <Form onSubmit={handleLogin}>
                  <Form.Group className="form-group">
                    <InputGroup>
                      <InputGroup.Text>
                        <FeatherIcon icon="mail" width={18} height={18} />
                      </InputGroup.Text>
                      <Form.Control
                        type="email"
                        placeholder="Adresse email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
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
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                      />
                    </InputGroup>
                  </Form.Group>

                  <Form.Group>
                    <Form.Check
                      type="checkbox"
                      label="Mémoriser mes identifiants"
                      checked={rememberMe}
                      onChange={(e) => setRememberMe(e.target.checked)}
                    />
                  </Form.Group>

                  <Button type="submit" className="btn-login">
                    Connexion
                  </Button>
                </Form>

                <div className="footer-links">
                  <p>
                    <NavLink to="/register">Créer un compte</NavLink>
                  </p>
                  <p>
                    <NavLink to="#">Mot de passe oublié ?</NavLink>
                  </p>
                </div>
              </Card.Body>
            </Card>
          </Col>
        </Row>
      </Container>
    </LoginWrapper>
  );
}
