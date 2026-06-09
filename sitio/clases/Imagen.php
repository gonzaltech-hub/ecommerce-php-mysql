<?php

class Imagen
{
    // MÉTODO PARA SUBIR
    // Se encarga de recibir el archivo que viene del formulario y guardarlo en la carpeta correcta.
    public static function subirImagen($directorio, $datosArchivo): string
    {
        // 1. Obtengo la extensión (.jpg, .png) del archivo original.
        // Uso 'explode' para separar por el punto y 'end' para agarrar lo último.
        $nombreOriginal = explode(".", $datosArchivo['name']);
        $extension = end($nombreOriginal);

        // 2. RENOMBRADO DE SEGURIDAD
        // No guardo el archivo con su nombre original (ej: "foto.jpg").
        // Genero un nombre nuevo usando time(), que me da la hora exacta en segundos.
        // ¿Por qué? Para evitar colisiones. Si dos productos suben "foto.jpg", uno sobrescribiría al otro.
        // Con esto, cada imagen tiene un nombre único.
        $nombreNuevo = time() . ".$extension";

        // 3. MOVIMIENTO
        // El archivo primero llega a una carpeta temporal de PHP.
        // Con esta función lo muevo a mi carpeta final (img/productos).
        $archivoSubido = move_uploaded_file(
            $datosArchivo['tmp_name'],
            "$directorio/$nombreNuevo"
        );

        if (!$archivoSubido) {
            throw new Exception("No se pudo subir la imagen");
        } else {
            // Devuelvo el nombre nuevo para que después la clase Producto lo guarde en la base de datos.
            return $nombreNuevo;
        }
    }

    // MÉTODO PARA BORRAR
    // Mantiene el servidor limpio. Lo uso cuando elimino un producto o cuando edito una foto vieja.
    public static function borrarImagen($archivo): bool
    {
        // Primero pregunto si el archivo existe físicamente en la carpeta.
        // Si intento borrar algo que no existe, PHP tiraría un error.
        if (file_exists($archivo)) {

            // Uso la función 'unlink', que es la forma que tiene PHP de borrar archivos del disco.
            $borrarArchivo = unlink($archivo);

            if (!$borrarArchivo) {
                throw new Exception("No se pudo eliminar la imagen");
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }
}
