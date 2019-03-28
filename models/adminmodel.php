<?php
include_once 'models/aulas.php';
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

  public function Insertar($archivo, $separador, $id){
    while(($data = fgetcsv($archivo, 1000, $separador)) !== FALSE){
      $sql = "INSERT INTO ". constant('todas_las_visitas') ."(matricula, id_aula, nombre, tipo, fecha, hora, no_copias) VALUE('$data[0]','$id','$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]')";
      $ejecutar = $this->con->prepare($sql);
      $ejecutar->execute();
    }
    return true;
  }

  public function Export($tabla){
    try{
      $sql_exists = "SHOW TABLES LIKE '$tabla'";
      $result = $this->con->prepare($sql_exists);
      $result->execute();
      $existe = false;

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $existe = true;
      }

      if($existe){
        try {

          if($tabla == constant('todas_las_visitas')){
            $sql = "SELECT matricula, id_aula, nombre, tipo, fecha, hora, no_copias FROM " . constant('todas_las_visitas') . " ORDER BY fecha ASC";
          }else{
            $sql = "SELECT matricula, id_aula, nombre, tipo, fecha, hora, no_copias FROM visitas_" . $tabla . " ORDER BY fecha ASC";
          }
          $ejecutar = $this->con->prepare($sql);
          $ejecutar->execute();
          $visitas = array();
          if($row2 = $ejecutar->fetch(PDO::FETCH_ASSOC)){
            while($row2 = $ejecutar->fetch(PDO::FETCH_ASSOC)){
              $visita = new Visita();
              $visita->matricula = $row2['matricula'];
              $visita->id_aula = $row2['id_aula'];
              $visita->nombre = $row2['nombre'];
              $visita->tipo = $row2['tipo'];
              $visita->fecha = $row2['fecha'];
              $visita->hora = $row2['hora'];
              $visita->no_copias = $row2['no_copias'];
              array_push($visitas, $visita);
            }

            return [true, json_encode($visitas)];
        }else{
          return [false, 'No hay resultados para esta sala'];
        }
        } catch (PDOException $e) {
          return [false, 'Hubo un error al buscar los datos'];
        }
      }else{
        return [false,'La sala que indico no existe'];
      }
    }catch(PDOException $e){
        return [false, 'Hubo un error al llevar a cabo la operación'];
    }
  }
}

 ?>
