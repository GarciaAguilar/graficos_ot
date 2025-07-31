<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

require_once '../models/OrdenTrabajoModel.php';
class GraficosController
{
    private $model;

    public function __construct()
    {
        $this->model = new OrdenTrabajoModel();
    }

    public function procesarSolicitud()
    {
        try {
            $action = $_GET['action'] ?? null;

            if (!$action) {
                throw new Exception('Parámetro action es requerido');
            }

            switch ($action) {
                case 'ordenes-por-tipo':
                    $result = $this->ordenesPorTipo();
                    break;
                case 'ordenes-por-prioridad':
                    $result = $this->ordenesPorPrioridad();
                    break;
                case 'ordenes-por-modo':
                    $result = $this->ordenesPorModo();
                    break;
                case 'evolucion-estados':
                    $result = $this->evolucionEstados();
                    break;
                case 'eficacia-ordenes':
                    $result = $this->eficaciaOrdenes();
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
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    //<<<<Acciones a manejar>>>>
    private function ordenesPorTipo()
    {
        $datos = $this->model->getOrdenesPorTipo();

        if (empty($datos)) {
            throw new Exception('No se encontraron datos de órdenes por tipo');
        }

        $chartData = array_map(function ($item) {
            return [
                'name' => $item['tipo_nombre'],
                'y' => (int) $item['cantidad'],
                'tipo_id' => (int) $item['tipo_mantenimiento'],
                'color' => $this->getTipoColor((int) $item['tipo_mantenimiento'])
            ];
        }, $datos);

        return [
            'success' => true,
            'data' => $chartData,
            'total' => array_sum(array_column($chartData, 'y')),
        ];
    }

    private function ordenesPorPrioridad()
    {
        $datos = $this->model->getOrdenesPorPrioridad();

        if (empty($datos)) {
            throw new Exception('No se encontraron datos de órdenes por prioridad');
        }

        $chartData = array_map(function ($item) {
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
        ];
    }

    private function ordenesPorModo()
    {
        $datos = $this->model->getOrdenesPorModo();

        if (empty($datos)) {
            throw new Exception('No se encontraron datos de órdenes por modo');
        }

        $chartData = array_map(function ($item) {
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
        ];
    }

    private function eficaciaOrdenes()
    {
        $datos = $this->model->getEficaciaOrdenes();

        if (empty($datos)) {
            throw new Exception('No se encontraron datos de eficacia de órdenes');
        }

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
            'eficacia_porcentaje' => $eficacia,
            'rechazo_porcentaje' => $rechazo
        ];
    }

    private function evolucionEstados()
    {
        $datos = $this->model->getEvolucionEstados();

        if (empty($datos)) {
            throw new Exception('No se encontraron datos de evolución de estados');
        }

        $series = [];
        $categorias = [];
        $estadosMap = [];

        foreach ($datos as $item) {
            $periodo = $item['periodo'];
            $estado = $item['estado_ot'];
            $estadoNombre = $item['estado_nombre'];
            $cantidad = (int) $item['cantidad'];

            if (!in_array($periodo, $categorias)) {
                $categorias[] = $periodo;
            }
            if (!isset($estadosMap[$estado])) {
                $estadosMap[$estado] = [
                    'name' => $estadoNombre,
                    'data' => [],
                    'color' => $this->getEvolucionColor($estado)
                ];
            }
            $estadosMap[$estado]['data'][$periodo] = $cantidad;
        }

        foreach ($estadosMap as $estado => &$serie) {
            $datosCompletos = [];
            foreach ($categorias as $cat) {
                $datosCompletos[] = $serie['data'][$cat] ?? 0;
            }
            $serie['data'] = $datosCompletos;
        }

        $series = array_values($estadosMap);

        return [
            'success' => true,
            'data' => $series,
            'categories' => $categorias,
        ];
    }

    //<<<<Configuracion de colores para enviar en la data>>>>
    private function getTipoColor($modo)
    {
        switch ($modo) {
            case 0:
                return '#28a745'; // Preventivo
            case 1:
                return '#dc3545'; // Correctivo
            default:
                return '#6c757d'; // Por defecto
        }
    }
    private function getPrioridadColor($prioridad)
    {
        switch ($prioridad) {
            case 0:
                return '#28a745'; // Baja
            case 1:
                return '#ffc107'; // Media
            case 2:
                return '#fd7e14'; // Alta
            case 3:
                return '#dc3545'; // Crítica
            default:
                return '#6c757d'; // Por defecto
        }
    }

    private function getModoColor($modo)
    {
        switch ($modo) {
            case 0:
                return '#007bff'; // Recursos Internos
            case 1:
                return '#28a745'; // Subcontratado
            default:
                return '#6c757d'; // Por defecto
        }
    }
    private function getEvolucionColor($estado)
    {
        switch ($estado) {
            case 1:
                return '#6c757d'; // Sin asignar
            case 2:
                return '#17a2b8'; // Asignado
            case 3:
                return '#007bff'; // En proceso
            case 4:
                return '#ffc107'; // En pausa
            case 5:
                return '#fd7e14'; // Apoyo solicitado
            case 6:
                return '#e83e8c'; // Esperando aprobación
            case 7:
                return '#28a745'; // Finalizado
            case 8:
                return '#dc3545'; // Rechazado
            case 9:
                return '#343a40'; // Eliminado
            default:
                return '#6c757d'; //Por defecto
        }
    }

    private function getEficaciaColor($estado)
    {
        switch ($estado) {
            case 7:
                return '#28a745'; // Finalizadas
            case 8:
                return '#dc3545'; // Rechazadas
            default:
                return '#6c757d'; // Por defecto
        }
    }
}

$controller = new GraficosController();
$controller->procesarSolicitud();
