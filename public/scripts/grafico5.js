$(function () {
    function cargarGrafico() {
        $('#container').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando datos...</div>');
        $.ajax({
            url: '../controllers/GraficosController.php?action=eficacia-ordenes',
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
                text: 'Eficacia y Aceptación de Órdenes',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold'
                }
            },
            subtitle: {
                text: `Total procesadas: ${json.total || 0} órdenes<br/>` +
                    `<span style="color: #28a745; font-weight: bold;">Eficacia: ${json.eficacia_porcentaje}%</span> | ` +
                    `<span style="color: #dc3545; font-weight: bold;">Rechazo: ${json.rechazo_porcentaje}%</span>`,
                useHTML: true
            },
            tooltip: {
                formatter: function () {
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
                    innerSize: '50%', 
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
            series: [{
                name: 'Estado de Órdenes',
                data: json.data
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
    }

    cargarGrafico();
});