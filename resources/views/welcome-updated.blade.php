<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍦 Amai Gelato - La mejor heladería de la ciudad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tus estilos existentes -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        /* ... todos tus estilos existentes ... */
    </style>
</head>
<body class="bg-gray-50">
     <!-- Notification Container -->
    <div id="notification-container"></div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg flex items-center space-x-4">
            <div class="loading-spinner"></div>
            <span class="text-gray-700 font-medium">Cargando productos...</span>
        </div>
    </div>

    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="text-4xl">🍦</div>
                    <div>
                        <h1 class="text-2xl font-bold">Amai Gelato</h1>
                        <p class="text-sm opacity-90">La mejor heladería de la ciudad</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button id="cart-btn" class="relative bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-all duration-300 flex items-center space-x-2">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="font-medium">Carrito</span>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold cart-badge">0</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="ice-cream-bg py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold text-gray-800 mb-4">¡Bienvenidos a Amai Gelato!</h2>
            <p class="text-xl text-gray-700 mb-8">Los helados más cremosos y deliciosos de la ciudad</p>
            <div class="flex justify-center space-x-8 text-6xl">
                <span class="animate-bounce">🍨</span>
                <span class="animate-bounce" style="animation-delay: 0.1s;">🍦</span>
                <span class="animate-bounce" style="animation-delay: 0.2s;">🧁</span>
                <span class="animate-bounce" style="animation-delay: 0.3s;">🍰</span>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="py-8 bg-white shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-4">
                <button onclick="filterProducts('all')" class="filter-btn bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition-colors duration-300 font-medium">
                    Todos los Productos
                </button>
                <button onclick="filterProducts('cremoso')" class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition-colors duration-300 font-medium">
                    Cremosos
                </button>
                <button onclick="filterProducts('frutal')" class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition-colors duration-300 font-medium">
                    Frutales
                </button>
                <button onclick="filterProducts('especial')" class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition-colors duration-300 font-medium">
                    Especiales
                </button>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-8">🍦 Nuestros Deliciosos Helados</h3>
            
            <!-- Error Message -->
            <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span id="error-text">Error al cargar los productos. Por favor, intenta de nuevo.</span>
                </div>
                <button onclick="loadProducts()" class="mt-2 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">
                    <i class="fas fa-sync-alt mr-1"></i> Reintentar
                </button>
            </div>
            
            <!-- Products Grid -->
            <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <!-- Products will be loaded here -->
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 text-white">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-4xl font-bold mb-8">¿Por qué elegir Helados Delicia?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white bg-opacity-20 p-6 rounded-lg backdrop-blur-sm">
                    <div class="text-4xl mb-4">🥛</div>
                    <h4 class="text-xl font-semibold mb-2">Ingredientes Naturales</h4>
                    <p>Utilizamos solo los mejores ingredientes naturales y frescos</p>
                </div>
                <div class="bg-white bg-opacity-20 p-6 rounded-lg backdrop-blur-sm">
                    <div class="text-4xl mb-4">🚚</div>
                    <h4 class="text-xl font-semibold mb-2">Entrega Rápida</h4>
                    <p>Entregamos tus helados favoritos en menos de 30 minutos</p>
                </div>
                <div class="bg-white bg-opacity-20 p-6 rounded-lg backdrop-blur-sm">
                    <div class="text-4xl mb-4">⭐</div>
                    <h4 class="text-xl font-semibold mb-2">Calidad Premium</h4>
                    <p>Más de 20 años creando los mejores helados artesanales</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-3xl font-bold text-center text-gray-800 mb-8">📞 Contáctanos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h4 class="text-xl font-semibold mb-4 text-gray-800">Información de Contacto</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-phone text-blue-500 mr-3"></i>
                                <span>+1 (555) 123-4567</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-blue-500 mr-3"></i>
                                <span>info@amaigelato.com</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-3"></i>
                                <span>Calle Principal 123, Ciudad</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-blue-500 mr-3"></i>
                                <span>Lun-Dom: 10:00 AM - 10:00 PM</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h4 class="text-xl font-semibold mb-4 text-gray-800">Síguenos en Redes Sociales</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-blue-500 text-white p-3 rounded-full hover:bg-blue-600 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="bg-pink-500 text-white p-3 rounded-full hover:bg-pink-600 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="bg-blue-400 text-white p-3 rounded-full hover:bg-blue-500 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="bg-red-500 text-white p-3 rounded-full hover:bg-red-600 transition-colors">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="flex justify-center items-center space-x-2 mb-4">
                <span class="text-2xl">🍦</span>
                <span class="text-xl font-bold">Amai Gelato</span>
            </div>
            <p class="text-gray-400 mb-4">Los mejores helados artesanales de la ciudad</p>
            <p class="text-sm text-gray-500">&copy; 2025 Amai Gelato. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Cart Modal -->
    <div id="cart-modal" class="fixed inset-0 bg-black bg-opacity-50 modal-overlay hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold flex items-center">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Tu Carrito de Compras
                        </h3>
                        <button onclick="closeCart()" class="text-white hover:text-gray-200 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div id="cart-items">
                        <!-- Cart items will be displayed here -->
                    </div>
                </div>
                
                <div class="border-t p-6 bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">Subtotal:</span>
                        <span id="cart-subtotal" class="text-xl font-bold text-green-600">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">Envío:</span>
                        <span class="text-xl font-bold text-blue-600">$25.00</span>
                    </div>
                    <div class="flex justify-between items-center mb-6 text-xl font-bold border-t pt-4">
                        <span>Total:</span>
                        <span id="cart-total" class="text-green-600">$25.00</span>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button onclick="closeCart()" class="flex-1 bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors font-medium">
                            Seguir Comprando
                        </button>
                        <button onclick="proceedToCheckout()" class="flex-1 bg-gradient-to-r from-green-500 to-blue-500 text-white py-3 px-6 rounded-lg hover:from-green-600 hover:to-blue-600 transition-all font-medium">
                            <i class="fas fa-credit-card mr-2"></i>
                            Proceder al Pago
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 modal-overlay hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold flex items-center">
                            <i class="fas fa-truck mr-2"></i>
                            Información de Entrega
                        </h3>
                        <button onclick="closeCheckout()" class="text-white hover:text-gray-200 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <form id="delivery-form" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                            <input type="text" name="customerName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                            <input type="tel" name="customerPhone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email (Opcional)</label>
                            <input type="email" name="customerEmail" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección de Entrega *</label>
                            <textarea name="deliveryAddress" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Entrega *</label>
                            <input type="date" name="deliveryDate" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Entrega *</label>
                            <select name="deliveryTime" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Seleccionar hora</option>
                                <option value="10:00-12:00">10:00 AM - 12:00 PM</option>
                                <option value="12:00-14:00">12:00 PM - 2:00 PM</option>
                                <option value="14:00-16:00">2:00 PM - 4:00 PM</option>
                                <option value="16:00-18:00">4:00 PM - 6:00 PM</option>
                                <option value="18:00-20:00">6:00 PM - 8:00 PM</option>
                                <option value="20:00-22:00">8:00 PM - 10:00 PM</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Instrucciones Especiales</label>
                            <textarea name="specialInstructions" rows="3" placeholder="Ej: Tocar el timbre, casa azul, etc." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-semibold mb-4">Resumen del Pedido</h4>
                        <div id="checkout-items" class="space-y-2 mb-4">
                            <!-- Items will be displayed here -->
                        </div>
                        <div class="border-t pt-4">
                            <div class="flex justify-between mb-2">
                                <span>Subtotal:</span>
                                <span id="checkout-subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Envío:</span>
                                <span>$25.00</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span id="checkout-total">$25.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4 mt-8">
                        <button type="button" onclick="closeCheckout()" class="flex-1 bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 transition-colors font-medium">
                            Cancelar
                        </button>
                        <button type="submit" id="submit-order-btn" class="flex-1 bg-gradient-to-r from-green-500 to-blue-500 text-white py-3 px-6 rounded-lg hover:from-green-600 hover:to-blue-600 transition-all font-medium">
                            <i class="fas fa-check mr-2"></i>
                            Confirmar Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receipt-modal" class="fixed inset-0 bg-black bg-opacity-50 modal-overlay hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-6 text-center">
                    <div class="text-6xl mb-4">🎉</div>
                    <h3 class="text-2xl font-bold">¡Pedido Confirmado!</h3>
                    <p class="mt-2">Tu pedido ha sido procesado exitosamente</p>
                </div>
                
                <div class="p-6">
                    <div id="receipt-content">
                        <!-- Receipt content will be generated here -->
                    </div>
                    
                    <div class="flex space-x-4 mt-8">
                        <button onclick="downloadPDF()" class="flex-1 bg-blue-500 text-white py-3 px-6 rounded-lg hover:bg-blue-600 transition-colors font-medium">
                            <i class="fas fa-download mr-2"></i>
                            Descargar PDF
                        </button>
                        <button onclick="closeReceipt()" class="flex-1 bg-green-500 text-white py-3 px-6 rounded-lg hover:bg-green-600 transition-colors font-medium">
                            <i class="fas fa-home mr-2"></i>
                            Continuar Comprando
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Loading indicator -->
    <div id="loading" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span>Cargando...</span>
        </div>
    </div>

    <!-- Error message -->
    <div id="error-message" class="hidden fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-exclamation-circle"></i>
            <span id="error-text">Error de conexión</span>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/cart-integration.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="/js/frontend.js"></script>
    <script>
        // Función para mostrar notificaciones
        function showNotification(message, type = 'success') {
            // Tu implementación existente de notificaciones
            console.log(`${type.toUpperCase()}: ${message}`);
        }

        // Función para mostrar/ocultar loading
        function showLoading(show) {
            const loadingElement = document.getElementById('loading');
            if (show) {
                loadingElement.classList.remove('hidden');
            } else {
                loadingElement.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
