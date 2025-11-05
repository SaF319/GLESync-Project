<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


Instrucciones del Despliegue Local:
----------------------------------------
Configuración del Entorno (.env)

Antes de iniciar el proyecto, asegurate de configurar correctamente el archivo .env.
Podés usar el siguiente ejemplo como base:

APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
#APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3308
DB_DATABASE=planazo
DB_USERNAME=root
DB_PASSWORD=root


Generar clave de aplicación

Ejecutá el siguiente comando para generar la clave de seguridad de Laravel:

php artisan key:generate

Esta clave es esencial para la seguridad de la aplicación, ya que se usa para cifrar y descifrar datos.
Asegurate de que cada instancia del proyecto tenga su propia clave única.
----------------------------------------


----------------------------------------
Instalación con Docker
----------------------------------------
El proyecto incluye un Dockerfile y un docker-compose.yml listos para levantar el entorno completo.
Podés crear y ejecutar los contenedores con:

********************
docker compose up -d
********************

Esto levantará los servicios definidos (web, base de datos, etc.) en modo detached.

🧱 Migraciones y Seeders

Una vez levantado el entorno, ejecutá:
********************
php artisan migrate --seed 
********************
para que el proyecto cargue las migraciones y los seeders

Finalmente, corré el comando php artisan serve y pronto!!