<?php

  class err extends Controller{

    function __construct(){
      parent::__construct();
      print("<script>alert('Hubo un error en la solicitud o no existe la página');window.location='".constant('URL')."nuevo';</script>");
    }

  }

 ?>
