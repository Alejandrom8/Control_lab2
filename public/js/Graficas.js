$(document).ready(function(){

  $("#generar_grafica").bind("submit", function(){
    var btnEnviar = $("#boton_enviar");
    $.ajax({
      type: $(this).attr('method'),//se toma el metodo del formulario del que fue enviado
      url: $(this).attr('action'),//se toma la url a la que esta indicada la accion en el formulario
      data: $(this).serialize(),// con la funcion serialize se toman todos los datos que fueron enviados por el formulario
      beforeSend: function(){
        //al enviar el formulario
        btnEnviar.val('Enviando...');
        btnEnviar.attr('disabled', 'disabled');
      },
      complete:function(){
          //al completar el proceso del servidor
          btnEnviar.val("Generar");
          btnEnviar.removeAttr("disabled");
      },
      success: function(data){
        //si se tiene exito en la conexion entre ajax y php
        //se lee el estado que regreso php
        if(data != false){
          //si todo salio bien ejecutamos la funcion para generar la grafica en pantalla
          $(".titulo_grafica").css({'display':'block'});
          generar_grafica(data);
        }else{
          //si el estado es fals, sucedio un error por lo que se procede
          // a imprimirlo en la consola del navegador
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
    //canvas
    var ctx_visitas = document.getElementById('chart_visitas').getContext('2d');
    var ctx_copias = document.getElementById('chart_copias').getContext('2d');
    //tipo de grafica y clasificacion para mostrar en la grafica
    var tipo = $('#tipo').val();
    var clasificacion = $('#clasif').val();
    //arrays que separaran las visitas de los dias
    var dias = [];
    var visitas = [];
    var copias = [];
    //JSON que contiene todos los datos, lo convierto a array para un mejor manejo de datos
    var obj = JSON.parse(data);
    //colores aleatorios que se generaran en las graficas
    var colores = [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
    ];
    var colores_mostrados = [];
    var colores_final;

    for(var i in obj){
      dias.push(obj[i].fecha);
      visitas.push(obj[i].visitas);
      copias.push(obj[i].copias);
    }

    //  Se colocan colores dependiendo de el tipo de grafica que se
    //  solicita ya que la grafica 'line' solo acepta un color
    if(tipo != 'line'){
      for(var i = 0; i < visitas.length; i++){
        var colorIndex = Math.floor(Math.random() * colores.length);
        colores_mostrados.push(colores[colorIndex]);
      }
      colores_final = colores_mostrados;
    }else{
      //si el tipo de grafica es 'line'
      colores_final = 'rgba(75, 192, 192, 0.2)';
    }


    var chartData = {
      labels: dias,
      datasets: [
        {
          label: "visitas",
          backgroundColor: colores_final,
          data: visitas
        }
      ]
    };
    var chartDataCopias = {
      labels: dias,
      datasets: [
        {
          label: "copias",
          backgroundColor: colores_final,
          data: copias
        }
      ]
    };

    if(window.barGraph){
      window.barGraph.clear();
      window.barGraph.destroy();
      window.barGraph2.clear();
      window.barGraph2.destroy();
    }

    window.barGraph = new Chart(ctx_visitas, {
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

    window.barGraph2 = new Chart(ctx_copias, {
      type: tipo,
      data: chartDataCopias,
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
