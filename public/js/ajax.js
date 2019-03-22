
const botones = document.querySelectorAll(".cerrar");
const copias = document.querySelectorAll(".copias");

botones.forEach(boton => {
  boton.addEventListener("click", function(){
    const matricula = this.dataset.matricula;
    const nombre = this.dataset.nombre;

    // const confirm = window.confirm("¿Deseas eliminar al alumno " + matricula + " ? ");
    // if(confirm){
    //   //solicitud ajax
      httpRequest("http://localhost/proyectos/control_lab/consulta/terminar/" + matricula + "/" + nombre, function(){
        console.log(this.responseText);
        document.querySelector("#Respuesta").innerHTML = this.responseText;
        const tbody = document.querySelector("#tbody-alumnos");
        const fila = document.querySelector("#fila-" + matricula);

        tbody.removeChild(fila);
      });
    // }
  });
});

function httpRequest(url, callback){
  const http = new XMLHttpRequest();
  http.open("GET", url);
  http.send();

  http.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
      callback.apply(http);
    }
  }
}
