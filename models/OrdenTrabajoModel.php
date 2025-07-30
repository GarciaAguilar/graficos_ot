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
}   
?>
