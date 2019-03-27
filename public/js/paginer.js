const botons = document.querySelectorAll(".obj_li");
botons.forEach(boton =>{
  boton.addEventListener("click", function(){
    const display = this.dataset.pantalla;
    $(document).ready(function(){
      $(".obj_li:not(#" + display + "_menu)").css({'background-color':'#fff'});
      $("#" + display + "_menu").css({'background-color':'transparent'});
      $(".pantallas:not(#" + display + ")").css({'display':'none'});
      $("#" + display).css({'display':'block'});
    });
  });
});
