<?php

class Usuario
{
    // Defino las propiedades privadas.
    // Son exactamente las mismas columnas que tengo en la tabla de la base de datos.
    // Las hago privadas para que nadie las modifique desde afuera sin permiso.
    private $usuario_id;
    private $username;
    private $email;
    private $password;
    private $rol_fk; // 1: SuperAdmin, 2: Admin, 3: Usuario

    // Le paso un email y va a la base de datos a ver si existe.
    // Si lo encuentra, me devuelve el Objeto Usuario completo (con contraseña y todo).
    // Si no, devuelve null.
    public static function usuario_x_email(string $email): ?Usuario
    {
        $conexion = Conexion::getConexion();

        $query = "SELECT * FROM usuarios WHERE email = ?";

        // Preparo la consulta para evitar inyecciones SQL.
        $stmt = $conexion->prepare($query);
        // Acá uso este truco: FETCH_CLASS.
        // Le digo a PDO que, en vez de un array suelto, me devuelva directamente una instancia de esta clase.
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute([$email]);

        $result = $stmt->fetch();

        return $result ? $result : null;
    }

    // Este lo uso en el Panel de Admin para listar a todos los usuarios en la tabla.
    public static function todos(): array
    {
        $conexion = Conexion::getConexion();

        $query = "SELECT * FROM usuarios ORDER BY usuario_id ASC";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Este sirve para cuando quiero editar a ub usuario específico.
    // Le paso el ID y me trae sus datos para rellenar el formulario de edición.
    public static function porId(int $id): ?Usuario
    {
        $conexion = Conexion::getConexion();

        $query = "SELECT * FROM usuarios WHERE usuario_id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        $stmt->execute([$id]);

        $result = $stmt->fetch();

        return $result ? $result : null;
    }

    // El método para guardar un usuario nuevo en la base de datos.
    // Acá recibo la password ya hasheada desde el controlador.
    public function crear(array $data)
    {
        $conexion = Conexion::getConexion();
        $query = "INSERT INTO usuarios (email, username, password, rol_fk) 
        VALUES (:email, :username, :password, :rol_fk)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => $data['password'],
            'rol_fk' => $data['rol_fk']
        ]);
    }

    // Este método actualiza los datos generales (nombre, email, rol).
    // NO actualiza la contraseña acá. Eso lo separé en otro método para mayor seguridad.
    public function editar(int $pk, array $data)
    {
        $conexion = Conexion::getConexion();

        $query = "UPDATE usuarios
        SET username = :username,
        email = :email, 
        rol_fk = :rol_fk
        WHERE usuario_id = :usuario_id";
        $stmt = $conexion->prepare($query);
        $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'rol_fk' => $data['rol_fk'],
            'usuario_id' => $pk
        ]);
    }

    // Este es el método específico solo para cambiar la contraseña.
    // Lo separé del 'editar' normal porque no siempre que edito un usuario quiero cambiarle la clave.
    // Solo lo llamo si el admin escribió algo en el campo de password.
    public function editarPassword(int $id, string $passwordHash)
    {
        $conexion = Conexion::getConexion();
        $query = "UPDATE usuarios SET password = ? WHERE usuario_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$passwordHash, $id]);
    }

    // Eliminar de la base de datos.
    public function eliminar(int $pk): bool
    {
        $conexion = Conexion::getConexion();

        $query = "DELETE FROM usuarios WHERE usuario_id = :usuario_id";
        $stmt = $conexion->prepare($query);
        $stmt->execute(['usuario_id' => $pk]);

        return true;
    }

    // Validación.
    // Acá reviso que los datos vengan bien antes de intentar guardar.
    // El parámetro $esEdicion:
    // - Si es FALSE (creando), la password es obligatoria.
    // - Si es TRUE (editando), la password es opcional.
    public static function validarDatos(array $datos, bool $esEdicion = false): array
    {
        $errores = [];

        // Valido que el nombre no esté vacío y tenga largo decente.
        if (empty($datos['username'])) {
            $errores['username'] = "El nombre de usuario es obligatorio.";
        } elseif (strlen($datos['username']) < 3) {
            $errores['username'] = "El usuario debe tener al menos 3 caracteres.";
        }

        // Valido formato de email con filter_var.
        if (empty($datos['email'])) {
            $errores['email'] = "El email es obligatorio.";
        } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = "El formato del email no es válido.";
        }

        // Valido que hayan elegido un rol.
        if (empty($datos['rol_fk'])) {
            $errores['rol_fk'] = "Debe asignar un rol al usuario.";
        }

        // Lógica condicional para la contraseña:
        if (!$esEdicion) {
            // Si estoy creando un usuario, sí o sí necesito contraseña.
            if (empty($datos['password'])) {
                $errores['password'] = "La contraseña es obligatoria.";
            } elseif (strlen($datos['password']) < 4) {
                $errores['password'] = "La contraseña debe tener al menos 4 caracteres.";
            }
        }

        return $errores;
    }

    // Getters
    // Métodos públicos para poder leer las propiedades privadas desde afuera (las vistas).
    public function getId()
    {
        return $this->usuario_id;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getRol()
    {
        return $this->rol_fk;
    }

    // Este es un "Helper" visual.
    // En la base de datos guardo números (1, 2, 3), pero en la tabla quiero mostrar texto.
    // Este método traduce el número a palabra.
    public function getNombreRol(): string
    {
        switch ($this->rol_fk) {
            case 1:
                return 'SuperAdmin';
            case 2:
                return 'Administrador';
            case 3:
                return 'Usuario';
            default:
                return 'Desconocido';
        }
    }
}
