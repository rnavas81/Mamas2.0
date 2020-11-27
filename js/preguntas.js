/* 
 * @author Rodrigo Navas
 * Contiene las funciones necesarias para crear/modificar el formulario de preguntas de un examen
 * 
 */

function cambiarTipoRespuestas(){
    var padre = $(this).parent().parent();
    const tipo = $(this).val();
    if(tipo==2 || tipo==3){
        $(padre).find(".opciones").removeClass('d-none');
    } else {
        $(padre).find(".opciones").addClass('d-none');
    }
}
/**
 * Elimina una pregunta y reordena el resto de preguntas
 * @returns {undefined}
 */
function eliminarPregunta() {
    var pregunta = $(this).parent().parent();
    $(pregunta).remove();
    var preguntas = $("li[name='pregunta']");
    for (var i = 0; i < preguntas.length; i++) {
        let id = i+1;
        $(preguntas[i]).attr("id",id);
        $(preguntas[i]).find("[name='titulo']").text(`Pregunta ${id}`);
    }
    document.nextId-=1;
}

function accionRespuesta(){
    var padre = $(this).parent().parent();
    var pregunta = $(padre).parent();
    const tipo = $(pregunta).find("[name='tipo']")[0].value;
    if(tipo==1){
        cambiarTipoRespuestas();
    } else if(tipo==2){
        var botones = padre.find(".opcion");
        for (var i = 0; i < botones.length; i++) {
            var $boton = $(botones[i]);
            var hijo=$(botones[i]).find(".fas");
            if(botones[i]==this){
                $boton.removeClass('btn-danger');
                $boton.addClass('btn-success');
                $(hijo).removeClass("fa-times").addClass('fa-check');
            } else {
                $boton.removeClass('btn-success');
                $boton.addClass('btn-danger');
                $(hijo).removeClass("fa-check").addClass('fa-times');
            }
            $boton.append(hijo);
        }        
    } else if (tipo==3){
        var i=$(this).find(".fas");
        if($(this).hasClass("btn-danger")){
            $(this).removeClass("btn-danger").addClass("btn-success");
            $(i).removeClass("fa-times").addClass('fa-check');
        } else {
            $(this).removeClass("btn-success").addClass("btn-danger");
            var i=$(this).find(".fas");
            $(i).removeClass("fa-check").addClass('fa-times');
        }
    }
}

/**
 * Recupera los datos del examen
 * Valida los datos
 * @returns Object 
 *  state devuelve el estado de validación
 *  data devuelve los datos del examen
 *  errores devuelve los mensajes de error
 */
function validarExamen(){
    var response={
        state:0,
        data:{},
        errores:[]
    }
    //** Mensajes de error
    const error_enunciado_vacio="No puede dejar un enunciado vacío";
    const error_opcion_vacia="No puede dejar una opción vacía";
    const error_opcion_correcta="Todas las opciones no pueden ser incorrectas";
    
    $("*").removeClass('border-danger');
    var enunciado = $("[name='enunciado']");
    //Comprueba si el enunciado esta vacío
    if(enunciado[0].value.trim().length==0){
        response.state=1;
        $(enunciado).addClass('border-danger');
        if(response.errores.indexOf(error_enunciado_vacio)==-1){
            response.errores.push(error_enunciado_vacio);
        }
    }
    var tipo = $("[name='tipo']")[0].value;
    response.data = {
        enunciado:enunciado[0].value.trim(),
        tipo:tipo,
        opciones:[]
    };
    //Comprueba las opciones
    if(tipo==2 || tipo==3){
        var opciones = $(".opciones div");
        var hayCorrecta=false;
        for (var j = 0; j < opciones.length; j++) {
            var opcion = opciones[j];
            var texto = opcion.children[0].value.trim();
            //Comprueba si la opción esta vacía
            if(texto.length==0){
                response.state=1;
                console.log(opcion.children[0])
                $(opcion.children[0]).addClass('border-danger');
                if(response.errores.indexOf(error_opcion_vacia)==-1){
                    response.errores.push(error_opcion_vacia);
                }
            }
            if(opcion.children[1].classList.contains('btn-success')){
                hayCorrecta=true;
            }
            response.data.opciones.push({
                texto:texto,
                correcta:opcion.children[1].classList.contains('btn-success')
            });
        }
        //Debe haber por lo menos una respuesta correcta
        if(!hayCorrecta){
            response.state=1;
            $(pregunta).addClass('border-danger');
            if(response.errores.indexOf(error_opcion_correcta)===-1){
                response.errores.push(error_opcion_correcta);
            }
        } else {

        }
    }
    return response;
}
window.onload = () => {
    //Recoge el cambio de un tipo de pregunta para cambiar las posibles respuestas
    $("[name='tipo']").change(cambiarTipoRespuestas);
    //Cambia la opción valida de una respuesta unica
    $(".opcion").click(accionRespuesta);
    $("#formPregunta").on("submit",function(event){
        if(event.originalEvent.submitter.name=='volver'){
            return true;
        }
        try {
            var validacion = validarExamen();
            if(validacion.state==0){
                $('#datos').val(JSON.stringify(validacion.data));
                return true;
            } else {
                var txtErrores=validacion.errores.join("<br>");
                showPopUp(txtErrores,{
                    type:'danger',
                    headerText:'Errores',
                    parent:$("header")[0]
                });
            }  
        } catch (e) {
            console.log(e);
            return false;
        }
        return false;
    });
}