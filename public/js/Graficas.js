$(document).ready(function(){
  $("#generar_grafica").bind("submit", function(){
    var btnEnviar = $("#boton_enviar");
    $.ajax({
      type: $(this).attr('method'),
      url: $(this).attr('action'),
      data: $(this).serialize(),
      beforeSend: function(){
        btnEnviar.val('Enviando...');
        btnEnviar.attr('disabled', 'disabled');
      },
      complete:function(data){
          btnEnviar.val("generar grafica");
          btnEnviar.removeAttr("disabled");
      },
      success: function(data){
          var dias = [];
          var visitas = [];
          var obj = JSON.parse(data);

          for(var i in obj){
            dias.push('dia: ' + obj[i].dia);
            visitas.push(obj[i].visitas);
          }

          var chartData = {
            labels: dias,
            datasets: [
              {
                label: "alumnos",
                backgroundColor: 'rgba(200, 200, 200, 0.5)',
                borderColor: 'rgb(200, 200, 200)',
                hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
                data: visitas
              }
            ]
          };

          var ctx = document.getElementById('chart');
          var barGraph = new Chart(ctx, {
            type: "bar",
            data: chartData,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
          });
      },
      error: function(data){
          alert("Problemas al tratar de enviar el formulario");
      }
    });
    return false;
  });
});
