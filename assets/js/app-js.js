$(document).ready(function() {  
  
  $('.date').datepicker({ dateFormat: 'yy-mm-dd' });  
  
  let openCellData = [];
  let closeCellData = [];
  let dateCellData = [];
  let companySymbol = $("#symbol_form_companySymbol").val();

  if($(".results").length) {

    $(".results tbody tr").each(function() {

      let dateCell = $(this).find("td.date-cell").text();
      let openCell = $(this).find("td.open-cell").text();
      let closeCell = $(this).find("td.close-cell").text();

      if(openCell!=="") openCellData.push(openCell);      
      if(closeCell!=="") closeCellData.push(closeCell);
      if(dateCell!=="") dateCellData.push(dateCell);
    });

    var ctx = $("#chart")[0].getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: dateCellData,
          datasets: [
            {
              label: 'Open',
              data: openCellData, 
              fill: false               
            },
            {
              label: 'Close',
              data: closeCellData,                
              fill: false
            }
          ]
        },
        options: {
          responsive: true,
          title: {
            display: true,
            text: companySymbol+' Open/Close chart for Date range '
          },
          tooltips: {
            mode: 'index',
            intersect: false,
          },
          hover: {
            mode: 'nearest',
            intersect: true
          },
          scales: {
            xAxes: [{                            
              display: true,
              scaleLabel: {
                display: true,
                labelString: 'Date'
              }
            }],
            yAxes: [{
              display: true,
              ticks: {
                beginAtZero: false
              },
              scaleLabel: {
                display: true,
                labelString: 'Value'
              }
            }]
          }
        }
    });
  } 
});
