<?php

  class MainModel extends Model{

    private $con;

    public function __construct(){
      parent::__construct();
      $this->con = $this->db->connect();
    }

    public function get_id($aula){

      $sql = "SELECT id, aula, permisos FROM aulas WHERE aula = '$aula' OR id='$aula'";
      $query = $this->con->prepare($sql);
      $query->execute();
      $id = null;
      while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $id = $row['id'];
        $nombre = $row['aula'];
        $permisos = $row['permisos'];
        break;
      }
      if($query){
        $_SESSION['id'] = $id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['permisos'] = $permisos;
        return true;
      }else{
        return false;
      }
    }

    public function login($aula, $password){
      //comprueba el logeo
      if($this->con){

        //comprobando que el usuario exista.
        $user = null;
        $sql_exist = "SELECT aula FROM aulas WHERE id ='$aula' OR aula ='$aula'";
        $query1 = $this->con->prepare($sql_exist);
        $query1->execute();

        while($row_u = $query1->fetch(PDO::FETCH_ASSOC)){
          $user = $row_u['aula'];
          break;
        }

        if($user != null and $user){
          //comprobando que la contraseña sea correcta.
          $pass = null;
          $sql_pass = "SELECT pass FROM aulas WHERE aula = '$user'";
          $query2 = $this->con->prepare($sql_pass);
          $query2->execute();

          while($row = $query2->fetch(PDO::FETCH_ASSOC)){
            $pass = $row['pass'];
            break;
          }


          //si es correcta la contraseña, se crea una sesion con su nombre y usuario.
          if($pass != null and password_verify($password, $pass)){

              session_regenerate_id();
              $_SESSION['aula'] = $user;
              $_SESSION['pass'] = $password;
              return true;

          }else{
              return "contraseña incorrecta";
          }
        }else{
          return "Usuario incorrecto";
        }
      }else{
        return "Hubo un fallo al conectar con la base de datos";
      }
    }

  }

?>
