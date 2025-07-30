<?php
// Detectamos entorno 
define('IS_LOCAL', strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
define('IS_DOCKER', isset($_ENV['DOCKER_ENV']) || isset($_SERVER['DOCKER_ENV']) || file_exists('/.dockerenv'));

// Configuración de rutas en base al entorno
$localPath = '/graficos_ot/'; 
$dockerPath = '/'; 

// Determinamos la BASE_URL según el entorno
if (IS_DOCKER) {
    define('BASE_URL', $dockerPath);
} elseif (IS_LOCAL) {
    define('BASE_URL', $localPath);
} else {
    // Producción 
    //define('BASE_URL', '/');
}

// Rutas absolutas del sistema 
define('ROOT_PATH', dirname(__DIR__)); 
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('MODELS_PATH', ROOT_PATH . '/models');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ASSETS_PATH', PUBLIC_PATH . '/assets');

// URLs para el navegador
define('PUBLIC_URL', BASE_URL . 'public/');
define('ASSETS_URL', PUBLIC_URL . 'assets/');
define('CSS_URL', ASSETS_URL . 'css/');
define('JS_URL', ASSETS_URL . 'js/');
define('IMG_URL', ASSETS_URL . 'img/');

// Variables de entorno para Docker
$baseUrlOverride = getenv('BASE_URL');
if ($baseUrlOverride !== false) {
    define('BASE_URL_OVERRIDE', $baseUrlOverride);
}

// Helper para incluir archivos
function requireFrom($basePath, $relativePath) {
    $fullPath = $basePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
    $realPath = realpath($fullPath);
    
    if ($realPath && file_exists($realPath)) {
        require_once $realPath;
    } else {
        throw new Exception("Archivo no encontrado: $fullPath (real: " . ($realPath ?: 'false') . ")");
    }
}

// Helper para generar URLs
function url($path = '') {
    $baseUrl = defined('BASE_URL_OVERRIDE') ? BASE_URL_OVERRIDE : BASE_URL;
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

// Helper para assets 
function asset($path = '') {
    $baseUrl = defined('BASE_URL_OVERRIDE') ? BASE_URL_OVERRIDE : BASE_URL;
    $assetsUrl = rtrim($baseUrl, '/') . '/public/assets/';
    return $assetsUrl . ltrim($path, '/');
}

?>
