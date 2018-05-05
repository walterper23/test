/*
 *  Document   : be_comp_charts_dashboard.js
 */

var BeCompCharts = function() {

    // Chart.js Charts, for more examples you can check out http://www.chartjs.org/docs
    var initChartsChartJS = function ( data ) {
        // Set Global Chart.js configuration
        Chart.defaults.global.defaultFontColor              = '#555555';
        Chart.defaults.scale.gridLines.color                = "rgba(0,0,0,.04)";
        Chart.defaults.scale.gridLines.zeroLineColor        = "rgba(0,0,0,.1)";
        Chart.defaults.scale.ticks.beginAtZero              = true;
        Chart.defaults.global.elements.line.borderWidth     = 2;
        Chart.defaults.global.elements.point.radius         = 5;
        Chart.defaults.global.elements.point.hoverRadius    = 7;
        Chart.defaults.global.tooltips.cornerRadius         = 3;
        Chart.defaults.global.legend.labels.boxWidth        = 12;

        // Get Chart Containers
        var chartBarsCon1   = jQuery('#js-chartjs-bars-1');
        var chartPieCon1    = jQuery('#js-chartjs-pie-1');
        var chartBarsCon2   = jQuery('#js-chartjs-bars-2');
        var chartPieCon2    = jQuery('#js-chartjs-pie-2');

        // Set Chart and Chart Data variables
        var documentosHoy, documentosSemana, documentosMes, documentosAnual;

        var documentosHoy = {
            labels: data.hoy.labels,
            datasets: [
                {
                    label: 'Recepción local',
                    fill: true,
                    backgroundColor: 'rgba(66,165,245,.75)',
                    borderColor: 'rgba(66,165,245,1)',
                    pointBackgroundColor: 'rgba(66,165,245,1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(66,165,245,1)',
                    data: data.hoy.locales
                },
                {
                    label: 'Recepción foránea',
                    fill: true,
                    backgroundColor: 'rgba(156,204,101,.75)',
                    borderColor: 'rgba(156,204,101,1)',
                    pointBackgroundColor: 'rgba(66,165,245,1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(66,165,245,1)',
                    data: data.hoy.foraneos
                }
            ]
        };

        var documentosSemana = {
            labels: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie'],
            datasets: [
                {
                    label: 'Denuncia',
                    fill: true,
                    backgroundColor: 'rgba(234,28,24,.75)',
                    borderColor: 'rgba(189,21,17,1)',
                    pointBackgroundColor: 'rgba(66,165,245,1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(66,165,245,1)',
                    data: data.semana.denuncias
                },
                {
                    label: 'Docto. denuncia',
                    fill: true,
                    backgroundColor: 'rgba(66,165,245,.75)',
                    borderColor: 'rgba(66,165,245,1)',
                    pointBackgroundColor: 'rgba(66,165,245,1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(66,165,245,1)',
                    data: data.semana.doctos_denuncias
                },
                {
                    label: 'Documentos',
                    fill: true,
                    backgroundColor: 'rgba(156,204,101,.75)',
                    borderColor: 'rgba(156,204,101,1)',
                    pointBackgroundColor: 'rgba(66,165,245,1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(66,165,245,1)',
                    data: data.semana.documentos
                }
            ]
        };

        // Polar/Pie/Donut Data
        var documentosMes = {
            labels: [
                'En seguimiento',
                'Resueltos',
                'Rechazados'
            ],
            datasets: [{
                data: data.mes,
                backgroundColor: [
                    'rgba(239,83,80,1)',
                    'rgba(156,204,101,1)',
                    'rgba(255,202,40,1)',
                ],
                hoverBackgroundColor: [
                    'rgba(239,83,80,.5)',
                    'rgba(156,204,101,.5)',
                    'rgba(255,202,40,.5)',
                ]
            }]
        };

        var documentosAnual = {
            labels: [
                'En seguimiento',
                'Resueltos',
                'Rechazados'
            ],
            datasets: [{
                data: data.anual,
                backgroundColor: [
                    'rgba(239,83,80,1)',
                    'rgba(156,204,101,1)',
                    'rgba(255,202,40,1)',
                ],
                hoverBackgroundColor: [
                    'rgba(239,83,80,.5)',
                    'rgba(156,204,101,.5)',
                    'rgba(255,202,40,.5)',
                ]
            }]
        };

        chartBars1  = new Chart(chartBarsCon1, { type: 'bar', data: documentosHoy });
        chartBars2  = new Chart(chartBarsCon2, { type: 'bar', data: documentosSemana });

        chartPie1   = new Chart(chartPieCon1, { type: 'pie', data: documentosMes });
        chartPie2   = new Chart(chartPieCon2, { type: 'pie', data: documentosAnual });

    };

    return {
        init: function () {
            App.ajaxRequest({
                url : '/manager',
                data  : { action : 'reporte-documentos'},
                success : function(result){
                    initChartsChartJS( result );
                }
            });
        }
    };
}();

// Initialize when page loads
jQuery(function(){ BeCompCharts.init(); });