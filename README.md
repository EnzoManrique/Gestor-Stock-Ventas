# Sistema de Gestión de Stock y Ventas

## 1. Descripción del Negocio
El **Sistema de Gestión de Stock y Ventas** es una aplicación web diseñada para resolver la administración de inventario y el registro de ventas de un comercio. Permite a los administradores 
y empleados llevar un control riguroso de:
- **Productos y Categorías**: Alta, baja (lógica) y modificación de productos, controlando el stock disponible y alertando sobre productos con bajo stock.
- **Clientes**: Registro de la cartera de clientes para asociarlos a las ventas.
- **Ventas**: Proceso de registro de ventas que descuenta automáticamente el stock y calcula totales.
- **Reportes y Estadísticas**: Visualización de ventas por período, rendimiento y estado general del negocio mediante un panel de control (Dashboard).
- **Usuarios y Roles**: Control de acceso al sistema con distintos permisos (Administrador y Empleado).

El problema principal que resuelve es la **digitalización del control de inventario y facturación interna**, evitando el uso de planillas manuales, reduciendo errores humanos en el cálculo de totales y 
previniendo la venta de productos sin stock.

## 2. Stack Tecnológico
El proyecto está desarrollado con las siguientes tecnologías (versiones recomendadas/mínimas):
- **Backend**: PHP 8.0+ (Arquitectura MVC puro, sin frameworks).
- **Base de Datos**: MySQL 8.0 o MariaDB 10.4+ (Acceso mediante PDO).
- **Frontend**: HTML5, CSS3, JavaScript.
- **Estilos y Componentes UI**: Bootstrap 5.3 CDN.
- **Iconografía**: Bootstrap Icons 1.11.
- **Servidor Web**: Apache (usualmente empaquetado con XAMPP v8+).

## 3. Guía de Instalación Local

Sigue estos pasos para levantar el entorno de desarrollo en tu máquina local usando XAMPP, WAMP o un entorno similar.

### Requisitos Previos
1. Tener [XAMPP](https://www.apachefriends.org/) instalado con PHP 8.0+ y MySQL.
2. Tener Git instalado.

### Pasos de Instalación

**Paso 1: Clonar el repositorio**
Abre una terminal y dirígete a la carpeta pública de tu servidor web (en XAMPP es la carpeta `htdocs`, en WAMP es `www`).
```bash
cd c:\xampp\htdocs\
git clone <URL_DEL_REPOSITORIO> gestion_ventas
cd gestion_ventas
```

**Paso 2: Configurar la Base de Datos**
1. Abre tu gestor de base de datos local (ej. phpMyAdmin en `http://localhost/phpmyadmin` o DBeaver).
2. Crea una nueva base de datos llamada `gestion_ventas` con el cotejamiento `utf8mb4_spanish_ci` o `utf8mb4_general_ci`.
3. Importa el archivo SQL con la estructura de la base de datos (por ejemplo, `gestion_ventas.sql` si cuentas con él)
   para crear las tablas (`usuarios`, `productos`, `categorias`, `clientes`, `ventas`, `detalle_ventas`, etc.) y las vistas necesarias.

**Paso 3: Configurar Variables de Entorno (Conexión a BD)**
1. En la raíz del proyecto, busca la carpeta `config`.
2. Duplica o renombra el archivo `db.php.example` a `db.php`.
   ```bash
   copy config\db.php.example config\db.php
   ```
3. Edita `config/db.php` y ajusta las credenciales de tu servidor MySQL local si no son las por defecto:
   ```php
   <?php
   // config/db.php
   $host = 'localhost';
   $db   = 'gestion_ventas';
   $user = 'root';      // Tu usuario de MySQL (por defecto en XAMPP es root)
   $pass = '';          // Tu contraseña de MySQL (por defecto en XAMPP es vacía)
   $charset = 'utf8mb4';
   // ... resto del archivo
   ```

**Paso 4: Levantar el Proyecto**
1. Asegúrate de que los servicios **Apache** y **MySQL** estén encendidos en el panel de control de XAMPP.
2. Abre tu navegador web y visita: 
   `http://localhost/gestion_ventas/`
3. Serás redirigido a la pantalla de Login (`controllers/auth.php`). Ingresa con las credenciales por defecto (si el volcado SQL incluyó un usuario administrador).

### Notas sobre la API
*Importante:* Este proyecto **no expone endpoints REST/JSON** al estilo de una API separada. Es un sistema monolítico con renderizado del lado del servidor (SSR) utilizando PHP. 
La lógica de negocio y las vistas HTML están estrechamente integradas mediante el patrón MVC. Para conectar luego con React o Angular, sería necesario reescribir los controladores para 
que retornen respuestas JSON (por ejemplo, usando `json_encode()`) en lugar de requerir archivos `.php` de vista.
