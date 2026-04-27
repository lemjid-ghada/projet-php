import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Button, Table, Badge, Modal, Form, Alert } from 'react-bootstrap';
import ApiService from '../../services/ApiService';

const ContactsPage = () => {
  const [messages, setMessages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [selectedMessage, setSelectedMessage] = useState(null);
  const [replyText, setReplyText] = useState('');
  const [message, setMessage] = useState({ type: '', text: '' });

  // Charger les messages au démarrage
  useEffect(() => {
    loadMessages();
  }, []);

  const loadMessages = async () => {
    setLoading(true);
    const result = await ApiService.getContactMessages();
    
    if (result.success) {
      setMessages(result.data);
    } else {
      setMessage({ type: 'danger', text: result.message });
    }
    setLoading(false);
  };

  // Voir un message
  const handleViewMessage = (msg) => {
    setSelectedMessage(msg);
    setReplyText('');
    setShowModal(true);
  };

  // Répondre à un message
  const handleReply = async () => {
    if (!replyText.trim()) {
      setMessage({ type: 'danger', text: 'Veuillez entrer votre réponse' });
      return;
    }

    const result = await ApiService.replyToContact(selectedMessage.id, replyText);
    
    if (result.success) {
      setMessage({ type: 'success', text: 'Réponse envoyée' });
      loadMessages();
      setShowModal(false);
      setReplyText('');
      setTimeout(() => setMessage({ type: '', text: '' }), 3000);
    } else {
      setMessage({ type: 'danger', text: result.message });
    }
  };

  // Supprimer un message
  const handleDelete = async (id, name) => {
    if (window.confirm(`Supprimer le message de "${name}" ?`)) {
      const result = await ApiService.deleteContactMessage(id);
      if (result.success) {
        setMessage({ type: 'success', text: 'Message supprimé' });
        loadMessages();
        setTimeout(() => setMessage({ type: '', text: '' }), 3000);
      } else {
        setMessage({ type: 'danger', text: result.message });
      }
    }
  };

  // Statut du message
  const getStatusBadge = (status) => {
    switch(status) {
      case 'new':
        return <Badge bg="danger">📩 Nouveau</Badge>;
      case 'read':
        return <Badge bg="warning">📖 Lu</Badge>;
      case 'replied':
        return <Badge bg="success">✅ Répondu</Badge>;
      default:
        return <Badge bg="secondary">{status}</Badge>;
    }
  };

  // Format date
  const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  if (loading) {
    return (
      <Container fluid style={{ padding: '30px 20px' }}>
        <div className="text-center py-5">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Chargement...</span>
          </div>
          <p className="mt-2">Chargement des messages...</p>
        </div>
      </Container>
    );
  }

  return (
    <Container fluid style={{ padding: '30px 20px' }}>
      <Row className="mb-4">
        <Col>
          <h1 style={{ color: '#333', fontWeight: '700' }}>📞 Messages de Contact</h1>
          <p style={{ color: '#999' }}>Messages reçus depuis le formulaire de contact</p>
        </Col>
      </Row>

      <Row>
        <Col lg={12}>
          <Card>
            <Card.Header className="d-flex justify-content-between align-items-center">
              <h5 className="mb-0">📨 Liste des messages</h5>
              <Button variant="outline-secondary" size="sm" onClick={loadMessages}>
                🔄 Rafraîchir
              </Button>
            </Card.Header>
            <Card.Body>
              {message.text && (
                <Alert variant={message.type} dismissible onClose={() => setMessage({ type: '', text: '' })}>
                  {message.text}
                </Alert>
              )}

              <Table responsive striped hover>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {messages.length === 0 ? (
                    <tr>
                      <td colSpan="7" className="text-center">
                        Aucun message reçu
                      </td>
                    </tr>
                  ) : (
                    messages.map((msg) => (
                      <tr key={msg.id}>
                        <td>{msg.id}</td>
                        <td><strong>{msg.name}</strong></td>
                        <td>{msg.email}</td>
                        <td>{msg.subject}</td>
                        <td>{formatDate(msg.created_at)}</td>
                        <td>{getStatusBadge(msg.status)}</td>
                        <td>
                          <Button 
                            variant="outline-primary" 
                            size="sm" 
                            className="me-1"
                            onClick={() => handleViewMessage(msg)}
                          >
                            👁️ Voir
                          </Button>
                          <Button 
                            variant="outline-danger" 
                            size="sm"
                            onClick={() => handleDelete(msg.id, msg.name)}
                          >
                            🗑️ Supprimer
                          </Button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </Table>
            </Card.Body>
          </Card>
        </Col>
      </Row>

      {/* Modal Voir message et répondre */}
      <Modal show={showModal} onHide={() => setShowModal(false)} size="lg">
        <Modal.Header closeButton>
          <Modal.Title>
            Message de {selectedMessage?.name}
            {selectedMessage && <span className="ms-2">{getStatusBadge(selectedMessage.status)}</span>}
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
          {selectedMessage && (
            <>
              <Row className="mb-3">
                <Col md={6}>
                  <strong>📧 Email :</strong> {selectedMessage.email}
                </Col>
                <Col md={6}>
                  <strong>📞 Téléphone :</strong> {selectedMessage.phone || 'Non renseigné'}
                </Col>
              </Row>
              <Row className="mb-3">
                <Col>
                  <strong>📌 Sujet :</strong> {selectedMessage.subject}
                </Col>
              </Row>
              <Row className="mb-3">
                <Col>
                  <strong>💬 Message :</strong>
                  <div className="border p-3 mt-2 rounded" style={{ backgroundColor: '#f8f9fa', whiteSpace: 'pre-wrap' }}>
                    {selectedMessage.message}
                  </div>
                </Col>
              </Row>
              
              {selectedMessage.response && (
                <Row className="mb-3">
                  <Col>
                    <strong>✅ Votre réponse :</strong>
                    <div className="border p-3 mt-2 rounded bg-success bg-opacity-10">
                      {selectedMessage.response}
                    </div>
                    <small className="text-muted">Répondu le {formatDate(selectedMessage.replied_at)}</small>
                  </Col>
                </Row>
              )}

              <hr />
              
              <Form.Group className="mb-3">
                <Form.Label><strong>✏️ Répondre</strong></Form.Label>
                <Form.Control
                  as="textarea"
                  rows={4}
                  value={replyText}
                  onChange={(e) => setReplyText(e.target.value)}
                  placeholder="Écrivez votre réponse ici..."
                />
              </Form.Group>
            </>
          )}
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setShowModal(false)}>
            Fermer
          </Button>
          <Button variant="primary" onClick={handleReply}>
            📤 Envoyer la réponse
          </Button>
        </Modal.Footer>
      </Modal>
    </Container>
  );
};

export default ContactsPage;