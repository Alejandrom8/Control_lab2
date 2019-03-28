$(document).ready(function(){
  $("#Export").on('submit', function(e){
    e.preventDefault();
    var f = $(this);
    var boton = $('#OT');
    var display = $('#mensaje');
    $.ajax({
      type:f.attr('method'),
      url:f.attr('action'),
      data: f.serialize(),
      beforeSend: function(){
        boton.val('Consultando...');
        boton.attr('disabled','disabled');
      },
      complete: function(){
        boton.val('Obtener tabla');
        boton.removeAttr('disabled');
      },
      success: function(data){
        display.css({'display':'block'});
        $('#tabla').empty();
        if(data){
          $('#bt_ex').css({'display':'block'});
          var obj = JSON.parse(data);
          var longitud = obj.length;
          $('#tabla').append('<tr><th>Matricula</th><th>id del aula</th><th>Nombre</th><th>tipo de usuario</th><th>Fecha</th><th>Hora</th><th>Numero de copias</th></tr>');
          for(var i = 0; i < obj.length; i++){
            $('#tabla').append(
              "<tr>"+
                "<td>"+ obj[i].matricula +"</td>" +
                "<td>"+ obj[i].id_aula +"</td>" +
                "<td>"+ obj[i].nombre +"</td>" +
                "<td>"+ obj[i].tipo +"</td>" +
                "<td>"+ obj[i].fecha +"</td>" +
                "<td>"+ obj[i].hora +"</td>" +
                "<td>"+ obj[i].no_copias +"</td>"
              +"</tr>"
            );
          }
        }else{
          $('#bt_ex').css({'display':'none'});
          $('#tabla').append('<tr><td><h3>Hubo un error en la consulta o no hay resultados para esta sala</h3></td></tr>');
        }
      },
      error: function(data){
        alert("Error");
      }
    });
    return false;
  });
});
