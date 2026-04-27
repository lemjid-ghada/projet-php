import React, { useState, useEffect } from 'react';
import { Row, Col, Card, Button, Table, Modal, Form, Alert, Badge } from 'react-bootstrap';
import { FiPlus, FiEdit, FiTrash2, FiRefreshCw } from 'react-icons/fi';
import ApiService from '../../services/ApiService';

const PlatsList = () => {
  const [plats, setPlats] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [modalMode, setModalMode] = useState('add');
  const [selectedPlat, setSelectedPlat] = useState(null);
  const [message, setMessage] = useState({ type: '', text: '' });
  const [formData, setFormData] = useState({
    nom: '',
    prix: '',
    prep_time: '30',
    category_id: '',
    description: '',
    image_url: ''
  });

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    setLoading(true);
    const [platsRes, catsRes] = await Promise.all([
      ApiService.getPlats(),
      ApiService.getCategories()
    ]);
    
    if (platsRes.success) setPlats(platsRes.data);
    if (catsRes.success) setCategories(catsRes.data);
    setLoading(false);
  };

  const handleOpenAddModal = () => {
    setModalMode('add');
    setSelectedPlat(null);
    setFormData({
      nom: '',
      prix: '',
      prep_time: '30',
      category_id: '',
      description: '',
      image_url: ''
    });
    setShowModal(true);
  };

  const handleOpenEditModal = (plat) => {
    setModalMode('edit');
    setSelectedPlat(plat);
    setFormData({
      nom: plat.nom,
      prix: plat.prix.toString(),
      prep_time: plat.prep_time.toString(),
      category_id: plat.category_id.toString(),
      description: plat.description || '',
      image_url: plat.image_url || ''
    });
    setShowModal(true);
  };

  const handleCloseModal = () => setShowModal(false);

  const handleInputChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage({ type: '', text: '' });

    const data = {
      nom: formData.nom,
      prix: parseFloat(formData.prix),
      prep_time: parseInt(formData.prep_time),
      category_id: parseInt(formData.category_id),
      description: formData.description,
      image_url: formData.image_url || null
    };

    let result;
    if (modalMode === 'add') {
      result = await ApiService.createPlat(data);
    } else {
      result = await ApiService.updatePlat(selectedPlat.id, data);
    }

    if (result.success) {
      setMessage({ type: 'success', text: result.message });
      loadData();
      handleCloseModal();
      setTimeout(() => setMessage({ type: '', text: '' }), 3000);
    } else {
      setMessage({ type: 'danger', text: result.message });
    }
  };

  const handleDelete = async (id, nom) => {
    if (window.confirm(`Supprimer le plat "${nom}" ?`)) {
      const result = await ApiService.deletePlat(id);
      if (result.success) {
        setMessage({ type: 'success', text: result.message });
        loadData();
        setTimeout(() => setMessage({ type: '', text: '' }), 3000);
      } else {
        setMessage({ type: 'danger', text: result.message });
      }
    }
  };

  const getCategoryName = (categoryId) => {
    const cat = categories.find(c => c.id === categoryId);
    return cat ? cat.name : 'Non catégorisé';
  };

  if (loading) {
    return (
      <div className="text-center py-5">
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Chargement...</span>
        </div>
        <p className="mt-2">Chargement des plats...</p>
      </div>
    );
  }

  return (
    <>
      <Row>
        <Col>
          <Card>
            <Card.Header className="d-flex justify-content-between align-items-center">
              <h5 className="mb-0">🍽️ Liste des plats</h5>
              <div>
                <Button variant="outline-secondary" size="sm" className="me-2" onClick={loadData}>
                  <FiRefreshCw size={16} /> Rafraîchir
                </Button>
                <Button variant="success" size="sm" onClick={handleOpenAddModal}>
                  <FiPlus size={16} /> Ajouter un plat
                </Button>
              </div>
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
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Catégorie</th>
                    <th>Temps</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {plats.length === 0 ? (
                    <tr><td colSpan="8" className="text-center">Aucun plat trouvé</td></tr>
                  ) : (
                    plats.map((plat) => (
                      <tr key={plat.id}>
                        <td>{plat.id}</td>
                        <td>{plat.image_url ? <img src={plat.image_url} alt={plat.nom} width="40" height="40" style={{ objectFit: 'cover', borderRadius: '5px' }} /> : '🍲'}</td>
                        <td><strong>{plat.nom}</strong></td>
                        <td><Badge bg="success">{plat.prix} DT</Badge></td>
                        <td>{getCategoryName(plat.category_id)}</td>
                        <td>{plat.prep_time} min</td>
                        <td>{plat.description?.substring(0, 50)}...</td>
                        <td>
                          <Button variant="outline-primary" size="sm" className="me-1" onClick={() => handleOpenEditModal(plat)}>
                            <FiEdit size={14} />
                          </Button>
                          <Button variant="outline-danger" size="sm" onClick={() => handleDelete(plat.id, plat.nom)}>
                            <FiTrash2 size={14} />
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

      <Modal show={showModal} onHide={handleCloseModal} size="lg">
        <Modal.Header closeButton>
          <Modal.Title>{modalMode === 'add' ? 'Ajouter un plat' : 'Modifier le plat'}</Modal.Title>
        </Modal.Header>
        <Form onSubmit={handleSubmit}>
          <Modal.Body>
            <Row>
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label>Nom du plat *</Form.Label>
                  <Form.Control type="text" name="nom" value={formData.nom} onChange={handleInputChange} required />
                </Form.Group>
              </Col>
              <Col md={3}>
                <Form.Group className="mb-3">
                  <Form.Label>Prix (DT) *</Form.Label>
                  <Form.Control type="number" step="0.01" name="prix" value={formData.prix} onChange={handleInputChange} required />
                </Form.Group>
              </Col>
              <Col md={3}>
                <Form.Group className="mb-3">
                  <Form.Label>Temps prép. (min)</Form.Label>
                  <Form.Control type="number" name="prep_time" value={formData.prep_time} onChange={handleInputChange} />
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label>Catégorie *</Form.Label>
                  <Form.Select name="category_id" value={formData.category_id} onChange={handleInputChange} required>
                    <option value="">Sélectionner une catégorie</option>
                    {categories.map((cat) => (
                      <option key={cat.id} value={cat.id}>{cat.name}</option>
                    ))}
                  </Form.Select>
                </Form.Group>
              </Col>
              <Col md={12}>
                <Form.Group className="mb-3">
                  <Form.Label>Description</Form.Label>
                  <Form.Control as="textarea" rows={3} name="description" value={formData.description} onChange={handleInputChange} />
                </Form.Group>
              </Col>
              <Col md={12}>
                <Form.Group className="mb-3">
                  <Form.Label>URL de l'image</Form.Label>
                  <Form.Control type="url" name="image_url" value={formData.image_url} onChange={handleInputChange} placeholder="https://..." />
                </Form.Group>
              </Col>
            </Row>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="secondary" onClick={handleCloseModal}>Annuler</Button>
            <Button variant="primary" type="submit">{modalMode === 'add' ? 'Ajouter' : 'Modifier'}</Button>
          </Modal.Footer>
        </Form>
      </Modal>
    </>
  );
};

export default PlatsList;