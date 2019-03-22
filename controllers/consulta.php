<?php

  class Consulta extends Controller{

    function __construct(){
      parent::__construct();
      $this->view->alumnos = [];
      $this->view->mensaje = " ";
    }

    function render(){
      $alumnos = $this->model->get();
      $this->view->alumnos = $alumnos;
      $this->view->render('consulta/index');
    }

    function copias(){
      $copias = $_POST['no_copias'];
      $id = $_POST['id_usuario'];
      $operacion = $_POST['boton'];
      $op = $operacion == '+' ? true: false;
      if(!$op and $copias == 0){
        $this->render();
      }else{
        $estado = $this->model->copias($id, $copias, $op);
        if(count(str_split($id)) == 6){
            $this->view->mensaje = "<div class='alert alert-success alert-dismissible'>Visita registrada</div>";
            $this->view->render('nuevo/index');
        }else{
          $this->render();
        }
      }
    }

    function terminar($param = null){
      $idalumno = $param[0];
      $nombre = $param[1];
      $alumno = $this->model->terminar($idalumno);
      $mensaje = $alumno ? "<div class='alert alert-success alert-dismissible fade show'><button type='button' class='close' data-dismiss='alert'>&times;</button>Sesion de " . $nombre . " finalizada</div>" : 'hubo un error al cerrar la sesion';
      $this->view->mensaje = $mensaje;
      echo $mensaje;
    }

  }
  ?>
