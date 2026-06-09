<?php

class Checkout
{

    // MÉTODO DE GUARDADO
    // Este método recibe dos paquetes de datos:
    // 1. $datosCompra: La información general (Usuario, Fecha, Total).
    // 2. $datosProducto: La lista de ítems del carrito.
    public static function insertar_datos_compra(array $datosCompra, array $datosProducto)
    {
        $conexion = Conexion::getConexion();

        // Primero inserto el registro general en la tabla 'compra'.
        // Aca guardo QUIÉN compró, CUÁNDO y CUÁNTO gastó en total.
        $query = "INSERT INTO compra (usuarios_usuario_id, fecha_compra, total_precio)
        VALUES (:id_usuario, :fecha, :importe)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([
            "id_usuario" => $datosCompra['id_usuario'],
            "fecha" => $datosCompra['fecha'],
            "importe" => $datosCompra['importe']
        ]);

        // Una vez que guardé, le pregunto a la base de datos:
        // Qué número de ID le pusiste a esta compra que recien de cree?
        $compraID = $conexion->lastInsertId();

        // Guardar el "Detalle" de la compra (Tabla Pivot).
        $queryDetalle = "INSERT INTO compra_productos (compra_fk, producto_fk, cantidad_productos)
        VALUES (:id_compra, :id_producto, :cantidad)";
        $stmtDetalle = $conexion->prepare($queryDetalle);

        // Recorro el carrito 
        // La clave ($idProducto) es el ID del producto y el valor ($cantidad) es cuántos llevó.
        foreach ($datosProducto as $idProducto => $cantidad) {

            // Ejecuto la sentencia guardada.
            $stmtDetalle->execute([
                "id_compra" => $compraID,
                "id_producto" => $idProducto,
                "cantidad" => $cantidad
            ]);
        }
    }
}
