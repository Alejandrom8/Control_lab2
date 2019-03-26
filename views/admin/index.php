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
    <link rel="stylesheet" type="text/css" href="<?php echo constant('URL'); ?>public/css/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?php echo constant('URL'); ?>public/node_modules/chart.js/dist/Chart.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo constant('URL'); ?>public/node_modules/chart.js/dist/Chart.css">
    <script src="<?php echo constant('URL'); ?>public/js/verificar.js"></script>
    <style>
      canvas{
        width:100%;
        height:100%;
      }
    </style>
  </head>
  <body>
      <?php require 'views/header.php'; ?>
      <div id="main" class="col-sm-12">
        <h1 class="center">Seccion de administraciòn</h1>
        <div class="contorno" id="display_admin">
            <div class="col-md-12 display">
              <aside>
                <table class="table table-striped">
                  <tbody>
                    <tr class='obj_li' data-pantalla = 'Registrar'><td>Registrar sala</td></tr>
                    <tr class='obj_li' data-pantalla = 'Graficas'><td>Graficas</td></tr>
                    <tr class='obj_li' data-pantalla = 'Estadisticas'><td>Estadisticas</td></tr>
                  </tbody>
                </table>
              </aside>
                <div class="pantallas" id="Registrar" style="display:block;">
                  <h2>Registro de sala</h2>
                  <div class="col-sm-8">
                    <form action="<?php echo constant('URL'); ?>admin/crear_sala" onsubmit="return validar();" method="POST" id="registro_aula">
                      <div class="form-group">
                        <label for="Nombre">Nombre del aula</label>
                        <input type="text" name="Nombre" id="Nombre" required pattern="[A-Za-z0-9]+">
                      </div>
                      <div class="form-group">
                        <label for="id">ID del aula</label>
                        <input type="text" name="id" id="id" required>
                      </div>
                      <div class="form-group">
                        <label for="pass">Contraseña para el aula</label>
                        <input type="password" name="pass" id="pass" required pattern="[A-Za-z0-9]+">
                      </div>
                      <div class="form-group">
                        <label for="pass_r">Repetir contraseña</label>
                        <input type="password" name="pass_r" id="pass_r" required pattern="[A-Za-z0-9]+">
                      </div>
                      <div class="form-group">
                        <label for="permisos">Permisos para esta sala</label>
                        <select name="permisos" id="permisos" required>
                          <option value="">Seleccionar</option>
                          <option value="0">Sin permisos</option>
                          <option value="1">Con permisos de administrador</option>
                        </select>
                      </div>
                      <input type="submit" value="Registrar aula" class="btn btn-primary">
                    </form>
                  </div>
                  <div><?php echo $this->mensaje; ?></div>
                </div>
                <div class="pantallas" id="Graficas" style="display:none;padding-top:0;">
                  <header>
                    <div class="col-sm-12">
                      <form method="POST" action="<?php echo constant('URL'); ?>admin/Graficas" id="generar_grafica" style="background-color:gray;">
                        <div class="row">
                          <div class="form-group">
                            <label for="de">de: </label>
                            <input type="date" name="de" id="de" required>
                          </div>
                          <div class="form-group">
                            <label for="a">a: </label>
                            <input type="date" name="a" id="a" required>
                          </div>
                          <div class="form-group">
                            <label for="clasif">Clasificar por:  </label>
                            <select name="clasif" id="clasif" required>
                              <option value="dia">dia</option>
                              <option value="mes">mes</option>
                              <option value="year">año</option>
                            </select>
                          </div>
                          <input type="submit" value="generar grafica" id="boton_enviar" class="btn btn-primary">
                        </div>
                      </form>
                    </div>
                  </header>
                  <div class="row">
                    <div class="chart-container col-sm-12" style="position: relative; height:60vh; width:80vw">
                        <h2><?php echo $this->titulo_st; ?></h2>
                        <canvas id="chart"></canvas>
                    </div>
                  </div>
                </div>
                <div class="pantallas" id="Estadisticas" style="display:none;">Adios de nuevo</div>
            </div>
        </div>
      </div>
      <?php require 'views/footer.php'; ?>
      <script src="<?php echo constant('URL'); ?>public/js/paginer.js"></script>
      <script src="<?php echo constant('URL'); ?>public/js/Graficas.js"></script>
  </body>
</html>
