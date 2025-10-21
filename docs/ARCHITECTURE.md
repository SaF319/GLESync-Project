#  Arquitectura MVC - GLESync Project

##  Overview
GLESync implementa una arquitectura MVC (Model-View-Controller) personalizada desarrollada en PHP vanilla, proporcionando una base sólida y escalable para la gestión de proyectos y tareas.

##  Principios de Diseño
- **Separación de Concerns**: Cada capa tiene responsabilidades específicas
- **Reutilización**: Componentes modulares y reutilizables
- **Mantenibilidad**: Código organizado y documentado
- **Seguridad**: Consultas preparadas y validación de datos

##  Estructura de Directorios

GLESync-Project/
├── app/ # Núcleo de la aplicación
│ ├── config/ # Configuración global
│ │ └── config.php # Parámetros de la aplicación
│ ├── controllers/ # Lógica de controladores
│ │ ├── AuthController.php # Autenticación y sesiones
│ │ ├── ProjectController.php # Gestión de proyectos
│ │ ├── TaskController.php # Gestión de tareas
│ │ ├── UserController.php # Perfiles de usuario
│ │ ├── TeamController.php # Gestión de equipos
│ │ ├── NotificationController.php # Sistema de notificaciones
│ │ └── DashboardController.php # Panel principal
│ ├── models/ # Capa de datos y negocio
│ │ ├── UserModel.php # Operaciones de usuario
│ │ ├── ProjectModel.php # Operaciones de proyectos
│ │ ├── TaskModel.php # Operaciones de tareas
│ │ ├── TeamModel.php # Operaciones de equipos
│ │ ├── NotificationModel.php # Operaciones de notificaciones
│ │ └── Database.php # Conexión y helpers de BD
│ ├── views/ # Capa de presentación
│ │ ├── auth/ # Vistas de autenticación
│ │ │ ├── login.php # Formulario de login
│ │ │ └── register.php # Formulario de registro
│ │ ├── projects/ # Vistas de proyectos
│ │ │ ├── index.php # Lista de proyectos
│ │ │ ├── create.php # Crear proyecto
│ │ │ ├── show.php # Ver proyecto
│ │ │ └── edit.php # Editar proyecto
│ │ ├── tasks/ # Vistas de tareas
│ │ │ ├── index.php # Lista de tareas
│ │ │ ├── create.php # Crear tarea
│ │ │ ├── show.php # Ver tarea
│ │ │ └── edit.php # Editar tarea
│ │ ├── layouts/ # Layouts principales
│ │ │ └── main.php # Layout base con header/footer
│ │ └── dashboard/ # Vistas del dashboard
│ │ └── index.php # Panel principal
│ └── core/ # Núcleo del framework
│ ├── Router.php # Sistema de enrutamiento
│ └── Database.php # Gestión de conexiones BD
├── public/ # Directorio público
│ ├── index.php #  Front Controller
│ └── assets/ # Recursos estáticos
│ ├── css/ # Hojas de estilo
│ └── js/ # Scripts JavaScript
└── docs/ # Documentación


##  Flujo de una Petición MVC

