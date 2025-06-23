<template>
  <v-container fluid class="fill-height">
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>Reset your password</v-toolbar-title>
          </v-toolbar>

          <v-card-text>
            <p class="text-center text-body-2 mb-4">
              Enter your email address and we'll send you a link to reset your password.
            </p>

            <v-form @submit.prevent="handleForgotPassword">
              <v-text-field v-model="formState.email" label="Email" name="email" prepend-icon="mdi-email" type="email"
                variant="outlined" required />

              <v-btn type="submit" color="primary" block :loading="loading" :disabled="loading" size="large">
                Send reset link
              </v-btn>
            </v-form>

            <div class="text-center mt-4">
              <NuxtLink to="/login" class="text-decoration-none">
                Back to sign in
              </NuxtLink>
            </div>
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
  email: ''
})

const loading = ref(false)
const error = ref('')
const success = ref('')

const handleForgotPassword = async () => {
  loading.value = true
  error.value = ''
  success.value = ''

  try {
    const result = await authStore.forgotPassword(formState.value.email)

    if (result?.success) {
      success.value = result.message || 'Reset link sent successfully!'
      formState.value.email = ''
    } else {
      error.value = result?.error || 'Failed to send reset link'
    }
  } catch {
    error.value = 'An unexpected error occurred'
  } finally {
    loading.value = false
  }
}
</script>