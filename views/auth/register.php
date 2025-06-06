<?php
require_once '../../config/conexion.php';

// Habilitar visualización de errores (solo para desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<div class="container-auth">
    <div class="auth-card">
        <div class="auth-header">
        <link rel="stylesheet" href="../../assets/css/formularios.css">
            <h1>CONSTRUCTION COMPANY</h1>
            <h2>Registro de Cliente</h2>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                switch ($_GET['error']) {
                    case 'campos_vacios':
                        echo "Complete todos los campos";
                        break;
                    case 'usuario_existente':
                        echo "Usuario o correo ya registrado";
                        break;
                    case 'bd':
                        echo "Error en el servidor";
                        break;
                    case 'email_invalido':
                        echo "Correo electrónico inválido";
                        break;
                    default:
                        echo "Error en el registro";
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
            <div class="form-group">
                <input type="text" name="nombre_completo" placeholder="Nombre Completo" required class="form-input"
                    value="<?php echo isset($_POST['nombre_completo']) ? htmlspecialchars($_POST['nombre_completo']) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="text" name="nombre_usuario" placeholder="Usuario" required class="form-input"
                    value="<?php echo isset($_POST['nombre_usuario']) ? htmlspecialchars($_POST['nombre_usuario']) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="email" name="correo" placeholder="Correo Electrónico" required class="form-input"
                    value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">
            </div>
            <div class="form-group">
                <input type="password" name="contraseña" placeholder="Contraseña" required class="form-input">
            </div>
            <div class="form-group">
                <input type="tel" name="telefono" placeholder="Teléfono (Opcional)" class="form-input"
                    value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
            </div>
            <button type="submit" class="btn-auth">Registrarse</button>
        </form>

        <div class="auth-footer">
            <p>¿Ya tienes cuenta? <a href="../auth/login.php">Inicia Sesión</a></p>
        </div>
    </div>
</div>