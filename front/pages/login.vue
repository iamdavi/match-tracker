<template>
  <v-container fluid class="fill-height">
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>Sign in to your account</v-toolbar-title>
          </v-toolbar>

          <v-card-text>
            <p class="text-center text-body-2 mb-4">
              Or
              <NuxtLink to="/register" class="text-decoration-none">
                create a new account
              </NuxtLink>
            </p>

            <v-form @submit.prevent="handleLogin">
              <v-text-field v-model="formState.email" label="Email" name="email" prepend-icon="mdi-email" type="email"
                variant="outlined" required />

              <v-text-field v-model="formState.password" label="Password" name="password" prepend-icon="mdi-lock"
                type="password" variant="outlined" required />

              <div class="d-flex justify-space-between align-center mb-4">
                <v-checkbox v-model="formState.rememberMe" label="Remember me" />
                <NuxtLink to="/forgot-password" class="text-decoration-none text-body-2">
                  Forgot your password?
                </NuxtLink>
              </div>

              <v-btn type="submit" color="primary" block :loading="loading" :disabled="loading" size="large">
                Sign in
              </v-btn>
            </v-form>
          </v-card-text>
        </v-card>

        <v-alert v-if="error" type="error" variant="tonal" class="mt-4">
          {{ error }}
        </v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const authStore = useAuthStore()
const router = useRouter()

const formState = ref({
  email: '',
  password: '',
  rememberMe: false
})

const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    const result = await authStore.login(formState.value.email, formState.value.password)

    if (result?.success) {
      await router.push('/')
    } else {
      error.value = result?.error || 'Login failed'
    }
  } catch {
    error.value = 'An unexpected error occurred'
  } finally {
    loading.value = false
  }
}
</script>