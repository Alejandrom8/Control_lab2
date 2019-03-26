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

  public function ValidarFecha($desde, $hasta){
    $fecha_actual = strtotime(date("y-m-d"));
    if($hasta <= $fecha_actual){
      try{
        $sql = "SELECT fecha, hora, no_copias FROM visitas_801 WHERE fecha BETWEEN '$desde' AND '$hasta'";
        $estado_sql = $this->con->prepare($sql);
        $estado_sql->execute();
        if($estado_sql){
          if($datos = $estado_sql->fetch(PDO::FETCH_ASSOC)){
            $visitas = array();
            while($row = $estado_sql->fetch(PDO::FETCH_ASSOC)){
              $visitas[] = $row;
            }
            return [true, $visitas];
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

  public function TomarDatos($data){
    return [true, $data];
    // $array = json_decode($data);
    // $visitas = array();
    // $dias = array();
    // //tomamos todos los dias diferentes que existen
    // foreach ($data as $key => $value) {
    //   $dia = date('d',strtotime($value['fecha']));
    //   if(!in_array($dia,$dias)){
    //     array_push($visitas, $dia);
    //   }
    // }
    // //  por cada dia encontrado, se buscara en el array general cuantos registros
    // //  cuentan con el mismo dia, sumando asi el contador debido para cada dia
    // //  al final se agrega el dia y el conteo total de visitas de ese dia a un array llamado visitas
    // for($i = 0; $i < count($dias); $i++){
    //   $v = 0;
    //   foreach ($data as $key_2 => $value_2) {
    //     $dia = date('d' ,strtotime($value_2['fecha']));
    //     if($dia == $dias[$i]){
    //       $v++;
    //     }
    //   }
    //   $visitas[] = ['dia' => $dias[$i], 'visitas' => $v];
    // }
    // //finalmente si existen elementos en el array 'visitas', lo retornamos
    // if(isset($visitas)){
    //   return [true, json_encode($visitas)];
    // }else{
    //   return [false, "Error al cargar los datos"];
    // }
  }
}

 ?>
