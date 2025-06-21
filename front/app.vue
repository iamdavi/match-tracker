<template>
  <div>
    <NuxtPage />
  </div>
</template>

<script setup lang="ts">
// Apply auth middleware globally
definePageMeta({
  middleware: 'auth'
})
</script>
