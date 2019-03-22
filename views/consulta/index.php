<?php
  if(!isset($_SESSION['aula']) or !isset($_SESSION['pass']) or !isset($_SESSION['id'])){
    print("<script>alert('Acceso denegado! Inicie sesion antes de entrar');window.location = '". constant('URL') ."salir';</script>");
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Sala de Còmputo</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL'); ?>public/imagenes/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </head>
  <body>
      <?php require 'views/header.php'; ?>
      <div id="main" class="col-sm-12">
        <h1 class="center">Seccion de consulta</h1>
        <div class="contorno">
          <div id="Respuesta"></div>
          <table id="alumnos" class='table table-bordered table-striped' style='background-color:#fff;float:left;width:99.9%;margin-bottom:4%;'>
            <thead>
              <tr>
                <th> </th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo de usuario</th>
                <th>hora de entrada</th>
                <th># copias</th>
                <th>estado de la sesion</th>
              </tr>
            </thead>
            <tbody id="tbody-alumnos">
              <?php
              include_once 'models/alumno.php';
              $i = 1;
              foreach($this->alumnos as $row){
                  $alumno = new Alumno();
                  $alumno = $row;
               ?>
              <tr id="fila-<?php echo $alumno->matricula; ?>">
                <td><?php echo $i; ?></td>
                <td><?php echo $alumno->matricula; ?></td>
                <td class="nombre"><?php echo $alumno->nombre; ?></td>
                <td><?php echo $alumno->tipo; ?></td>
                <td><?php echo $alumno->hora; ?></td>
                <td>
                  <form action="<?php echo constant('URL'); ?>consulta/copias" method="post">
                    <div class="row" style="margin:0;">
                      <div class="col-sm-1" style="margin-right:10px;margin-left:18%;">
                        <input type="submit" value="-" name='boton' class="btn btn-danger">
                      </div>
                      <div class="col-sm-4">
                        <input class="form-control no_copias" type="text" name="no_copias" value="<?php echo $alumno->no_copias; ?>" style="text-align:center;">
                      </div>
                      <input type="hidden" name="id_usuario" value="<?php echo $alumno->matricula; ?>">
                      <div class="col-sm-1">
                        <input type="submit" value="+" name='boton' class="btn btn-primary">
                      </div>
                    </div>
                  </form>
                </td>
                <td class="final">
                  <div class="row">
                    <div class="col-sm-4" style="text-align:center;">
                        <div class="parpadea" id="on"><span></span></div>
                        <span><?php echo $alumno->session; ?></span>
                    </div>
                    <div class="col-sm-4">
                        <!-- href="<?php //echo constant('URL') . 'consulta/terminar/' . $alumno->matricula . '/' . $alumno->nombre; ?>" -->
                        <button class="btn btn-danger cerrar" data-matricula = "<?php echo $alumno->matricula; ?>" data-nombre = "<?php echo $alumno->nombre; ?>">terminar</button>
                    </div>
                  </div>
                </td>
              </tr>
              <?php
                $i++;
              }
             ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php require 'views/footer.php'; ?>
      <script src="<?php echo constant('URL'); ?>public/js/ajax.js"></script>
  </body>
</html>
