/*GRAFICO DE BARRAS HORIZONTALES PARA VER PRIORIDADES DE ÓRDENES */
$(document).ready(function() {
    
    // Función para cargar el gráfico
    function cargarGrafico() {
        // Mostrar indicador de carga
        $('#container').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</div>');
        
        $.ajax({
            url: '../controllers/GraficosController.php?action=ordenes-por-prioridad',
            method: 'GET',
            dataType: 'json',
            timeout: 10000, // 10 segundos timeout
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    // Crear el gráfico con los datos recibidos
                    Highcharts.chart('container', {
                        chart: {
                            type: 'bar',
                            backgroundColor: 'transparent'
                        },
                        title: {
                            text: 'Distribución de Órdenes por Prioridad',
                            style: {
                                fontSize: '18px',
                                fontWeight: 'bold'
                            }
                        },
                        subtitle: {
                            text: `Total de órdenes: ${response.total || 0}`
                        },
                        xAxis: {
                            categories: response.data.map(item => item.name),
                            title: {
                                text: 'Nivel de Prioridad'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Cantidad de Órdenes',
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify'
                            }
                        },
                        tooltip: {
                            formatter: function() {
                                return '<b>' + this.point.name + '</b><br/>' +
                                       'Cantidad: ' + this.y + ' órdenes<br/>' +
                                       'Porcentaje: ' + ((this.y / response.total) * 100).toFixed(1) + '%';
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true,
                                    format: '{y} órdenes'
                                },
                                showInLegend: false
                            }
                        },
                        series: [{
                            name: 'Órdenes',
                            data: response.data.map(item => ({
                                name: item.name,
                                y: item.y,
                                color: item.color
                            }))
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
                                        bar: {
                                            dataLabels: {
                                                enabled: false
                                            }
                                        }
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
