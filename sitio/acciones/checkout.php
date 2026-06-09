<?php
require_once "../funciones/autoload.php";

// Traigo el contenido del carrito de la sesión.
// Necesito saber qué productos eligió el usuario.
$items = Carrito::get_carrito();

// Traigo el ID del usuario desde la sesión de login.
// Necesito saber quien está comprando.
// Si no hay nadie logueado, esto devuelve FALSE.
$userID = $_SESSION['loggedIn']['id'] ?? FALSE;

try {
    // Solo procedo si tengo un usuario válido y si el carrito tiene al menos un producto.
    // Es una validación de seguridad para no guardar compras vacías
    if ($userID && count($items) > 0) {

        // Preparo los datos para la tabla 'compra'.
        // Acá guardo los datos generales: el ID del cliente, la fecha actual y el total a pagar.
        $datosCompra = [
            "id_usuario" => $userID,
            "fecha" => date("Y-m-d H:i:s"),
            "importe" => Carrito::precio_total()
        ];

        // Preparo los datos para la tabla 'compra_productos'.
        // Recorro el carrito y armo un array más simple donde solo me importa el ID del producto y la cantidad.
        $detalleCompra = [];
        foreach ($items as $key => $value) {
            $detalleCompra[$key] = $value['cantidad'];
        }

        // Llamo a mi método estático que hace los inserts en la base de datos.
        // Le paso los dos paquetes de datos que armé recién.
        // La clase Checkout se encarga del trabajo sucio de SQL.
        Checkout::insertar_datos_compra($datosCompra, $detalleCompra);

        // Si la compra se guardó bien en la DB, ya puedo borrar el carrito de la sesión para que quede limpio para la próxima.
        Carrito::vaciar();

        // Feedback
        // Le aviso al usuario que salió todo bien.
        Alerta::add_alerta('success', "¡Compra realizada con éxito! Gracias por elegirnos.");

        // Lo mando a 'mis-compras' para que vea su historial de pedidos.
        header('Location: ../index.php?seccion=mis-compras');
    } else {
        // Si llegó acá sin estar logueado o con el carrito vacío, le aviso y lo saco.
        Alerta::add_alerta('warning', "El carrito está vacío o la sesión expiró.");
        header('Location: ../index.php?seccion=carrito');
    }
} catch (Exception $e) {
    // Manejo de errores
    Alerta::add_alerta('error', "Hubo un problema al procesar su compra. Por favor, intente nuevamente.");
    header('Location: ../index.php?seccion=carrito');
}
