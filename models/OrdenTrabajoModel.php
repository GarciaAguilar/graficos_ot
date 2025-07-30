<?php
require_once 'BaseModel.php';

/*MODELO PARA LAS ÓRDENES DE TRABAJO */
class OrdenTrabajoModel extends BaseModel {
    
    protected $table = 'ordent';
    
    //Métodos para consultas
    
    /**
     * Obtener órdenes por tipo de mantenimiento
     */
    public function getOrdenesPorTipo() {
        $query = "SELECT 
                    tipo_mantenimiento,
                    COUNT(*) as cantidad,
                    CASE tipo_mantenimiento 
                        WHEN 0 THEN 'Preventivo'
                        WHEN 1 THEN 'Correctivo'
                        ELSE 'Otro'
                    END as tipo_nombre
                FROM {$this->table} 
                WHERE eliminado_por IS NULL
                GROUP BY tipo_mantenimiento, tipo_nombre
                ORDER BY tipo_mantenimiento";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener órdenes por prioridad
     */
    public function getOrdenesPorPrioridad() {
        $query = "SELECT 
                    prioridad,
                    COUNT(*) as cantidad,
                    CASE prioridad 
                        WHEN 0 THEN 'Baja'
                        WHEN 1 THEN 'Media'
                        WHEN 2 THEN 'Alta'
                        WHEN 3 THEN 'Crítica'
                        ELSE 'Sin Prioridad'
                    END as prioridad_nombre
                FROM {$this->table} 
                WHERE eliminado_por IS NULL
                GROUP BY prioridad, prioridad_nombre
                ORDER BY prioridad";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener órdenes por modo (recursos internos vs externos)
     */
    public function getOrdenesPorModo() {
        $query = "SELECT 
                    modo,
                    COUNT(*) as cantidad,
                    CASE modo 
                        WHEN 0 THEN 'Recursos Internos'
                        WHEN 1 THEN 'Subcontratado'
                        ELSE 'No Definido'
                    END as modo_nombre
                FROM {$this->table} 
                WHERE eliminado_por IS NULL
                GROUP BY modo, modo_nombre
                ORDER BY modo";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener evolución de estados por período
     */
    public function getEvolucionEstados($periodo = 'dia') {
        // Definir el formato de fecha según el período
        $formatoFecha = '';
        switch ($periodo) {
            case 'dia':
                $formatoFecha = 'DATE(fecha_inicio)';
                break;
            case 'semana':
                $formatoFecha = 'YEAR(fecha_inicio), WEEK(fecha_inicio)';
                break;
            case 'mes':
                $formatoFecha = 'YEAR(fecha_inicio), MONTH(fecha_inicio)';
                break;
            default:
                $formatoFecha = 'DATE(fecha_inicio)';
        }
        
        $query = "SELECT 
                    {$formatoFecha} as periodo,
                    estado_ot,
                    COUNT(*) as cantidad,
                    CASE estado_ot 
                        WHEN 1 THEN 'Sin asignar'
                        WHEN 2 THEN 'Asignado'
                        WHEN 3 THEN 'En proceso'
                        WHEN 4 THEN 'En pausa'
                        WHEN 5 THEN 'Apoyo solicitado'
                        WHEN 6 THEN 'Esperando aprobación'
                        WHEN 7 THEN 'Finalizado'
                        WHEN 8 THEN 'Rechazado'
                        WHEN 9 THEN 'Eliminado'
                        ELSE 'Desconocido'
                    END as estado_nombre
                FROM {$this->table} 
                WHERE eliminado_por IS NULL 
                  AND fecha_inicio >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                  AND fecha_inicio IS NOT NULL
                GROUP BY periodo, estado_ot, estado_nombre
                ORDER BY periodo, estado_ot";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}   
?>
