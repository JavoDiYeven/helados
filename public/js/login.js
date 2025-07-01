// Configuración
const API_BASE_URL = window.location.origin
const REDIRECT_URL = "/admin/dashboard" // Página principal después del login

// Usuarios demo (en producción esto vendría de la base de datos)
const DEMO_USERS = {
  "admin@amaigelato.com": {
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

// Inicialización segura
document.addEventListener("DOMContentLoaded", () => {
  try {
    console.log("🚀 Inicializando página de login...")

    // Verificar si ya está logueado (con timeout para evitar bloqueos)
    setTimeout(() => {
      if (isLoggedIn()) {
        console.log("✅ Usuario ya autenticado, redirigiendo...")
        showNotification("Ya tienes una sesión activa", "info")
        setTimeout(redirectToMain, 1500)
        return
      }

      // Configurar event listeners solo si no está logueado
      setupEventListeners()
      console.log("✅ Página de login inicializada")
    }, 100)
  } catch (error) {
    console.error("❌ Error en inicialización:", error)
    // Continuar con la carga normal
    setupEventListeners()
  }
})

// Configurar event listeners
function setupEventListeners() {
  try {
    const loginForm = document.getElementById("login-form")
    const registerForm = document.getElementById("register-form")
    const togglePassword = document.getElementById("toggle-password")
    const registerModal = document.getElementById("register-modal")

    if (loginForm) {
      loginForm.addEventListener("submit", handleLogin)
    }

    if (registerForm) {
      registerForm.addEventListener("submit", handleRegister)
    }

    if (togglePassword) {
      togglePassword.addEventListener("click", function () {
        const input = this.previousElementSibling
        const icon = this.querySelector("i")

        if (input && icon) {
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
      })
    }

    // Cerrar modal al hacer clic fuera
    if (registerModal) {
      registerModal.addEventListener("click", function (e) {
        if (e.target === this) {
          closeRegisterModal()
        }
      })
    }
  } catch (error) {
    console.error("Error configurando event listeners:", error)
  }
}

// Función para mostrar notificaciones
function showNotification(message, type = "success") {
  try {
    const notification = document.createElement("div")
    notification.className = `notification ${type}`
    notification.innerHTML = `
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <i class="fas fa-${type === "success" ? "check-circle" : type === "error" ? "exclamation-circle" : "info-circle"} mr-2"></i>
          <span>${message}</span>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
          <i class="fas fa-times"></i>
        </button>
      </div>
    `

    const container = document.getElementById("notification-container") || document.body
    container.appendChild(notification)

    // Mostrar notificación
    setTimeout(() => notification.classList.add("show"), 100)

    // Auto-remover después de 5 segundos
    setTimeout(() => {
      notification.classList.remove("show")
      setTimeout(() => {
        if (notification.parentNode) {
          notification.remove()
        }
      }, 300)
    }, 5000)
  } catch (error) {
    console.error("Error mostrando notificación:", error)
    // Fallback: usar alert
    alert(`${type.toUpperCase()}: ${message}`)
  }
}

// Función para manejar el login
async function handleLogin(e) {
  e.preventDefault()

  try {
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

    // Simular delay de red
    await new Promise((resolve) => setTimeout(resolve, 1000))

    // Verificar credenciales
    const loginResult = await authenticateUser(email, password)

    if (loginResult.success) {
      // Guardar sesión
      saveUserSession(loginResult.user, remember)
      showNotification(`¡Bienvenido ${loginResult.user.name}!`, "success")

      // Redirigir después de un breve delay
      setTimeout(() => {
        redirectToMain()
      }, 1500)
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
    // En producción, esto sería una llamada a tu API Laravel
    /*
    const response = await fetch(`${API_BASE_URL}/api/login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
    });
    
    return await response.json();
    */

    // Simulación para demo
    const user = DEMO_USERS[email]

    if (!user) {
      return {
        success: false,
        message: "Usuario no encontrado",
      }
    }

    if (user.password !== password) {
      return {
        success: false,
        message: "Contraseña incorrecta",
      }
    }

    return {
      success: true,
      user: {
        id: user.id,
        email: email,
        name: user.name,
        role: user.role,
      },
      token: "demo_token_" + Date.now(),
    }
  } catch (error) {
    console.error("Error en autenticación:", error)
    return {
      success: false,
      message: "Error de conexión",
    }
  }
}

// Función para manejar el registro
async function handleRegister(e) {
  e.preventDefault()

  try {
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

    // Simular registro
    await new Promise((resolve) => setTimeout(resolve, 1500))

    // Simulación exitosa
    showNotification("¡Cuenta creada exitosamente! Ahora puedes iniciar sesión.", "success")
    closeRegisterModal()

    // Llenar el formulario de login con los datos del registro
    const emailInput = document.getElementById("email")
    if (emailInput) {
      emailInput.value = data.email
    }
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

  if (errorElement) {
    errorElement.textContent = message
    errorElement.classList.remove("hidden")
  }

  if (inputElement) {
    inputElement.classList.add("border-red-500")
  }
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

  if (btn && text && spinner) {
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
}

// Funciones de sesión
function saveUserSession(user, remember) {
  try {
    const storage = remember ? localStorage : sessionStorage
    storage.setItem("user", JSON.stringify(user))
    storage.setItem("isLoggedIn", "true")
    storage.setItem("loginTime", Date.now().toString())
  } catch (error) {
    console.error("Error guardando sesión:", error)
  }
}

function isLoggedIn() {
  try {
    const sessionUser = sessionStorage.getItem("isLoggedIn")
    const localUser = localStorage.getItem("isLoggedIn")
    return sessionUser === "true" || localUser === "true"
  } catch (error) {
    console.error("Error verificando sesión:", error)
    return false
  }
}

function getCurrentUser() {
  try {
    const sessionUser = sessionStorage.getItem("user")
    const localUser = localStorage.getItem("user")

    if (sessionUser) return JSON.parse(sessionUser)
    if (localUser) return JSON.parse(localUser)
    return null
  } catch (error) {
    console.error("Error obteniendo usuario:", error)
    return null
  }
}

function logout() {
  try {
    sessionStorage.clear()
    localStorage.removeItem("user")
    localStorage.removeItem("isLoggedIn")
    localStorage.removeItem("loginTime")
    window.location.href = "/login.html"
  } catch (error) {
    console.error("Error en logout:", error)
    // Forzar recarga de página
    window.location.reload()
  }
}

function redirectToMain() {
  try {
    console.log("🔄 Redirigiendo a:", REDIRECT_URL)
    window.location.href = REDIRECT_URL
  } catch (error) {
    console.error("❌ Error en redirección:", error)
    // Fallback: intentar ir a la raíz
    try {
      window.location.href = "/"
    } catch (fallbackError) {
      console.error("❌ Error en fallback:", fallbackError)
      // Último recurso: recargar la página
      window.location.reload()
    }
  }
}

// Funciones del modal de registro
function showRegisterForm() {
  const modal = document.getElementById("register-modal")
  if (modal) {
    modal.classList.remove("hidden")
    document.body.style.overflow = "hidden"
  }
}

function closeRegisterModal() {
  const modal = document.getElementById("register-modal")
  if (modal) {
    modal.classList.add("hidden")
    document.body.style.overflow = "auto"
  }
}

// Función para llenar credenciales demo
function fillDemoCredentials(type) {
  try {
    const emailInput = document.getElementById("email")
    const passwordInput = document.getElementById("password")

    if (emailInput && passwordInput) {
      if (type === "admin") {
        emailInput.value = "admin@heladosdelicia.com"
        passwordInput.value = "admin123"
      } else if (type === "cliente") {
        emailInput.value = "cliente@test.com"
        passwordInput.value = "cliente123"
      }

      showNotification(`Credenciales de ${type} cargadas`, "success")
    }
  } catch (error) {
    console.error("Error llenando credenciales:", error)
  }
}

function showForgotPassword() {
  showNotification("Función de recuperación de contraseña en desarrollo", "warning")
}

// Función para verificar sesión en otras páginas
function requireAuth() {
  if (!isLoggedIn()) {
    window.location.href = "/login.html"
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

console.log("✅ Login.js cargado correctamente")
