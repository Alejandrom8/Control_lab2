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
      $fecha_de_inicio = $_POST['de'];
      $fecha_de_cierre = $_POST['a'];
      $clasificacion = $_POST['clasif'];

      $fechas = [$fecha_de_inicio, $fecha_de_cierre];
      foreach ($fechas as $key => $value) {
        ${'fecha' . $key} = explode('-', $value);
      }

      $fecha0[0] = substr($fecha0[0], 2);
      $fecha1[0] = substr($fecha1[0], 2);
      // $fecha0[1] = substr($fecha0[1], 1);
      // $fecha1[1] = substr($fecha1[1], 1);

      $datos = ['desde' => $fecha0, 'hasta' => $fecha1, 'por' => $clasificacion];


      if($fecha_de_inicio != "" and $fecha_de_inicio != null){
        if($fecha_de_cierre != "" and $fecha_de_cierre != null){
          if($clasificacion != "" and $fecha_de_cierre != null){
            $validacion_de_fecha = $this->model->ValidarFecha([$datos['desde'], $datos['hasta']]);
            if($validacion_de_fecha == true and !is_array($validacion_de_fecha)){
              $data = $this->model->TomarDatos($datos);
              $colores = ["rgba(255, 99, 132, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(255, 206, 86, 0.2)", "rgba(75, 192, 192, 0.2)", "rgba(153, 102, 255, 0.2)", "rgba(255, 159, 64, 0.2)"];
              if(!empty($data) and $data != null){
                $longitud_de_datos = count($data) -1;
                $this->view->statistics = "
                    labels: [";foreach ($data as $key => $value) {
                                  $insert = $key != $longitud_de_datos ? "'$value[0]'," : "'$value[0]'";
                                  $this->view->statistics .= $insert;
                                }
                $this->view->statistics .= "],
                    datasets: [{
                        data: [";foreach ($data as $key => $value) {
                                    $insert = $key != $longitud_de_datos ? $value[1] . "," : $value[1];
                                    $this->view->statistics .= $insert;
                                  }
                $this->view->statistics .= "],
                        //backgroundColor: [";
                // foreach ($data as $key => $value) {
                //   $indice = rand(0,count($colores)-1);
                //   $insert = $key != $longitud_de_datos ? "'$colores[$indice]'," : "'$colores[$indice]'";
                //   $this->view->statistics .= $insert;
                // }
                $this->view->statistics .= "],
                        //borderWidth: 1
                    }]";
                $this->render();
              }else{
                $this->render();
                print("<script>alert('Error');</script>");
              }
            }else{
              $this->render();
              foreach ($validacion_de_fecha[1] as $key => $array) {
                ${'fecharango' . $key} = "";
                //foreach ($array as $key2 => $datos) {
                $index = count($array) -1;
                while($index >= 0){
                  $insert = $index != 0 ? $array[$index] . "/" : $array[$index];
                  ${'fecharango' . $key} .= $insert;
                  $index--;
                }
              }
              print("<script>alert('Este rango de fechas es mayor o menor a las fechas registradas en la base de datos, La fecha mas antigua registrada es: $fecharango0 y la mas reciente es: $fecharango1');</script>");
            }
          }
        }
      }

    }
  }

 ?>
