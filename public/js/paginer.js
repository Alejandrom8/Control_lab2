const botons = document.querySelectorAll(".obj_li");
botons.forEach(boton =>{
  boton.addEventListener("click", function(){
    const display = this.dataset.pantalla;
    $(document).ready(function(){
      $(".obj_li:not(#" + display + "_menu)").css({'background-color':'#fff','color':'#111'});
      $("#" + display + "_menu").css({'background-color':'#141E26', 'color':'#fff'});
      $(".pantallas:not(#" + display + ")").css({'display':'none'});
      $("#" + display).css({'display':'block'});
    });
  });
});
