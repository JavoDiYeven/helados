// Configuración de la API
const API_BASE_URL = window.location.origin // Usa la URL actual
let products = []
const cart = []
let orderData = {}

// Funciones auxiliares
function showLoading(isLoading) {
  const loadingElement = document.getElementById("loading")
  if (isLoading) {
    loadingElement.classList.remove("hidden")
  } else {
    loadingElement.classList.add("hidden")
  }
}

function displayProducts(products) {
  const productContainer = document.getElementById("product-container")
  productContainer.innerHTML = ""
  products.forEach((product) => {
    const productElement = document.createElement("div")
    productElement.classList.add("product")
    productElement.innerHTML = `
            <h3>${product.name}</h3>
            <p>${product.description}</p>
            <p>Precio: $${product.price}</p>
            <button class="add-to-cart" data-id="${product.id}">Agregar al carrito</button>
        `
    productContainer.appendChild(productElement)
  })
}

function showNotification(message, type) {
  const notification = document.getElementById("notification")
  notification.textContent = message
  notification.classList.remove("hidden")
  notification.classList.add(type)
  setTimeout(() => {
    notification.classList.add("hidden")
  }, 3000)
}

function updateCartDisplay() {
  const cartDisplay = document.getElementById("cart-display")
  cartDisplay.innerHTML = ""
  cart.forEach((item) => {
    const cartItemElement = document.createElement("div")
    cartItemElement.classList.add("cart-item")
    cartItemElement.innerHTML = `
            <p>${item.name} - Cantidad: ${item.quantity}</p>
            <button class="remove-from-cart" data-id="${item.id}">Eliminar</button>
        `
    cartDisplay.appendChild(cartItemElement)
  })
}

function setMinDeliveryDate() {
  const deliveryDateInput = document.getElementById("delivery-date")
  const today = new Date().toISOString().split("T")[0]
  deliveryDateInput.min = today
}

function openCart() {
  const cartModal = document.getElementById("cart-modal")
  cartModal.classList.remove("hidden")
}

function handleOrderSubmit(event) {
  event.preventDefault()
  const form = event.target
  const customerName = form.elements["customer-name"].value
  const customerPhone = form.elements["customer-phone"].value
  const customerEmail = form.elements["customer-email"].value
  const deliveryAddress = form.elements["delivery-address"].value
  const deliveryDate = form.elements["delivery-date"].value
  const deliveryTime = form.elements["delivery-time"].value
  const specialInstructions = form.elements["special-instructions"].value

  orderData = {
    orderNumber: Date.now().toString(),
    customerName,
    customerPhone,
    customerEmail,
    deliveryAddress,
    deliveryDate,
    deliveryTime,
    specialInstructions,
    items: cart.map((item) => ({ id: item.id, quantity: item.quantity })),
  }

  enviarVentaAlBackend(orderData)
}

// Función mejorada para cargar productos desde la API
async function loadProducts() {
  showLoading(true)

  try {
    const response = await fetch(`${API_BASE_URL}/api/productos`, {
      method: "GET",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()

    if (data.success) {
      products = data.data
      displayProducts(products)
      document.getElementById("error-message").classList.add("hidden")
      console.log("Productos cargados:", products.length)
    } else {
      throw new Error(data.message || "Error al cargar productos")
    }
  } catch (error) {
    console.error("Error loading products:", error)
    document.getElementById("error-message").classList.remove("hidden")
    document.getElementById("error-text").textContent = `Error al conectar con el servidor: ${error.message}`
    showNotification("Error al cargar los productos", "error")
  } finally {
    showLoading(false)
  }
}

// Función mejorada para enviar venta al backend
async function enviarVentaAlBackend(orderData) {
  try {
    const ventaData = {
      numero_pedido: orderData.orderNumber,
      cliente_nombre: orderData.customerName,
      cliente_telefono: orderData.customerPhone,
      cliente_email: orderData.customerEmail || null,
      direccion_entrega: orderData.deliveryAddress,
      fecha_entrega: orderData.deliveryDate,
      hora_entrega: orderData.deliveryTime,
      instrucciones_especiales: orderData.specialInstructions || null,
      items: orderData.items.map((item) => ({
        id: item.id,
        quantity: item.quantity,
      })),
      subtotal: orderData.items.reduce((sum, item) => sum + item.price * item.quantity, 0),
      total: orderData.items.reduce((sum, item) => sum + item.price * item.quantity, 0) + 25,
    }

    console.log("Enviando venta:", ventaData)

    const response = await fetch(`${API_BASE_URL}/api/ventas`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify(ventaData),
    })

    const result = await response.json()

    if (response.ok && result.success) {
      console.log("Venta registrada exitosamente:", result)
      showNotification("¡Pedido procesado exitosamente!", "success")
      return true
    } else {
      console.error("Error al registrar venta:", result)
      showNotification(`Error: ${result.message || "Error desconocido"}`, "error")
      return false
    }
  } catch (error) {
    console.error("Error de conexión:", error)
    showNotification("Error de conexión al procesar la venta", "error")
    return false
  }
}

// Función para verificar el estado de la API
async function checkApiStatus() {
  try {
    const response = await fetch(`${API_BASE_URL}/api/productos`)
    const data = await response.json()

    if (data.success) {
      console.log("✅ API conectada correctamente")
      return true
    } else {
      console.warn("⚠️ API responde pero con errores:", data.message)
      return false
    }
  } catch (error) {
    console.error("❌ Error de conexión con la API:", error)
    return false
  }
}

// Función para obtener mis pedidos (si el usuario está logueado)
async function obtenerMisPedidos(email) {
  try {
    const response = await fetch(`${API_BASE_URL}/mis-pedidos?email=${encodeURIComponent(email)}`, {
      headers: {
        Accept: "application/json",
      },
    })

    const result = await response.json()

    if (result.success) {
      return result.data
    } else {
      console.error("Error al obtener pedidos:", result.message)
      return []
    }
  } catch (error) {
    console.error("Error de conexión:", error)
    return []
  }
}

// Inicialización mejorada
document.addEventListener("DOMContentLoaded", async () => {
  console.log("🚀 Iniciando aplicación...")

  // Verificar estado de la API
  const apiStatus = await checkApiStatus()
  if (!apiStatus) {
    showNotification("Problemas de conexión con el servidor", "warning")
  }

  // Cargar productos
  await loadProducts()

  // Configurar otros elementos
  updateCartDisplay()
  setMinDeliveryDate()

  // Event listeners
  document.getElementById("cart-btn").addEventListener("click", openCart)
  document.getElementById("delivery-form").addEventListener("submit", handleOrderSubmit)

  console.log("✅ Aplicación inicializada correctamente")
})

// Exportar funciones para uso global
window.apiUtils = {
  loadProducts,
  enviarVentaAlBackend,
  checkApiStatus,
  obtenerMisPedidos,
}
