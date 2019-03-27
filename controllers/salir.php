<?php
  class Salir extends Controller{
    function __construct(){
      parent::__construct();
      $this->view->mensaje = "";
    }
    function salir(){
      echo "Espere un momento porfavor...";

      foreach($_SESSION as $key => $value){
        $_SESSION[$key] = NULL;
      }

      session_destroy();
      print("<script>window.location = '" . constant('URL') . "main';</script>'");
    }

    function render(){
      $this->view->render('nuevo/index');
    }

    function finalizar(){
      $ejecutar = $this->model->finalizar();
      if($ejecutar){
        print("<script>alert('Se finalizo correctamente');</script>");
      }else{
        print("<script>alert('No se logro finalizar correctamente');</script>");
      }
      $this->render();
    }
  }
 ?>
