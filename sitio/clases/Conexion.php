<?php

class Conexion
{
    // Datos de acceso a la base de datos
    private const DB_HOST = 'localhost';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_NAME = 'dwt3av_gonzalez_agustin';

    private const DB_DSN = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';

    // Esta variable '$db' va a guardar la conexión activa.
    // Al ser estática, pertenece a la Clase y no se borra, se mantiene viva mientras cargue la página.
    // Inicialmente es null porque todavía no nos conectamos.
    private static ?PDO $db = null;

    // MÉTODO PRIVADO DE CONEXIÓN
    // Lo hice 'private' por seguridad: nadie desde afuera de esta clase debería poder forzar una nueva conexión manualmente.
    private static function conectar()
    {
        try {
            // Intento crear la conexión usando los datos de arriba.
            self::$db = new PDO(self::DB_DSN, self::DB_USER, self::DB_PASS);
        } catch (Exception $e) {
            // Si falla la conexión, freno todo
            die('Error de Conexión.');
        }
    }

    // MÉTODO PÚBLICO
    // Este es el único método que uso desde afuera (Producto::todos(), Usuario::crear(), etc).
    // La lógica es simple:
    // 1. Pregunto: Ya tengo una conexión abierta en '$db'?
    // 2. Si es NO (es null), entonces llamo a conectar().
    // 3. Si es SÍ, me salto el paso de conectar y devuelvo la que ya tenía.
    // Esto evita que si cargo 20 productos, abra 20 conexiones a la base de datos. Uso siempre la misma
    public static function getConexion(): PDO
    {
        // Si es la primera vez que llamo a esto, conecto.
        if (self::$db === null) {
            self::conectar();
        }
        // Si ya estaba conectado de antes, devuelvo la misma conexión.
        return self::$db;
    }
}
