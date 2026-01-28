# 🛒 Sistema de Gestión de Ventas e Inventario

Sistema web completo para la gestión de ventas, control de stock y administración de clientes, desarrollado con **PHP** y **MySQL**. Ideal para pequeños comercios o puntos de venta (POS).

![Dashboard Preview](ruta/a/una/captura_de_pantalla.png)
*(Reemplaza esto con una captura de tu dashboard)*

## Características Principales

### Módulo de Ventas (POS)
- **Carrito de Compras:** Agregado de múltiples productos en una sola transacción.
- **Buscador en Vivo (AJAX):** Búsqueda instantánea de productos por nombre o categoría sin recargar.
- **Control de Stock:** Validación automática de disponibilidad antes de la venta.
- **Tickets:** Generación de registro de venta asociado a cliente y vendedor.

### Gestión de Inventario
- **ABM de Productos:** Creación, edición y baja lógica de productos.
- **Categorías Dinámicas:** Gestión flexible de categorías.
- **Alertas Visuales:** Indicadores de color para stock bajo (amarillo) y crítico (rojo).

### Administración
- **Roles de Usuario:**
  - **Administrador:** Acceso total (Configuración, ABM, Reportes, Gráficos).
  - **Empleado:** Acceso restringido solo a Ventas.
- **Gestión de Clientes:** Base de datos de clientes con historial.

### Reportes y Estadísticas
- **Dashboard Gerencial:** Gráficos interactivos (Chart.js) de ventas mensuales y productos más vendidos.
- **Reportes Detallados:** Filtrado por rango de fechas y vendedor específico.

## Tecnologías Utilizadas

- **Backend:** PHP 8 (PDO para conexiones seguras).
- **Frontend:** HTML5, CSS3, **Bootstrap 5**.
- **Base de Datos:** MySQL / MariaDB.
- **JavaScript:**
  - **AJAX (Fetch API):** Para búsquedas asíncronas.
  - **Chart.js:** Para visualización de datos.
  - **Select2:** Para selectores de búsqueda mejorados.
