$(function () {
    function cargarGrafico() {
        $('#container').html('<div class="text-center"><i class="bx bx-loader"></i> Cargando datos...</div>');
        $.ajax({
            url: '../controllers/GraficosController.php?action=ordenes-por-tipo',
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
                text: `Total de órdenes: ${json.total || 0}`
            },
            tooltip: {
                formatter: function () {
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
                    showInLegend: true
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Tipo de Mantenimiento',
                data: json.data
            }],
        });
    }

    cargarGrafico();
});