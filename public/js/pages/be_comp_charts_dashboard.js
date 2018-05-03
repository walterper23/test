/*
 *  Document   : be_comp_charts_dashboard.js
 */

var BeCompCharts = function() {

    // Chart.js Charts, for more examples you can check out http://www.chartjs.org/docs
    var initChartsChartJS = function () {
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
        var chartLinesBarsRadarData, chartPolarPieDonutData;

        // Polar/Pie/Donut Data
        var chartPolarPieDonutData = {
            labels: [
                'Earnings',
                'Sales',
                'Tickets'
            ],
            datasets: [{
                data: [
                    50,
                    25,
                    25
                ],
                backgroundColor: [
                    'rgba(156,204,101,1)',
                    'rgba(255,202,40,1)',
                    'rgba(239,83,80,1)'
                ],
                hoverBackgroundColor: [
                    'rgba(156,204,101,.5)',
                    'rgba(255,202,40,.5)',
                    'rgba(239,83,80,.5)'
                ]
            }]
        };

        var chartLinesBarsRadarData = {
            labels: ['LUN', 'MAR', 'MIE', 'JUE', 'VIE'],
            datasets: [
                {
                    label: 'This Week',
                    fill: true,
                    backgroundColor: 'rgba(66,165,245,.75)',
                    borderColor: 'rgba(66,165,245,1)',
                    pointBackgroundColor: 'rgba(66,165,245,1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(66,165,245,1)',
                    data: [25, 38, 62, 45, 90, 115, 130]
                },
                {
                    label: 'Last Week',
                    fill: true,
                    backgroundColor: 'rgba(66,165,245,.25)',
                    borderColor: 'rgba(66,165,245,1)',
                    pointBackgroundColor: 'rgba(66,165,245,1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(66,165,245,1)',
                    data: [112, 90, 142, 130, 170, 188, 196]
                }
            ]
        };

        chartBars1  = new Chart(chartBarsCon1, { type: 'bar', data: chartLinesBarsRadarData });
        chartBars2  = new Chart(chartBarsCon2, { type: 'bar', data: chartLinesBarsRadarData });

        chartPie1   = new Chart(chartPieCon1, { type: 'pie', data: chartPolarPieDonutData });
        chartPie2   = new Chart(chartPieCon2, { type: 'pie', data: chartPolarPieDonutData });

    };

    return {
        init: function () {
            // Init Chart.js Charts
            initChartsChartJS();
        }
    };
}();

// Initialize when page loads
jQuery(function(){ BeCompCharts.init(); });