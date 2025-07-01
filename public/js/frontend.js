// Configuración de la API
const API_BASE_URL = "http://localhost:8000" 
const csrfToken = getCookie("XSRF-TOKEN")
let products = []
let cart = []
let orderData = {}

// Inicialización
document.addEventListener("DOMContentLoaded", () => {
  loadProducts()
  updateCartDisplay()
  setMinDeliveryDate()

  // Event listeners
  document.getElementById("cart-btn").addEventListener("click", openCart)
  document.getElementById("delivery-form").addEventListener("submit", handleOrderSubmit)
})

// Esta función permite leer cookies
function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'))
  if (match) return decodeURIComponent(match[2])
  return null
}


// Función para mostrar notificaciones
function showNotification(message, type = "success") {
  const notification = document.createElement("div")
  notification.className = `notification ${type}`
  notification.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `

  document.getElementById("notification-container").appendChild(notification)

  // Mostrar notificación
  setTimeout(() => notification.classList.add("show"), 100)

  // Auto-remover después de 5 segundos
  setTimeout(() => {
    notification.classList.remove("show")
    setTimeout(() => notification.remove(), 300)
  }, 5000)
}

// Función para mostrar/ocultar loading
function showLoading(show = true) {
  const overlay = document.getElementById("loading-overlay")
  if (show) {
    overlay.classList.remove("hidden")
  } else {
    overlay.classList.add("hidden")
  }
}

// Función para cargar productos desde la API
async function loadProducts() {
  showLoading(true)

  try {
    const response = await fetch(`${API_BASE_URL}/api/productos`)

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()

    if (data.success) {
      products = data.data
      displayProducts(products)
      document.getElementById("error-message").classList.add("hidden")
    } else {
      throw new Error(data.message || "Error al cargar productos")
    }
  } catch (error) {
    console.error("Error loading products:", error)
    document.getElementById("error-message").classList.remove("hidden")
    document.getElementById("error-text").textContent =
      "Error al conectar con el servidor. Verifica tu conexión e intenta de nuevo."
    showNotification("Error al cargar los productos", "error")
  } finally {
    showLoading(false)
  }
}

// Función para mostrar productos
function displayProducts(productsToShow) {
  const grid = document.getElementById("products-grid")

  if (productsToShow.length === 0) {
    grid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">🍦</div>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">No hay productos disponibles</h3>
                <p class="text-gray-500">Intenta cambiar los filtros o vuelve más tarde</p>
            </div>
        `
    return
  }

  grid.innerHTML = productsToShow
    .map((product) => {
      const isOutOfStock = product.stock === 0
      const isLowStock = product.stock > 0 && product.stock <= 10

      return `
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover ${isOutOfStock ? "opacity-75" : ""}">
                <div class="relative">
                    <img src="${product.imagen || "/placeholder.svg?height=200&width=300"}" 
                         alt="${product.nombre}" 
                         class="w-full h-48 object-cover">
                    
                    ${
                      isOutOfStock
                        ? `
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <span class="out-of-stock px-4 py-2 rounded-lg text-sm font-bold">
                                AGOTADO
                            </span>
                        </div>
                    `
                        : isLowStock
                          ? `
                        <div class="absolute top-2 right-2">
                            <span class="stock-warning px-2 py-1 rounded-full text-xs">
                                ¡Últimas ${product.stock}!
                            </span>
                        </div>
                    `
                          : ""
                    }
                    
                    <div class="absolute top-2 left-2">
                        <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                            ${product.categoria}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <h4 class="text-xl font-bold text-gray-800 mb-2">${product.nombre}</h4>
                    <p class="text-gray-600 mb-4 text-sm">${product.descripcion}</p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-green-600">$${new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(product.precio)}</span>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-box mr-1"></i>
                            Stock: ${product.stock}
                        </div>
                    </div>
                    
                    <button onclick="addToCart(${product.id})" 
                            ${isOutOfStock ? "disabled" : ""}
                            class="w-full py-3 px-4 rounded-lg font-medium transition-all duration-300 ${
                              isOutOfStock
                                ? "bg-gray-300 text-gray-500 cursor-not-allowed"
                                : "bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 transform hover:scale-105"
                            }">
                        ${isOutOfStock ? "Agotado" : "Agregar al Carrito"}
                        <i class="fas fa-cart-plus ml-2"></i>
                    </button>
                </div>
            </div>
        `
    })
    .join("")
}

// Función para filtrar productos
function filterProducts(category) {
  // Actualizar botones de filtro
  document.querySelectorAll(".filter-btn").forEach((btn) => {
    btn.classList.remove("bg-blue-500", "text-white")
    btn.classList.add("bg-gray-200", "text-gray-700")
  })

  event.target.classList.remove("bg-gray-200", "text-gray-700")
  event.target.classList.add("bg-blue-500", "text-white")

  // Filtrar productos
  let filteredProducts = products
  if (category !== "all") {
    filteredProducts = products.filter((product) => product.categoria.toLowerCase() === category.toLowerCase())
  }

  displayProducts(filteredProducts)
}

// Función para agregar al carrito
function addToCart(productId) {
  const product = products.find((p) => p.id === productId)
  if (!product || product.stock === 0) {
    showNotification("Producto no disponible", "error")
    return
  }

  const existingItem = cart.find((item) => item.id === productId)

  if (existingItem) {
    if (existingItem.quantity >= product.stock) {
      showNotification(`Solo hay ${product.stock} unidades disponibles`, "warning")
      return
    }
    existingItem.quantity += 1
  } else {
    cart.push({
      id: product.id,
      name: product.nombre,
      price: Number.parseFloat(product.precio),
      quantity: 1,
      stock: product.stock,
      image: product.imagen,
    })
  }

  updateCartDisplay()
  showNotification(`${product.nombre} agregado al carrito`, "success")
}

// Función para actualizar la visualización del carrito
function updateCartDisplay() {
  const cartCount = document.getElementById("cart-count")
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0)

  cartCount.textContent = totalItems
  cartCount.classList.add("cart-badge")

  setTimeout(() => {
    cartCount.classList.remove("cart-badge")
  }, 500)
}

// Función para abrir el carrito
function openCart() {
  displayCartItems()
  document.getElementById("cart-modal").classList.remove("hidden")
  document.body.style.overflow = "hidden"
}

// Función para cerrar el carrito
function closeCart() {
  document.getElementById("cart-modal").classList.add("hidden")
  document.body.style.overflow = "auto"
}

// Función para mostrar items del carrito
function displayCartItems() {
  const cartItemsContainer = document.getElementById("cart-items")
  const cartSubtotal = document.getElementById("cart-subtotal")
  const cartTotal = document.getElementById("cart-total")

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = `
            <div class="text-center py-8">
                <div class="text-6xl mb-4">🛒</div>
                <h4 class="text-xl font-semibold text-gray-600 mb-2">Tu carrito está vacío</h4>
                <p class="text-gray-500">¡Agrega algunos deliciosos helados!</p>
            </div>
        `
    cartSubtotal.textContent = "$0.00"
    cartTotal.textContent = "$25.00"
    return
  }

  cartItemsContainer.innerHTML = cart
    .map(
      (item) => `
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <img src="${item.image || "/placeholder.svg?height=60&width=60"}" 
                     alt="${item.name}" 
                     class="w-16 h-16 object-cover rounded-lg">
                <div>
                    <h5 class="font-semibold text-gray-800">${item.name}</h5>
                    <p class="text-green-600 font-bold">$${item.price.toFixed(2)}</p>
                    <p class="text-xs text-gray-500">Stock disponible: ${item.stock}</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" 
                        class="bg-red-500 text-white w-8 h-8 rounded-full hover:bg-red-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-minus text-xs"></i>
                </button>
                
                <span class="font-bold text-lg min-w-[2rem] text-center">${item.quantity}</span>
                
                <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" 
                        class="bg-green-500 text-white w-8 h-8 rounded-full hover:bg-green-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-plus text-xs"></i>
                </button>
                
                <button onclick="removeFromCart(${item.id})" 
                        class="bg-gray-500 text-white w-8 h-8 rounded-full hover:bg-gray-600 transition-colors flex items-center justify-center ml-2">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </div>
        </div>
    `,
    )
    .join("")

  const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0)
  const total = subtotal + 25 // $25 shipping

  cartSubtotal.textContent = `$${subtotal.toFixed(2)}`
  cartTotal.textContent = `$${total.toFixed(2)}`
}

// Función para actualizar cantidad
function updateQuantity(productId, newQuantity) {
  const item = cart.find((item) => item.id === productId)
  if (!item) return

  if (newQuantity <= 0) {
    removeFromCart(productId)
    return
  }

  if (newQuantity > item.stock) {
    showNotification(`Solo hay ${item.stock} unidades disponibles`, "warning")
    return
  }

  item.quantity = newQuantity
  updateCartDisplay()
  displayCartItems()
}

// Función para remover del carrito
function removeFromCart(productId) {
  cart = cart.filter((item) => item.id !== productId)
  updateCartDisplay()
  displayCartItems()
  showNotification("Producto removido del carrito", "success")
}

// Función para proceder al checkout
function proceedToCheckout() {
  if (cart.length === 0) {
    showNotification("Tu carrito está vacío", "warning")
    return
  }

  closeCart()
  displayCheckoutItems()
  document.getElementById("checkout-modal").classList.remove("hidden")
  document.body.style.overflow = "hidden"
}

// Función para mostrar items en checkout
function displayCheckoutItems() {
  const checkoutItemsContainer = document.getElementById("checkout-items")
  const checkoutSubtotal = document.getElementById("checkout-subtotal")
  const checkoutTotal = document.getElementById("checkout-total")

  checkoutItemsContainer.innerHTML = cart
    .map(
      (item) => `
        <div class="flex justify-between items-center">
            <span>${item.name} x ${item.quantity}</span>
            <span class="font-semibold">$${(item.price * item.quantity).toFixed(2)}</span>
        </div>
    `,
    )
    .join("")

  const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0)
  const total = subtotal + 25

  checkoutSubtotal.textContent = `$${subtotal.toFixed(2)}`
  checkoutTotal.textContent = `$${total.toFixed(2)}`
}

// Función para cerrar checkout
function closeCheckout() {
  document.getElementById("checkout-modal").classList.add("hidden")
  document.body.style.overflow = "auto"
}

// Función para establecer fecha mínima de entrega
function setMinDeliveryDate() {
  const today = new Date()
  const tomorrow = new Date(today)
  tomorrow.setDate(tomorrow.getDate() + 1)

  const dateInput = document.querySelector('input[name="deliveryDate"]')
  dateInput.min = tomorrow.toISOString().split("T")[0]
  dateInput.value = tomorrow.toISOString().split("T")[0]
}

// Función para manejar el envío del pedido
async function handleOrderSubmit(e) {
  e.preventDefault()

  const submitBtn = document.getElementById("submit-order-btn")
  const originalText = submitBtn.innerHTML

  // Mostrar loading en el botón
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...'
  submitBtn.disabled = true

  try {
    const formData = new FormData(e.target)
    orderData = {
      customerName: formData.get("customerName"),
      customerPhone: formData.get("customerPhone"),
      customerEmail: formData.get("customerEmail"),
      deliveryAddress: formData.get("deliveryAddress"),
      deliveryDate: formData.get("deliveryDate"),
      deliveryTime: formData.get("deliveryTime"),
      specialInstructions: formData.get("specialInstructions"),
      items: [...cart],
      orderNumber: "HD" + Date.now(),
      orderDate: new Date().toLocaleDateString("es-ES"),
      orderTime: new Date().toLocaleTimeString("es-ES"),
    }

    // Enviar venta al backend
    const ventaRegistrada = await enviarVentaAlBackend(orderData)

    if (ventaRegistrada) {
      closeCheckout()
      showReceipt()

      // Limpiar carrito
      cart = []
      updateCartDisplay()

      showNotification("¡Pedido confirmado exitosamente!", "success")
    }
  } catch (error) {
    console.error("Error al procesar pedido:", error)
    showNotification("Error al procesar el pedido. Intenta de nuevo.", "error")
  } finally {
    // Restaurar botón
    submitBtn.innerHTML = originalText
    submitBtn.disabled = false
  }
}

// Función para enviar venta al backend
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

    const response = await fetch('http://localhost:8000/api/ventas', {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify(ventaData),
    })

    const result = await response.json()

    if (result.success) {
      console.log("Venta registrada exitosamente:", result)
      return true
    } else {
      console.error("Error al registrar venta:", result.message)
      showNotification("Error al procesar la venta: " + result.message, "error")
      return false
    }
  } catch (error) {
    console.error("Error de conexión:", error)
    showNotification("Error de conexión al procesar la venta", "error")
    return false
  }
}

// Función para mostrar recibo
function showReceipt() {
  const receiptContent = document.getElementById("receipt-content")
  const subtotal = orderData.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
  const total = subtotal + 25

  receiptContent.innerHTML = `
        <div class="text-center mb-6">
            <h4 class="text-2xl font-bold text-gray-800 mb-2">🍦 Helados Delicia</h4>
            <p class="text-gray-600">Recibo de Compra</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <strong>Número de Pedido:</strong><br>
                    ${orderData.orderNumber}
                </div>
                <div>
                    <strong>Fecha:</strong><br>
                    ${orderData.orderDate} ${orderData.orderTime}
                </div>
                <div>
                    <strong>Cliente:</strong><br>
                    ${orderData.customerName}
                </div>
                <div>
                    <strong>Teléfono:</strong><br>
                    ${orderData.customerPhone}
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <h5 class="font-semibold mb-3">Productos Pedidos:</h5>
            <div class="space-y-2">
                ${orderData.items
                  .map(
                    (item) => `
                    <div class="flex justify-between items-center py-2 border-b">
                        <div>
                            <span class="font-medium">${item.name}</span>
                            <span class="text-gray-500 text-sm"> x ${item.quantity}</span>
                        </div>
                        <span class="font-semibold">$${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `,
                  )
                  .join("")}
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <div class="flex justify-between mb-2">
                <span>Subtotal:</span>
                <span>$${subtotal.toFixed(2)}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span>Envío:</span>
                <span>$25.00</span>
            </div>
            <div class="flex justify-between font-bold text-lg border-t pt-2">
                <span>Total:</span>
                <span class="text-green-600">$${total.toFixed(2)}</span>
            </div>
        </div>
        
        <div class="bg-blue-50 p-4 rounded-lg">
            <h5 class="font-semibold mb-2">Información de Entrega:</h5>
            <p class="text-sm text-gray-700 mb-1">
                <strong>Dirección:</strong> ${orderData.deliveryAddress}
            </p>
            <p class="text-sm text-gray-700 mb-1">
                <strong>Fecha:</strong> ${orderData.deliveryDate}
            </p>
            <p class="text-sm text-gray-700 mb-1">
                <strong>Hora:</strong> ${orderData.deliveryTime}
            </p>
            ${
              orderData.specialInstructions
                ? `
                <p class="text-sm text-gray-700">
                    <strong>Instrucciones:</strong> ${orderData.specialInstructions}
                </p>
            `
                : ""
            }
        </div>
    `

  document.getElementById("receipt-modal").classList.remove("hidden")
  document.body.style.overflow = "hidden"
}

// Función para cerrar recibo
function closeReceipt() {
  document.getElementById("receipt-modal").classList.add("hidden")
  document.body.style.overflow = "auto"
}

// Función para descargar PDF
function downloadPDF() {
  const { jsPDF } = window.jspdf
  const doc = new jsPDF()

  // Configurar fuente
  doc.setFont("helvetica")

  // Título
  doc.setFontSize(20)
  doc.setTextColor(40, 40, 40)
  doc.text("🍦 Helados Delicia", 20, 30)

  doc.setFontSize(14)
  doc.text("Recibo de Compra", 20, 45)

  // Información del pedido
  doc.setFontSize(12)
  doc.text(`Número de Pedido: ${orderData.orderNumber}`, 20, 65)
  doc.text(`Fecha: ${orderData.orderDate} ${orderData.orderTime}`, 20, 75)
  doc.text(`Cliente: ${orderData.customerName}`, 20, 85)
  doc.text(`Teléfono: ${orderData.customerPhone}`, 20, 95)

  // Productos
  doc.text("Productos:", 20, 115)
  let yPos = 125

  orderData.items.forEach((item) => {
    doc.text(`${item.name} x ${item.quantity}`, 25, yPos)
    doc.text(`$${(item.price * item.quantity).toFixed(2)}`, 150, yPos)
    yPos += 10
  })

  // Totales
  const subtotal = orderData.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
  const total = subtotal + 25

  yPos += 10
  doc.text("Subtotal:", 25, yPos)
  doc.text(`$${subtotal.toFixed(2)}`, 150, yPos)

  yPos += 10
  doc.text("Envío:", 25, yPos)
  doc.text("$25.00", 150, yPos)

  yPos += 10
  doc.setFontSize(14)
  doc.text("Total:", 25, yPos)
  doc.text(`$${total.toFixed(2)}`, 150, yPos)

  // Información de entrega
  yPos += 20
  doc.setFontSize(12)
  doc.text("Información de Entrega:", 20, yPos)
  yPos += 10
  doc.text(`Dirección: ${orderData.deliveryAddress}`, 25, yPos)
  yPos += 10
  doc.text(`Fecha: ${orderData.deliveryDate}`, 25, yPos)
  yPos += 10
  doc.text(`Hora: ${orderData.deliveryTime}`, 25, yPos)

  if (orderData.specialInstructions) {
    yPos += 10
    doc.text(`Instrucciones: ${orderData.specialInstructions}`, 25, yPos)
  }

  // Descargar
  doc.save(`Recibo-${orderData.orderNumber}.pdf`)
  showNotification("PDF descargado exitosamente", "success")
}