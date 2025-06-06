<?php 
session_start();
if(!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'administrador') {
    header('Location: /auth/login');
    exit();
}
require_once '../layouts/header.php'; 
?>

<div class="container">
    <h1 class="mt-4">Panel de Administración</h1>
    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Proyectos</h5>
                    <p class="card-text">Gestiona todos los proyectos activos</p>
                    <a href="/proyectos" class="btn btn-light">Administrar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text">Administra la información de clientes</p>
                    <a href="/clientes" class="btn btn-light">Administrar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Reportes</h5>
                    <p class="card-text">Genera reportes financieros</p>
                    <a href="/reportes" class="btn btn-light">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>