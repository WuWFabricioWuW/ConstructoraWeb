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

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'login':
                    $this->login();
                    break;
                case 'register':
                default:
                    $this->register();
                    break;
            }
        }
    }

    public function register() {
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
                header("Location: ../views/auth/register.php?error=usuario_existente");
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
            error_log("Error en registro: " . $e->getMessage());
            header("Location: ../views/auth/register.php?error=bd");
            exit();
        }
    }

    public function login() {
        try {
            // Asignar valores del formulario
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Validaciones
            if (empty($email) || empty($password)) {
                header("Location: ../views/auth/login.php?error=campos_vacios");
                exit();
            }

            // Configurar credenciales en el modelo User
            $this->user->nombre_usuario = $email;
            $this->user->correo = $email;
            $this->user->contrasena = $password;

            if ($this->user->login()) {
                // Iniciar sesión
                session_start();
                $_SESSION['user_id'] = $this->user->id_usuario;
                $_SESSION['username'] = $this->user->nombre_usuario;
                $_SESSION['role'] = $this->user->rol;
                
                // Redirigir al dashboard
                header("Location: ../views/dashboard.php");
                exit();
            } else {
                header("Location: ../views/auth/login.php?error=credenciales_invalidas");
                exit();
            }
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            header("Location: ../views/auth/login.php?error=bd");
            exit();
        }
    }
}

// Ejecutar el controlador
$authController = new AuthController();
$authController->handleRequest();
?>