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

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    function crear_sala(){

      $nombre = $this->test_input($_POST['Nombre']);
      $id = $this->test_input($_POST['id']);
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
      $aula = $this->test_input($_REQUEST['sala']);
      $fecha_de_inicio = $this->test_input($_REQUEST['de']);
      $fecha_de_cierre = $this->test_input($_REQUEST['a']);
      $clasificacion = $this->test_input($_REQUEST['clasif']);

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
      $visitas = array();//contendra la relacion de dias meses o años con su numero de visitas correspondientes ejemplo dia
      $terminos = array();// contiene los dias o meses o años diferentes dentro del rango de fechas
      $años = $this->FormarArrayFecha($array, 'y');//tomamos todos los años diferentes que existen
      $meses = $this->FormarArrayFecha($array, 'm');

      //  por cada dia encontrado, se buscara en el array general cuantos registros
      //  cuentan con el mismo dia, sumando asi el contador debido para cada dia,
      //  al final se agrega el dia y el conteo total de visitas de ese dia a un array llamado visitas
      switch ($clasificacion) {
        case 'dia':
          foreach ($años as $index => $año) {
            //por cada año
            foreach ($meses as $index_mes => $mes) {
              //por cada mes
              $dias = array();
              foreach ($array as $key => $value) {
                $mes_comp_mes = date('m', strtotime($value->fecha));
                $dia_comp_dia = date('d', strtotime($value->fecha));
                if($mes_comp_mes == $mes && !in_array($dia_comp_dia, $dias)){
                  array_push($dias, $dia_comp_dia);
                }
              }
              foreach ($dias as $index_dia => $dia) {
                //por cada dia
                $cont = 0;
                $copias_cont = 0;
                foreach ($array as $key => $registro) {
                  //por cada registro
                  $fecha_reg = strtotime($registro->fecha);
                  $año_registro = date('y', $fecha_reg);
                  $mes_registro = date('m', $fecha_reg);
                  $dia_registro = date('d', $fecha_reg);
                  $copias = (int)$registro->no_copias;
                  if($año == $año_registro && $mes == $mes_registro && $dia == $dia_registro){
                    //se aumentara el contador de visitas
                    $cont++;
                    $copias_cont += $copias;
                  }
                }
                $fecha = $año . "-" . $mes . "-" . $dia;
                $visitas[] = ['fecha' => $fecha, 'visitas' => $cont, 'copias' => $copias_cont];
              }
            }
          }
          break;
        case 'mes':
          foreach($años as $index => $año){
            //por cada año
            foreach ($meses as $key => $mes){
              //por cada mes
              $cont = 0;
              $copias_cont = 0;
              foreach ($array as $indice => $registro) {
                $año_registro = date('y', strtotime($registro->fecha));
                $mes_registro = date('m', strtotime($registro->fecha));
                $copias = $registro->no_copias;
                if($año == $año_registro && $mes == $mes_registro){
                  $cont++;
                  $copias_cont += $copias;
                }
              }
              $fecha = $año . "-" . $mes;
              $visitas[] = ['fecha' => $fecha, 'visitas' => $cont, 'copias' => $copias_cont];
            }
          }
          break;
        case 'year':
          foreach ($años as $index => $año) {
              $cont = 0;
              $copias_cont = 0;
              foreach ($array as $key => $registro) {
                $año_registro = date('y',strtotime($registro->fecha));
                $copias = $registro->no_copias;
                if($año_registro == $año){
                  $cont++;
                  $copias_cont += $copias;
                }
              }
              $visitas[] = ['fecha' => $año, 'visitas' => $cont, 'copias' => $copias_cont];
          }
          break;
        default:
          return [false, 'Error al generar el conteo'];
          break;
      }
      //finalmente si existen elementos en el array 'visitas', lo retornamos
      if(isset($visitas)){
        function method1($a,$b){
          return ($a['fecha'] <= $b["fecha"]) ? -1 : 1;
        }
        usort($visitas, "method1");
        return [true, json_encode($visitas)];
      }else{
        return [false, "Error al cargar los datos"];
      }
    }

    function FormarArrayFecha($array, $termino_evaluado){
      $terminos = array();
      foreach ($array as $key => $value) {
        $termino = date($termino_evaluado,strtotime($value->fecha));
        if(!in_array($termino,$terminos)){
            $terminos[] = $termino;
        }
      }
      return $terminos;
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

    function Export(){
      $aula = $this->test_input($_REQUEST['sala']);
      $tabla = $aula == 'todas' ? constant('todas_las_visitas') : 'visitas_' . $aula;
      if($tabla != null){
        $tomar_datos = $this->model->Export($tabla);
        if($tomar_datos){
          $estado = $tomar_datos[0];
          $resultado = $tomar_datos[1];
          if($estado){
              echo $resultado;
          }else{
            echo false;
          }
        }else{
          echo false;
        }
      }else{
        echo false;
      }
    }
  }

 ?>
