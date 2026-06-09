<?php

class Etiqueta
{
    // PROPIEDADES
    // Defino las variables privadas que coinciden exactamente con las columnas de mi tabla 'etiquetas' en MySQL.
    private $id;
    private $nombre;

    // Este método estático va a la base de datos y me trae el listado completo de etiquetas.
    // Lo uso en:
    // 1. En el formulario de "Crear Producto", para generar la lista de checkboxes.
    // 2. En la página de Productos, para generar los botones de filtro.
    public static function lista_completa(): array
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM etiquetas";

        $stmt = $conexion->prepare($query);
        // Uso FETCH_CLASS para que me devuelva objetos 'Etiqueta' listos para usar, en vez de arrays sueltos.
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // BUSCAR POR ID
    // Recupera una etiqueta puntual.
    // Sirve para mostrar el título "Productos: Oferta" cuando filtro por una etiqueta específica.
    public static function get_x_id(int $id): ?Etiqueta
    {
        $conexion = Conexion::getConexion();
        $query = "SELECT * FROM etiquetas WHERE id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute([$id]);

        $result = $stmt->fetch();
        return $result ? $result : null;
    }

    // Para este método en lugar de tener la lógica de colores en el CSS o en el HTML, la tengo acá.
    // Dependiendo del nombre de la etiqueta, devuelvo una clase CSS distinta (ej: badge-oferta es rojo)
    // Si agrego una etiqueta nueva que no está en la lista, devuelve un color por defecto.
    public function getClaseColor(): string
    {
        $colores = [
            'Nuevo' => 'badge-nuevo',
            'Oferta' => 'badge-oferta',
            'Destacado' => 'badge-destacado',
            'Envio Gratis' => 'badge-envio-gratis'
        ];

        // Si el nombre de esta etiqueta existe en mi array de colores, devuelvo la clase.
        // Si no (??), devuelvo 'badge-default' (gris).
        return $colores[$this->nombre] ?? 'badge-default';
    }

    // Getters
    // Métodos públicos para leer los datos privados desde afuera.
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
}
