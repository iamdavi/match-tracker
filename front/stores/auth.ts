import { defineStore } from 'pinia'

interface User {
  id: number
  email: string
  roles: string[]
}

interface LoginResponse {
  token: string
  refresh_token?: string
}

interface RegisterResponse {
  message: string
  user: {
    id: number
    email: string
  }
}

interface UserResponse {
  user: User
}

interface ForgotPasswordResponse {
  message: string
}

interface AuthState {
  user: User | null
  token: string | null
  refreshToken: string | null
  isAuthenticated: boolean
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: null,
    refreshToken: null,
    isAuthenticated: false
  }),

  getters: {
    getUser: (state) => state.user,
    getToken: (state) => state.token,
    getIsAuthenticated: (state) => state.isAuthenticated
  },

  actions: {
    async login(email: string, password: string) {
      try {
        const config = useRuntimeConfig()
        const response = await $fetch<LoginResponse>(`${config.public.apiBase}/api/login_check`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: {
            email,
            password
          }
        })

        if (response.token) {
          this.token = response.token
          this.refreshToken = response.refresh_token || null
          this.isAuthenticated = true
          
          // Get user info
          await this.fetchUser()
          
          // Store tokens in localStorage
          if (import.meta.client) {
            localStorage.setItem('token', this.token)
            if (this.refreshToken) {
              localStorage.setItem('refreshToken', this.refreshToken)
            }
          }
          
          return { success: true }
        }
      } catch (error: unknown) {
        console.error('Login error:', error)
        const errorData = error as { data?: { message?: string } }
        return { 
          success: false, 
          error: errorData.data?.message || 'Login failed' 
        }
      }
    },

    async register(email: string, password: string) {
      try {
        const config = useRuntimeConfig()
        const response = await $fetch<RegisterResponse>(`${config.public.apiBase}/api/register`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: {
            email,
            password
          }
        })

        return { success: true, data: response }
      } catch (error: unknown) {
        console.error('Register error:', error)
        const errorData = error as { data?: { message?: string } }
        return { 
          success: false, 
          error: errorData.data?.message || 'Registration failed' 
        }
      }
    },

    async fetchUser() {
      try {
        if (!this.token) {
          return { success: false, error: 'No token available' }
        }

        const config = useRuntimeConfig()
        const response = await $fetch<UserResponse>(`${config.public.apiBase}/api/me`, {
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        })

        this.user = response.user
        return { success: true, user: response.user }
      } catch (error: unknown) {
        console.error('Fetch user error:', error)
        const errorData = error as { status?: number; data?: { message?: string } }
        if (errorData.status === 401) {
          await this.refreshAuth()
        }
        return { success: false, error: errorData.data?.message || 'Failed to fetch user' }
      }
    },

    async refreshAuth() {
      if (!this.refreshToken) {
        this.logout()
        return false
      }

      try {
        const config = useRuntimeConfig()
        const response = await $fetch<LoginResponse>(`${config.public.apiBase}/api/token/refresh`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: {
            refresh_token: this.refreshToken
          }
        })

        if (response.token) {
          this.token = response.token
          this.refreshToken = response.refresh_token || this.refreshToken
          
          if (import.meta.client) {
            localStorage.setItem('token', this.token)
            if (this.refreshToken) {
              localStorage.setItem('refreshToken', this.refreshToken)
            }
          }
          
          return true
        }
      } catch (error) {
        console.error('Refresh token error:', error)
        this.logout()
        return false
      }
    },

    async forgotPassword(email: string) {
      try {
        const config = useRuntimeConfig()
        const response = await $fetch<ForgotPasswordResponse>(`${config.public.apiBase}/api/forgot-password`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: {
            email
          }
        })

        return { success: true, message: response.message }
      } catch (error: unknown) {
        console.error('Forgot password error:', error)
        const errorData = error as { data?: { message?: string } }
        return { 
          success: false, 
          error: errorData.data?.message || 'Failed to send reset email' 
        }
      }
    },

    logout() {
      this.user = null
      this.token = null
      this.refreshToken = null
      this.isAuthenticated = false
      
      if (import.meta.client) {
        localStorage.removeItem('token')
        localStorage.removeItem('refreshToken')
      }
    },

    initAuth() {
      if (import.meta.client) {
        const token = localStorage.getItem('token')
        const refreshToken = localStorage.getItem('refreshToken')
        
        if (token) {
          this.token = token
          this.refreshToken = refreshToken
          this.isAuthenticated = true
          this.fetchUser()
        }
      }
    }
  }
}) 