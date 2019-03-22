<?php

  class Main extends Controller{

    function __construct(){
      parent::__construct();
      $this->view->mensaje = "";
    }
    function render(){
      $this->view->render('main/index');
    }
    function comprobar(){
      $aula = $_POST['aula'];
      $password = $_POST['pass'];

      $login = $this->model->login($aula, $password);

      if($login === true){
        $id = $this->model->get_id($aula);
        if($id == true){
            print("<script>window.location = '". constant('URL') ."nuevo';</script>");
        }else{
          $this->view->mensaje = "<div class='alert alert-danger alert-dismissible fade show'><button type='button' class='close' data-dismiss='alert'>&times;</button>". $login ."</div>";
          $this->render();
        }
      }else{
        $this->view->mensaje = "<div class='alert alert-danger alert-dismissible fade show'><button type='button' class='close' data-dismiss='alert'>&times;</button>". $login ."</div>";
        $this->render();
      }

    }

  }

?>
