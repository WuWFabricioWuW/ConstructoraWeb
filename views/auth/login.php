<?php
require_once '../../config/conexion.php';

// Habilitar visualización de errores (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir si ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: ../views/dashboard.php");
    exit();
}
?>
<div class="container-auth">
    <div class="auth-card">
        <div class="auth-header">
            <link rel="stylesheet" href="../../assets/css/formularios.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <h1>CONSTRUCTION COMPANY</h1>
            <h2>Inicio de Sesión</h2>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                switch ($_GET['error']) {
                    case 'campos_vacios':
                        echo "Complete todos los campos";
                        break;
                    case 'credenciales_invalidas':
                        echo "Correo o contraseña incorrectos";
                        break;
                    case 'bd':
                        echo "Error en el servidor";
                        break;
                    default:
                        echo "Error en el inicio de sesión";
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
            <div class="alert alert-success">
                Registro exitoso! Por favor inicie sesión.
            </div>
        <?php endif; ?>

        <form class="auth-form" method="POST" action="../../controllers/AuthController.php">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <input type="email" name="email" placeholder="Correo electrónico" required class="form-input"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Contraseña" required class="form-input">
            </div>
            <button type="submit" class="btn-auth">INGRESAR</button>
        </form>

        <div class="auth-footer">
            <p>¿No tienes cuenta? <a href="../auth/register.php">Regístrate aquí</a></p>
        </div>
    </div>
</div>