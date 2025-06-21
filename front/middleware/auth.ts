import { useAuthStore } from '~/stores/auth'

export default defineNuxtRouteMiddleware((to) => {
  const authStore = useAuthStore()
  
  // Initialize auth on client side
  if (import.meta.client) {
    authStore.initAuth()
  }
  
  // Public routes that don't require authentication
  const publicRoutes = ['/', '/login', '/register', '/forgot-password']
  
  // Check if the current route is public
  const isPublicRoute = publicRoutes.includes(to.path)
  
  // If not authenticated and trying to access protected route, redirect to login
  if (!authStore.getIsAuthenticated && !isPublicRoute) {
    return navigateTo('/login')
  }
  
  // If authenticated and trying to access login/register, redirect to home
  if (authStore.getIsAuthenticated && (to.path === '/login' || to.path === '/register')) {
    return navigateTo('/')
  }
}) 