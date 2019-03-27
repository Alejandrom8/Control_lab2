<?php
  class Salir extends Controller{
    function __construct(){
      parent::__construct();
      $this->view->mensaje = "";
    }

    function salir(){

      $consultar = $this->model->consultar();
      if($consultar){
        $this->saliendo();
      }else{
        print("<script>alert('Esta saliendo sin finalizar antes');window.location='".constant('URL')."consulta';</script>");
      }
    }
    function saliendo(){
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
        print("<script>alert('Se finalizo correctamente');window.location='".constant('URL')."consulta';</script>");
      }else{
        print("<script>alert('No se logro finalizar correctamente');</script>");
      }
    }
  }
 ?>
