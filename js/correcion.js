$('.btnCorrecion').on('click', function(){    
    var respuesta = $(this).siblings(".respuestaText");
    respuesta.toggleClass('resp-corecta');
    if(respuesta.hasClass('resp-corecta')) {
        $(this).removeClass('btn-dark-green');
        $(this).addClass('btn-danger');
        $(this).children("i").removeClass("fa-check");
        $(this).children("i").addClass("fa-times");
        $(this).attr("title","Deshacer");
    } else {
        $(this).removeClass('btn-danger');
        $(this).addClass('btn-dark-green');
        $(this).children("i").removeClass("fa-times");
        $(this).children("i").addClass("fa-check");
        $(this).attr("title","Marcar como correcta");
    }
});


        
    
    
$("#terminarCorrecion").on('click', function(){
    var acertadasTot = 0;
    var preguntasTot = $("#lista-preguntas").children().length;    
    $(".multi-val").each(function(){
        var correctas = $(this).siblings(".opciones").children(".corrOpt").length;          
        var acertadas = $(this).siblings("div").children(".sortable").children(".correcta").length;        
        if(correctas === acertadas) {
            acertadasTot++;
        }
    });

    $(".unica").children(".correcta").each(function(){
        acertadasTot++;
    });

    $(".respuestaText").each(function(){
        if($(this).hasClass("resp-corecta")) {
            acertadasTot++;
        }
    });
    console.log("Aciertos: "+acertadasTot);
    var nota = (acertadasTot/preguntasTot)*10;
    nota = parseFloat(nota).toFixed(2);
    console.log("Nota:"+nota);
    $("#notasFin").val(nota);
    $("#mensajeModal").text("La nota final del examen es: "+nota);
})
