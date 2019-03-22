<?php
include_once 'models/visita.php';

class AdminModel extends Model{

  private $con;

  public function __construct(){
    parent::__construct();
    $this->con = $this->db->connect();
  }

  public function comprobar($id, $aula){

    $sql = "SELECT id,aula FROM aulas WHERE id = $id OR aula = '$aula'";
    $query_prep = $this->con->prepare($sql);
    $query_prep->execute();

    while($row = $query_prep->fetch(PDO::FETCH_ASSOC)){
      return true;
    }

    return false;
  }

  public function CrearSala($datos){
    $nombre = $datos['nombre'];
    $id = $datos['id'];
    $password = $datos['password'];
    $permisos = $datos['permisos'];

    $sql = "INSERT INTO aulas(id, aula, pass, permisos) VALUE('$id', '$nombre', '$password', '$permisos')";
    $query_insert = $this->con->prepare($sql);
    $query_insert->execute();

    if($query_insert){
      //creando la tabla donde se almacenaran las visitas diarias de la sala
        $tabla = "CREATE TABLE visitas_$id(
                  `matricula` int(10) NOT NULL,
                  `id_aula` int(10) NOT NULL,
                  `nombre` varchar(60) NOT NULL,
                  `tipo` int(1) NOT NULL,
                  `dia` int(2) NOT NULL,
                  `mes` int(2) NOT NULL,
                  `year` int(2) NOT NULL,
                  `hora` varchar(10) NOT NULL,
                  `no_copias` int(2) NOT NULL,
                  `no_visita` int(10) NOT NULL AUTO_INCREMENT,
                  `session` tinyint(1) NOT NULL DEFAULT '1'
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $query_create = $this->con->prepare($tabla);
        $query_create->execute();

        if($query_create){
          return true;
        }else{
          return false;
        }
    }else{
      return false;
    }
  }

  public function ValidarFecha($data){

    $sql_min = "SELECT * FROM visitas_801 WHERE year = (SELECT MIN(year) FROM visitas_801) AND mes = (SELECT MIN(mes) FROM visitas_801) AND dia = (SELECT min(dia) FROM visitas_801) LIMIT 1";
    $fecha_min = $this->con->prepare($sql_min);
    $fecha_min->execute();

    $sql_max = "SELECT * FROM visitas_801 WHERE year = (SELECT max(year) FROM visitas_801) AND mes = (SELECT max(mes) FROM visitas_801) AND dia = (SELECT max(dia) FROM visitas_801) LIMIT 1";
    $fecha_max = $this->con->prepare($sql_max);
    $fecha_max->execute();

          $fechas = [];
          while($a = $fecha_min->fetch(PDO::FETCH_ASSOC)){
            array_push($fechas,[$a['year'], $a['mes'], $a['dia']]);
            break;
          }
          while($b = $fecha_max->fetch(PDO::FETCH_ASSOC)){
            array_push($fechas,[$b['year'], $b['mes'], $b['dia']]);
            break;
          }

    //fechas maximas y minimas registradas en la base de datos
    $fecha_reg_min = $fechas[0];
    $fecha_reg_max = $fechas[1];
    //fechas maximas y minimas buscadas
    $fecha_int_min = $data[0];
    $fecha_int_max = $data[1];

    if($fecha_int_min[0] == $fecha_reg_min[0]){
      //validando que el mes y el dia esten dentro del dominio [$desde, $hasta]
      if($fecha_int_min[1] == $fecha_reg_min[1]){
        //solo validamos el dia si es el mismo mes y el mismo año
        if($fecha_int_min[2] == $fecha_reg_min[2]){
          return true;
        }else if($fecha_int_min[2] < $fecha_reg_min[2]){
          return [false, $fechas];
        }else{
          return true;
        }
      }else if($fecha_int_min[1] < $fecha_int_min[1]){
        return [false, $fechas];
      }else if($fecha_int_min[1] > $fecha_reg_min){
        if($fecha_int_min[2] == $fecha_reg_min[2]){
          return true;
        }else if($fecha_int_min[2] < $fecha_reg_min[2]){
          return [false, $fechas];
        }else{
          return true;
        }
      }
    }else if($fecha_int_min[0] < $fecha_reg_min[0]){
      //si son diferentes años validamos que no sea
      //un año menor al que esta registrado en la base de datos
      return [false, $fechas];
    }else if($fecha_int_min[0] > $fecha_reg_min[0]){
      if($fecha_int_min[1] == $fecha_reg_min[1]){
        //solo validamos el dia si es el mismo mes y el mismo año
        if($fecha_int_min[2] == $fecha_reg_min[2]){
          return true;
        }else if($fecha_int_min[2] < $fecha_reg_min[2]){
          return [false, $fechas];
        }else{
          return [false, $fechas];
        }
      }else if($fecha_int_min[1] < $fecha_int_min[1]){
        return [false, $fechas];
      }else if($fecha_int_min[1] > $fecha_reg_min){
        if($fecha_int_min[2] == $fecha_reg_min[2]){
          return true;
        }else if($fecha_int_min[2] < $fecha_reg_min[2]){
          return [false, $fechas];
        }else{
          return true;
        }
      }
    }else{
        return [false, $fechas];
    }
  }

  public function TomarDatos($data){

    //recibiendo datos si se han introducido
    $desde = isset($data['desde']) ? $data['desde'] : null;
    $hasta = isset($data['hasta']) ? $data['hasta'] : null;
    $por = $data['por'];

    if($desde != null and $hasta != null){

      $fechas = [$desde, $hasta];

      foreach ($fechas as $key => $value) {
        ${'dia' . $key} = $value[2];
        ${'mes' . $key} = $value[1];
        ${'year' . $key} = $value[0];
      }
      //AND mes >= $mes0 AND mes <= $mes1 AND year >= $year0 AND year <= $year1
      $sql = "SELECT * FROM visitas_801 WHERE dia >= $dia0 AND dia <= $dia1";
      $query_data = $this->con->prepare($sql);
      $query_data->execute();
      $visitas = [];

      while($row = $query_data->fetch(PDO::FETCH_ASSOC)){
        $visita = new Visita();
        $visita->dia = $row['dia'];
        $visita->mes = $row['mes'];
        $visita->year = $row['year'];
        $visita->hora = $row['hora'];
        $visita->no_copias = $row['no_copias'];
        array_push($visitas, $visita);
      }

      if(!empty($visitas)){

        $result = null;

        switch ($por) {
          case 'dia':
            $visitas_por_dia = [];

            for($i = $dia0; $i <= $dia1; $i++){
              ${'cont' . $i} = 0;
              foreach ($visitas as $key => $value) {
                if($value->dia == $i){
                  ${'cont' . $i}++;
                }
              }
              $name = 'dia: ' . $i;
              array_push($visitas_por_dia, [$name, ${'cont' . $i}]);
            }
            $result = $visitas_por_dia;
            break;
          case 'mes':
            $visitas_por_mes = [];
            for($i = $mes0; $i <= $mes1; $i++){
              ${'cont' . $i} = 0;
              foreach ($visitas as $key => $value) {
                if($value->mes == $i){
                  ${'cont' . $i}++;
                }
              }
              $name = 'mes: ' . $i;
              array_push($visitas_por_mes, [$name, ${'cont' . $i}]);
            }
            $result = $visitas_por_mes;
            break;
          case 'year':
            $visitas_por_year = [];

            for($i = $year0; $i <= $year1; $i++){
              ${'cont' . $i} = 0;
              foreach ($visitas as $key => $value) {
                if($value->year == $i){
                  ${'cont' . $i}++;
                }
              }
              $name = 'año: 20' . $i;
              array_push($visitas_por_year, [$name, ${'cont' . $i}]);
            }
            $result = $visitas_por_year;
            break;
          default:
            return null;
            break;
        }

        return $result;
      }else{
        return null;
      }
    }else{
      return null;
    }
  }
}

 ?>
