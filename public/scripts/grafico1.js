/*GRAFICO DE PASTEL PARA VER TIPOS DE MANTENIMIENTO */
$(document).ready(function() {
    
    // Función para cargar el gráfico
    function cargarGrafico() {
        // Mostrar indicador de carga
        $('#container').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</div>');
        
        $.ajax({
            url: '../controllers/GraficosController.php?action=ordenes-por-tipo',
            method: 'GET',
            dataType: 'json',
            timeout: 10000, // 10 segundos timeout
            success: function(response) {
                console.log('Respuesta del controlador específico:', response);
                
                if (response.success && response.data.length > 0) {
                    // Crear el gráfico con los datos recibidos
                    Highcharts.chart('container', {
                        chart: {
                            type: 'pie',
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: 'Tipos de Mantenimiento',
                            style: {
                                fontSize: '18px',
                                fontWeight: 'bold'
                            }
                        },
                        subtitle: {
                            text: `Total de órdenes: ${response.total || 0}`
                        },
                        tooltip: {
                            formatter: function() {
                                return '<b>' + this.point.name + '</b><br/>' +
                                       'Cantidad: ' + this.y + ' órdenes<br/>' +
                                       'Porcentaje: ' + this.percentage.toFixed(1) + '%';
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                                    style: {
                                        fontWeight: 'bold'
                                    }
                                },
                                showInLegend: true,
                                colors: ['#28a745', '#dc3545'] // Verde para preventivo, rojo para correctivo
                            }
                        },
                        legend: {
                            align: 'center',
                            verticalAlign: 'bottom',
                            layout: 'horizontal'
                        },
                        series: [{
                            name: 'Tipo de Mantenimiento',
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