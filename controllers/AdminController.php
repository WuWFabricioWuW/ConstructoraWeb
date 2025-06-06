<?php
require_once '../config/conexion.php';
require_once '../models/User.php';

class AuthController {
    private $user;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->user = new User($db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Asignar valores del formulario
                $this->user->nombre_usuario = $_POST['nombre_usuario'];
                $this->user->contrasena = $_POST['contraseña'];
                $this->user->nombre_completo = $_POST['nombre_completo'];
                $this->user->correo = $_POST['correo'];
                $this->user->telefono = $_POST['telefono'] ?? null;
                $this->user->rol = 'cliente';

                // Validaciones
                if (empty($this->user->nombre_usuario) || empty($_POST['contraseña']) || 
                    empty($this->user->nombre_completo) || empty($this->user->correo)) {
                    header("Location: ../views/auth/register.php?error=campos_vacios");
                    exit();
                }

                if (!filter_var($this->user->correo, FILTER_VALIDATE_EMAIL)) {
                    header("Location: ../views/auth/register.php?error=email_invalido");
                    exit();
                }

                if ($this->user->usuarioExiste()) {
                    header("Location: ../views/auth/register.php?error=email_invalido");
                    exit();
                }

                if ($this->user->registrar()) {
                    header("Location: ../views/auth/login.php?registro=exitoso");
                    exit();
                } else {
                    header("Location: ../views/auth/register.php?error=bd");
                    exit();
                }
            } catch (PDOException $e) {
                error_log("Error en AuthController (register): " . $e->getMessage());
                header("Location: ../auth/register.php?error=bd");
                exit();
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                session_start();
                
                // Asegurarnos de obtener los campos correctos
                $credencial = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
    
                if (empty($credencial) || empty($password)) {
                    header("Location: ../views/auth/login.php?error=campos_vacios");
                    exit();
                }
    
                // Configurar el usuario para el login
                $this->user->nombre_usuario = $credencial;
                $this->user->correo = $credencial; // Para buscar por email también
                $this->user->contrasena = $password;
    
                if ($this->user->login()) {
                    $_SESSION['user_id'] = $this->user->id_usuario;
                    $_SESSION['username'] = $this->user->nombre_usuario;
                    $_SESSION['user_role'] = $this->user->rol;
                    
                    header("Location: ../views/dashboard.php");
                    exit();
                } else {
                    header("Location: ../views/auth/login.php?error=credenciales_invalidas");
                    exit();
                }
            } catch (PDOException $e) {
                error_log("Error en AuthController (login): " . $e->getMessage());
                header("Location: ../views/auth/login.php?error=bd");
                exit();
            }
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../views/auth/login.php");
        exit();
    }
}

// Ejecutar la acción correspondiente
$action = $_GET['action'] ?? $_POST['action'] ?? '';

$authController = new AuthController();

switch ($action) {
    case 'register':
        $authController->register();
        break;
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    default:
        header("Location: ../views/auth/login.php");
        exit();
}
?> 