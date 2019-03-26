<?php
  class SalirModel extends Model{
    function __construct(){
      parent::__construct();
      $this->con = $this->db->connect();
    }
    function finalizar(){
      $finalizar_sesion = "UPDATE visitas_" . $_SESSION['id'] . " SET session = 0";
      $finalizando = $this->con->prepare($finalizar_sesion);
      $finalizando->execute();
      if($finalizando){
        $visitas_del_dia = "INSERT INTO visitas_totales SELECT * FROM visitas_" . $_SESSION['id'];
        $ejecutar = $this->con->prepare($visitas_del_dia);
        $ejecutar->execute();
        if($ejecutar){
          $borrar = "TRUNCATE visitas_" . $_SESSION['id'];
          $borrando = $this->con->query($borrar);
          $estado = $borrando ? true : false;
          return $estado;
        }else {
          return false;
        }
      }else{
        return false;
      }
    }
  }

 ?>
