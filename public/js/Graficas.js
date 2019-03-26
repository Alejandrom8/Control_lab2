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


          for(var i in data){
            dias.push('dia: ' + data[i].dia);
            visitas.push(data[i].visitas);
          }

          var chartData = {
            labels: dias,
            datasets: [
              {
                label: "tipo_usuario",
                backgroundColor: 'rgba(200, 200, 200, 0.75)',
                borderColor: 'rgba(200, 200, 200, 1)',
                hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
                data: visitas,
              }
            ]
          };

          var ctx = $('#chart');
          var barGraph = new Chart(ctx, {
            type: "bar",
            data: chartData,
          });
          console.log(data);
      },
      error: function(data){
          alert("Problemas al tratar de enviar el formulario");
      }
    });
    return false;
  });
});
