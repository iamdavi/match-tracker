<template>
  <v-container fluid class="fill-height">
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>Create your account</v-toolbar-title>
          </v-toolbar>

          <v-card-text>
            <p class="text-center text-body-2 mb-4">
              Or
              <NuxtLink to="/login" class="text-decoration-none">
                sign in to your existing account
              </NuxtLink>
            </p>

            <v-form @submit.prevent="handleRegister">
              <v-text-field v-model="formState.email" label="Email" name="email" prepend-icon="mdi-email" type="email"
                variant="outlined" required />

              <v-text-field v-model="formState.password" label="Password" name="password" prepend-icon="mdi-lock"
                type="password" variant="outlined" required />

              <v-text-field v-model="formState.confirmPassword" label="Confirm Password" name="confirmPassword"
                prepend-icon="mdi-lock-check" type="password" variant="outlined" required />

              <v-btn type="submit" color="primary" block :loading="loading" :disabled="loading" size="large">
                Create account
              </v-btn>
            </v-form>
          </v-card-text>
        </v-card>

        <v-alert v-if="error" type="error" variant="tonal" class="mt-4">
          {{ error }}
        </v-alert>

        <v-alert v-if="success" type="success" variant="tonal" class="mt-4">
          {{ success }}
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

const formState = ref({
  email: '',
  password: '',
  confirmPassword: ''
})

const loading = ref(false)
const error = ref('')
const success = ref('')

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  success.value = ''

  // Validate passwords match
  if (formState.value.password !== formState.value.confirmPassword) {
    error.value = 'Passwords do not match'
    loading.value = false
    return
  }

  // Validate password length
  if (formState.value.password.length < 6) {
    error.value = 'Password must be at least 6 characters long'
    loading.value = false
    return
  }

  try {
    const result = await authStore.register(formState.value.email, formState.value.password)

    if (result?.success) {
      success.value = 'Account created successfully! You can now sign in.'
      formState.value = {
        email: '',
        password: '',
        confirmPassword: ''
      }
    } else {
      error.value = result?.error || 'Registration failed'
    }
  } catch {
    error.value = 'An unexpected error occurred'
  } finally {
    loading.value = false
  }
}
</script>