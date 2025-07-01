// Integración específica para el carrito sin CSRF token
class CartAPI {
  constructor() {
    this.baseURL = window.location.origin
  }

  // Headers sin CSRF token para las rutas excluidas
  getHeaders() {
    return {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
    }
  }

  // Cargar productos (sin CSRF)
  async loadProducts() {
    try {
      const response = await fetch(`${this.baseURL}/api/productos`, {
        method: "GET",
        headers: this.getHeaders(),
      })

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`)
      }

      const data = await response.json()
      return data
    } catch (error) {
      console.error("Error loading products:", error)
      throw error
    }
  }

  // Procesar venta del carrito (sin CSRF)
  async processCartSale(cartData) {
    try {
      const saleData = {
        numero_pedido: "PED-" + Date.now(),
        cliente_nombre: cartData.customerName,
        cliente_telefono: cartData.customerPhone,
        cliente_email: cartData.customerEmail || null,
        direccion_entrega: cartData.deliveryAddress,
        fecha_entrega: cartData.deliveryDate,
        hora_entrega: cartData.deliveryTime,
        instrucciones_especiales: cartData.specialInstructions || null,
        items: cartData.items.map((item) => ({
          id: item.id,
          quantity: item.quantity,
        })),
        subtotal: cartData.subtotal,
        total: cartData.total,
      }

      console.log("Enviando venta:", saleData)

      const response = await fetch(`${this.baseURL}/api/ventas`, {
        method: "POST",
        headers: this.getHeaders(),
        body: JSON.stringify(saleData),
      })

      const result = await response.json()

      if (!response.ok) {
        throw new Error(result.message || `HTTP ${response.status}`)
      }

      return result
    } catch (error) {
      console.error("Error processing sale:", error)
      throw error
    }
  }
}

// Instancia global para el carrito
window.cartAPI = new CartAPI()

// Funciones específicas para el carrito
window.cartUtils = {
  // Cargar productos para mostrar en el catálogo
  async loadProducts() {
    try {
      const response = await window.cartAPI.loadProducts()
      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || "Error al cargar productos")
      }
    } catch (error) {
      console.error("Error:", error)
      throw error
    }
  },

  // Procesar compra del carrito
  async processPurchase(orderData) {
    try {
      const response = await window.cartAPI.processCartSale(orderData)
      if (response.success) {
        return {
          success: true,
          message: "Pedido procesado exitosamente",
          orderNumber: response.data.numero_pedido,
          orderId: response.data.venta_id,
        }
      } else {
        throw new Error(response.message || "Error al procesar la compra")
      }
    } catch (error) {
      return {
        success: false,
        message: error.message || "Error de conexión",
      }
    }
  },
}

console.log("✅ Cart API inicializada sin CSRF token")