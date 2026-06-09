# Plataforma E-Commerce Full-Stack | PHP & MySQL

Una tienda online dinámica y completamente funcional construida desde cero para demostrar los fundamentos del desarrollo web. Este proyecto cuenta con una interfaz pública para clientes (Frontend) y un panel administrativo seguro (Backend).

## 🚀 Características Principales

### Frontend (Interfaz del Cliente)
* **Catálogo Dinámico:** Muestra un mínimo de 15 productos distintos a la venta con imágenes ilustrativas, descripciones y precios[cite: 1].
* **Filtrado y Navegación:** Los productos se pueden agrupar y filtrar por parámetros que compartan (ej. color, precio, género)[cite: 1].
* **Vistas Detalladas:** Páginas de detalle dinámicas para cada producto individual que muestran toda la información pertinente desde la base de datos[cite: 1].
* **Carrito de Compras:** Muestra todos los productos adquiridos por el cliente[cite: 1].
* **Páginas Adicionales:** Incluye una sección "Home" de introducción, un formulario de contacto y una sección con los datos del alumno[cite: 1].

### Backend (Interfaz Administrativa)
* **Autenticación Segura:** Sistema de login que restringe el acceso únicamente a los usuarios autorizados[cite: 1].
* **Gestión de Contenido:** Incluye paneles con funcionalidad ABM (Altas, Bajas, Modificaciones) para gestionar todo el funcionamiento del sitio[cite: 1].

## 🛠️ Stack Tecnológico

* **Lenguaje:** PHP Orientado a Objetos, utilizando clases y métodos para controlar todo el contenido dinámico del Frontend y Backend[cite: 1].
* **Base de Datos:** MySQL/MariaDB utilizando una conexión PDO y el uso de Queries de SQL con "holders" para ejecutar de forma segura consultas SELECT, INSERT, UPDATE y DELETE[cite: 1].
* **Arquitectura de BD:** Diseño relacional que incluye una tabla principal de productos, parámetros en tablas relacionales, una tabla pívot para relaciones de muchos a muchos y el almacenamiento de contraseñas correctamente cifradas[cite: 1].
* **Diseño Frontend:** Maquetado responsivo utilizando HTML5 semántico y estilización personalizada con CSS3[cite: 1].
* **Implementaciones Extra:** Incluye métodos propios para manipular datos en el catálogo y guarda en la base de datos las compras realizadas por cada cliente[cite: 1].

## 🔐 Credenciales de Prueba

Para probar el sistema y evaluar los distintos niveles de acceso, puedes utilizar las siguientes cuentas:

**Rol Superadministrador** (Acceso total al sistema, puede gestionar productos y usuarios)[cite: 2]
* **Email:** superadmin@gonzaltech.com[cite: 2]
* **Contraseña:** admin123[cite: 2]

**Rol Administrador** (Puede gestionar productos pero no tiene acceso al menú de usuarios)[cite: 2]
* **Email:** admin@gonzaltech.com[cite: 2]
* **Contraseña:** admin123[cite: 2]

**Roles de Cliente** (Pueden navegar en el catálogo, agregar productos al carrito, realizar compras y ver su historial)[cite: 2]
* **Email:** agustin@correo.com | **Contraseña:** agustin123[cite: 2]
* **Email:** alejandro@correo.com | **Contraseña:** alejandro123[cite: 2]

## 📥 Instalación local

1. Clona este directorio dentro de la carpeta pública de tu servidor local (ej. la carpeta `htdocs` si usas XAMPP)[cite: 1].
2. Importa el archivo `.sql` proporcionado con la totalidad de la base de datos en tu gestor MySQL (ej. phpMyAdmin)[cite: 1].
3. Actualiza las credenciales de conexión PDO en las clases correspondientes para conectarlo con tu base de datos local[cite: 1].
