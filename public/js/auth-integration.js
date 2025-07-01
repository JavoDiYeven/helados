// Integración de autenticación actualizada
document.addEventListener("DOMContentLoaded", async () => {
  // Verificar estado de autenticación al cargar
  await checkAuthStatus()

  // Configurar interceptor para manejar errores 401
  setupAuthInterceptor()
})

async function checkAuthStatus() {
  if (window.apiUtils.isAuthenticated()) {
    try {
      const user = await window.apiUtils.getCurrentUser()
      if (user) {
        updateUIForAuthenticatedUser(user)
      } else {
        // Token inválido, limpiar
        await window.apiUtils.logout()
        updateUIForGuestUser()
      }
    } catch (error) {
      console.warn("Error verificando autenticación:", error)
      updateUIForGuestUser()
    }
  } else {
    updateUIForGuestUser()
  }
}

function updateUIForAuthenticatedUser(user) {
  // Actualizar UI para usuario autenticado
  const userElements = document.querySelectorAll("[data-user-name]")
  userElements.forEach((el) => (el.textContent = user.name))

  const authElements = document.querySelectorAll('[data-auth="true"]')
  authElements.forEach((el) => (el.style.display = "block"))

  const guestElements = document.querySelectorAll('[data-auth="false"]')
  guestElements.forEach((el) => (el.style.display = "none"))

  console.log("✅ Usuario autenticado:", user.name)
}

function updateUIForGuestUser() {
  const authElements = document.querySelectorAll('[data-auth="true"]')
  authElements.forEach((el) => (el.style.display = "none"))

  const guestElements = document.querySelectorAll('[data-auth="false"]')
  guestElements.forEach((el) => (el.style.display = "block"))

  console.log("👤 Usuario no autenticado")
}

function setupAuthInterceptor() {
  // Interceptar errores 401 globalmente
  const originalFetch = window.fetch

  window.fetch = async function (...args) {
    const response = await originalFetch.apply(this, args)

    if (response.status === 401) {
      console.warn("Token expirado o inválido")
      await window.apiUtils.logout()

      // Redirigir a login si no estamos ya ahí
      if (!window.location.pathname.includes("login")) {
        window.location.href = "/login.html"
      }
    }

    return response
  }
}

// Función para requerir autenticación en páginas protegidas
function requireAuth() {
  if (!window.apiUtils.isAuthenticated()) {
    window.location.href = "/login.html"
    return false
  }
  return true
}

// Exportar funciones
window.authIntegration = {
  checkAuthStatus,
  requireAuth,
  updateUIForAuthenticatedUser,
  updateUIForGuestUser,
}
