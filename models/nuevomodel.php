<?php

  class NuevoModel extends Model{

    private $con;
    private $con2;

    public function __construct(){
      parent::__construct();
      $this->con = $this->db->connect();
      $this->con2 = $this->db->connect2();
    }

    public function insert($datos){
      //insertar datos en la BD
      if($this->con){

          $matricula = $datos['matricula'];
          $nombre = $datos['nombre'];
          $fecha = $datos['fecha'];
          $hora = $datos['hora'];
          $tipo_usuario = $datos['tipo_usuario'];
          $id_aula = $_SESSION['id'];

          //validando que no halla sesion abierta
          $consulta = "SELECT session FROM visitas_" . $_SESSION['id'] . " WHERE matricula ='$matricula'";
          $consluta_query = $this->con->prepare($consulta);
          $consluta_query->execute();

          while($row = $consluta_query->fetch(PDO::FETCH_ASSOC)){
            if($row['session'] == 1){
              return false;
            }
          }

          //insertando los datos
          if($tipo_usuario == 1){
            $sql = "INSERT INTO visitas_" . $_SESSION['id'] . "(matricula, id_aula, nombre, tipo, fecha, hora, no_copias, no_visita, session) VALUE('$matricula', '$id_aula','$nombre', '$tipo_usuario','$fecha', '$hora', 0, 0, 0)";
          }else{
              $sql = "INSERT INTO visitas_" . $_SESSION['id'] . "(matricula, id_aula, nombre, tipo, fecha, hora, no_copias, no_visita) VALUE('$matricula', '$id_aula','$nombre', '$tipo_usuario','$fecha', '$hora', 0, 0)";
          }
          $query = $this->con->prepare($sql);
          $query->execute();
          if($query){
            return true;
          }else{
            return false;
          }
      }else{
        return false;
      }
    }
    public function BuscarNombre($datos){
      //buscar datos de acuerdo al numero de cuenta o id de trabajador
      if($this->con2){
        $user = $datos['user'];
        $tabla = $datos['tabla'];
        $reference = $datos['reference'];
        $matricula = $datos['matricula'];
        $tipo = $datos['tipo_usuario'];

        $sql = "SELECT $user FROM $tabla WHERE $reference = $matricula";
        $query = $this->con2->prepare($sql);
        $query->execute();

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
          $nombre = $tipo == 1 ? $row['ApPat'] . " " . $row['ApMat'] . " " . $row['Nom'] : $row[$user];
        }

        if($tipo == 1 and $nombre != null){

        }

        if(!isset($nombre) or $nombre == null or $nombre == ""){
          return null;
        }else{
          return $nombre;
        }

      }else{
        return false;
      }

    }
  }

 ?>
