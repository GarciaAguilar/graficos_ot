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
            
        case 'ordenes-por-modo':
            $result = handleOrdenesPorModo($model);
            break;
            
        case 'evolucion-estados':
            $result = handleEvolucionEstados($model);
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
 * Manejar gráfico de órdenes por modo (recursos internos vs externos)
 */
function handleOrdenesPorModo($model) {
    $datos = $model->getOrdenesPorModo();
    
    if (empty($datos)) {
        throw new Exception('No se encontraron datos de órdenes por modo');
    }
    
    $chartData = array_map(function($item) {
        return [
            'name' => $item['modo_nombre'],
            'y' => (int) $item['cantidad'],
            'modo_id' => (int) $item['modo'],
            'color' => getModoColor((int) $item['modo'])
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
 * Manejar gráfico de evolución de estados a lo largo del tiempo
 */
function handleEvolucionEstados($model) {
    // Obtener el período solicitado (por defecto 'dia')
    $periodo = $_GET['periodo'] ?? 'dia';
    $datos = $model->getEvolucionEstados($periodo);
    
    if (empty($datos)) {
        throw new Exception('No se encontraron datos de evolución de estados');
    }
    
    // Organizar datos para el gráfico de líneas/stacked bar
    $series = [];
    $categorias = [];
    $estadosMap = [];
    
    // Procesar datos
    foreach ($datos as $item) {
        $periodo = $item['periodo'];
        $estado = $item['estado_ot'];
        $estadoNombre = $item['estado_nombre'];
        $cantidad = (int) $item['cantidad'];
        
        // Agregar período a categorías si no existe
        if (!in_array($periodo, $categorias)) {
            $categorias[] = $periodo;
        }
        
        // Inicializar serie para este estado si no existe
        if (!isset($estadosMap[$estado])) {
            $estadosMap[$estado] = [
                'name' => $estadoNombre,
                'data' => [],
                'color' => getEstadoColor($estado)
            ];
        }
        
        // Agregar dato a la serie
        $estadosMap[$estado]['data'][$periodo] = $cantidad;
    }
    
    // Completar datos faltantes con 0
    foreach ($estadosMap as $estado => &$serie) {
        $datosCompletos = [];
        foreach ($categorias as $cat) {
            $datosCompletos[] = $serie['data'][$cat] ?? 0;
        }
        $serie['data'] = $datosCompletos;
    }
    
    // Convertir a array indexado
    $series = array_values($estadosMap);
    
    return [
        'success' => true,
        'data' => $series,
        'categories' => $categorias,
        'chart_type' => 'line',
        'periodo' => $periodo,
        'timestamp' => date('Y-m-d H:i:s')
    ];
}

/**
 * Obtener color según el estado de OT
 */
function getEstadoColor($estado) {
    switch ($estado) {
        case 1: return '#6c757d'; // Gris - Sin asignar
        case 2: return '#17a2b8'; // Cyan - Asignado
        case 3: return '#007bff'; // Azul - En proceso
        case 4: return '#ffc107'; // Amarillo - En pausa
        case 5: return '#fd7e14'; // Naranja - Apoyo solicitado
        case 6: return '#e83e8c'; // Rosa - Esperando aprobación
        case 7: return '#28a745'; // Verde - Finalizado
        case 8: return '#dc3545'; // Rojo - Rechazado
        case 9: return '#343a40'; // Negro - Eliminado
        default: return '#6c757d'; // Gris por defecto
    }
}

/**
 * Obtener color según el modo
 */
function getModoColor($modo) {
    switch ($modo) {
        case 0: return '#007bff'; // Azul - Recursos Internos
        case 1: return '#28a745'; // Verde - Subcontratado
        default: return '#6c757d'; // Gris - No Definido
    }
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
