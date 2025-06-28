// Configuración
const API_BASE_URL = "http://127.0.0.1:8000" // Cambia por tu URL de Laravel
const REDIRECT_URL = "/backend/dashboard" // Página principal después del login

// Usuarios demo (en producción esto vendría de la base de datos)
const DEMO_USERS = {
  "admin@heladosdelicia.com": {
    password: "admin123",
    role: "admin",
    name: "Administrador",
    id: 1,
  },
  "cliente@test.com": {
    password: "cliente123",
    role: "cliente",
    name: "Cliente Demo",
    id: 2,
  },
}

// Inicialización
document.addEventListener("DOMContentLoaded", () => {
  // Verificar si ya está logueado
  if (isLoggedIn()) {
    redirectToMain()
    return
  }

  // Event listeners
  document.getElementById("login-form").addEventListener("submit", handleLogin)
  document.getElementById("register-form").addEventListener("submit", handleRegister)
  document.getElementById("toggle-password").addEventListener("click", togglePassword)

  // Cerrar modal al hacer clic fuera
  document.getElementById("register-modal").addEventListener("click", function (e) {
    if (e.target === this) {
      closeRegisterModal()
    }
  })
})

// Función para mostrar notificaciones
function showNotification(message, type = "success") {
  const notification = document.createElement("div")
  notification.className = `notification ${type}`
  notification.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-${type === "success" ? "check-circle" : type === "error" ? "exclamation-circle" : "exclamation-triangle"} mr-2"></i>
                <span>${message}</span>
            </div>
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

// Función para manejar el login
async function handleLogin(e) {
  e.preventDefault()

  const email = document.getElementById("email").value.trim()
  const password = document.getElementById("password").value
  const remember = document.getElementById("remember").checked

  // Validaciones básicas
  if (!validateEmail(email)) {
    showFieldError("email", "Por favor ingresa un email válido")
    return
  }

  if (password.length < 6) {
    showFieldError("password", "La contraseña debe tener al menos 6 caracteres")
    return
  }

  // Limpiar errores previos
  clearFieldErrors()

  // Mostrar loading
  showLoginLoading(true)

  try {
    // Simular delay de red
    await new Promise((resolve) => setTimeout(resolve, 1000))

    // Verificar credenciales (en producción esto sería una llamada a la API)
    const loginResult = await authenticateUser(email, password)

    if (loginResult.success) {
      // Guardar sesión
      saveUserSession(loginResult.user, remember)
      localStorage.setItem("auth_token", loginResult.token)
      showNotification(`¡Bienvenido ${loginResult.user.name}!`, "success");
      setTimeout(() => {
        redirectToMain();
      }, 1500);
    } else {
      showNotification(loginResult.message, "error")
    }
  } catch (error) {
    console.error("Error en login:", error)
    showNotification("Error de conexión. Intenta de nuevo.", "error")
  } finally {
    showLoginLoading(false)
  }
}

// Función para autenticar usuario (simulada)
async function authenticateUser(email, password) {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const response = await fetch(`${API_BASE_URL}/backend/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken })
      },
      body: JSON.stringify({ email, password })
    });

    const data = await response.json();

    if (!response.ok) {
      return {
        success: false,
        message: data.message || 'Error al iniciar sesión',
        errors: data.errors || null
      };
    }

    return {
      success: true,
      user: data.user,
      token: data.token
    };

  } catch (error) {
    console.error("Error en la autenticación:", error);
    return {
      success: false,
      message: "Error de red o del servidor"
    };
  }
}

// Función para manejar el registro
async function handleRegister(e) {
  e.preventDefault()

  const formData = new FormData(e.target)
  const data = Object.fromEntries(formData)

  // Validaciones
  if (!validateEmail(data.email)) {
    showNotification("Email inválido", "error")
    return
  }

  if (data.password !== data.password_confirmation) {
    showNotification("Las contraseñas no coinciden", "error")
    return
  }

  if (data.password.length < 6) {
    showNotification("La contraseña debe tener al menos 6 caracteres", "error")
    return
  }

  if (!data.terms) {
    showNotification("Debes aceptar los términos y condiciones", "error")
    return
  }

  try {
    // Simular registro
    await new Promise((resolve) => setTimeout(resolve, 1500))

    // En producción, aquí harías la llamada a la API
    /*
        const response = await fetch(`${API_BASE_URL}/api/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        */

    // Simulación exitosa
    showNotification("¡Cuenta creada exitosamente! Ahora puedes iniciar sesión.", "success")
    closeRegisterModal()

    // Llenar el formulario de login con los datos del registro
    document.getElementById("email").value = data.email
  } catch (error) {
    console.error("Error en registro:", error)
    showNotification("Error al crear la cuenta. Intenta de nuevo.", "error")
  }
}

// Funciones de utilidad
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

function showFieldError(fieldName, message) {
  const errorElement = document.getElementById(`${fieldName}-error`)
  const inputElement = document.getElementById(fieldName)

  errorElement.textContent = message
  errorElement.classList.remove("hidden")
  inputElement.classList.add("border-red-500")
}

function clearFieldErrors() {
  const errorElements = document.querySelectorAll('[id$="-error"]')
  const inputElements = document.querySelectorAll("input")

  errorElements.forEach((el) => {
    el.classList.add("hidden")
    el.textContent = ""
  })

  inputElements.forEach((el) => {
    el.classList.remove("border-red-500")
  })
}

function showLoginLoading(show) {
  const btn = document.getElementById("login-btn")
  const text = document.getElementById("login-text")
  const spinner = document.getElementById("login-spinner")

  if (show) {
    text.textContent = "Iniciando sesión..."
    spinner.classList.remove("hidden")
    btn.disabled = true
    btn.classList.add("opacity-75")
  } else {
    text.textContent = "Iniciar Sesión"
    spinner.classList.add("hidden")
    btn.disabled = false
    btn.classList.remove("opacity-75")
  }
}

function togglePassword() {
  const passwordInput = document.getElementById("password")
  const toggleIcon = document.querySelector("#toggle-password i")

  if (passwordInput.type === "password") {
    passwordInput.type = "text"
    toggleIcon.classList.remove("fa-eye")
    toggleIcon.classList.add("fa-eye-slash")
  } else {
    passwordInput.type = "password"
    toggleIcon.classList.remove("fa-eye-slash")
    toggleIcon.classList.add("fa-eye")
  }
}

function togglePasswordVisibility(button) {
  const input = button.previousElementSibling
  const icon = button.querySelector("i")

  if (input.type === "password") {
    input.type = "text"
    icon.classList.remove("fa-eye")
    icon.classList.add("fa-eye-slash")
  } else {
    input.type = "password"
    icon.classList.remove("fa-eye-slash")
    icon.classList.add("fa-eye")
  }
}

// Funciones de sesión
function saveUserSession(user, remember) {
  const storage = remember ? localStorage : sessionStorage
  storage.setItem("user", JSON.stringify(user))
  storage.setItem("isLoggedIn", "true")
  storage.setItem("loginTime", Date.now().toString())
}

function isLoggedIn() {
  const sessionUser = sessionStorage.getItem("isLoggedIn")
  const localUser = localStorage.getItem("isLoggedIn")
  return sessionUser === "true" || localUser === "true"
}

function getCurrentUser() {
  const sessionUser = sessionStorage.getItem("user")
  const localUser = localStorage.getItem("user")

  if (sessionUser) return JSON.parse(sessionUser)
  if (localUser) return JSON.parse(localUser)
  return null
}

function logout() {
  sessionStorage.clear()
  localStorage.removeItem("user")
  localStorage.removeItem("isLoggedIn")
  localStorage.removeItem("loginTime")
  window.location.href = "login.html"
}

function redirectToMain() {
  window.location.href = REDIRECT_URL
}

// Funciones del modal de registro
function showRegisterForm() {
  document.getElementById("register-modal").classList.remove("hidden")
  document.body.style.overflow = "hidden"
}

function closeRegisterModal() {
  document.getElementById("register-modal").classList.add("hidden")
  document.body.style.overflow = "auto"
}

// Función para llenar credenciales demo
function fillDemoCredentials(type) {
  if (type === "admin") {
    document.getElementById("email").value = "admin@amaigelato.com"
    document.getElementById("password").value = "admin123"
  } else if (type === "cliente") {
    document.getElementById("email").value = "cliente@test.com"
    document.getElementById("password").value = "cliente123"
  }

  showNotification(`Credenciales de ${type} cargadas`, "success")
}

function showForgotPassword() {
  showNotification("Función de recuperación de contraseña en desarrollo", "warning")
}

// Función para verificar sesión en otras páginas
function requireAuth() {
  if (!isLoggedIn()) {
    window.location.href = "login.html"
    return false
  }
  return true
}

// Función para obtener token de autenticación
function getAuthToken() {
  const user = getCurrentUser()
  return user ? `Bearer demo_token_${user.id}` : null
}

// Exportar funciones para uso en otras páginas
window.authUtils = {
  requireAuth,
  getCurrentUser,
  logout,
  getAuthToken,
  isLoggedIn,
}