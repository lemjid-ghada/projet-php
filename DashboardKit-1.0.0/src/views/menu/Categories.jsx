import React, { useState, useEffect } from 'react';
import { Row, Col, Card, Button, Table, Modal, Form, Alert } from 'react-bootstrap';
import { FiPlus, FiEdit, FiTrash2, FiRefreshCw } from 'react-icons/fi';
import ApiService from '../../services/ApiService';

const Categories = () => {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [modalMode, setModalMode] = useState('add'); // 'add' ou 'edit'
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [categoryName, setCategoryName] = useState('');
  const [message, setMessage] = useState({ type: '', text: '' });

  // Charger les catégories au démarrage
  useEffect(() => {
    loadCategories();
  }, []);

  const loadCategories = async () => {
    setLoading(true);
    const result = await ApiService.getCategories();
    
    if (result.success) {
      setCategories(result.data);
    } else {
      setMessage({ type: 'danger', text: result.message });
    }
    setLoading(false);
  };

  // Ouvrir modal d'ajout
  const handleOpenAddModal = () => {
    setModalMode('add');
    setSelectedCategory(null);
    setCategoryName('');
    setShowModal(true);
  };

  // Ouvrir modal de modification
  const handleOpenEditModal = (category) => {
    setModalMode('edit');
    setSelectedCategory(category);
    setCategoryName(category.name);
    setShowModal(true);
  };

  // Fermer modal
  const handleCloseModal = () => {
    setShowModal(false);
    setCategoryName('');
  };

  // Soumettre formulaire (ajout ou modification)
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!categoryName.trim()) {
      setMessage({ type: 'danger', text: 'Le nom de la catégorie est requis' });
      return;
    }

    let result;
    if (modalMode === 'add') {
      result = await ApiService.createCategory(categoryName);
    } else {
      result = await ApiService.updateCategory(selectedCategory.id, categoryName);
    }

    if (result.success) {
      setMessage({ type: 'success', text: result.message });
      loadCategories(); // Recharger la liste
      handleCloseModal();
      setTimeout(() => setMessage({ type: '', text: '' }), 3000);
    } else {
      setMessage({ type: 'danger', text: result.message });
    }
  };

  // Supprimer une catégorie
  const handleDelete = async (id, name) => {
    if (window.confirm(`Supprimer la catégorie "${name}" ?`)) {
      const result = await ApiService.deleteCategory(id);
      
      if (result.success) {
        setMessage({ type: 'success', text: result.message });
        loadCategories();
        setTimeout(() => setMessage({ type: '', text: '' }), 3000);
      } else {
        setMessage({ type: 'danger', text: result.message });
      }
    }
  };

  if (loading) {
    return (
      <div className="text-center py-5">
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Chargement...</span>
        </div>
        <p className="mt-2">Chargement des catégories...</p>
      </div>
    );
  }

  return (
    <>
      <Row>
        <Col>
          <Card>
            <Card.Header className="d-flex justify-content-between align-items-center">
              <h5 className="mb-0">📁 Gestion des Catégories</h5>
              <div>
                <Button 
                  variant="outline-secondary" 
                  size="sm" 
                  className="me-2" 
                  onClick={loadCategories}
                >
                  <FiRefreshCw size={16} /> Rafraîchir
                </Button>
                <Button 
                  variant="success" 
                  size="sm" 
                  onClick={handleOpenAddModal}
                >
                  <FiPlus size={16} /> Ajouter une catégorie
                </Button>
              </div>
            </Card.Header>
            <Card.Body>
              {message.text && (
                <Alert 
                  variant={message.type} 
                  dismissible 
                  onClose={() => setMessage({ type: '', text: '' })}
                >
                  {message.text}
                </Alert>
              )}

              <Table responsive striped hover>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th style={{ width: '150px' }}>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {categories.length === 0 ? (
                    <tr>
                      <td colSpan="3" className="text-center">
                        Aucune catégorie trouvée
                      </td>
                    </tr>
                  ) : (
                    categories.map((category) => (
                      <tr key={category.id}>
                        <td>{category.id}</td>
                        <td><strong>{category.name}</strong></td>
                        <td>
                          <Button 
                            variant="outline-primary" 
                            size="sm" 
                            className="me-1"
                            onClick={() => handleOpenEditModal(category)}
                          >
                            <FiEdit size={14} /> Modifier
                          </Button>
                          <Button 
                            variant="outline-danger" 
                            size="sm"
                            onClick={() => handleDelete(category.id, category.name)}
                          >
                            <FiTrash2 size={14} /> Supprimer
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

      {/* Modal Ajouter/Modifier */}
      <Modal show={showModal} onHide={handleCloseModal}>
        <Modal.Header closeButton>
          <Modal.Title>
            {modalMode === 'add' ? 'Ajouter une catégorie' : 'Modifier la catégorie'}
          </Modal.Title>
        </Modal.Header>
        <Form onSubmit={handleSubmit}>
          <Modal.Body>
            <Form.Group>
              <Form.Label>Nom de la catégorie</Form.Label>
              <Form.Control 
                type="text" 
                value={categoryName} 
                onChange={(e) => setCategoryName(e.target.value)}
                placeholder="Ex: Entrées, Plats, Desserts..."
                autoFocus
                required
              />
            </Form.Group>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="secondary" onClick={handleCloseModal}>
              Annuler
            </Button>
            <Button variant="primary" type="submit">
              {modalMode === 'add' ? 'Ajouter' : 'Enregistrer'}
            </Button>
          </Modal.Footer>
        </Form>
      </Modal>
    </>
  );
};

export default Categories;