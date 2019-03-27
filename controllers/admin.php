<?php
  class Admin extends Controller{

    function __construct(){
      parent::__construct();
      $this->view->mensaje = "";
      $this->view->titulo_st = "";
      $this->view->aulas = [];
    }

    function render(){
      if($_SESSION['permisos'] == 1){
        $aulas = $this->model->ConsultarSalas($_SESSION['id']);
        $this->view->aulas = $aulas;
          $this->view->render('admin/index');
      }else{
          print("<script>alert('acceso denegado'); window.location='".constant('URL')."nuevo';</script>");
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
      //reciviendo variables ajax
      $aula = $_REQUEST['sala'];
      $fecha_de_inicio = $_REQUEST['de'];
      $fecha_de_cierre = $_REQUEST['a'];
      $clasificacion = $_REQUEST['clasif'];

      if($fecha_de_inicio > $fecha_de_cierre){
        $this->view->mensaje = "Rango de fechas invalido";
        $this->render();
        echo false;
      }else{
        $data = ['donde' => $aula, 'desde' => $fecha_de_inicio, 'hasta' => $fecha_de_cierre, 'por' => $clasificacion];

        $estado_de_verificacion = $this->model->ValidarFecha($data['donde'],$data['desde'],$data['hasta']);
        $resultado = $estado_de_verificacion[0];
        $mensaje = $estado_de_verificacion[1];
        if($estado_de_verificacion[0]){
          $tomar_datos = $this->TomarDatos($mensaje, $data['por']);
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

    function TomarDatos($data, $clasificacion){
      $array = json_decode($data);
      $visitas = array();//contendra la relacion de dias meses o años con su numero de visitas correspondientes
      $terminos = array();// contiene los dias o meses o años diferentes dentro del rango de fechas


      switch ($clasificacion) {
        case 'dia':
          $termino_evaluado = 'd';
          break;
        case 'mes':
          $termino_evaluado = 'm';
          break;
        case 'year':
          $termino_evaluado = 'y';
          break;
        default:
          $termino_evaluado = 'd';
          break;
      }

      //tomamos todos los dias diferentes que existen
      foreach ($array as $key => $value) {
        $termino = date($termino_evaluado,strtotime($value->fecha));
        if(!in_array($termino,$terminos)){
            $terminos[] = $termino;
        }
      }
      //  por cada dia encontrado, se buscara en el array general cuantos registros
      //  cuentan con el mismo dia, sumando asi el contador debido para cada dia
      //  al final se agrega el dia y el conteo total de visitas de ese dia a un array llamado visitas
      for($i = 0; $i < count($terminos); $i++){
        $v = 0;
        foreach ($array as $key_2 => $value_2) {
          $termino = date($termino_evaluado ,strtotime($value_2->fecha));
          if($termino == $terminos[$i]){
            $v++;
          }
        }
        $visitas[] = ['termino' => $terminos[$i], 'visitas' => $v];
      }
      //finalmente si existen elementos en el array 'visitas', lo retornamos
      if(isset($visitas)){
        return [true, json_encode($visitas)];
      }else{
        return [false, "Error al cargar los datos"];
      }
    }

    function Insert(){
      $archivo = $_FILES['archivo'];
      $separation = $_POST['separation'];
      $id = $_POST['id'];

      $nombre_archivo = $archivo['name'];
      $size_archivo = $archivo['size'];
      $tipo_archivo = explode('.', $nombre_archivo);

      if(strtolower(end($tipo_archivo)) == 'csv'){
        $filename = $_FILES['archivo']['tmp_name'];
        $handle = fopen($filename, 'r');
        $insertar = $this->model->Insertar($handle, $separation, $id);
        if($insertar){
          echo "Se insertaron todos los datos correctamente";
        }else{
          echo "No se insertaron los datos correctamente";
        }
      }else{
        echo "El tipo de archivo es invalido";
      }
    }
  }

 ?>
