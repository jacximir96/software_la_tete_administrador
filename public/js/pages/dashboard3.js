$(document).ready(function()  {
  'use strict'
  $.ajax({
        url: ruta + '/dashboard/datosActual/listarActual',
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        method: "GET",
        success: function(data) {
            var nombre = [];
            var stock = [];
            var color = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'];
            var bordercolor = ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'];

            var cronogramas = [
                data[0][0][0][0]['enero'],
                data[0][0][1][0]['febrero'],
                data[0][0][2][0]['marzo'],
                data[0][0][3][0]['abril'],
                data[0][0][4][0]['mayo'],
                data[0][0][5][0]['junio'],
                data[0][0][6][0]['julio'],
                data[0][0][7][0]['agosto'],
                data[0][0][8][0]['setiembre'],
                data[0][0][9][0]['octubre'],
                data[0][0][10][0]['noviembre'],
                data[0][0][11][0]['diciembre'],
            ];

            var cronogramas1 = [
                data[1][0][0][0]['enero'],
                data[1][0][1][0]['febrero'],
                data[1][0][2][0]['marzo'],
                data[1][0][3][0]['abril'],
                data[1][0][4][0]['mayo'],
                data[1][0][5][0]['junio'],
                data[1][0][6][0]['julio'],
                data[1][0][7][0]['agosto'],
                data[1][0][8][0]['setiembre'],
                data[1][0][9][0]['octubre'],
                data[1][0][10][0]['noviembre'],
                data[1][0][11][0]['diciembre'],
            ];

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
              }

              var mode      = 'index'
              var intersect = true

              var $salesChart = $('#sales-chart')
              var salesChart  = new Chart($salesChart, {
                type   : 'bar',
                data   : {
                  labels  : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                  datasets: [
                    {
                      backgroundColor: '#007bff',
                      borderColor    : '#007bff',
                      data           : cronogramas
                    },
                    {
                      backgroundColor: '#ced4da',
                      borderColor    : '#ced4da',
                      data           : cronogramas1
                    }
                  ]
                },
                options: {
                  maintainAspectRatio: false,
                  tooltips           : {
                    mode     : mode,
                    intersect: intersect
                  },
                  hover              : {
                    mode     : mode,
                    intersect: intersect
                  },
                  legend             : {
                    display: false
                  },
                  scales             : {
                    yAxes: [{
                      gridLines: {
                        display      : true,
                        lineWidth    : '4px',
                        color        : 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                      },
                      ticks    : $.extend({
                        beginAtZero: true,
                        callback: function (value, index, values) {

                          return value
                        }
                      }, ticksStyle)
                    }],
                    xAxes: [{
                      display  : true,
                      gridLines: {
                        display: false
                      },
                      ticks    : ticksStyle
                    }]
                  }
                }
              })
        }
    });
});
