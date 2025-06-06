<?php
// index.php

require_once 'config/conexion.php';

// Obtener la URL solicitada
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = explode('/', $url);

// Determinar qué controlador y acción usar
$controllerName = isset($url[0]) ? ucfirst($url[0]).'Controller' : 'HomeController';
$action = isset($url[1]) ? $url[1] : 'index';

// Rutas de autenticación
if($controllerName == 'AuthController') {
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    
    if($action == 'login') {
        $controller->login();
    } elseif($action == 'register') {
        $controller->register();
    } elseif($action == 'logout') {
        $controller->logout();
    } else {
        header('Location: auth/login.php');
    }
    exit();
}

// Verificar sesión para otras rutas
session_start();
if(!isset($_SESSION['id_usuario'])) {
    header('Location: ../ConstructoraWeb/views/auth/login.php');
    exit();
}

// Cargar el controlador correspondiente
$controllerFile = 'controllers/' . $controllerName . '.php';
if(file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    
    if(method_exists($controller, $action)) {
        $controller->$action();
    } else {
        header('HTTP/1.0 404 Not Found');
        require_once 'views/errors/404.php';
    }
} else {
    header('HTTP/1.0 404 Not Found');
    require_once 'views/errors/404.php';
}