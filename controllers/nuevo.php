<?php

  class Nuevo extends Controller{

    function __construct(){
      parent::__construct();
      $this->view->mensaje = " ";
    }
    function render(){
        $this->view->render('nuevo/index');
    }

    function registrarVisita(){

      //Registra las visitas

      $matricula = $_POST['matricula'];
      $digitos = count(str_split($matricula));
      $mensaje = " ";

      setlocale(LC_TIME, "es_MX.UTF-8");
      date_default_timezone_set("America/Mexico_City");

      //fecha
      $fecha = strftime("%y-%m-%d");
      $hora = strftime("%H:%M");
      $validacion = false;

      if($digitos == 6){
        $user = '*';
        $reference = 'NumTrab';
        $tabla = constant('Consulta_prof');
        $tipo_usuario = 1;
        $validacion = true;
      }else if($digitos == 9){
        $user = 'nombre';
        $reference = 'nocta';
        $tabla = constant('Consulta_alumn');
        $tipo_usuario = 2;
        $validacion = true;
      }

      if($validacion){
        $nombre = $this->model->BuscarNombre(['user' => $user, 'reference' => $reference , 'tabla' => $tabla, 'matricula' => $matricula, 'tipo_usuario' => $tipo_usuario]);
        if(isset($nombre) and $nombre != null){
            $insert = $this->model->insert(['matricula' => $matricula, 'nombre' => $nombre,'fecha' => $fecha, 'hora' => $hora, 'tipo_usuario' => $tipo_usuario]);
            if($insert){
                if($tipo_usuario == 1){
                  $mensaje .= "
                  <style>
                    #form_RV{
                      display:none;
                    }
                    #RV{
                      background-color:#888;
                    }
                  </style>
                    <form action='" . constant('URL') . "consulta/copias/' method='POST' style='width:50%;'>
                      <div class='row'>
                      <label for='no_copias'>¿Cuantas impresiones realizo?</label>
                      <div class=' col-sm-10' style='padding-right:0;'>
                        <input type='number' name='no_copias' class='form-control copias' onkeypress='return justNumbers(event);' maxlength='3'>
                      </div>
                      <div class='col-sm-2' style='padding-left:0;margin-left:0;'>
                        <input type='hidden' name='id_usuario' value='". $matricula ."'>
                        <input type='hidden' name='boton' value='+'>
                        <input type='submit' name='enviar' value='enviar' class='btn btn-primary'>
                      </div>
                      </div>
                    </form>
                  ";
                }else{
                  $mensaje .= "<div class='alert alert-success alert-dismissible'>Visita registrada</div>";
                }
            }else{
              $mensaje .= "<div class='alert alert-danger alert-dismissible'>Hubo un error al registrar tu visita</div>";
            }
        }else{
            $mensaje .= "<div class='alert alert-danger alert-dismissible'>No encontramos tu nombre en la base de datos</div>";
        }
      }else{
        $mensaje .= "<div class='alert alert-danger alert-dismissible'>matricula incorrecta</div>";
      }

      $this->view->mensaje = $mensaje;
      $this->render();
    }

  }
  ?>
