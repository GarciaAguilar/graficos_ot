<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

try {
    // Obtener la acción solicitada
    $action = $_GET['action'] ?? null;
    
    if (!$action) {
        throw new Exception('Parámetro action es requerido');
    }
    
    // Incluir el modelo
    require_once '../models/OrdenTrabajoModel.php';
    
    $model = new OrdenTrabajoModel();
    
    // Procesar según la acción solicitada
    switch ($action) {
        case 'ordenes-por-tipo':
            $result = handleOrdenesPorTipo($model);
            break;
            
        case 'ordenes-por-prioridad':
            $result = handleOrdenesPorPrioridad($model);
            break;
        
        default:
            throw new Exception('Acción no válida: ' . $action);
    }
    
    // Respuesta exitosa
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Respuesta de error
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}

/**
 * Manejar gráfico de órdenes por tipo 
 */
function handleOrdenesPorTipo($model) {
    $datos = $model->getOrdenesPorTipo();
    
    if (empty($datos)) {
        throw new Exception('No se encontraron datos de órdenes por tipo');
    }
    
    $chartData = array_map(function($item) {
        return [
            'name' => $item['tipo_nombre'],
            'y' => (int) $item['cantidad'],
            'tipo_id' => (int) $item['tipo_mantenimiento']
        ];
    }, $datos);
    
    return [
        'success' => true,
        'data' => $chartData,
        'total' => array_sum(array_column($chartData, 'y')),
        'chart_type' => 'pie',
        'timestamp' => date('Y-m-d H:i:s')
    ];
}

/**
 * Manejar gráfico de órdenes por prioridad 
 */
function handleOrdenesPorPrioridad($model) {
    $datos = $model->getOrdenesPorPrioridad();
    
    if (empty($datos)) {
        throw new Exception('No se encontraron datos de órdenes por prioridad');
    }
    
    $chartData = array_map(function($item) {
        return [
            'name' => $item['prioridad_nombre'],
            'y' => (int) $item['cantidad'],
            'prioridad_id' => (int) $item['prioridad'],
            'color' => getPrioridadColor((int) $item['prioridad'])
        ];
    }, $datos);
    
    return [
        'success' => true,
        'data' => $chartData,
        'total' => array_sum(array_column($chartData, 'y')),
        'chart_type' => 'bar',
        'timestamp' => date('Y-m-d H:i:s')
    ];
}

/**
 * Obtener color según la prioridad
 */
function getPrioridadColor($prioridad) {
    switch ($prioridad) {
        case 0: return '#28a745'; // Verde - Baja
        case 1: return '#ffc107'; // Amarillo - Media
        case 2: return '#fd7e14'; // Naranja - Alta
        case 3: return '#dc3545'; // Rojo - Crítica
        default: return '#6c757d'; // Gris - Sin prioridad
    }
}

?>
