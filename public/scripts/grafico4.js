/* GRÁFICO DE LÍNEAS PARA VER EVOLUCIÓN DE ESTADOS DE OT */
$(document).ready(function() {
    
    // Función para cargar el gráfico
    function cargarGrafico() {
        // Mostrar indicador de carga
        $('#container').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</div>');
        
        $.ajax({
            url: '../controllers/GraficosController.php?action=evolucion-estados',
            method: 'GET',
            dataType: 'json',
            timeout: 10000, // 10 segundos timeout
            success: function(response) {
                console.log('Respuesta del controlador específico:', response);
                
                if (response.success && response.data.length > 0) {
                    // Crear el gráfico con los datos recibidos
                    Highcharts.chart('container', {
                        chart: {
                            type: 'line',
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: 'Evolución de Estados de Órdenes de Trabajo',
                            style: {
                                fontSize: '18px',
                                fontWeight: 'bold'
                            }
                        },
                        subtitle: {
                            text: `Período: ${response.periodo || 'día'} - Últimos 30 días`
                        },
                        xAxis: {
                            categories: response.categories,
                            title: {
                                text: 'Período de Tiempo'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Cantidad de Órdenes'
                            },
                            min: 0
                        },
                        tooltip: {
                            formatter: function() {
                                return '<b>' + this.series.name + '</b><br/>' +
                                       'Período: ' + this.x + '<br/>' +
                                       'Cantidad: ' + this.y + ' órdenes';
                            }
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: true,
                                marker: {
                                    enabled: true,
                                    radius: 4
                                }
                            }
                        },
                        legend: {
                            align: 'center',
                            verticalAlign: 'bottom',
                            layout: 'horizontal'
                        },
                        series: response.data,
                        credits: {
                            enabled: false
                        },
                        responsive: {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
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
