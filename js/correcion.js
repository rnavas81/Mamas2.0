$('.btnAcierto').on('click', function(){    
    var respuesta = $(this).siblings(".respuestaText");
    respuesta.addClass('resp-corecta');
    $(this).removeClass('btn-dark-green');
    $(this).addClass('btn-grey');
    $(this).siblings('.btnFallo').removeClass('btn-grey');
    $(this).siblings('.btnFallo').addClass('btn-danger');
    
});

$('.btnFallo').on('click', function(){    
    var respuesta = $(this).siblings(".respuestaText");
    respuesta.removeClass('resp-corecta');
    $(this).removeClass('.btn-danger');
    $(this).addClass('btn-grey');
    $(this).siblings('.btnAcierto').removeClass('btn-grey');
    $(this).siblings('.btnAcierto').addClass('btn-dark-green');
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
