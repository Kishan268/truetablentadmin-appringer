$(function() {
  'use strict';


  var colors = {
    primary        : "#14BC9A",
    secondary      : "#7987a1",
    success        : "#14BC9A",
    info           : "#66d1d1",
    warning        : "#fbbc06",
    danger         : "#ff3366",
    light          : "#e9ecef",
    dark           : "#060c17",
    muted          : "#7987a1",
    gridBorder     : "rgba(77, 138, 240, .15)",
    bodyColor      : "#000",
    cardBg         : "#fff"
  }

  var fontFamily = "'Roboto', Helvetica, sans-serif"




  // Bar chart
  if($('#chartjsBar').length) {
    new Chart($("#chartjsBar"), {
      type: 'bar',
      data: {
        labels: [ "China", "America", "India", "Germany", "Oman"],
        datasets: [
          {
            label: "Population",
            backgroundColor: [colors.primary, colors.danger, colors.warning, colors.success, colors.info],
            data: [2478,5267,734,2084,1433],
          }
        ]
      },
      options: {
        plugins: {
          legend: { display: false },
        },
        scales: {
          x: {
            display: true,
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
          y: {
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          }
        }
      }
    });
  }




  // Line Chart
  if($('#chartjsLine').length) {
    new Chart($('#chartjsLine'), {
      type: 'line',
      data: {
        labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
        datasets: [{ 
            data: [86,114,106,106,107,111,133,221,783,2478],
            label: "Africa",
            borderColor: colors.info,
            backgroundColor: "transparent",
            fill: true,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            tension: .3
          }, { 
            data: [282,350,411,502,635,809,947,1402,3700,5267],
            label: "Asia",
            borderColor: colors.danger,
            backgroundColor: "transparent",
            fill: true,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            tension: .3
          }
        ]
      },
      options: {
        plugins: {
          legend: { 
            display: true,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        scales: {
          x: {
            display: true,
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
          y: {
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          }
        }
      }
    });
  }

  // Doughnut Chart
  if($('#chartjsDoughnut').length) {
    new Chart($('#chartjsDoughnut'), {
      type: 'doughnut',
      data: {
        labels: companies_by_industries_graph['labels'],
        datasets: [
          {
            label: "COMPANIES BY INDUSTRIES",
            backgroundColor: ['#553AFE', '#01C0F6', '#DFDC27','#033270','#D4A276','#FF8000','#5BC0BE','#F26A4F','#FA699D','#20C9AC'],
            borderColor: colors.cardBg,
            data: companies_by_industries_graph['data'],
          }
        ]
      },
      options: {
        aspectRatio: 2,
        plugins: {
          legend: { 
            display: false,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        responsive: true,
      cutout: "90%"
      }
      
    });
  }

  // Doughnut Chart
  if($('#jobsDoughnut').length) {
    new Chart($('#jobsDoughnut'), {
      type: 'doughnut',
      data: {
        labels: jobs_by_industries_graph['labels'],
        datasets: [
          {
            label: "JOBS BY INDUSTRIES",
            backgroundColor: ['#553AFE', '#01C0F6', '#DFDC27','#033270','#D4A276','#FF8000','#5BC0BE','#F26A4F','#FA699D','#20C9AC'],
            borderColor: colors.cardBg,
            data: jobs_by_industries_graph['data'],
          }
        ]
      },
      options: {
        aspectRatio: 2,
        plugins: {
          legend: { 
            display: false,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        responsive: true,
      cutout: "90%"
      }
      
    });
  }




  // Area Chart
  if($('#chartjsArea').length) {
    new Chart($('#chartjsArea'), {
      type: 'line',
      data: {
        labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
        datasets: [{ 
            data: [86,114,106,106,107,111,133,221,783,2478],
            label: "Africa",
            borderColor: colors.danger,
            backgroundColor: 'rgba(255,51,102,.3)',
            fill: true,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            tension: .3
          }, { 
            data: [282,350,411,502,635,809,947,1402,3700,5267],
            label: "Asia",
            borderColor: colors.info,
            backgroundColor: 'rgba(102,209,209,.3)',
            fill: true,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            tension: .3
          }
        ]
      },
      options: {
        plugins: {
          legend: { 
            display: true,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        scales: {
          x: {
            display: true,
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
          y: {
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          }
        }
      }
    });
  }




  // Pie Chart
  if($('#chartjsPie').length) {
    new Chart($('#chartjsPie'), {
      type: 'pie',
      data: {
        labels: ["Africa", "Asia", "Europe"],
        datasets: [{
          label: "Population (millions)",
          backgroundColor: [colors.primary, colors.danger, colors.info],
          borderColor: colors.cardBg,
          data: [2478,4267,1334]
        }]
      },
      options: {
        plugins: {
          legend: { 
            display: true,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        aspectRatio: 2,
      }
    });
  }




  // Bubble Chart
  if($('#chartjsBubble').length) {
    new Chart($('#chartjsBubble'), {
      type: 'bubble',
      data: {
        labels: "Africa",
        datasets: [
          {
            label: ["China"],
            backgroundColor: 'rgba(102,209,209,.3)',
            borderColor: colors.info,
            data: [{
              x: 21269017,
              y: 5.245,
              r: 15
            }]
          }, {
            label: ["Denmark"],
            backgroundColor: "rgba(255,51,102,.3)",
            borderColor: colors.danger,
            data: [{
              x: 258702,
              y: 7.526,
              r: 10
            }]
          }, {
            label: ["Germany"],
            backgroundColor: "rgba(101,113,255,.3)",
            borderColor: colors.primary,
            data: [{
              x: 3979083,
              y: 6.994,
              r: 15
            }]
          }, {
            label: ["Japan"],
            backgroundColor: "rgba(251,188,6,.3)",
            borderColor: colors.warning,
            data: [{
              x: 4931877,
              y: 5.921,
              r: 15
            }]
          }
        ]
      },
      options: {
        plugins: {
          legend: { 
            display: true,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        scales: {
          x: { 
            display: true,
            title: {
              display: true,
              text: "GDP (PPP)"
            },
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
          y: { 
            display: true,
            title: {
              display: true,
              text: "Happiness"
            },
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
        }
      }
    });
  }




  // Radar Chart
  if($('#chartjsRadar').length) {
    new Chart($('#chartjsRadar'), {
      type: 'radar',
      data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [
          {
            label: "1950",
            fill: true,
            backgroundColor: "rgba(255,51,102,.3)",
            borderColor: colors.danger,
            pointBorderColor: colors.danger,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            data: [8.77,55.61,21.69,6.62,6.82]
          }, {
            label: "2050",
            fill: true,
            backgroundColor: "rgba(102,209,209,.3)",
            borderColor: colors.info,
            pointBorderColor: colors.info,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            data: [25.48,54.16,7.61,8.06,4.45]
          }
        ]
      },
      options: {
        aspectRatio: 2,
        scales: {
          r: {
            angleLines: {
              display: true,
              color: colors.gridBorder,
            },
            grid: {
              color: colors.gridBorder
            },
            suggestedMin: 0,
            suggestedMax: 60,
            ticks: {
              backdropColor: colors.cardBg,
              color: colors.bodyColor,
              font: {
                size: 11,
                family: fontFamily
              }
            },
            pointLabels: {
              color: colors.bodyColor,
              font: {
                color: colors.bodyColor,
                family: fontFamily,
                size: '13px'
              }
            }
          }
        },
        plugins: {
          legend: { 
            display: true,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
      }
    });
  }




  // Polar Area Chart
  if($('#chartjsPolarArea').length) {
    new Chart($('#chartjsPolarArea'), {
      type: 'polarArea',
      data: {
        labels: ["Africa", "Asia", "Europe", "Latin America"],
        datasets: [
          {
            label: "Population (millions)",
            backgroundColor: [colors.primary, colors.danger, colors.success, colors.info],
            borderColor: colors.cardBg,
            data: [3578,5000,1034,2034]
          }
        ]
      },
      options: {
        aspectRatio: 2,
        scales: {
          r: {
            angleLines: {
              display: true,
              color: colors.gridBorder,
            },
            grid: {
              color: colors.gridBorder
            },
            suggestedMin: 1000,
            suggestedMax: 5500,
            ticks: {
              backdropColor: colors.cardBg,
              color: colors.bodyColor,
              font: {
                size: 11,
                family: fontFamily
              }
            },
            pointLabels: {
              color: colors.bodyColor,
              font: {
                color: colors.bodyColor,
                family: fontFamily,
                size: '13px'
              }
            }
          }
        },
        plugins: {
          legend: { 
            display: true,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
      }
    });
  }



  // Grouped Bar Chart
  if($('#chartjsGroupedBar').length) {
    new Chart($('#chartjsGroupedBar'), {
      type: 'bar',
      data: {
        labels: graph1_data.labels,
        datasets: graph1_data.datasets
      },
      options: {
        plugins: {
          legend: { 
            display: true,
            labels: {
              usePointStyle: true,
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        scales: {
          x: {
            display: false,
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
          y: {
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          }
        },
        responsive: true,
        borderRadius: 10,
        barThickness: 5,
      }
    });
  }




  // Mixed Line Bar Chart
  if($('#chartjsMixedBar').length) {
    new Chart($('#chartjsMixedBar'), {
      type: 'bar',
      data: {
        labels: ["1900", "1950", "1999", "2050"],
        datasets: [{
            label: "Europe",
            type: "line",
            borderColor: colors.danger,
            backgroundColor: "transparent",
            data: [408,547,675,734],
            fill: false,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            tension: .3
          }, {
            label: "Africa",
            type: "line",
            borderColor: colors.primary,
            backgroundColor: "transparent",
            data: [133,221,783,2478],
            fill: false,
            pointBackgroundColor: colors.cardBg,
            pointBorderWidth: 2,
            pointHoverBorderWidth: 3,
            tension: .3
          }, {
            label: "Europe",
            type: "bar",
            backgroundColor: colors.danger,
            data: [408,547,675,734],
          }, {
            label: "Africa",
            type: "bar",
            backgroundColor: colors.primary,
            data: [133,221,783,2478]
          }
        ]
      },
      options: {
        plugins: {
          legend: { 
            display: true,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        scales: {
          x: {
            display: true,
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
          y: {
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          }
        }
      }
    });
  }

  //code for candidate tabs............
  if($('#customersChart').length) {
    new Chart($('#customersChart'), {
      type: 'doughnut',
      data: {
        labels: candidates_by_industries_graph['labels'],
        datasets: [
          {
            label: "MALE-FEMALE RATIO",
            backgroundColor: ['#033270','#553AFE','#14BC9A'],
            borderColor: colors.cardBg,
            data: candidates_by_industries_graph['data'],
          }
        ]
      },
      options: {
        aspectRatio: 2,
        plugins: {
          legend: { 
            display: false,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        responsive: true,
      cutout: "90%"
      }
      
    });
  }

  // Doughnut Chart
  if($('#chartjsDoughnutCandidates').length) {
    new Chart($('#chartjsDoughnutCandidates'), {
      type: 'doughnut',
      data: {
        labels: candidates_by_industries_graph['labels'],
        datasets: [
          {
            label: "MALE-FEMALE RATIO",
            backgroundColor: ['#033270','#553AFE','#14BC9A'],
            borderColor: colors.cardBg,
            data: candidates_by_industries_graph['data'],
          }
        ]
      },
      options: {
        aspectRatio: 2,
        plugins: {
          legend: { 
            display: false,
            labels: {
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        responsive: true,
      cutout: "90%"
      }
      
    });
  }




  // Grouped Bar Chart
  if($('#chartjsGroupedBarCandidates').length) {
    new Chart($('#chartjsGroupedBarCandidates'), {
      type: 'bar',
      data: {
        labels: graph1_data.labels,
        datasets: graph1_data.datasets
      },
      options: {
        plugins: {
          legend: { 
            display: true,
            labels: {
              usePointStyle: true,
              color: colors.bodyColor,
              font: {
                size: '13px',
                family: fontFamily
              }
            }
          },
        },
        scales: {
          x: {
            display: false,
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          },
          y: {
            grid: {
              display: true,
              color: colors.gridBorder,
              borderColor: colors.gridBorder,
            },
            ticks: {
              color: colors.bodyColor,
              font: {
                size: 12
              }
            }
          }
        },
        responsive: true,
        borderRadius: 10,
        barThickness: 5,
      }
    });
  }

});