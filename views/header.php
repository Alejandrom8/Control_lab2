<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/bootstrap/css/boostrap.min.css">
    <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/default.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
      $(document).ready(function(){
        $('.alert').fadeIn();
        setTimeout(function() {
             $(".alert").fadeOut('slow', 'swing');
        },3000);
      });
    </script>
  </head>
  <body>
    <div id="header" class="col-sm-12">
        <div class="row" style="width:99%;">
          <div class="col-sm-6">
            <ul>
              <li id="logo_unam"><img class="logo_unam" src="<?php echo constant('URL'); ?>public/imagenes/escudounam_blanco.png"></li>
              <li><a href="<?php echo constant('URL'); ?>nuevo">Registrar</a></li>
              <li><a href="<?php echo constant('URL'); ?>consulta">Consulta</a></li>
              <li><a href="<?php echo constant('URL'); ?>admin">Administrador</a></li>
            </ul>
          </div>
          <div class="col-sm-6 botones">
            <ul>
              <li id="btn_salir"><a class="btn btn-danger" href="<?php echo constant('URL'); ?>salir">Cerrar Sesion</a></li>
              <li id="btn_fin"><a class="btn btn-warning" href="<?php echo constant('URL'); ?>salir">Finalizar</a></li>
            </ul>
          </div>
        </div>
    </div>
  </body>
</html>
