<?php

class EstadoPublicacion
{
    // PROPIEDADES
    // Defino las variables que coinciden con las columnas de mi tabla 'estados_publicacion'.
    private $estado_publicacion_id;
    private $nombre;

    // Este método va a la base de datos y me trae todos los estados disponibles.
    // Es fundamental para el formulario de "Crear Producto":
    // Lo llamo para generar las opciones del desplegable y que el admin elija el estado sin escribirlo a mano.
    public static function lista_completa(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM estados_publicacion";

        $stmt = $conexion->prepare($query);
        // Uso FETCH_CLASS para que me devuelva objetos listos.
        // Como las propiedades se llaman igual que las columnas, se llenan solas.
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // BUSCAR POR ID
    // Recupera un estado específico.
    // Lo uso internamente en la clase Producto.
    // Cuando cargo un producto, uso este método para convertir el número "1" en el objeto "Publicado".
    public static function get_x_id(int $id): ?self
    {
        $conexion = Conexion::getConexion();
        // Acá el WHERE usa el nombre exacto de la columna ID en la tabla.
        $query = "SELECT * FROM estados_publicacion WHERE estado_publicacion_id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute([$id]);

        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    // Getters
    // Métodos para leer los datos privados.
    public function getEstadoPublicacionId()
    {
        return $this->estado_publicacion_id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
}
