<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Sala de Còmputo</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL'); ?>public/imagenes/logo.png">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo constant('URL'); ?>public/css/landing_page.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </head>
  <body>
      <div class="content-follow">
          <div id="right" class="col-md-12">
            <div class="margen">
              <div class="col-sm-6 bloques izquierda">
                <section id="titulo">
                  <div class="contenedor">
                    <h1>SALA DE CÒMPUTO</h1>
                  </div>
                </section>
                <header id="logos">
                  <center>
                  <ul>
                    <li style="margin-left:14.5%;"><img class="" src="<?php echo constant('URL'); ?>public/imagenes/escudounam_negro.png" alt=""></li>
                    <li><img class="" src="<?php echo constant('URL'); ?>public/imagenes/leopardos.png" alt=""></li>
                  </ul>
                </center>
                </header>
              </div>
              <div class="col-sm-6 bloques derecha">
                <div class="contenedor">
                  <h1>Login</h1>
                  <br>
                  <form class="" action="<?php echo constant('URL'); ?>main/comprobar" method="POST">
                    <div class="form-group">
                      <label for="aula">Nombre o ID del aula: </label>
                      <input type="text" name="aula" class="form-control" placeholder="Ingresa el nombre o id del aula" required>
                    </div>
                    <div class="form-group">
                      <label for="pass">Contraseña: </label>
                      <input type="password" name="pass" class="form-control" placeholder="Ingresa la contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Entrar</button>
                  </form>
                  <p><?php echo $this->mensaje; ?></p>
                </div>
              </div>
            </div>
          </div>
      </div>
  </body>
</html>
