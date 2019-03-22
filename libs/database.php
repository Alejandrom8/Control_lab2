<?php

  class Database{

    private $host;
    private $db;
    private $db2;
    private $user;
    private $password;
    private $charset;

    public function __construct(){

      $this->host = constant('HOST');
      $this->db = constant('DB');
      $this->db2 = constant('DB2');
      $this->user = constant('USER');
      $this->password = constant('PASSWORD');
      $this->charset = constant('CHARSET');

    }

    function connect(){

      try{

        $conection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
        $options = [
          PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_EMULATE_PREPARES    => false,
        ];
        $pdo = new PDO($conection, $this->user, $this->password, $options);

        return $pdo;

      }catch(PDOException $e){
        print_r('Error connection: ' . $e->getMessage());
      }

    }

    function connect2(){

      try{

        $conection = "mysql:host=" . $this->host . ";dbname=" . $this->db2 . ";charset=" . $this->charset;
        $options = [
          PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_EMULATE_PREPARES    => false,
        ];
        $pdo = new PDO($conection, $this->user, $this->password, $options);

        return $pdo;

      }catch(PDOException $e){
        print_r('Error connection: ' . $e->getMessage());
      }

    }

  }

?>
