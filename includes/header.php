<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
     <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <!--HEADER -->
        <div class="header">
            <div class="menu-btn" id="menu-btn">
                <i class='bx bx-menu'></i>
            </div>
            <div class="brand">
                <img src="<?= asset('img/brand-light.svg') ?>" alt="Logo">
                <span>Gráficos</span>
            </div>
        </div>
        <!--SIDEBAR  -->
        <div class="menu-container">
            <div class="search">
                <i class='bx bx-search-alt-2'></i>
                <input type="text" placeholder="Buscar...">
            </div>
            <ul class="menu">
                <li class="menu-item menu-item-static active">
                    <a href="#" class="menu-link">
                        <i class='bx bx-home-alt-2'></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="menu-item menu-item-static">
                    <a href="#" class="menu-link">
                        <i class='bx bx-pie-chart-alt-2'></i>
                        <span>Gráfico 1</span>
                    </a>
                </li>
                <li class="menu-item menu-item-static">
                    <a href="#" class="menu-link">
                        <i class='bx bx-pie-chart-alt-2'></i>
                        <span>Gráfico 2</span>
                    </a>
                </li>
            </ul>
        </div>
        <!--FOOTER SIDEBAR -->
        <div class="footer">
            <ul class="menu">
                <li class="menu-item menu-item-static">
                    <a href="#" class="menu-link">
                        <i class='bx bx-cog'></i>
                        <span>Configuración</span>
                    </a>
                </li>
                <li class="menu-item menu-item-static">
                    <a href="#" class="menu-link">
                        <i class='bx bx-log-out'></i>
                        <span>Salir</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="content">