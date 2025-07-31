$(function () {
    function cargarGrafico() {
        $('#container').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</div>');
        $.ajax({
            url: '../controllers/GraficosController.php?action=evolucion-estados',
            method: 'GET',
            dataType: 'json',
        }).done(function (json) {
            if (json.success && json.data.length > 0) {
                construirGrafico(json);
            } else {
                $('#container').html('<div class="alert alert-warning">No hay datos disponibles para mostrar</div>');
            }
        }).fail(function (xhr, status, errorThrown) {
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        }).always(function (xhr, status) {
            console.log("Solicitud completada: " + status);
        });
    }

    function construirGrafico(json) {
        Highcharts.chart('container', {
            chart: {
                type: 'line',
                backgroundColor: 'transparent'
            },
            title: {
                text: 'Evolución de Estados de Órdenes de Trabajo',
                align: 'left',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold'
                }
            },
            subtitle: {
                text: 'Últimos 30 días',
                align: 'left'
            },
            xAxis: {
                categories: json.categories,
                title: {
                    text: 'Período de Tiempo'
                },
                accessibility: {
                    rangeDescription: `Rango: ${json.categories[0]} a ${json.categories[json.categories.length - 1]}`
                }
            },
            yAxis: {
                title: {
                    text: 'Cantidad de Órdenes'
                },
                min: 0
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                        'Período: ' + this.x + '<br/>' +
                        'Cantidad: ' + this.y + ' órdenes';
                }
            },
            plotOptions: {
                line: {
                    label: {
                        connectorAllowed: false
                    },
                    marker: {
                        enabled: true,
                        radius: 4
                    }
                }
            },
            series: json.data,
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 750
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            },
            credits: {
                enabled: false
            }
        });
    }

    cargarGrafico();
});