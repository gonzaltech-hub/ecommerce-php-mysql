<?php

class Categoria
{
    // PROPIEDADES
    // Defino las variables privadas de mi tabla 'categorias' en la base de datos.
    private $id;
    private $nombre;

    // TRAER TODAS
    // Este método es el que uso para hacer el sitio dinámico.
    // En lugar de escribir los botones de filtro a mano en el HTML, 
    // llamo a este método y recorro el resultado con un foreach.
    // Si en el futuro el administrador agrega otra categoría, aparece sola en el menú sin tocar código.
    public static function lista_completa(): array
    {
        // Obtengo la conexión a la base de datos
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM categorias";

        $stmt = $conexion->prepare($query);
        // FETCH_CLASS:
        // Le aviso a PDO que quiero que me devuelva objetos 'Categoria' ya armados.
        // Así puedo usar $categoria->getNombre() en la vista.
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // BUSCAR POR ID
    // Recupera una categoría puntual.
    // Lo uso, por ejemplo, cuando filtro productos por categoría. 
    // Con este método obtengo el nombre para mostrarlo en el título de la página.
    public static function get_x_id(int $id): ?Categoria
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM categorias WHERE id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute([$id]);

        $result = $stmt->fetch();

        // Si existe devuelvo, si no devuelvo null.
        return $result ? $result : null;
    }

    // GETTERS
    // Métodos públicos para leer los datos privados.
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
}
