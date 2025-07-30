# graficos_ot

Sistema de visualización de datos para órdenes de trabajo usando Highcharts

## Gráficos Implementados

**Grafico 1:** Tipos de Mantenimiento (Pie Chart)
- Muestra la distribución entre mantenimiento preventivo vs correctivo
- Campo clave: `tipo_mantenimiento` (0=Preventivo, 1=Correctivo)

**Grafico 2:** Distribución por Prioridad (Barras Horizontales)  
- Visualiza las órdenes según su nivel de prioridad
- Campo clave: `prioridad` (0=Baja, 1=Media, 2=Alta, 3=Crítica)

**Grafico 3:** Dependencia de Recursos (Pie Chart)
- Mide la dependencia entre recursos internos vs externos subcontratados
- Campo clave: `modo` (0=Recursos Internos, 1=Subcontratado)

**Grafico 4:** Evolución de Estados (Líneas Múltiples)
- Visualiza cómo cambian los estados de OT a lo largo del tiempo por fecha de inicio
- Campo clave: `estado_ot` (1=Sin asignar, 2=Asignado, 3=En proceso, 4=En pausa, 5=Apoyo solicitado, 6=Esperando aprobación, 7=Finalizado, 8=Rechazado, 9=Eliminado)
*Nota: No estoy muy seguro si esos completamente los estados completos

**Grafico 5:** Eficacia y Aceptación (Donut Chart)
- Mide eficacia y aceptación de órdenes con indicadores
- Campo clave: `estado_ot` filtrado por Finalizado (7) y Rechazado (8)