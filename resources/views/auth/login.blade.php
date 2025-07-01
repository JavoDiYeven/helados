<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍦 Amai Gelato - Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .ice-cream-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 2px, transparent 2px);
            background-size: 50px 50px;
        }
        
        .form-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group input {
            transition: all 0.3s ease;
        }
        
        .input-group input:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            max-width: 350px;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .notification.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        .notification.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .loading-spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .floating-icons {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .password-toggle {
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .remember-checkbox:checked {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login-card {
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg ice-cream-pattern flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Floating Icons -->
    <div class="floating-icons" style="top: 10%; left: 10%; animation-delay: 0s;">🍦</div>
    <div class="floating-icons" style="top: 20%; right: 15%; animation-delay: 1s;">🍨</div>
    <div class="floating-icons" style="bottom: 30%; left: 20%; animation-delay: 2s;">🧁</div>
    <div class="floating-icons" style="bottom: 20%; right: 10%; animation-delay: 3s;">🍰</div>
    <div class="floating-icons" style="top: 50%; left: 5%; animation-delay: 4s;">🍓</div>
    <div class="floating-icons" style="top: 60%; right: 5%; animation-delay: 5s;">🥛</div>

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <!-- Login Card -->
    <div class="form-container login-card rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🍦</div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Amai Gelato</h1>
            <p class="text-gray-600">Inicia sesión para continuar</p>
        </div>

        <!-- Login Form -->
        <form id="login-form" class="space-y-6">
            <!-- Email Field -->
            <div class="input-group">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                    Correo Electrónico
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                    placeholder="tu@email.com"
                    autocomplete="email"
                >
                <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Password Field -->
            <div class="input-group">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-blue-500"></i>
                    Contraseña
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        placeholder="••••••••"
                        autocomplete="current-password"
                    >
                    <button 
                        type="button" 
                        id="toggle-password" 
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 password-toggle text-gray-500"
                    >
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div id="password-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="remember-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                </label>
                <a href="#" onclick="showForgotPassword()" class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <!-- Login Button -->
            <button 
                type="submit" 
                id="login-btn"
                class="w-full btn-login text-white py-3 px-6 rounded-lg font-medium text-lg flex items-center justify-center space-x-2"
            >
                <span id="login-text">Iniciar Sesión</span>
                <div id="login-spinner" class="loading-spinner hidden"></div>
            </button>
        </form>

        <!-- Divider -->
        <div class="my-8 flex items-center">
            <div class="flex-1 border-t border-gray-300"></div>
            <span class="px-4 text-gray-500 text-sm">o</span>
            <div class="flex-1 border-t border-gray-300"></div>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-gray-600 mb-4">¿No tienes una cuenta?</p>
            <button 
                onclick="showRegisterForm()" 
                class="w-full bg-white border-2 border-blue-500 text-blue-500 py-3 px-6 rounded-lg font-medium hover:bg-blue-50 transition-colors"
            >
                <i class="fas fa-user-plus mr-2"></i>
                Crear Cuenta Nueva
            </button>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h4 class="text-sm font-semibold text-yellow-800 mb-2">
                <i class="fas fa-info-circle mr-1"></i>
                Credenciales de Prueba:
            </h4>
            <div class="text-xs text-yellow-700 space-y-1">
                <p><strong>Admin:</strong> admin@heladosdelicia.com / admin123</p>
                <p><strong>Cliente:</strong> cliente@test.com / cliente123</p>
            </div>
            <button 
                onclick="fillDemoCredentials('admin')" 
                class="mt-2 text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-300 transition-colors mr-2"
            >
                Usar Admin
            </button>
            <button 
                onclick="fillDemoCredentials('cliente')" 
                class="mt-2 text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-300 transition-colors"
            >
                Usar Cliente
            </button>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="register-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="form-container rounded-2xl shadow-2xl p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="text-center mb-6">
                <div class="text-4xl mb-2">👤</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Crear Cuenta</h2>
                <p class="text-gray-600">Únete a Amai Gelato</p>
            </div>

            <form id="register-form" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            placeholder="Juan"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                        <input 
                            type="text" 
                            name="apellido" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            placeholder="Pérez"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        placeholder="juan@email.com"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input 
                        type="tel" 
                        name="telefono" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        placeholder="+1 234 567 8900"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            minlength="6"
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility(this)" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-blue-500"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms" 
                        required 
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        Acepto los <a href="#" class="text-blue-600 hover:underline">términos y condiciones</a>
                    </label>
                </div>

                <div class="flex space-x-4 pt-4">
                    <button 
                        type="button" 
                        onclick="closeRegisterModal()" 
                        class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 btn-login text-white py-2 px-4 rounded-lg font-medium"
                    >
                        Registrarse
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="/js/login.js"></script>
</body>
</html>
