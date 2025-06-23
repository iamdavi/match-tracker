// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: true },

  modules: [
    '@nuxt/content',
    '@nuxt/eslint',
    '@nuxt/fonts',
    '@nuxt/icon',
    '@nuxt/image',
    '@nuxt/scripts',
    '@pinia/nuxt'
  ],

  css: [
    '@mdi/font/css/materialdesignicons.css',
    'vuetify/styles'
  ],

  build: {
    transpile: ['vuetify']
  },

  vite: {
    define: {
      'process.env.DEBUG': false,
    },
  },

  runtimeConfig: {
    public: {
      apiBase: 'http://localhost:8000'
    }
  }
})