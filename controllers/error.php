<?php

  class err extends Controller{

    function __construct(){
      parent::__construct();
      $this->view->mensaje = "Hubo un error en la solicitud o no existe la paàgina";
      $this->view->render('error/index');
    }

  }

 ?>
