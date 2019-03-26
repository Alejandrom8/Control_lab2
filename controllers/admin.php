<?php
  class Admin extends Controller{

    function __construct(){
      parent::__construct();
      $this->view->mensaje = "";
      $this->view->titulo_st = "";
      $this->view->statistics = "";
    }

    function render(){
      if($_SESSION['permisos'] == 1){
          $this->view->render('admin/index');
      }else{
          print("<script>alert('acceso denegado'); history.back();</script>");
      }
    }

    function crear_sala(){

      $nombre = $_POST['Nombre'];
      $id = $_POST['id'];
      $pass = $_POST['pass'];
      $permisos = $_POST['permisos'];
      $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

      $comprobar = $this->model->comprobar($id, $nombre);

      if(!$comprobar){
          $estado = $this->model->CrearSala(['nombre' => $nombre, 'id' => $id, 'password' => $pass_hash, 'permisos' => $permisos]);
          if($estado){
            $mensaje = "Se creo con exito la sala";
          }else{
            $mensaje = "No se logro crear correctamente la sala";
          }
      }else{
          $mensaje = "Ya existe un aula con esta id: $id o este nombre de sala: $nombre";
      }

      $this->view->mensaje = $mensaje;
      $this->render();
    }

    function Graficas(){
      $fecha_de_inicio = $_REQUEST['de'];
      $fecha_de_cierre = $_REQUEST['a'];
      $clasificacion = $_REQUEST['clasif'];

      $data = ['desde' => $fecha_de_inicio, 'hasta' => $fecha_de_cierre, 'por' => $clasificacion];

      $estado_de_verificacion = $this->model->ValidarFecha($data['desde'],$data['hasta']);
      $resultado = $estado_de_verificacion[0];
      $mensaje = $estado_de_verificacion[1];
      if($estado_de_verificacion[0]){
        $tomar_datos = $this->model->TomarDatos($mensaje);
        $resultado_d = $tomar_datos[0];
        $mensaje_d = $tomar_datos[1];
        if($resultado_d){
          print_r($mensaje_d);
        }else{
          echo $resultado_d;
        }
      }else {
        echo $resultado;
      }
    }
  }

 ?>
