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
        <p><?php echo $this->mensaje; ?></p>
        <div class="contorno" id="display_admin">
            <div class="col-md-12 display">
              <aside>
                <table class="table table-striped" style="background-color:#fff;border-top:20px solid #666;">
                  <tbody>
                    <tr class='obj_li' data-pantalla = 'Registrar' id="Registrar_menu"><td>Registrar sala</td></tr>
                    <tr class='obj_li' data-pantalla = 'Graficas' id="Graficas_menu"><td>Graficas</td></tr>
                    <tr class='obj_li' data-pantalla = 'Estadisticas' id="Estadisticas_menu"><td>Estadisticas</td></tr>
                    <tr class='obj_li' data-pantalla = 'Importar' id="Importar_menu"><td>Importar datos</td></tr>
                  </tbody>
                </table>
              </aside>
              <section>
                <div class="margen_dispaly">
                <div class="pantallas" id="Registrar" style="display:block;">
                  <div class="col-sm-8">
                    <form action="<?php echo constant('URL'); ?>admin/crear_sala" onsubmit="return validar();" method="POST" id="registro_aula">
                      <table class="table">
                        <thead>
                          <tr>
                            <th colspan="2"><h2>Registro de sala</h2><th>
                          </tr>
                        </thead>
                        <tr>
                          <td><label for="Nombre">Nombre del aula</label></td>
                          <td><input type="text" name="Nombre" id="Nombre" required pattern="[A-Za-z0-9]+" class="form-control" autofocus></td>
                        </tr>
                        <tr>
                          <td><label for="id">ID del aula</label></td>
                          <td><input type="text" name="id" id="id" required class="form-control"></td>
                        </tr>
                        <tr>
                          <td><label for="pass">Contraseña para el aula</label></td>
                          <td><input type="password" name="pass" id="pass" required pattern="[A-Za-z0-9]+" class="form-control"></td>
                        </tr>
                        <tr>
                          <td><label for="pass_r">Repetir contraseña</label></td>
                          <td><input type="password" name="pass_r" id="pass_r" required pattern="[A-Za-z0-9]+" class="form-control"></td>
                        </tr>
                        <tr>
                          <td><label for="permisos">Permisos para esta sala</label></td>
                          <td><select name="permisos" id="permisos" required class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="0">Sin permisos</option>
                            <option value="1">Con permisos de administrador</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td colspan="2"><input type="submit" value="Registrar aula" class="btn btn-primary"></td>
                        </tr>
                      </table>
                    </form>
                  </div>
                  <div><?php echo $this->mensaje; ?></div>
                </div>
                <div class="pantallas" id="Graficas" style="display:none;">
                  <h2>Graficas</h2>
                  <header class="box-chart">
                    <div class="col-sm-12">
                      <form method="POST" action="<?php echo constant('URL'); ?>admin/Graficas" id="generar_grafica">
                        <div class="row">
                          <div class="form-group" style="background-color:#FFCF44;text-align:center;color:#333;padding:5px;width:20%;">
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
                          <div class="form-group" style="background-color:#045D56;text-align:center;color:#fff;padding:5px;width:15%;">
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
                          <div class="form-group" style="background-color:#FFCF44;text-align:center;color:#333;padding:5px;width:15%;">
                            <label for="clasif">Clasificar por</label>
                            <select name="clasif" id="clasif" class="form-control" required>
                              <option value="dia">dia</option>
                              <option value="mes">mes</option>
                              <option value="year">año</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <input type="submit" value="Generar" id="boton_enviar" class="btn btn-primary" style="height:100%;border:0;margin-left:20%;">
                          </div>
                        </div>
                      </form>
                    </div>
                  </header>
                  <div class="row">
                    <div class="chart-container col-sm-12" style="position: relative; height:46vh; width:57vw">
                        <h2><?php echo $this->titulo_st; ?></h2>
                        <h2 class="titulo_grafica center" style="display:none;">Gráfica de visitas</h2>
                        <canvas id="chart"></canvas>
                        <br>
                        <h2 class="titulo_grafica center" style="display:none;">Gráfica de copias</h2>
                        <canvas id="chart"></canvas>
                        <br><br>
                    </div>
                  </div>
                </div>
                <div class="pantallas" id="Estadisticas" style="display:none;">Adios de nuevo</div>
                <div class="pantallas" id="Importar" style="display:none;">
                  <div class="cont-pantallas-form">
                    <center><h2>Importar datos</h2></center>
                    <br>
                    <div class="import_section" style="height:250px;">
                      <p>Los datos que introduzca en el formulario de abajo deberán tener el siguiente formato:</p>
                      <div class="tabla_muestra">
                        <table class="table table-bordered">
                          <tr class="table-info">
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo de usuario</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>No. de copias</th>
                          </tr>
                          <tr>
                            <td>texto: no_cuenta,id_trabajador</td>
                            <td>texto</td>
                            <td>numérico: 1=alumno, 2 = profesor</td>
                            <td>aaaa-mm-dd</td>
                            <td>hh:mm</td>
                            <td>numérico</td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div class="import_section">
                      <h2>Llene el formulario</h2>
                      <form action="<?php echo constant('URL'); ?>admin/Insert" method="post" enctype="multipart/form-data" id="Import">
                        <table class="table">
                          <tr>
                            <td><label for="id">Id del aula</label></td>
                            <td><input type="text" name="id" class="form-control" maxlength="3" required autofocus></td>
                          </tr>
                          <tr>
                              <td><label for="archivo">Introduce el archivo csv</label></td>
                              <td>
                                <span class="btn btn-info btn-file">
                                  Archivos<input type="file" name="archivo" required>
                                </span>
                              </td>
                          </tr>
                          <tr>
                            <td><label for="separation">Columnas separadas por</label></td>
                            <td>
                                <select name="separation" id="separation" class="form-control" required>
                                  <option value=",">,</option>
                                  <option value=";">;</option>
                                  <option value=":">:</option>
                                </select>
                            </td>
                          </tr>
                          <tr>
                            <br>
                            <td colspan="2"><input type="submit" name="enviar" value="Registrar Datos" class="btn btn-primary" id="RD"></td>
                        </tr>
                      </table>
                      </form>
                    </div>
                  </div>
                </div>
                </div>
              </section>
            </div>
        </div>
      </div>
      <?php require 'views/footer.php'; ?>
      <script src="<?php echo constant('URL'); ?>public/js/paginer.js"></script>
      <script src="<?php echo constant('URL'); ?>public/js/Graficas.js"></script>
      <script src="<?php echo constant('URL'); ?>public/js/Import.js"></script>
  </body>
</html>
