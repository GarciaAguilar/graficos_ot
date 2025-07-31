$(function () {
    function cargarGrafico() {
        $('#container').html('<div class="text-center"><i class="bx bx-loader"></i> Cargando datos...</div>');
        $.ajax({
            url: '../controllers/GraficosController.php?action=ordenes-por-prioridad',
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
                text: `Total de órdenes: ${json.total || 0}`
            },
            xAxis: {
                categories: json.data.map(item => item.name),
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
                formatter: function () {
                    return '<b>' + this.point.name + '</b><br/>' +
                        'Cantidad: ' + this.y + ' órdenes<br/>' +
                        'Porcentaje: ' + ((this.y / json.total) * 100).toFixed(1) + '%';
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
                data: json.data.map(item => ({
                    name: item.name,
                    y: item.y,
                    color: item.color
                }))
            }],
            credits: {
                enabled: false
            }
        });
    }

    cargarGrafico();
});