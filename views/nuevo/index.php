<?php
  if(!isset($_SESSION['aula']) or !isset($_SESSION['pass']) or !isset($_SESSION['id'])){
    print("<script>alert('Acceso denegado! Inicie sesion antes de entrar');window.location = '". constant('URL') ."salir';</script>");
  }
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Sala de Còmputo</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL'); ?>public/imagenes/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="<?php echo constant('URL'); ?>public/css/default.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
  <body>
      <?php require 'views/header.php'; ?>
      <div id="main">
        <div class="registro">
          <br>
          <center><img src="<?php echo constant('URL'); ?>public/imagenes/leopardos.png" id="leopardo" alt="P8"></center>
          <h1 class="center">Registrar Visita</h1>
          <br>
          <form id="form_RV" action="<?php echo constant('URL'); ?>nuevo/registrarVisita" method="post">
            <div class="form-group">
              <input type="text" name="matricula" class="form-control matricula" required autofocus>
            </div>
            <p>
              <input type="submit" id="RV" value="Registrar visita" class="btn btn-primary" >
            </p>
          </form>
          <div class="center">
            <center><?php echo $this->mensaje; ?></center>
          </div>
        </div>
      </div>
      <?php require 'views/footer.php'; ?>
      <script>
        function justNumbers(e){
          var keynum = window.event ? window.event.keyCode : e.which;
          if ((keynum == 8) || (keynum == 46))
          return true;

          return /\d/.test(String.fromCharCode(keynum));
        }
      </script>
  </body>
</html>
