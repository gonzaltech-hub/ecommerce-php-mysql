<?php

class Carrito
{

    // OBTENER CARRITO
    // Este método es el que usan las vistas para mostrar la tabla de compras.
    // Devuelve el array de productos guardado en la sesión.
    // Si es la primera vez que entra y no existe, devuelvo un array vacío [] para que el foreach del HTML no tire error.
    public static function get_carrito(): array
    {
        if (!empty($_SESSION['carrito'])) {
            return $_SESSION['carrito'];
        } else {
            return [];
        }
    }

    // AGREGAR ITEM
    // Recibe el ID y la Cantidad.
    public static function agregar(int $productoID, int $cantidad)
    {
        // SEGURIDAD:
        // Busco el producto a la base de datos para obtener el precio real.
        $itemData = Producto::porId($productoID);

        if ($itemData) {
            // LÓGICA DE ACTUALIZACIÓN:
            // Verifico si este producto ya estaba en el carrito.
            // Uso el ID del producto como "Clave" (Key) del array para encontrarlo rápido.
            if (isset($_SESSION['carrito'][$productoID])) {
                // Si ya existe. Le sumo la nueva cantidad a la que tenía.
                $_SESSION['carrito'][$productoID]['cantidad'] += $cantidad;
            } else {
                // No existe. Lo creo desde cero.
                $_SESSION['carrito'][$productoID] = [
                    'titulo' => $itemData->getNombre(),
                    'portada' => $itemData->getImg(),
                    'precio' => $itemData->getPrecio(),
                    'cantidad' => $cantidad
                ];
            }
        }
    }

    // ELIMINAR UN ITEM
    // Saca un producto específico del carrito.
    // Uso la función unset() de PHP sobre la clave del array.
    public static function eliminar(int $productoID)
    {
        if (isset($_SESSION['carrito'][$productoID])) {
            unset($_SESSION['carrito'][$productoID]);
        }
    }

    // VACIAR TODO
    // Reinicia el carrito a cero. Lo uso cuando se termina la compra o el usuario quiere limpiar todo.
    public static function vaciar()
    {
        $_SESSION['carrito'] = [];
    }

    // ACTUALIZAR CANTIDADES
    // Este método procesa el formulario del carrito donde el usuario cambia los numeritos a mano.
    // Recibe un array donde la clave es el ID y el valor es la nueva cantidad.
    public static function actualizar_cantidades(array $cantidades)
    {
        foreach ($cantidades as $key => $value) {
            // Verifico que el producto exista en mi carrito actual
            if (isset($_SESSION['carrito'][$key])) {

                // Me aseguro que la cantidad sea un número entero positivo
                $val = (int)$value;
                if ($val > 0) {
                    $_SESSION['carrito'][$key]['cantidad'] = $val;
                }
            }
        }
    }

    // CALCULAR TOTAL
    // Recorre todo el carrito para calcular cuánto tiene que pagar el cliente.
    public static function precio_total(): float
    {
        $total = 0;
        if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $item) {
                // Multiplico precio x cantidad por cada ítem y lo voy sumando al acumulador.
                $total += $item['precio'] * $item['cantidad'];
            }
        }
        return $total;
    }

    // CONTROL DE STOCK (UX)
    // Trae los datos de la sesión pero les AGREGA el dato del stock real de la base de datos.
    // Lo uso en la vista del Carrito para ponerle el atributo 'max' al input de cantidad.
    // Así el usuario no puede seleccionar más de lo que hay en stock.
    public static function obtenerItemsConStock(): array
    {
        $items = self::get_carrito();

        foreach ($items as $id => $datos) {
            // Busco el stock actualizado en la DB
            $productoDb = Producto::porId($id);
            // Se lo agrego al array temporalmente
            $items[$id]['stock_maximo'] = $productoDb ? $productoDb->getStock() : 0;
        }

        return $items;
    }

    // CONTADOR DE ÍTEMS
    // Devuelve la cantidad total.
    // Lo uso para mostrar el numerito al lado del ícono del carrito en el menú (badge)
    public static function cantidad_articulos(): int
    {
        $cantidad = 0;

        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $item) {
                $cantidad += $item['cantidad'];
            }
        }

        return $cantidad;
    }
}
