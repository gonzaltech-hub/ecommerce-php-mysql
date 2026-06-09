# Plataforma E-Commerce Full-Stack | PHP & MySQL

Una tienda online dinámica y completamente funcional construida desde cero para demostrar los fundamentos del desarrollo web. Este proyecto cuenta con una interfaz pública para clientes (Frontend) y un panel administrativo seguro (Backend).

## 🚀 Características Principales

### Frontend (Interfaz del Cliente)
* **Catálogo Dinámico:** Muestra un mínimo de 15 productos distintos a la venta con imágenes ilustrativas, descripciones y precios.
* **Filtrado y Navegación:** Los productos se pueden agrupar y filtrar por parámetros que compartan (ej. categoria, estado).
* **Vistas Detalladas:** Páginas de detalle dinámicas para cada producto individual que muestran toda la información pertinente desde la base de datos.
* **Carrito de Compras:** Muestra todos los productos adquiridos por el cliente.
* **Páginas Adicionales:** Incluye una sección "Home" de introducción, un formulario de contacto y una sección con los datos del alumno.

### Backend (Interfaz Administrativa)
* **Autenticación Segura:** Sistema de login que restringe el acceso únicamente a los usuarios autorizados.
* **Gestión de Contenido:** Incluye paneles con funcionalidad ABM (Altas, Bajas, Modificaciones) para gestionar todo el funcionamiento del sitio.

## 🛠️ Stack Tecnológico

* **Lenguaje:** PHP Orientado a Objetos, utilizando clases y métodos para controlar todo el contenido dinámico del Frontend y Backend.
* **Base de Datos:** MySQL/MariaDB utilizando una conexión PDO y el uso de Queries de SQL con "holders" para ejecutar de forma segura consultas SELECT, INSERT, UPDATE y DELETE.
* **Arquitectura de BD:** Diseño relacional que incluye una tabla principal de productos, parámetros en tablas relacionales, una tabla pívot para relaciones de muchos a muchos y el almacenamiento de contraseñas correctamente cifradas.
* **Diseño Frontend:** Maquetado responsivo utilizando HTML5 semántico y estilización personalizada con CSS3.
* **Implementaciones Extra:** Incluye métodos propios para manipular datos en el catálogo y guarda en la base de datos las compras realizadas por cada cliente.

## 🔐 Credenciales de Prueba

Para probar el sistema y evaluar los distintos niveles de acceso, puedes utilizar las siguientes cuentas:

**Rol Superadministrador** (Acceso total al sistema, puede gestionar productos y usuarios) 
* **Email:** superadmin@gonzaltech.com
* **Contraseña:** admin123

**Rol Administrador** (Puede gestionar productos pero no tiene acceso al menú de usuarios)
* **Email:** admin@gonzaltech.com
* **Contraseña:** admin123 

**Roles de Cliente** (Pueden navegar en el catálogo, agregar productos al carrito, realizar compras y ver su historial) 
* **Email:** agustin@correo.com | **Contraseña:** agustin123 
* **Email:** alejandro@correo.com | **Contraseña:** alejandro123 

## 📥 Instalación local

1. Clona este directorio dentro de la carpeta pública de tu servidor local (ej. la carpeta `htdocs` si usas XAMPP).
2. Importa el archivo `.sql` proporcionado con la totalidad de la base de datos en tu gestor MySQL (ej. phpMyAdmin).
3. Actualiza las credenciales de conexión PDO en las clases correspondientes para conectarlo con tu base de datos local.
