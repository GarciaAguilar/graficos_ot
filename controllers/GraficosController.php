<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

class GraficosController {
    
    private $model;
    
    public function __construct() {
        // Incluir el modelo
        require_once '../models/OrdenTrabajoModel.php';
        $this->model = new OrdenTrabajoModel();
    }
    
    /**
     * Procesar solicitud y enrutar a método correspondiente
     */
    public function procesarSolicitud() {
        try {
            // Obtener la acción solicitada
            $action = $_GET['action'] ?? null;
            
            if (!$action) {
                throw new Exception('Parámetro action es requerido');
            }
            
            // Procesar según la acción solicitada
            switch ($action) {
                case 'ordenes-por-tipo':
                    $result = $this->handleOrdenesPorTipo();
                    break;
                    
                case 'ordenes-por-prioridad':
                    $result = $this->handleOrdenesPorPrioridad();
                    break;
                    
                case 'ordenes-por-modo':
                    $result = $this->handleOrdenesPorModo();
                    break;
                    
                case 'evolucion-estados':
                    $result = $this->handleEvolucionEstados();
                    break;
                    
                case 'eficacia-ordenes':
                    $result = $this->handleEficaciaOrdenes();
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
    }
    
    /**
     * Manejar gráfico de órdenes por tipo 
     */
    private function handleOrdenesPorTipo() {
        $datos = $this->model->getOrdenesPorTipo();
        
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
    private function handleOrdenesPorPrioridad() {
        $datos = $this->model->getOrdenesPorPrioridad();
        
        if (empty($datos)) {
            throw new Exception('No se encontraron datos de órdenes por prioridad');
        }
        
        $chartData = array_map(function($item) {
            return [
                'name' => $item['prioridad_nombre'],
                'y' => (int) $item['cantidad'],
                'prioridad_id' => (int) $item['prioridad'],
                'color' => $this->getPrioridadColor((int) $item['prioridad'])
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
    private function handleOrdenesPorModo() {
        $datos = $this->model->getOrdenesPorModo();
        
        if (empty($datos)) {
            throw new Exception('No se encontraron datos de órdenes por modo');
        }
        
        $chartData = array_map(function($item) {
            return [
                'name' => $item['modo_nombre'],
                'y' => (int) $item['cantidad'],
                'modo_id' => (int) $item['modo'],
                'color' => $this->getModoColor((int) $item['modo'])
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
     * Manejar gráfico de eficacia de órdenes (Finalizadas vs Rechazadas)
     */
    private function handleEficaciaOrdenes() {
        $datos = $this->model->getEficaciaOrdenes();
        
        if (empty($datos)) {
            throw new Exception('No se encontraron datos de eficacia de órdenes');
        }
        
        // Calcular totales y porcentajes para KPIs
        $finalizadas = 0;
        $rechazadas = 0;
        $chartData = [];
        
        foreach ($datos as $item) {
            $cantidad = (int) $item['cantidad'];
            if ($item['estado_ot'] == 7) { // Finalizadas
                $finalizadas = $cantidad;
            } elseif ($item['estado_ot'] == 8) { // Rechazadas
                $rechazadas = $cantidad;
            }
            
            $chartData[] = [
                'name' => $item['estado_nombre'],
                'y' => $cantidad,
                'estado_id' => (int) $item['estado_ot'],
                'color' => $this->getEficaciaColor((int) $item['estado_ot'])
            ];
        }
        
        $total = $finalizadas + $rechazadas;
        $eficacia = $total > 0 ? round(($finalizadas / $total) * 100, 1) : 0;
        $rechazo = $total > 0 ? round(($rechazadas / $total) * 100, 1) : 0;
        
        return [
            'success' => true,
            'data' => $chartData,
            'total' => $total,
            'finalizadas' => $finalizadas,
            'rechazadas' => $rechazadas,
            'eficacia_porcentaje' => $eficacia,
            'rechazo_porcentaje' => $rechazo,
            'chart_type' => 'donut',
            'kpis' => [
                'eficacia' => $eficacia,
                'aceptacion' => $eficacia,
                'rechazo' => $rechazo,
                'total_procesadas' => $total
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Manejar gráfico de evolución de estados a lo largo del tiempo
     */
    private function handleEvolucionEstados() {
        // Obtener el período solicitado (por defecto 'dia')
        $periodo = $_GET['periodo'] ?? 'dia';
        $datos = $this->model->getEvolucionEstados($periodo);
        
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
                    'color' => $this->getEstadoColor($estado)
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
     * Obtener color según el resultado de eficacia
     */
    private function getEficaciaColor($estado) {
        switch ($estado) {
            case 7: return '#28a745'; // Verde - Finalizadas (éxito)
            case 8: return '#dc3545'; // Rojo - Rechazadas (fallo)
            default: return '#6c757d'; // Gris por defecto
        }
    }
    
    /**
     * Obtener color según el estado de OT - Evolución de estados
     */
    private function getEstadoColor($estado) {
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
     * Obtener color según el modo - Ordenes por modo
     */
    private function getModoColor($modo) {
        switch ($modo) {
            case 0: return '#007bff'; // Azul - Recursos Internos
            case 1: return '#28a745'; // Verde - Subcontratado
            default: return '#6c757d'; // Gris - No Definido
        }
    }
    
    /**
     * Obtener color según la prioridad - Ordenes por prioridad
     */
    private function getPrioridadColor($prioridad) {
        switch ($prioridad) {
            case 0: return '#28a745'; // Verde - Baja
            case 1: return '#ffc107'; // Amarillo - Media
            case 2: return '#fd7e14'; // Naranja - Alta
            case 3: return '#dc3545'; // Rojo - Crítica
            default: return '#6c757d'; // Gris - Sin prioridad
        }
    }
}

// Instanciar y ejecutar el controlador
$controller = new GraficosController();
$controller->procesarSolicitud();

?>
