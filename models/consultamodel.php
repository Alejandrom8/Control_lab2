<?php

  include_once 'models/alumno.php';

  class ConsultaModel extends Model{

    private $con;

    public function __construct(){
      parent::__construct();
      $this->con = $this->db->connect();
    }
    public function get(){

      $items = [];

      try{

        $query = $this->con->query("SELECT * FROM visitas_" . $_SESSION['id'] . " WHERE id_aula ='" . $_SESSION['id'] . "' AND session = 1 AND tipo = 2 ORDER BY hora DESC");

        while($row = $query->fetch()){

          $item = new Alumno();
          $item->matricula = $row['matricula'];
          $item->nombre = $row['nombre'];

          $tipo = $row['tipo'] == 1 ? 'profesor': 'alumno';

          $item->tipo = $tipo;
          $item->hora = $row['hora'];
          $item->no_copias = $row['no_copias'];

          $estado = $row['session'] == 1 ? 'activo': 'inactivo';

          $item->session = $estado;

          array_push($items, $item);
        }

        return $items;

      }catch(PDOException $e){
        return [];
      }
    }

    public function copias($id, $copias, $op){

      if($op){
        if(count(str_split($id)) <= 6){
            $actualizar_copias = "UPDATE visitas_" . $_SESSION['id'] . " SET no_copias = $copias WHERE matricula = '$id' ORDER BY no_visita DESC LIMIT 1";
        }else{
            $actualizar_copias = "UPDATE visitas_" . $_SESSION['id'] . " SET no_copias = no_copias + 1 WHERE matricula = '$id' AND session = 1";
        }
      }else{
          $actualizar_copias = "UPDATE visitas_" . $_SESSION['id'] . " SET no_copias = no_copias - 1 WHERE matricula = '$id' AND session = 1";
      }

      $query_update = $this->con->prepare($actualizar_copias);
      $query_update->execute();

      if($query_update){
        return true;
      }else{
        return false;
      }

    }

    public function terminar($id){
      if($this->con){
        $up = "UPDATE visitas_" . $_SESSION['id'] . " SET session = 0 WHERE matricula = '$id'";
        $query = $this->con->prepare($up);
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

  }


 ?>
