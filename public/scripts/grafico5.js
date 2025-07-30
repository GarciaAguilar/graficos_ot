/* GRÁFICO DONUT PARA VER EFICACIA Y ACEPTACIÓN DE ÓRDENES */
$(document).ready(function() {
    
    // Función para cargar el gráfico
    function cargarGrafico() {
        // Mostrar indicador de carga
        $('#container').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</div>');
        
        $.ajax({
            url: '../controllers/GraficosController.php?action=eficacia-ordenes',
            method: 'GET',
            dataType: 'json',
            timeout: 10000, // 10 segundos timeout
            success: function(response) {
                console.log('Respuesta del controlador específico:', response);
                
                if (response.success && response.data.length > 0) {
                    // Crear el gráfico donut con KPIs
                    Highcharts.chart('container', {
                        chart: {
                            type: 'pie',
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: 'Eficacia y Aceptación de Órdenes',
                            style: {
                                fontSize: '18px',
                                fontWeight: 'bold'
                            }
                        },
                        subtitle: {
                            text: `Total procesadas: ${response.total || 0} órdenes<br/>` +
                                  `<span style="color: #28a745; font-weight: bold;">Eficacia: ${response.eficacia_porcentaje}%</span> | ` +
                                  `<span style="color: #dc3545; font-weight: bold;">Rechazo: ${response.rechazo_porcentaje}%</span>`,
                            useHTML: true
                        },
                        tooltip: {
                            formatter: function() {
                                const kpi = this.point.estado_id === 7 ? 'Eficacia' : 'Tasa de Rechazo';
                                return '<b>' + this.point.name + '</b><br/>' +
                                       'Cantidad: ' + this.y + ' órdenes<br/>' +
                                       'Porcentaje: ' + this.percentage.toFixed(1) + '%<br/>' +
                                       '<i>KPI: ' + kpi + '</i>';
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b><br/>{point.percentage:.1f}%<br/>({point.y} órdenes)',
                                    style: {
                                        fontWeight: 'bold',
                                        fontSize: '12px'
                                    },
                                    distance: 20
                                },
                                showInLegend: true,
                                innerSize: '50%', // Esto hace que sea un donut chart
                                size: '80%',
                                center: ['50%', '50%']
                            }
                        },
                        legend: {
                            align: 'center',
                            verticalAlign: 'bottom',
                            layout: 'horizontal',
                            itemStyle: {
                                fontSize: '14px',
                                fontWeight: 'bold'
                            }
                        },
                        // Agregar texto en el centro del donut
                        annotations: [{
                            labels: [{
                                point: {
                                    x: 0,
                                    y: 0,
                                    xAxis: 0,
                                    yAxis: 0
                                },
                                text: `<div style="text-align: center;">
                                         <div style="font-size: 24px; font-weight: bold; color: #333;">
                                           ${response.eficacia_porcentaje}%
                                         </div>
                                         <div style="font-size: 14px; color: #666;">
                                           Eficacia
                                         </div>
                                       </div>`,
                                useHTML: true,
                                style: {
                                    fontSize: '16px',
                                    textAlign: 'center'
                                }
                            }]
                        }],
                        series: [{
                            name: 'Estado de Órdenes',
                            data: response.data
                        }],
                        credits: {
                            enabled: false
                        },
                        responsive: {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    plotOptions: {
                                        pie: {
                                            dataLabels: {
                                                format: '{point.percentage:.1f}%',
                                                distance: 10
                                            },
                                            size: '90%'
                                        }
                                    },
                                    legend: {
                                        align: 'center',
                                        verticalAlign: 'bottom',
                                        layout: 'horizontal'
                                    }
                                }
                            }]
                        }
                    });
                } else {
                    $('#container').html('<div class="alert alert-warning">No hay datos disponibles para mostrar</div>');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error en AJAX:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    response: xhr.responseJSON
                });
                let errorMessage = 'Error al cargar los datos';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $('#container').html('<div class="alert alert-danger">' + errorMessage + '</div>');
            }
        });
    }
    
    // Cargar el gráfico al iniciar
    cargarGrafico();
    
    
});
