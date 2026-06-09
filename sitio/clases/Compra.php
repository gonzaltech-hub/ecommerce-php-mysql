<?php

class Compra
{
    // PROPIEDADES
    // Estas variables son de las columnas de mi tabla 'compra' en la base de datos.
    private $compra_id;
    private $usuarios_usuario_id;
    private $fecha_compra;
    private $total_precio;

    // HISTORIAL DE USUARIO
    // Este es el método principal de esta clase.
    // Lo uso en la sección "Mis Compras" del perfil del cliente.
    // Recibe el ID del usuario logueado y busca todas las compras que hizo.
    public static function historial_por_usuario(int $idUsuario): array
    {
        $conexion = Conexion::getConexion();

        // La consulta SQL filtra por el ID del usuario.
        // Un detalle de UX: Agrego 'ORDER BY fecha_compra DESC'.
        // Esto hace que las compras más recientes aparezcan primero en la lista, que es lo más cómodo para el usuario.
        $query = "SELECT * FROM compra WHERE usuarios_usuario_id = ? ORDER BY fecha_compra DESC";
        $stmt = $conexion->prepare($query);
        // Uso FETCH_CLASS de nuevo.
        // Así, en vez de devolverme arrays sueltos, me devuelve una lista de objetos 'Compra'.
        // Esto me permite usar los getters en la vista ($compra->getTotal()) en lugar de acceder al array ($compra['total']).
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute([$idUsuario]);

        return $stmt->fetchAll();
    }

    // GETTERS
    // Métodos simples para poder leer los datos privados desde la vista HTML.
    public function getId()
    {
        return $this->compra_id;
    }
    public function getUsuarioId()
    {
        // Hago un casting a (int) por seguridad, para asegurar que siempre devuelva un número.
        return (int) $this->usuarios_usuario_id;
    }
    public function getFecha()
    {
        return $this->fecha_compra;
    }
    public function getTotal()
    {
        return $this->total_precio;
    }
}
