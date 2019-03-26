function validar(){
  var nombre = document.getElementById("Nombre").value;
  var id = document.getElementById("id").value;
  var pass = document.getElementById("pass").value;
  var pass_r = document.getElementById("pass_r").value;

  if(!nombre || nombre == null || nombre.length > 20){
      $(document).ready(function(){$('#Nombre').css({'background-color':'red'});});
      alert('El nombre del aula es muy grande o es invalido');
      return false;
  }else if(!id || id == null || id.length > 3 || !/^([0-9])*$/.test(id)){
      $(document).ready(function(){$('#id').css({'background-color':'red'});});
      alert('El id del aula es invalido');
      return false;
  }else if(!pass || pass == null){
      $(document).ready(function(){$('#pass').css({'background-color':'red'});});
      alert('La contraseña contiene caracteres invalidos');
      return false;
  }else if (!pass_r || pass_r == null) {
      $(document).ready(function(){$('#pass_r').css({'background-color':'red'});});
      alert('La contraseña repetida contiene caracteres invalidos');
      return false;
  }

  if(pass === pass_r){
      return true;
  }else{
    alert("Las contraseñas no coinciden");
    return false;
  }
}
