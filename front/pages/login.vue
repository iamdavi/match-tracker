<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sign in to your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or
          <NuxtLink to="/register" class="font-medium text-primary-600 hover:text-primary-500">
            create a new account
          </NuxtLink>
        </p>
      </div>
      
      <UCard>
        <UForm :state="formState" @submit="handleLogin">
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
          
          <div class="flex items-center justify-between">
            <UCheckbox
              v-model="formState.rememberMe"
              label="Remember me"
            />
            <NuxtLink
              to="/forgot-password"
              class="text-sm font-medium text-primary-600 hover:text-primary-500"
            >
              Forgot your password?
            </NuxtLink>
          </div>
          
          <UButton
            type="submit"
            :loading="loading"
            :disabled="loading"
            class="w-full"
          >
            Sign in
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
    </div>
  </div>
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
    
    if (result.success) {
      await router.push('/')
    } else {
      error.value = result.error || 'Login failed'
    }
  } catch {
    error.value = 'An unexpected error occurred'
  } finally {
    loading.value = false
  }
}
</script> 