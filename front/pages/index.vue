<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-semibold text-gray-900">
              Match Tracker
            </h1>
          </div>
          <div class="flex items-center space-x-4">
            <div v-if="user" class="text-sm text-gray-700">
              Welcome, {{ user.email }}
            </div>
            <UButton
              v-if="isAuthenticated"
              @click="handleLogout"
              color="red"
              variant="soft"
            >
              Sign out
            </UButton>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 flex items-center justify-center">
          <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
              Welcome to Match Tracker
            </h2>
            <p class="text-gray-600 mb-6">
              This is your dashboard. You are successfully authenticated!
            </p>
            
            <div v-if="user" class="bg-white p-6 rounded-lg shadow-sm max-w-md mx-auto">
              <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div><strong>ID:</strong> {{ user.id }}</div>
                <div><strong>Email:</strong> {{ user.email }}</div>
                <div><strong>Roles:</strong> {{ user.roles.join(', ') }}</div>
              </div>
            </div>
            
            <div v-else class="text-gray-500">
              Loading user information...
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const authStore = useAuthStore()
const router = useRouter()

const user = computed(() => authStore.getUser)
const isAuthenticated = computed(() => authStore.getIsAuthenticated)

const handleLogout = async () => {
  authStore.logout()
  await router.push('/login')
}

// Initialize auth when component mounts
onMounted(() => {
  if (import.meta.client) {
    authStore.initAuth()
  }
})
</script> 