$(document).ready(function(){
  $("#Import").on('submit', function(e){
    e.preventDefault();
    var f = $(this);
    var formData = new FormData(document.getElementById("Import"));
    var boton = $('#RD');
    $.ajax({
      type:f.attr('method'),
      url:f.attr('action'),
      dataType: 'html',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function(){
        boton.val('Registrando...');
        boton.attr('disabled','disabled');
      },
      complete: function(){
        boton.val('Registrar Datos');
        boton.removeAttr('disabled');
      },
      success: function(data){
        alert("mensaje: " + data);
      },
      error: function(data){
        alert("Error");
      }
    });
    return false;
  });
});
