<template>
  <v-app>
    <NuxtPage />
  </v-app>
</template>

<script setup lang="ts">
// Apply auth middleware globally
definePageMeta({
  middleware: 'auth'
})
</script>
