<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Reset your password
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Enter your email address and we'll send you a link to reset your password.
        </p>
      </div>
      
      <UCard>
        <UForm :state="formState" @submit="handleForgotPassword">
          <UFormGroup label="Email" name="email">
            <UInput
              v-model="formState.email"
              type="email"
              placeholder="Enter your email"
              required
            />
          </UFormGroup>
          
          <UButton
            type="submit"
            :loading="loading"
            :disabled="loading"
            class="w-full"
          >
            Send reset link
          </UButton>
        </UForm>
      </UCard>
      
      <div class="text-center">
        <NuxtLink
          to="/login"
          class="font-medium text-primary-600 hover:text-primary-500"
        >
          Back to sign in
        </NuxtLink>
      </div>
      
      <UAlert
        v-if="error"
        color="red"
        variant="soft"
        :title="error"
        class="mt-4"
      />
      
      <UAlert
        v-if="success"
        color="green"
        variant="soft"
        :title="success"
        class="mt-4"
      />
    </div>
  </div>
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
    
    if (result.success) {
      success.value = result.message || 'Reset link sent successfully!'
      formState.value.email = ''
    } else {
      error.value = result.error || 'Failed to send reset link'
    }
  } catch {
    error.value = 'An unexpected error occurred'
  } finally {
    loading.value = false
  }
}
</script> 