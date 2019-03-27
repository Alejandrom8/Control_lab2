<?php
  setlocale(LC_TIME, "es_MX.UTF-8");
  date_default_timezone_set("America/Mexico_City");
  define('URL', 'http://localhost/proyectos/control-lab/');

  define('HOST', 'localhost');
  define('DB', 'control');
  define('DB2', 'califica');
  define('USER', 'root');
  define('PASSWORD', 'A_lex 123');
  define('CHARSET', 'utf8');

  //tablas
  define('Consulta_prof', 'profdegae');
  define('Consulta_alumn', 'calif');
  define('todas_las_visitas', 'visitas_totales');

  if(isset($_SESSION['id']) and $_SESSION['id'] != null and $_SESSION['id'] != ""){
    $__tabla = $_SESSION['id'];
  }

?>
