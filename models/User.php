<?php
require_once '../config/conexion.php';

class User {
    private $conn;
    private $table = 'usuarios';

    public $id_usuario;
    public $nombre_usuario;
    public $contrasena;
    public $rol;
    public $nombre_completo;
    public $correo;
    public $telefono;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar un nuevo usuario
    public function registrar() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre_usuario, contrasena, rol, nombre_completo, correo, telefono, fecha_creacion)
                  VALUES 
                  (:nombre_usuario, :contrasena, :rol, :nombre_completo, :correo, :telefono, NOW())";

        $stmt = $this->conn->prepare($query);

        // Sanitizar y hashear contraseña
        $this->nombre_usuario = htmlspecialchars(strip_tags($this->nombre_usuario));
        $this->contrasena = password_hash($this->contrasena, PASSWORD_BCRYPT);
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        // Bind parameters
        $stmt->bindParam(':nombre_usuario', $this->nombre_usuario);
        $stmt->bindParam(':contrasena', $this->contrasena);
        $stmt->bindParam(':rol', $this->rol);
        $stmt->bindParam(':nombre_completo', $this->nombre_completo);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':telefono', $this->telefono);

        if($stmt->execute()) {
            return true;
        }
        
        error_log("Error en registro: " . implode(":", $stmt->errorInfo()));
        return false;
    }

    // Verificar si el usuario existe
    public function usuarioExiste() {
        $query = "SELECT id_usuario FROM " . $this->table . " 
                  WHERE nombre_usuario = :nombre_usuario OR correo = :correo LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_usuario', $this->nombre_usuario);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Iniciar sesión (método actualizado)
    public function login() {
        $query = "SELECT id_usuario, nombre_usuario, contrasena, rol FROM " . $this->table . " 
                  WHERE (nombre_usuario = :credencial OR correo = :credencial) LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':credencial', $this->nombre_usuario);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($this->contrasena, $row['contrasena'])) {
                $this->id_usuario = $row['id_usuario'];
                $this->nombre_usuario = $row['nombre_usuario'];
                $this->rol = $row['rol'];
                return true;
            }
        }
        return false;
    }
}