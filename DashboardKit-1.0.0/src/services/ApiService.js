// API Service pour Dashboard Admin
const API_BASE_URL = "http://localhost:8000";

class ApiService {
  // ========================
  // PLATS (Menu)
  // ========================

  static async getPlats() {
    try {
      const response = await fetch(`${API_BASE_URL}/plats.php`, {
        method: "GET",
        headers: { "Content-Type": "application/json" },
      });
      const result = await response.json();
      return result;
    } catch (error) {
      console.error("Erreur getPlats:", error);
      return { success: false, message: "Erreur de connexion" };
    }
  }

  static async getCategories() {
    try {
      const response = await fetch(`${API_BASE_URL}/plats.php?action=categories`, {
        method: "GET",
        headers: { "Content-Type": "application/json" },
      });
      const result = await response.json();
      return result;
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  static async createPlat(platData) {
    try {
      const response = await fetch(`${API_BASE_URL}/plats.php?action=create`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(platData),
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  static async updatePlat(id, platData) {
    try {
      const response = await fetch(`${API_BASE_URL}/plats.php?action=update&id=${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(platData),
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  static async deletePlat(id) {
    try {
      const response = await fetch(`${API_BASE_URL}/plats.php?action=delete&id=${id}`, {
        method: "DELETE",
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  static async createCategory(name) {
    try {
      const response = await fetch(`${API_BASE_URL}/plats.php?action=create-category`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name }),
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  static async deleteCategory(id) {
    try {
      const response = await fetch(`${API_BASE_URL}/plats.php?action=delete-category&id=${id}`, {
        method: "DELETE",
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  // ========================
  // CONTACT
  // ========================

  static async getContactMessages() {
    try {
      const response = await fetch(`${API_BASE_URL}/contact.php?action=list`, {
        method: "GET",
        headers: { "Content-Type": "application/json" },
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  static async replyToContact(id, responseText) {
    try {
      const response = await fetch(`${API_BASE_URL}/contact.php?action=reply&id=${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ response: responseText }),
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  static async deleteContactMessage(id) {
    try {
      const response = await fetch(`${API_BASE_URL}/contact.php?action=delete&id=${id}`, {
        method: "DELETE",
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  // ========================
  // STATISTIQUES
  // ========================

  static async getStats() {
    try {
      const [platsRes, contactsRes] = await Promise.all([
        this.getPlats(),
        this.getContactMessages(),
      ]);
      
      return {
        success: true,
        data: {
          totalPlats: platsRes.data?.length || 0,
          totalMessages: contactsRes.data?.length || 0,
          newMessages: contactsRes.data?.filter(m => m.status === 'new').length || 0,
        }
      };
    } catch (error) {
      return { success: false, message: "Erreur" };
    }
  }
  // ========================
// CATEGORIES
// ========================

static async getCategories() {
  try {
    const response = await fetch(`${API_BASE_URL}/plats.php?action=categories`);
    const result = await response.json();
    return result;
  } catch (error) {
    console.error("Erreur getCategories:", error);
    return { success: false, message: "Erreur de connexion" };
  }
}

static async createCategory(name) {
  try {
    const response = await fetch(`${API_BASE_URL}/plats.php?action=create-category`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name })
    });
    return await response.json();
  } catch (error) {
    return { success: false, message: "Erreur de connexion" };
  }
}

static async updateCategory(id, name) {
  try {
    const response = await fetch(`${API_BASE_URL}/plats.php?action=update-category&id=${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name })
    });
    return await response.json();
  } catch (error) {
    return { success: false, message: "Erreur de connexion" };
  }
}

static async deleteCategory(id) {
  try {
    const response = await fetch(`${API_BASE_URL}/plats.php?action=delete-category&id=${id}`, {
      method: "DELETE"
    });
    return await response.json();
  } catch (error) {
    return { success: false, message: "Erreur de connexion" };
  }
}
  // ========================
  // CONTACT MESSAGES
  // ========================

  // Récupérer tous les messages
  static async getContactMessages() {
    try {
      const response = await fetch(`${API_BASE_URL}/contact.php?action=list`);
      const result = await response.json();
      return result;
    } catch (error) {
      console.error("Erreur getContactMessages:", error);
      return { success: false, message: "Erreur de connexion" };
    }
  }

  // Récupérer un message par ID
  static async getContactMessageById(id) {
    try {
      const response = await fetch(`${API_BASE_URL}/contact.php?action=get&id=${id}`);
      const result = await response.json();
      return result;
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  // Répondre à un message
  static async replyToContact(id, responseText) {
    try {
      const response = await fetch(`${API_BASE_URL}/contact.php?action=reply&id=${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ response: responseText })
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }

  // Supprimer un message
  static async deleteContactMessage(id) {
    try {
      const response = await fetch(`${API_BASE_URL}/contact.php?action=delete&id=${id}`, {
        method: "DELETE"
      });
      return await response.json();
    } catch (error) {
      return { success: false, message: "Erreur de connexion" };
    }
  }
}

export default ApiService;