# Match Tracker - Full Stack Authentication Project

Este proyecto es un ejemplo completo de autenticación JWT con Symfony (backend) y Nuxt 3 (frontend).

## Estructura del Proyecto

```
match-tracker-new/
├── back/          # API REST en Symfony
└── front/         # SPA en Nuxt 3
```

## Características

### Backend (Symfony)

- ✅ Symfony 7.3 (versión estable más reciente)
- ✅ Autenticación JWT con `lexik/jwt-authentication-bundle`
- ✅ Refresh tokens con `gesdinet/jwt-refresh-token-bundle`
- ✅ Entidad `User` con campos: `id`, `email`, `password` (hash), `roles`
- ✅ Base de datos MySQL configurada
- ✅ CORS configurado para `http://localhost:3000`
- ✅ Rutas API:
  - `POST /api/register` - Registro de usuario
  - `POST /api/login_check` - Login
  - `POST /api/token/refresh` - Refresh de token
  - `POST /api/forgot-password` - Restablecimiento de contraseña (simulado)
  - `GET /api/me` - Información del usuario autenticado

### Frontend (Nuxt 3)

- ✅ Nuxt 3 con NuxtUI y Tailwind CSS
- ✅ Pinia como store de estado
- ✅ Middleware de autenticación
- ✅ Páginas:
  - `/login` - Formulario de login
  - `/register` - Registro de usuario
  - `/forgot-password` - Restablecimiento de contraseña
  - `/` - Dashboard principal (protegida)
- ✅ Store de autenticación con:
  - Login y registro
  - Manejo de tokens JWT
  - Refresh automático de tokens
  - Persistencia en localStorage

## Instalación y Configuración

### Prerrequisitos

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 8.0+
- Symfony CLI (opcional)

### 1. Configurar la Base de Datos

Crear una base de datos MySQL llamada `match_tracker` y configurar las credenciales en `back/.env`:

```env
DATABASE_HOST=127.0.0.1
DATABASE_USER=match_tracker
DATABASE_PASSWORD=match_tracker
DATABASE_NAME=match_tracker
DATABASE_PORT=3306

DATABASE_URL="mysql://${DATABASE_USER}:${DATABASE_PASSWORD}@${DATABASE_HOST}:${DATABASE_PORT}/${DATABASE_NAME}?serverVersion=8.0.37&charset=utf8mb4"
```

### 2. Configurar el Backend

```bash
cd back

# Instalar dependencias
composer install

# Crear la base de datos
php bin/console doctrine:database:create

# Ejecutar migraciones
php bin/console doctrine:migrations:migrate

# Generar claves JWT (si no las tienes ya)
# En Ubuntu/Linux:
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

# Configurar permisos
chmod 644 config/jwt/public.pem
chmod 600 config/jwt/private.pem
```

### 3. Configurar el Frontend

```bash
cd front

# Instalar dependencias
npm install

# Configurar la URL del backend en nuxt.config.ts
# (ya está configurado para http://localhost:8000)
```

## Ejecución

### Backend

```bash
cd back
symfony serve
# O alternativamente:
php -S localhost:8000 -t public
```

El backend estará disponible en: http://localhost:8000

### Frontend

```bash
cd front
npm run dev
```

El frontend estará disponible en: http://localhost:3000

## Uso

1. **Registro**: Ve a http://localhost:3000/register y crea una cuenta
2. **Login**: Ve a http://localhost:3000/login e inicia sesión
3. **Dashboard**: Una vez autenticado, serás redirigido al dashboard principal
4. **Logout**: Usa el botón "Sign out" en la barra de navegación

## API Endpoints

### Públicos (sin autenticación)

- `POST /api/register` - Registro de usuario
- `POST /api/login_check` - Login
- `POST /api/forgot-password` - Restablecimiento de contraseña

### Protegidos (requieren JWT)

- `GET /api/me` - Información del usuario
- `POST /api/token/refresh` - Refresh de token

## Características de Seguridad

- ✅ Contraseñas hasheadas con bcrypt
- ✅ Tokens JWT con expiración (1 hora)
- ✅ Refresh tokens con expiración (30 días)
- ✅ CORS configurado para el frontend
- ✅ Middleware de autenticación en el frontend
- ✅ Redirección automática a login si no autenticado
- ✅ Refresh automático de tokens expirados

## Estructura de Archivos Clave

### Backend

- `src/Entity/User.php` - Entidad de usuario
- `src/Controller/AuthController.php` - Controlador de autenticación
- `config/packages/security.yaml` - Configuración de seguridad
- `config/packages/lexik_jwt_authentication.yaml` - Configuración JWT
- `config/packages/nelmio_cors.yaml` - Configuración CORS

### Frontend

- `stores/auth.ts` - Store de autenticación con Pinia
- `middleware/auth.ts` - Middleware de autenticación
- `pages/login.vue` - Página de login
- `pages/register.vue` - Página de registro
- `pages/forgot-password.vue` - Página de restablecimiento
- `pages/index.vue` - Dashboard principal

## Notas

- El restablecimiento de contraseña está simulado (solo devuelve un mensaje de éxito)
- Las claves JWT deben ser generadas manualmente en Windows (usar Ubuntu/WSL o similar)
- El proyecto está configurado para desarrollo local
- Para producción, cambiar las URLs y configuraciones de seguridad

## Solución de Problemas

### Error de CORS

Asegúrate de que el backend esté ejecutándose en `http://localhost:8000` y el frontend en `http://localhost:3000`.

### Error de Base de Datos

Verifica que MySQL esté ejecutándose y las credenciales en `.env` sean correctas.

### Error de Claves JWT

Si no tienes las claves JWT, genera las claves usando OpenSSL en Ubuntu/Linux o usa claves de prueba.

### Error de Dependencias

Ejecuta `composer install` en el backend y `npm install` en el frontend.
