<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or
          <NuxtLink to="/login" class="font-medium text-primary-600 hover:text-primary-500">
            sign in to your existing account
          </NuxtLink>
        </p>
      </div>
      
      <UCard>
        <UForm :state="formState" @submit="handleRegister">
          <UFormGroup label="Email" name="email">
            <UInput
              v-model="formState.email"
              type="email"
              placeholder="Enter your email"
              required
            />
          </UFormGroup>
          
          <UFormGroup label="Password" name="password">
            <UInput
              v-model="formState.password"
              type="password"
              placeholder="Enter your password"
              required
            />
          </UFormGroup>
          
          <UFormGroup label="Confirm Password" name="confirmPassword">
            <UInput
              v-model="formState.confirmPassword"
              type="password"
              placeholder="Confirm your password"
              required
            />
          </UFormGroup>
          
          <UButton
            type="submit"
            :loading="loading"
            :disabled="loading"
            class="w-full"
          >
            Create account
          </UButton>
        </UForm>
      </UCard>
      
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
    
    if (result.success) {
      success.value = 'Account created successfully! You can now sign in.'
      formState.value = {
        email: '',
        password: '',
        confirmPassword: ''
      }
    } else {
      error.value = result.error || 'Registration failed'
    }
  } catch {
    error.value = 'An unexpected error occurred'
  } finally {
    loading.value = false
  }
}
</script> 