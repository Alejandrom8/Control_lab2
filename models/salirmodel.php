<?php
  class SalirModel extends Model{
    function __construct(){
      parent::__construct();
      $this->con = $this->db->connect();
    }
    function finalizar(){
      $sql = "UPDATE visitas_". $_SESSION['id'] ." SET session = 0";
      $sql_execute = $this->con->prepare($sql);
      $sql_execute->execute();
      if($sql_execute){
          $visitas_del_dia = "
            INSERT INTO ". constant('todas_las_visitas') ."(matricula,id_aula,nombre,tipo,fecha,hora,no_copias)
            SELECT matricula,id_aula,nombre,tipo,fecha,hora,no_copias FROM visitas_". $_SESSION['id'];
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
