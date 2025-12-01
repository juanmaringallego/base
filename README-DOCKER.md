# 🐳 Sistema de Gestión de Turnos - Guía Docker

Esta guía te explica cómo ejecutar la aplicación Laravel en un contenedor Docker.

## 📋 Requisitos Previos

- Docker instalado en tu sistema
- Docker Compose instalado (generalmente viene con Docker Desktop)

**Verificar instalación:**
```bash
docker --version
docker-compose --version
```

---

## 🚀 Opción 1: Ejecutar con Docker Compose (Recomendado)

Esta es la forma más sencilla de ejecutar la aplicación.

### Paso 1: Construir y ejecutar el contenedor

```bash
docker-compose up -d --build
```

**¿Qué hace este comando?**
- `up`: Inicia los servicios
- `-d`: Ejecuta en segundo plano (detached mode)
- `--build`: Construye la imagen antes de iniciar

### Paso 2: Verificar que el contenedor esté corriendo

```bash
docker-compose ps
```

Deberías ver algo como:
```
NAME                    STATUS              PORTS
sistema-turnos-laravel  Up X seconds        0.0.0.0:8080->80/tcp
```

### Paso 3: Acceder a la aplicación

Abre tu navegador y visita:
```
http://localhost:8080
```

### Comandos útiles

**Ver logs del contenedor:**
```bash
docker-compose logs -f
```

**Detener el contenedor:**
```bash
docker-compose down
```

**Reiniciar el contenedor:**
```bash
docker-compose restart
```

**Acceder a la terminal del contenedor:**
```bash
docker-compose exec laravel-app bash
```

**Ejecutar comandos de Artisan:**
```bash
# Desde fuera del contenedor
docker-compose exec laravel-app php artisan migrate

# O entrando al contenedor
docker-compose exec laravel-app bash
php artisan migrate
```

---

## 🔧 Opción 2: Ejecutar con Docker directamente

Si prefieres usar Docker sin Docker Compose:

### Paso 1: Construir la imagen

```bash
docker build -t sistema-turnos-laravel .
```

### Paso 2: Ejecutar el contenedor

```bash
docker run -d \
  --name sistema-turnos \
  -p 8080:80 \
  -v $(pwd)/storage:/var/www/html/storage \
  -v $(pwd)/database:/var/www/html/database \
  sistema-turnos-laravel
```

### Paso 3: Acceder a la aplicación

```
http://localhost:8080
```

### Comandos útiles

**Ver logs:**
```bash
docker logs -f sistema-turnos
```

**Detener:**
```bash
docker stop sistema-turnos
```

**Eliminar:**
```bash
docker rm sistema-turnos
```

**Reiniciar:**
```bash
docker restart sistema-turnos
```

---

## 🔍 Verificación

Una vez que el contenedor esté corriendo, verifica que todo funcione:

1. **Página principal**: http://localhost:8080
2. **Servicios**: http://localhost:8080/services
3. **Turnos**: http://localhost:8080/appointments

---

## 🗄️ Base de Datos

La aplicación usa SQLite, y la base de datos se encuentra dentro del contenedor en:
```
/var/www/html/database/database.sqlite
```

**Los datos ya están precargados con:**
- 10 usuarios de ejemplo
- 8 servicios (salon de belleza)
- 10 turnos con diferentes estados

**⚠️ Importante sobre la persistencia:**
La base de datos se crea automáticamente durante el build del contenedor. Los datos persistirán mientras el contenedor exista, pero se perderán si eliminas el contenedor con `docker-compose down` y lo reconstruyes.

**Para reiniciar la base de datos (sin perder el contenedor):**
```bash
# Acceder al contenedor
docker-compose exec laravel-app bash

# Dentro del contenedor
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force
exit
```

**Para preservar datos entre reconstrucciones:**
Si necesitas que los datos persistan incluso al reconstruir el contenedor, puedes usar un volumen Docker nombrado o descomentar el volumen de base de datos en docker-compose.yml (requiere configuración adicional).

---

## 🛠️ Troubleshooting

### El contenedor no inicia

**Ver logs detallados:**
```bash
docker-compose logs
```

### Permisos de archivos

Si hay problemas de permisos:
```bash
docker-compose exec laravel-app chown -R www-data:www-data /var/www/html/storage
docker-compose exec laravel-app chmod -R 775 /var/www/html/storage
```

### Puerto 8080 ya está en uso

Edita `docker-compose.yml` y cambia el puerto:
```yaml
ports:
  - "8081:80"  # Cambiar 8080 por otro puerto disponible
```

### Limpiar todo y empezar de nuevo

```bash
# Detener y eliminar contenedores
docker-compose down

# Eliminar volúmenes
docker-compose down -v

# Eliminar imágenes
docker rmi sistema-turnos-laravel

# Reconstruir
docker-compose up -d --build
```

---

## 📊 Arquitectura del Contenedor

```
┌─────────────────────────────────┐
│  Container: sistema-turnos      │
│                                  │
│  ┌──────────────────────────┐  │
│  │   Apache Web Server      │  │
│  │   Puerto 80              │  │
│  └──────────────────────────┘  │
│              ↓                   │
│  ┌──────────────────────────┐  │
│  │   PHP 8.2                │  │
│  │   + Extensiones          │  │
│  └──────────────────────────┘  │
│              ↓                   │
│  ┌──────────────────────────┐  │
│  │   Laravel 12 App         │  │
│  │   /var/www/html          │  │
│  └──────────────────────────┘  │
│              ↓                   │
│  ┌──────────────────────────┐  │
│  │   SQLite Database        │  │
│  │   database.sqlite        │  │
│  └──────────────────────────┘  │
│                                  │
└─────────────────────────────────┘
         ↓ (Puerto 8080)
    Tu Navegador
```

---

## 🎯 Características del Contenedor

✅ **PHP 8.4** con todas las extensiones necesarias
✅ **Apache** como servidor web
✅ **Composer** para gestión de dependencias
✅ **SQLite** como base de datos
✅ **Laravel 12** con toda la aplicación
✅ **Datos de ejemplo** precargados
✅ **Permisos configurados** correctamente
✅ **Base de datos** creada automáticamente durante el build

---

## 📝 Notas Importantes

1. **Persistencia de Datos**: Los datos de la base de datos se mantienen mientras el contenedor exista. Si haces `docker-compose down` y luego `up`, los datos persisten. Pero si reconstruyes el contenedor (`docker-compose down` + `docker-compose up --build`), se creará una nueva base de datos con datos de ejemplo.

2. **Puerto**: La aplicación corre en el puerto 8080 (puedes cambiarlo en docker-compose.yml)

3. **Desarrollo**: Este contenedor está configurado para desarrollo/demo. Para producción necesitarías ajustes adicionales de seguridad y persistencia.

4. **Variables de entorno**: El archivo .env se genera automáticamente durante el build del contenedor

---

## 🎓 Ventajas de usar Docker

✅ **Portabilidad**: Funciona igual en cualquier sistema
✅ **Aislamiento**: No afecta tu sistema local
✅ **Fácil de compartir**: Solo necesitas Docker instalado
✅ **Consistencia**: Mismo entorno para todos
✅ **Limpieza**: Fácil de eliminar sin dejar rastros

---

## 🆘 Ayuda

Si tienes problemas:

1. Revisa los logs: `docker-compose logs -f`
2. Verifica que Docker esté corriendo
3. Asegúrate que el puerto 8080 esté libre
4. Consulta la documentación oficial de Docker

---

**¡Listo para usar!** 🚀

Tu aplicación de gestión de turnos está corriendo en un contenedor Docker y lista para explorar.
