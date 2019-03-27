<?php
include_once 'models/aulas.php';

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
      break;
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
                  `fecha` DATE,
                  `hora` varchar(10) NOT NULL,
                  `no_copias` int(2) NOT NULL,
                  `no_visita` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
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

  public function ValidarFecha($donde,$desde, $hasta){
    $fecha_actual = strtotime(date("y-m-d"));
    if($hasta <= $fecha_actual){
      if($donde != 'todas'){
        $sql = "SELECT tipo, fecha, hora, no_copias FROM " . constant('todas_las_visitas')  . " WHERE (fecha BETWEEN '$desde' AND '$hasta') AND id_aula = '$donde' ORDER BY fecha ASC";
      }else{
        $sql = "SELECT tipo, fecha, hora, no_copias FROM " . constant('todas_las_visitas')  . " WHERE fecha BETWEEN '$desde' AND '$hasta' ORDER BY fecha ASC";
      }
      try{
        $estado_sql = $this->con->prepare($sql);
        $estado_sql->execute();
        if($estado_sql){
          if($datos = $estado_sql->fetch(PDO::FETCH_ASSOC)){
            $visitas = array();
            while($row = $estado_sql->fetch(PDO::FETCH_ASSOC)){
              array_push($visitas, $row);
            }
            return [true, json_encode($visitas)];
          }else{
            return [false, "No hay ningùn registro entre este rango de fechas"];
          }
        }else{
          return [false, "hubo un error al realizar la consulta"];
        }
      }catch(PDOException $e){
        print_r('Error al realizar la consulta: ' . $e->getMessage());
      }
    }else{
      return [false, "La fecha de cierre es mayor a la fecha actual, introdusca una fecha valida"];
    }
  }

  public function ConsultarSalas($exception){
    $sql = "SELECT id, aula FROM aulas WHERE id <> $exception";
    $ejecutar = $this->con->prepare($sql);
    $ejecutar->execute();
    $aulas = array();
    while($row = $ejecutar->fetch(PDO::FETCH_ASSOC)){
      $aula = new Aulas();
      $aula->id = $row['id'];
      $aula->aula = $row['aula'];
      array_push($aulas, $aula);
    }
    return $aulas;
  }
}

 ?>
