<?php

class Producto
{
    // PROPIEDADES
    // Defino variables privadas para cada columna de mi tabla 'productos'.
    private $producto_id;
    private $categoria_fk;
    private $img;
    private $nombre;
    private $precio;
    private $stock;
    private $descripcion;
    private $estado_publicacion_fk;

    // PROPIEDADES ESPECIALES (Relaciones)
    // Además de los IDs, guardo acá los OBJETOS completos relacionados.
    // Esto es muy útil: en vez de tener solo un número "2", tengo el objeto Estado entero para poder pedirle su nombre después.
    private $estado_publicacion;
    private $etiquetas = []; // Acá guardaré la lista de etiquetas (Oferta, Nuevo, etc.)

    // Array auxiliar para no repetir código al llenar las propiedades básicas.
    private static $propiedadesCarga = ['producto_id', 'categoria_fk', 'nombre', 'precio', 'descripcion', 'img', 'stock', 'estado_publicacion_fk'];

    // Este método es un "armador" de objetos.
    // Recibe el array crudo que viene de la base de datos y me devuelve un objeto Producto limpio y completo.
    private static function crearDesdeDatos($datos): Producto
    {
        $obj = new self();

        // 1. Lleno los datos básicos (nombre, precio...) recorriendo mi lista de propiedades.
        foreach (self::$propiedadesCarga as $propiedad) {
            $obj->{$propiedad} = $datos[$propiedad] ?? null;
        }

        // 2. Relación 1 a Muchos (Estado):
        // Si el producto tiene un estado asignado, voy a buscar el objeto Estado completo y lo guardo dentro.
        if (!empty($datos['estado_publicacion_fk'])) {
            $obj->estado_publicacion = EstadoPublicacion::get_x_id($datos['estado_publicacion_fk']);
        }

        // 3. Relación Muchos a Muchos (Etiquetas):
        // Llamo a un método privado que va a la tabla intermedia y me trae todas las etiquetas de este producto.
        $obj->etiquetas = self::obtenerEtiquetasPorProducto($obj->producto_id);

        return $obj;
    }

    // Helper privado para buscar las etiquetas relacionadas.
    // Hace un JOIN con la tabla intermedia 'productos_x_etiquetas'.
    private static function obtenerEtiquetasPorProducto($idProducto): array
    {
        $conexion = Conexion::getConexion();
        // La consulta SQL une 'etiquetas' con la tabla pivot para traer solo las que corresponden a este ID.
        $query = "SELECT e.* FROM etiquetas e 
        JOIN productos_x_etiquetas pxe ON e.id = pxe.etiqueta_id 
        WHERE pxe.producto_id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Etiqueta');
        $stmt->execute([$idProducto]);

        return $stmt->fetchAll();
    }

    // Trae TODOS los productos.
    // Lo uso en la administración para el listado general.
    public static function todos(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM productos";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $lista = [];
        // Recorro cada fila de la DB y uso mi "armador" para crear objetos Producto reales.
        while ($datos = $stmt->fetch()) {
            $lista[] = self::crearDesdeDatos($datos);
        }

        return $lista;
    }

    // Busca UN solo producto por su ID.
    // Lo uso en la página de Detalle y para editar.
    public static function porId(int $id): ?self
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM productos WHERE producto_id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute([$id]);

        $datos = $stmt->fetch();

        // Si lo encuentra, lo armo. Si no, devuelvo null.
        return $datos ? self::crearDesdeDatos($datos) : null;
    }

    // Buscador de texto.
    // Usa el operador LIKE de SQL para encontrar coincidencias parciales en el nombre.
    public static function filtrar(string $texto): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM productos WHERE nombre LIKE :texto";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        // Le agrego los porcentajes % para que busque en cualquier parte del texto.
        $stmt->execute(['texto' => "%$texto%"]);

        $lista = [];
        while ($datos = $stmt->fetch()) {
            $lista[] = self::crearDesdeDatos($datos);
        }
        return $lista;
    }

    // --- SECCIÓN ABM (Alta, Baja, Modificación) ---

    //CREAR: Guarda un producto nuevo.
    //Este es un proceso de dos pasos: primero el producto, luego las etiquetas.
    public function crear(array $data)
    {
        $conexion = Conexion::getConexion();

        // Paso 1: Inserto los datos principales en la tabla 'productos'.
        $query = "INSERT INTO productos (img, nombre, precio, stock, descripcion, estado_publicacion_fk, categoria_fk) 
        VALUES (:img, :nombre, :precio, :stock, :descripcion, :estado_publicacion_fk, :categoria_fk)";

        $stmt = $conexion->prepare($query);
        $stmt->execute([
            'img' => $data['img'],
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'stock' => $data['stock'],
            'descripcion' => $data['descripcion'],
            'estado_publicacion_fk' => $data['estado_publicacion_fk'],
            'categoria_fk' => $data['categoria_fk']
        ]);

        // Paso 2: Obtengo el ID que MySQL le asignó a este nuevo producto.
        $idProducto = (int) $conexion->lastInsertId();

        // Paso 3: Si eligieron etiquetas (checkboxes), las guardo en la tabla intermedia.
        if (!empty($data['etiquetas']) && is_array($data['etiquetas'])) {
            foreach ($data['etiquetas'] as $etiquetaId) {
                $this->agregarEtiqueta($idProducto, $etiquetaId);
            }
        }
    }

    // EDITAR: Actualiza un producto existente.
    public function editar(int $pk, array $data)
    {
        $conexion = Conexion::getConexion();

        // Paso 1: Actualizo la tabla principal con un UPDATE normal.
        $query = "UPDATE productos 
        SET img = :img, 
            nombre = :nombre, 
            precio = :precio, 
            stock = :stock,
            descripcion = :descripcion, 
            estado_publicacion_fk = :estado_publicacion_fk,
            categoria_fk = :categoria_fk
        WHERE producto_id = :producto_id";

        $stmt = $conexion->prepare($query);
        $stmt->execute([
            'img' => $data['img'],
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'stock' => $data['stock'],
            'descripcion' => $data['descripcion'],
            'estado_publicacion_fk' => $data['estado_publicacion_fk'],
            'categoria_fk' => $data['categoria_fk'],
            'producto_id' => $pk
        ]);

        // Paso 2: Sincronizo las etiquetas.
        // Primero BORRO todas las relaciones viejas de este producto...
        $this->vaciarEtiquetas($pk);

        // ...y luego INSERTO las nuevas que vinieron marcadas en el formulario.
        // Esto es mucho más fácil que calcular cuáles agregar y cuáles quitar una por una.
        if (!empty($data['etiquetas']) && is_array($data['etiquetas'])) {
            foreach ($data['etiquetas'] as $etiquetaId) {
                $this->agregarEtiqueta($pk, $etiquetaId);
            }
        }
    }

    // ELIMINAR: Borra el producto de la base.
    public function eliminar(int $pk): bool
    {
        $conexion = Conexion::getConexion();
        // Borro de la tabla productos.
        $query = "DELETE FROM productos WHERE producto_id = :producto_id";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['producto_id' => $pk]);

        return true;
    }

    // --- MÉTODOS DE FILTRADO ---

    // Filtra por etiqueta (JOIN con tabla intermedia).
    public static function porEtiqueta(int $etiquetaId): array
    {
        $conexion = Conexion::getConexion();

        $query = "SELECT DISTINCT p.* FROM productos p
        INNER JOIN productos_x_etiquetas pxe ON p.producto_id = pxe.producto_id
        WHERE pxe.etiqueta_id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute([$etiquetaId]);

        $lista = [];
        while ($datos = $stmt->fetch()) {
            $lista[] = self::crearDesdeDatos($datos);
        }

        return $lista;
    }


    // Filtra por categoría (Consulta simple por Foreign Key).
    public static function porCategoria(int $categoriaId): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM productos WHERE categoria_fk = ?";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute([$categoriaId]);

        $lista = [];
        while ($datos = $stmt->fetch()) {
            $lista[] = self::crearDesdeDatos($datos);
        }
        return $lista;
    }

    // Helper privados para insertar/borrar en la tabla muchos-a-muchos.
    private function agregarEtiqueta($productoId, $etiquetaId)
    {
        $conexion = Conexion::getConexion();
        $query = "INSERT INTO productos_x_etiquetas (producto_id, etiqueta_id) VALUES (:p_id, :e_id)";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['p_id' => $productoId, 'e_id' => $etiquetaId]);
    }

    private function vaciarEtiquetas($productoId)
    {
        $conexion = Conexion::getConexion();
        $query = "DELETE FROM productos_x_etiquetas WHERE producto_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$productoId]);
    }

    // Trae solo los primeros X productos para mostrarlos en el Home.
    public static function obtenerDestacados(int $cantidad): array
    {
        $todos = self::todos();
        // Uso array_slice para recortar la lista.
        return array_slice($todos, 0, $cantidad);
    }

    // Este método decide qué productos mostrar en la página principal según qué filtro tocó el usuario.
    // Recibe los 3 posibles filtros (texto, etiqueta, categoría) y decide cuál usar.
    public static function obtenerConFiltros($filtro = null, $etiquetaId = null, $categoriaId = null): array
    {
        // Preparo el array de respuesta.
        $resultado = [
            'productos' => [],
            'titulo' => '', // El título de la página cambia dinámicamente
            'filtro_texto' => $filtro,
            'etiqueta_activa' => $etiquetaId,
            'categoria_activa' => $categoriaId
        ];

        // Lógica de decisión:
        if ($filtro) {
            // Caso 1: Usuario usó el buscador.
            $resultado['productos'] = self::filtrar($filtro);
            $resultado['titulo'] = "Resultados para: " . htmlspecialchars($filtro);
        } elseif ($etiquetaId) {
            // Caso 2: Usuario hizo clic en una etiqueta.
            $resultado['productos'] = self::porEtiqueta((int)$etiquetaId);
            $etiqueta = Etiqueta::get_x_id((int)$etiquetaId);
            // Busco el nombre de la etiqueta para ponerlo lindo en el título.
            $nombreEtiqueta = $etiqueta ? $etiqueta->getNombre() : "Seleccionados";
            $resultado['titulo'] = "Productos: " . $nombreEtiqueta;
        } elseif ($categoriaId) {
            // Caso 3: Usuario filtró por categoría.
            $resultado['productos'] = self::porCategoria((int)$categoriaId);
            $categoria = Categoria::get_x_id((int)$categoriaId);
            $nombreCat = $categoria ? $categoria->getNombre() : "Categoría";
            $resultado['titulo'] = "Categoría: " . $nombreCat;
        } else {
            // Caso 4: No hay filtros, muestro todo.
            $resultado['productos'] = self::todos();
            $resultado['titulo'] = "Explora nuestra selección de tecnología";
        }
        return $resultado;
    }

    // Helpers visuales para iconos y colores.
    public static function obtenerIconosEtiquetas(): array
    {
        return [
            1 => 'fa-percent',    // Oferta
            2 => 'fa-plus',       // Nuevo
            3 => 'fa-star',       // Destacado
            4 => 'fa-truck-fast'  // Envío Gratis
        ];
    }
    public static function obtenerColoresBadges(): array
    {
        return [
            'Nuevo' => 'badge-nuevo',
            'Oferta' => 'badge-oferta',
            'Destacado' => 'badge-destacado',
            'Envio Gratis' => 'badge-envio-gratis'
        ];
    }


    // VALIDACIÓN DE DATOS
    // Reviso que lo que llega del formulario tenga sentido antes de guardar.
    public static function validarDatos(array $datos): array
    {
        $errores = [];

        // Valido campos obligatorios y tipos de datos.
        if (empty($datos['nombre'])) {
            $errores['titulo'] = "Error: El titulo no debe estar vacio";
        } else if (strlen($datos['nombre']) < 3) {
            $errores['titulo'] = "Error: El titulo debe tener al menos 3 caracteres";
        }

        if (empty($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] <= 0) {
            $errores['precio'] = "Error: El precio debe ser un numero mayor a 0";
        }

        // Valido el Stock
        if (!isset($datos['stock']) || $datos['stock'] === '') {
            $errores['stock'] = "Error: El stock es obligatorio";
        } else if (!is_numeric($datos['stock']) || $datos['stock'] < 0) {
            $errores['stock'] = "Error: El stock debe ser un número positivo";
        }

        if (empty($datos['descripcion']) || strlen($datos['descripcion']) < 10) {
            $errores['descripcion'] = "Error: La descripcion debe tener al menos 10 caracteres";
        }

        if (empty($datos['categoria_fk'])) {
            $errores['categoria_fk'] = "Error: Debes seleccionar una categoría";
        }

        return $errores;
    }

    // Getters
    // Métodos públicos para leer los datos desde las vistas.
    public function getProductoId(): int
    {
        return $this->producto_id;
    }
    public function getImg(): ?string
    {
        return $this->img;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getPrecio(): float
    {
        return $this->precio;
    }
    public function getStock(): int
    {
        return $this->stock;
    }
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }
    public function getEstadoPublicacionFk(): int
    {
        return $this->estado_publicacion_fk;
    }
    public function getCategoriaFk()
    {
        return $this->categoria_fk;
    }
    public function getEstadoPublicacion(): ?EstadoPublicacion
    {
        return $this->estado_publicacion;
    }
    public function getEtiquetas(): array
    {
        return $this->etiquetas;
    }
    // Este getter es especial: devuelve solo un array de IDs [1, 3] 
    // Lo uso para saber qué checkboxes marcar cuando estoy editando un producto.
    public function getEtiquetasIds(): array
    {
        $ids = [];
        foreach ($this->etiquetas as $etiqueta) {
            $ids[] = $etiqueta->getId();
        }
        return $ids;
    }
}
