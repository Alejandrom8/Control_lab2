const botons = document.querySelectorAll(".obj_li");
botons.forEach(boton =>{
  boton.addEventListener("click", function(){
    const display = this.dataset.pantalla;
    $(document).ready(function(){
      $(".pantallas:not(#" + display + ")").css({'display':'none'});
      $("#" + display).css({'display':'block'});
    });
  });
});
