<?php
session_start();
require_once 'controllers/error.php';
/*
  La clase App sirve para controlar la paginacion en esta aplicacion web, Ayuda a controlar
  todos lo que se introdusca en la url.
*/
class App{

    function __construct(){
      $url = isset($_GET['url']) ? $_GET['url'] : null;
      //  se quitan espacios entre '/' de la url
      $url = rtrim($url, '/');
      //  convertimos la url a un array para una mejor evaluacion
      //  de lo que se introduce por la misma.
      $url = explode('/', $url);

      //  cuando se ingresa sin controlador
      if(empty($url[0])){

        //  Si el usuario manipulara la url de tal manera que esta quedase
        //  sin metodos ni paramentros mientras tiene una sesion abierta,
        //  se le redireccionara al metodo 'nuevo':

        if(isset($_SESSION['aula']) and isset($_SESSION['pass']) and isset($_SESSION['id'])){
          $archivoController = 'controllers/nuevo.php';
          require_once $archivoController;
          $controller = new Nuevo();
          $controller->loadModel('nuevo');
        }else{
          $archivoController = 'controllers/main.php';
          require_once $archivoController;
          $controller = new Main();
          $controller->loadModel('main');
        }

        $controller->render();
        return false;
      }

      //  definimos el archivo al que se va a redireccionar
      //  de acuerdo al controlador que se llame
      $archivoController = 'controllers/' . $url[0] . '.php';

      //  verificando que el archivo exista
      if(file_exists($archivoController)){

          //  llamamos al archivo solicitado para usar sus clases
          require_once $archivoController;
          $controller = new $url[0];
          $controller->loadModel($url[0]);
          //  #numero de elementos que contiene la url dividida en un array
          $nparam = sizeof($url);

          //  verificamos que halla una sesion iniciada para
          //  que el usuario pueda entrar a los diferentes metodos
          if($url[0] != 'main'){
             if(!isset($_SESSION['aula']) or !isset($_SESSION['pass']) or !isset($_SESSION['id'])){
               print("<script>alert('Acceso denegado! Inicie sesion antes de entrar');window.location = '". constant('URL') ."salir';</script>");
               die();
             }
          }
          //  si en la url se introdujera mas de 2 posiciones (ejemplo: ../controlador[0]/metodo[1]/parametro[2])
          //  se generara un bucle que almacena en un array nuevo llamado 'param' y posteriormente llamamos al archivo
          //  solicitado con el objeto que se indique y los diferentes parametros que se introduscan a la url.

          if($nparam > 1){
            if($nparam > 2){
              $param = [];
              for($i = 2; $i < $nparam; $i++){
                array_push($param, $url[$i]);
              }
              if(method_exists($controller, $url[1])){
                  $controller->{$url[1]}($param);
              }else{
                $controller = new err();
              }
            }else{
              if(method_exists($controller, $url[1])){
                $controller->{$url[1]}();
              }else{
                $controller = new err();
              }
            }
          }else{
            $controller->render();
          }

      }else{
        //  Si el archivo que se solicito, no existe, se llama al objeto err para indicarle
        //  al usuario que hubo un error al llamar al archivo o que el archivo no existe
        $controller = new err();
      }

    }

}

?>
