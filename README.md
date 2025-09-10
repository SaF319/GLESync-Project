# GLESync-Project 🎵

Sistema de gestión de eventos desarrollado con Laravel.

## 🚀 Tecnologías Utilizadas

- **Backend:** Laravel 10+ (PHP Framework)
- **Frontend:** Bootstrap 5, HTML5, CSS3
- **Base de datos:** MySQL
- **Autenticación:** Sistema personalizado con Laravel Auth
- **Storage:** Sistema de archivos local

## Estructura del Proyecto

GLESync-Project/
├── app/
│ ├── Models/
│ │ ├── Usuarios.php # Modelo personalizado de usuarios
│ │ └── Evento.php # Modelo de eventos
│ ├── Http/
│ │ ├── Controllers/
│ │ │ ├── AuthController.php # Controlador de autenticación
│ │ │ └── EventoController.php # Controlador de eventos
│ │ └── Middleware/
│ ├── Providers/
│ └── Console/
├── config/
│ └── auth.php # Configuración de autenticación personalizada
├── database/
│ ├── migrations/
│ │ ├── create_usuarios_table.php # Migración de usuarios
│ │ └── create_eventos_table.php # Migración de eventos
│ └── seeders/
├── public/
│ ├── css/
│ ├── js/
│ ├── imagenes/ # Assets de imágenes
│ │ ├── logo.jpg
│ │ ├── recitales.jpg
│ │ ├── footbool.jpg
│ │ └── muestraArte.jpg
│ └── index.php
├── resources/
│ ├── views/
│ │ ├── layouts/
│ │ │ └── app.blade.php # Layout principal
│ │ ├── auth/
│ │ │ ├── login.blade.php # Vista de login
│ │ │ └── register.blade.php # Vista de registro
│ │ ├── home.blade.php # Página principal
│ │ ├── dashboard.blade.php # Dashboard de usuario
│ │ └── eventos/
│ │ └── create.blade.php # Creación de eventos
│ └── lang/
├── routes/
│ └── web.php # Rutas de la aplicación
├── storage/
├── tests/
├── vendor/
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
└── README.md
