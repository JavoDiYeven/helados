// Cliente API mejorado sin CSRF token
class ApiClient {
  constructor(baseUrl = window.location.origin) {
    this.baseUrl = baseUrl
    this.token = this.getStoredToken()
  }

  // Obtener token almacenado
  getStoredToken() {
    return localStorage.getItem("auth_token") || sessionStorage.getItem("auth_token")
  }

  // Guardar token
  setToken(token, remember = false) {
    this.token = token
    const storage = remember ? localStorage : sessionStorage
    storage.setItem("auth_token", token)
  }

  // Limpiar token
  clearToken() {
    this.token = null
    localStorage.removeItem("auth_token")
    sessionStorage.removeItem("auth_token")
  }

  // Headers por defecto
  getHeaders(includeAuth = true) {
    const headers = {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
    }

    // Agregar token de autenticación si está disponible
    if (includeAuth && this.token) {
      headers["Authorization"] = `Bearer ${this.token}`
    }

    return headers
  }

  // Método genérico para hacer requests
  async request(endpoint, options = {}) {
    const url = `${this.baseUrl}/api${endpoint}`

    const config = {
      headers: this.getHeaders(options.auth !== false),
      ...options,
    }

    try {
      const response = await fetch(url, config)
      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.message || `HTTP ${response.status}`)
      }

      return data
    } catch (error) {
      console.error(`API Error [${endpoint}]:`, error)
      throw error
    }
  }

  // Métodos específicos
  async login(email, password, remember = false) {
    const data = await this.request("/login", {
      method: "POST",
      body: JSON.stringify({ email, password }),
      auth: false, // No necesita auth para login
    })

    if (data.success && data.token) {
      this.setToken(data.token, remember)
    }

    return data
  }

  async register(userData) {
    return await this.request("/register", {
      method: "POST",
      body: JSON.stringify(userData),
      auth: false, // No necesita auth para registro
    })
  }

  async logout() {
    try {
      await this.request("/logout", { method: "POST" })
    } finally {
      this.clearToken()
    }
  }

  async getMe() {
    return await this.request("/me")
  }

  async checkAuth() {
    return await this.request("/check")
  }

  async getProductos() {
    return await this.request("/productos", { auth: false })
  }

  async createVenta(ventaData) {
    return await this.request("/ventas", {
      method: "POST",
      body: JSON.stringify(ventaData),
    })
  }

  async getMisPedidos() {
    return await this.request("/mis-pedidos")
  }

  async getApiStatus() {
    return await this.request("/status", { auth: false })
  }
}

// Instancia global
window.apiClient = new ApiClient()

// Funciones de utilidad
window.apiUtils = {
  // Verificar si está autenticado
  isAuthenticated() {
    return !!window.apiClient.token
  },

  // Obtener usuario actual
  async getCurrentUser() {
    try {
      const response = await window.apiClient.getMe()
      return response.success ? response.user : null
    } catch (error) {
      return null
    }
  },

  // Login
  async login(email, password, remember = false) {
    try {
      const response = await window.apiClient.login(email, password, remember)
      return response
    } catch (error) {
      return {
        success: false,
        message: error.message || "Error de conexión",
      }
    }
  },

  // Logout
  async logout() {
    try {
      await window.apiClient.logout()
      return { success: true }
    } catch (error) {
      // Limpiar token local aunque falle la API
      window.apiClient.clearToken()
      return { success: true }
    }
  },

  // Cargar productos
  async loadProducts() {
    try {
      const response = await window.apiClient.getProductos()
      return response
    } catch (error) {
      return {
        success: false,
        message: error.message || "Error al cargar productos",
      }
    }
  },

  // Crear venta
  async createSale(saleData) {
    try {
      const response = await window.apiClient.createVenta(saleData)
      return response
    } catch (error) {
      return {
        success: false,
        message: error.message || "Error al procesar la venta",
      }
    }
  },
}

console.log("✅ API Client inicializado sin CSRF token")
