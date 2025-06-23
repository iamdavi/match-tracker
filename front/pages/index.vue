<template>
  <v-app>
    <v-app-bar color="primary" dark>
      <v-app-bar-title>Match Tracker</v-app-bar-title>
      <v-spacer />
      <div v-if="user" class="text-body-2 mr-4">
        Welcome, {{ user.email }}
      </div>
      <v-btn v-if="isAuthenticated" @click="handleLogout" color="error" variant="outlined">
        Sign out
      </v-btn>
    </v-app-bar>
    <v-main>
      <v-container fluid>
        <v-row justify="center">
          <v-col cols="12" md="8">
            <v-card class="pa-8 text-center">
              <v-card-title class="text-h4 mb-4">
                Welcome to Match Tracker
              </v-card-title>
              <v-card-text class="text-body-1 mb-6">
                This is your dashboard. You are successfully authenticated!
              </v-card-text>

              <v-card v-if="user" class="mx-auto" max-width="400" variant="outlined">
                <v-card-title class="text-h6">User Information</v-card-title>
                <v-card-text>
                  <v-list>
                    <v-list-item>
                      <v-list-item-title><strong>ID:</strong> {{ user.id }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-title><strong>Email:</strong> {{ user.email }}</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-title><strong>Roles:</strong> {{ user.roles.join(', ') }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-card-text>
              </v-card>

              <div v-else class="text-body-2 text-medium-emphasis">
                Loading user information...
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
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