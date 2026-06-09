<?php

class Alerta
{

    // AGREGAR ALERTA
    // Recibe el tipo y el texto.
    // Lo guardo en un array dentro de la SESIÓN
    public static function add_alerta(string $tipo, string $mensaje)
    {
        $_SESSION['alertas'][] = [
            'tipo' => $tipo, // 'success', 'error', 'warning'
            'mensaje' => $mensaje
        ];
    }

    // LIMPIAR
    // Reinicia el array de alertas.
    public static function clear_alertas()
    {
        $_SESSION['alertas'] = [];
    }

    // Este es un "helper" interno.
    // Recibe los datos crudos y me devuelve el string HTML con los divs y clases CSS listos.
    // Lo hice privado porque desde afuera solo necesito llamar a get_alertas().
    private static function print_alerta($alerta): string
    {
        // Asigno la clase CSS dinámica según el tipo (ej: .alerta-flotante .error)
        $claseTipo = $alerta['tipo'];

        $html = "<div class='alerta-flotante $claseTipo'>";
        $html .= "<i class='fas fa-info-circle'></i> " . $alerta['mensaje'];
        $html .= "</div>";

        return $html;
    }

    // MOSTRAR Y BORRAR
    // 1. Verifica si hay mensajes pendientes.
    // 2. Los transforma en HTML.
    // 3. LOS BORRA de la sesión
    // Si no los borrara, el mensaje seguiría apareciendo cada vez que recargo la página.
    public static function get_alertas()
    {
        if (!empty($_SESSION['alertas'])) {
            $alertasActuales = "";

            // Recorro y acumulo el HTML de todas las alertas pendientes
            foreach ($_SESSION['alertas'] as $alerta) {
                $alertasActuales .= self::print_alerta($alerta);
            }

            // Las borro después de imprimirlas.
            self::clear_alertas();

            return $alertasActuales;
        } else {
            return null;
        }
    }
}
