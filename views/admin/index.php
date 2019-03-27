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
        <p><?php echo $this->mensaje; ?></p>
        <div class="contorno" id="display_admin">
            <div class="col-md-12 display">
              <aside>
                <table class="table table-striped">
                  <tbody>
                    <tr class='obj_li' data-pantalla = 'Registrar' id="Registrar_menu"><td>Registrar sala</td></tr>
                    <tr class='obj_li' data-pantalla = 'Graficas' id="Graficas_menu"><td>Graficas</td></tr>
                    <tr class='obj_li' data-pantalla = 'Estadisticas' id="Estadisticas_menu"><td>Estadisticas</td></tr>
                  </tbody>
                </table>
              </aside>
              <section>
                <div class="margen_dispaly">
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
                      <form method="POST" action="<?php echo constant('URL'); ?>admin/Graficas" id="generar_grafica">
                        <div class="row">
                          <div class="form-group" style="background-color:#FF6859;text-align:center;color:#fff;padding:5px;width:20%;">
                            <label for="sala">Selecciona una sala</label>
                            <select class="form-control" name="sala">
                              <option value="<?php echo $_SESSION['id']; ?>">Esta sala</option>
                              <?php
                                include_once 'models/aulas.php';
                                foreach ($this->aulas as $key => $value) {
                                  $aula = new Aulas();
                                  $aula = $value;
                                  echo "<option value='$aula->id'>Sala $aula->aula</option>";
                                }
                              ?>
                              <option value="todas">todas</option>
                            </select>
                          </div>
                          <div class="form-group" style="background-color:#1EB980;text-align:center;color:#fff;padding:5px;width:15%;">
                            <label for="tipo">Tipo de grafica</label>
                            <select class="form-control" name="tipo" id="tipo">
                              <option value="bar">Barras</option>
                              <option value="line">Lineal</option>
                              <option value="doughnut">Pastel</option>
                            </select>
                          </div>
                          <div class="form-group" style="background-color:#FFCF44;text-align:center;color:#333;padding:5px;">
                            <label for="de">Fecha inicial</label>
                            <input type="date" name="de" id="de" class="form-control" required>
                          </div>
                          <div class="form-group" style="background-color:#045D56;text-align:center;color:#fff;padding:5px;">
                            <label for="a">Fecha final</label>
                            <input type="date" name="a" id="a" class="form-control" required>
                          </div>
                          <div class="form-group" style="background-color:#FF6859;text-align:center;color:#fff;padding:5px;width:10%;">
                            <label for="clasif">Clasificar por</label>
                            <select name="clasif" id="clasif" class="form-control" required>
                              <option value="dia">dia</option>
                              <option value="mes">mes</option>
                              <option value="year">año</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <input type="submit" value="generar grafica" id="boton_enviar" class="btn btn-primary" style="height:100%;border:0;">
                          </div>
                        </div>
                      </form>
                    </div>
                  </header>
                  <div class="row">
                    <div class="chart-container col-sm-12" style="position: relative; height:55vh; width:70vw">
                        <h2><?php echo $this->titulo_st; ?></h2>
                        <canvas id="chart"></canvas>
                    </div>
                  </div>
                </div>
                <div class="pantallas" id="Estadisticas" style="display:none;">Adios de nuevo</div>
                </div>
              </section>
            </div>
        </div>
      </div>
      <?php require 'views/footer.php'; ?>
      <script src="<?php echo constant('URL'); ?>public/js/paginer.js"></script>
      <script src="<?php echo constant('URL'); ?>public/js/Graficas.js"></script>
  </body>
</html>
