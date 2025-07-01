// Tu archivo script.js existente, solo agregamos la integración
// ... todo tu código existente ...

// Configuración global
const API_BASE_URL = window.location.origin
let products = []
const cart = []
const orderData = {}
const generateInvoicePDF = null // Declarar la variable generateInvoicePDF

// Funciones de utilidad para UI
function showLoading(isLoading) {
  const loadingElement = document.getElementById("loading")
  if (loadingElement) {
    if (isLoading) {
      loadingElement.classList.remove("hidden")
    } else {
      loadingElement.classList.add("hidden")
    }
  }
}

function showNotification(message, type = "success") {
  // Crear elemento de notificación
  const notification = document.createElement("div")
  notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transform transition-transform duration-300 translate-x-full`

  // Aplicar color según el tipo
  switch (type) {
    case "success":
      notification.classList.add("bg-green-500")
      break
    case "error":
      notification.classList.add("bg-red-500")
      break
    case "warning":
      notification.classList.add("bg-yellow-500")
      break
    default:
      notification.classList.add("bg-blue-500")
  }

  notification.innerHTML = `
    <div class="flex items-center justify-between">
      <span>${message}</span>
      <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
        <i class="fas fa-times"></i>
      </button>
    </div>
  `

  document.body.appendChild(notification)

  // Mostrar notificación
  setTimeout(() => {
    notification.classList.remove("translate-x-full")
  }, 100)

  // Auto-remover después de 5 segundos
  setTimeout(() => {
    notification.classList.add("translate-x-full")
    setTimeout(() => notification.remove(), 300)
  }, 5000)
}

function displayProducts(products) {
  const productContainer = document.getElementById("product-container")
  if (!productContainer) return

  productContainer.innerHTML = ""

  if (products.length === 0) {
    productContainer.innerHTML = `
      <div class="col-span-full text-center py-8">
        <div class="text-gray-500">
          <i class="fas fa-ice-cream text-4xl mb-4"></i>
          <h3 class="text-xl font-semibold mb-2">No hay productos disponibles</h3>
          <p>Vuelve pronto para ver nuestros deliciosos helados</p>
        </div>
      </div>
    `
    return
  }

  products.forEach((product) => {
    const productElement = document.createElement("div")
    productElement.classList.add(
      "product-card",
      "bg-white",
      "rounded-xl",
      "shadow-lg",
      "overflow-hidden",
      "transform",
      "transition-all",
      "duration-300",
      "hover:scale-105",
      "hover:shadow-xl",
    )

    productElement.innerHTML = `
      <div class="relative">
        <img src="${product.image || "/placeholder.svg?height=200&width=300"}" 
             alt="${product.name}" 
             class="w-full h-48 object-cover">
        <div class="absolute top-2 right-2">
          <span class="bg-${product.category === "cremoso" ? "blue" : product.category === "frutal" ? "green" : "purple"}-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
            ${product.category || "cremoso"}
          </span>
        </div>
        ${
          product.stock <= 10
            ? `
          <div class="absolute top-2 left-2">
            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
              ${product.stock === 0 ? "Agotado" : "Últimas unidades"}
            </span>
          </div>
        `
            : ""
        }
      </div>
      
      <div class="p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">${product.name}</h3>
        <p class="text-gray-600 mb-4 text-sm">${product.description}</p>
        
        <div class="flex items-center justify-between mb-4">
          <span class="text-2xl font-bold text-blue-600">$${product.price}</span>
          <span class="text-sm text-gray-500">Stock: ${product.stock}</span>
        </div>
        
        <button 
          class="add-to-cart w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300 hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed" 
          data-id="${product.id}"
          data-name="${product.name}"
          data-price="${product.price}"
          data-stock="${product.stock}"
          ${product.stock === 0 ? "disabled" : ""}
        >
          <i class="fas fa-cart-plus mr-2"></i>
          ${product.stock === 0 ? "Agotado" : "Agregar al carrito"}
        </button>
      </div>
    `

    productContainer.appendChild(productElement)
  })

  // Agregar event listeners a los botones
  document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", handleAddToCart)
  })
}

function handleAddToCart(event) {
  const button = event.currentTarget
  const productId = Number.parseInt(button.dataset.id)
  const productName = button.dataset.name
  const productPrice = Number.parseFloat(button.dataset.price)
  const productStock = Number.parseInt(button.dataset.stock)

  if (productStock === 0) {
    showNotification("Producto agotado", "error")
    return
  }

  // Verificar si ya está en el carrito
  const existingItem = cart.find((item) => item.id === productId)

  if (existingItem) {
    if (existingItem.quantity >= productStock) {
      showNotification("No hay más stock disponible", "warning")
      return
    }
    existingItem.quantity += 1
  } else {
    cart.push({
      id: productId,
      name: productName,
      price: productPrice,
      quantity: 1,
      stock: productStock,
    })
  }

  updateCartDisplay()
  updateCartCounter()
  showNotification(`${productName} agregado al carrito`, "success")
}

function updateCartDisplay() {
  const cartDisplay = document.getElementById("cart-display")
  const cartItems = document.getElementById("cart-items")
  const cartTotal = document.getElementById("cart-total")

  if (!cartDisplay && !cartItems) return

  const target = cartItems || cartDisplay
  target.innerHTML = ""

  if (cart.length === 0) {
    target.innerHTML = `
      <div class="text-center py-8 text-gray-500">
        <i class="fas fa-shopping-cart text-4xl mb-4"></i>
        <p>Tu carrito está vacío</p>
      </div>
    `
    if (cartTotal) cartTotal.textContent = "$0.00"
    return
  }

  let total = 0

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.quantity
    total += itemTotal

    const cartItemElement = document.createElement("div")
    cartItemElement.classList.add(
      "cart-item",
      "flex",
      "items-center",
      "justify-between",
      "p-4",
      "border-b",
      "border-gray-200",
    )

    cartItemElement.innerHTML = `
      <div class="flex-1">
        <h4 class="font-semibold text-gray-800">${item.name}</h4>
        <p class="text-sm text-gray-600">$${item.price} c/u</p>
      </div>
      
      <div class="flex items-center space-x-3">
        <button 
          class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center transition-colors"
          onclick="updateQuantity(${index}, -1)"
        >
          <i class="fas fa-minus text-xs"></i>
        </button>
        
        <span class="font-semibold text-lg min-w-[2rem] text-center">${item.quantity}</span>
        
        <button 
          class="quantity-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center transition-colors"
          onclick="updateQuantity(${index}, 1)"
          ${item.quantity >= item.stock ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ""}
        >
          <i class="fas fa-plus text-xs"></i>
        </button>
        
        <button 
          class="remove-item text-red-500 hover:text-red-700 ml-4 transition-colors"
          onclick="removeFromCart(${index})"
        >
          <i class="fas fa-trash"></i>
        </button>
      </div>
      
      <div class="text-right ml-4">
        <span class="font-bold text-lg">$${itemTotal.toFixed(2)}</span>
      </div>
    `

    target.appendChild(cartItemElement)
  })

  if (cartTotal) {
    cartTotal.textContent = `$${total.toFixed(2)}`
  }
}

function updateCartCounter() {
  const cartCounter = document.getElementById("cart-counter")
  const cartBtn = document.getElementById("cart-btn")

  if (cartCounter) {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0)
    cartCounter.textContent = totalItems

    if (totalItems > 0) {
      cartCounter.classList.remove("hidden")
      if (cartBtn) cartBtn.classList.add("animate-pulse")
    } else {
      cartCounter.classList.add("hidden")
      if (cartBtn) cartBtn.classList.remove("animate-pulse")
    }
  }
}

function updateQuantity(index, change) {
  const item = cart[index]
  const newQuantity = item.quantity + change

  if (newQuantity <= 0) {
    removeFromCart(index)
    return
  }

  if (newQuantity > item.stock) {
    showNotification("No hay más stock disponible", "warning")
    return
  }

  item.quantity = newQuantity
  updateCartDisplay()
  updateCartCounter()
}

function removeFromCart(index) {
  const item = cart[index]
  cart.splice(index, 1)
  updateCartDisplay()
  updateCartCounter()
  showNotification(`${item.name} eliminado del carrito`, "success")
}

function clearCart() {
  cart.length = 0
  updateCartDisplay()
  updateCartCounter()
  showNotification("Carrito vaciado", "success")
}

function setMinDeliveryDate() {
  const deliveryDateInput = document.getElementById("delivery-date")
  if (deliveryDateInput) {
    const today = new Date()
    const tomorrow = new Date(today)
    tomorrow.setDate(tomorrow.getDate() + 1)
    deliveryDateInput.min = tomorrow.toISOString().split("T")[0]
  }
}

function openCart() {
  const cartModal = document.getElementById("cart-modal")
  if (cartModal) {
    cartModal.classList.remove("hidden")
    document.body.style.overflow = "hidden"
  }
}

function closeCart() {
  const cartModal = document.getElementById("cart-modal")
  if (cartModal) {
    cartModal.classList.add("hidden")
    document.body.style.overflow = "auto"
  }
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

      // Ocultar mensaje de error si existe
      const errorMessage = document.getElementById("error-message")
      if (errorMessage) {
        errorMessage.classList.add("hidden")
      }

      console.log("✅ Productos cargados:", products.length)
    } else {
      throw new Error(data.message || "Error al cargar productos")
    }
  } catch (error) {
    console.error("Error loading products:", error)

    // Mostrar mensaje de error
    const errorMessage = document.getElementById("error-message")
    const errorText = document.getElementById("error-text")

    if (errorMessage && errorText) {
      errorMessage.classList.remove("hidden")
      errorText.textContent = `Error al conectar con el servidor: ${error.message}`
    }

    showNotification("Error al cargar los productos", "error")
  } finally {
    showLoading(false)
  }
}

// Función para procesar la venta (sin requerir autenticación)
async function enviarVentaAlBackend(orderData) {
  try {
    const ventaData = {
      numero_pedido: "PED-" + Date.now(),
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
      subtotal: calculateSubtotal(),
      total: calculateTotal(),
    }

    console.log("Enviando venta:", ventaData)

    // Headers básicos sin token de autenticación
    const headers = {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
    }

    // Si hay un usuario logueado, agregar el token
    const authToken = getAuthToken()
    if (authToken) {
      headers["Authorization"] = authToken
    }

    const response = await fetch(`${API_BASE_URL}/api/ventas`, {
      method: "POST",
      headers: headers,
      body: JSON.stringify(ventaData),
    })

    const result = await response.json()

    if (response.ok && result.success) {
      console.log("Venta registrada exitosamente:", result)

      const tipoCliente = result.data.tipo_cliente || "invitado"
      const mensaje =
        tipoCliente === "registrado"
          ? "¡Pedido procesado exitosamente! Se ha guardado en tu historial."
          : "¡Pedido procesado exitosamente!"

      showNotification(mensaje, "success")

      // Limpiar carrito
      clearCart()
      closeCart()

      // Generar PDF si la función existe
      if (typeof generateInvoicePDF === "function") {
        generateInvoicePDF(orderData, result.data.numero_pedido)
      }

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

// Función auxiliar para obtener token si existe
function getAuthToken() {
  try {
    const token = localStorage.getItem("auth_token") || sessionStorage.getItem("auth_token")
    return token ? `Bearer ${token}` : null
  } catch (error) {
    return null
  }
}

// Funciones de cálculo
function calculateSubtotal() {
  return cart.reduce((sum, item) => sum + item.price * item.quantity, 0)
}

function calculateTotal() {
  const subtotal = calculateSubtotal()
  const shipping = 25.0 // Costo fijo de envío
  return subtotal + shipping
}

// Función para manejar el envío del formulario (sin verificar autenticación)
function handleOrderSubmit(event) {
  event.preventDefault()

  if (cart.length === 0) {
    showNotification("Agrega productos al carrito antes de realizar el pedido", "warning")
    return
  }

  const form = event.target
  const formData = new FormData(form)

  const orderData = {
    customerName: formData.get("customer-name"),
    customerPhone: formData.get("customer-phone"),
    customerEmail: formData.get("customer-email"),
    deliveryAddress: formData.get("delivery-address"),
    deliveryDate: formData.get("delivery-date"),
    deliveryTime: formData.get("delivery-time"),
    specialInstructions: formData.get("special-instructions"),
    items: cart.map((item) => ({
      id: item.id,
      quantity: item.quantity,
      name: item.name,
      price: item.price,
    })),
  }

  // Validaciones básicas
  if (!orderData.customerName || !orderData.customerPhone || !orderData.deliveryAddress) {
    showNotification("Por favor completa todos los campos obligatorios", "warning")
    return
  }

  // Procesar venta directamente (sin verificar autenticación)
  enviarVentaAlBackend(orderData)
}

// Función para verificar el estado de la API
async function checkApiStatus() {
  try {
    const response = await fetch(`${API_BASE_URL}/api/status`)
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

// Inicialización
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
  updateCartCounter()
  setMinDeliveryDate()

  // Event listeners
  const cartBtn = document.getElementById("cart-btn")
  if (cartBtn) {
    cartBtn.addEventListener("click", openCart)
  }

  const deliveryForm = document.getElementById("delivery-form")
  if (deliveryForm) {
    deliveryForm.addEventListener("submit", handleOrderSubmit)
  }

  const closeCartBtn = document.getElementById("close-cart")
  if (closeCartBtn) {
    closeCartBtn.addEventListener("click", closeCart)
  }

  console.log("✅ Aplicación inicializada correctamente")
})

// Exportar funciones para uso global
window.cartUtils = {
  loadProducts,
  enviarVentaAlBackend,
  checkApiStatus,
  updateCartDisplay,
  updateCartCounter,
  clearCart,
  openCart,
  closeCart,
}