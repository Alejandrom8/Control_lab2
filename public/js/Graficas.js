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
      complete:function(){
          btnEnviar.val("Generar");
          btnEnviar.removeAttr("disabled");
      },
      success: function(data){
        if(data != false){
          $(".titulo_grafica").css({'display':'block'});
          generar_grafica(data);
        }else{
          console.log(data);
        }
      },
      error: function(){
          alert("Problemas al tratar de enviar el formulario");
      }
    });
    return false;
  });

  function generar_grafica(data){
    var tipo = $('#tipo').val();
    var clasificacion = $('#clasif').val();
    var dias = [];
    var visitas = [];
    var obj = JSON.parse(data);
    var colores = [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
    ];
    var colores_mostrados = [];
    var ctx = document.getElementById('chart').getContext('2d');
    var colores_final;

    for(var i in obj){
      dias.push(clasificacion + ' : ' + obj[i].termino);
      visitas.push(obj[i].visitas);
    }

    if(tipo != 'line'){
      for(var i = 0; i < visitas.length; i++){
        var colorIndex = Math.floor(Math.random() * colores.length);
        colores_mostrados.push(colores[colorIndex]);
      }
      colores_final = colores_mostrados;
    }else{
      colores_final = 'rgba(75, 192, 192, 0.2)';
    }

    var chartData = {
      labels: dias,
      datasets: [
        {
          label: "alumnos",
          backgroundColor: colores_final,
          data: visitas
        }
      ]
    };

    if(window.barGraph){
      window.barGraph.clear();
      window.barGraph.destroy();
    }

    window.barGraph = new Chart(ctx, {
      type: tipo,
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
  }
});
