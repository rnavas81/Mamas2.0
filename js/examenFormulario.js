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

function crearPregunta(id){
   var nuevo = $.parseHTML(
    `<li class="list-group-item border p-2 mb-3" name="pregunta" id="${id}">
        <div class="d-flex mb-2">
            <p name="titulo" class="h5 col text-left">Pregunta ${id}</p>
            <p class="h6 align-self-center mr-2">Tipo</p>
            <select name="tipo">
                <option value="1">A desarrollar</option>
                <option value="2">Respuesta única</option>
                <option value="3">Respuesta multiple</option>
            </select>
            <button type="button" class="btn btn-sm m-0 ml-2 btn-danger" name="eliminarPregunta" title="Eliminar pregunta">
                <i class="fas fa-times"></i>
            </button>  
        </div>
        <div class="form-group">
            <input class="form-control" name="enunciado" type="text" placeholder="Enunciado"/>
        </div>
        <div class="form-row opciones d-none">
            <div class="form-group d-flex col-12 col-sm-6">
                <input class="form-control" id="opcion_1" type="text" placeholder="Opción 1"/>
                <button type="button" class="opcion btn btn-sm m-0 ml-1 btn-success" opcion="1">
                    <i class="fas fa-check"></i>
                </button>                                                    
            </div>
            <div class="form-group d-flex col-12 col-sm-6">
                <input class="form-control" id="opcion_2" type="text" placeholder="Opción 2"/>
                <button type="button" class="opcion btn btn-sm m-0 ml-1 btn-danger" opcion="2">
                    <i class="fas fa-times"></i>
                </button>                                                    
            </div>
            <div class="form-group d-flex col-12 col-sm-6">
                <input class="form-control" id="opcion_3" type="text" placeholder="Opción 3"/>
                <button type="button" class="opcion btn btn-sm m-0 ml-1 btn-danger" opcion="3">
                    <i class="fas fa-times"></i>
                </button>                                                    
            </div>
            <div class="form-group d-flex col-12 col-sm-6">
                <input class="form-control" id="opcion_4" type="text" placeholder="Opción 4"/>
                <button type="button" class="opcion btn btn-sm m-0 ml-1 btn-danger" opcion="4">
                    <i class="fas fa-times"></i>
                </button>                                                    
            </div>
        </div>
    </li>`);
    
    $(nuevo).find("[name='tipo']").on('change',cambiarTipoRespuestas);
    $(nuevo).find(".opcion").on('click',accionRespuesta);
    $(nuevo).find("[name='eliminarPregunta']").on('click',eliminarPregunta);
    return nuevo;
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
    $("*").removeClass('border-danger');
    response.data.nombre = $("[name='nombre']").val().trim();
    //El nombre no puede estar vacío
    if(response.data.nombre.length==0){
        response.state=1;
        const error_nombre_vacio="No puede dejar el nombre de examen vacío";
        if(response.errores.indexOf(error_nombre_vacio)==-1){
            response.errores.push(error_nombre_vacio);
        }
        $("[name='nombre']").addClass('border-danger');
    }
    response.data.descripcion = $("[name='descripcion']").val().trim();
    response.data.fechaInicio = $("[name='fechaInicio']").val();
    response.data.fechaFin = $("[name='fechaFin']").val();
    response.data.activo = $("[name='activo']")[0].checked==true?1:0;
    response.data.preguntas=[];
    var preguntas = $("[name='pregunta']");
    if(preguntas.length==0){
        response.state=1;
        response.errores.push('Debe haber por lo menos una pregunta');
    } else {
        for (var i = 0; i < preguntas.length; i++) {
            var pregunta = preguntas[i];
            var enunciado = $(pregunta).find("[name='enunciado']");
            //Comprueba si el enunciado esta vacío
            if(enunciado[0].value.trim().length==0){
                response.state=1;
                const error_enunciado_vacio="No puede dejar un enunciado vacío";
                $(enunciado).addClass('border-danger');
                if(response.errores.indexOf(error_enunciado_vacio)==-1){
                    response.errores.push(error_enunciado_vacio);
                }
            }
            var tipo = $(pregunta).find("[name='tipo']")[0].value;
            var datosPregunta = {
                enunciado:enunciado[0].value.trim(),
                tipo:$(pregunta).find("[name='tipo']")[0].value,
                opciones:[]
            };
            //Comprueba las opciones
            if(tipo==2 || tipo==3){
                var opciones = $(pregunta).find(".opciones div");
                var hayCorrecta=false;
                for (var i = 0; i < opciones.length; i++) {
                    var opcion = opciones[i];
                    var texto = opcion.children[0].value.trim();
                    //Comprueba si la opción esta vacía
                    if(texto.length==0){
                        response.state=1;
                        const error_opcion_vacia="No puede dejar una opción vacía";
                        $(opcion.children[1]).addClass('border-danger');
                        if(response.errores.indexOf(error_opcion_vacia)==-1){
                            response.errores.push(error_opcion_vacia);
                        }
                    }
                    if(opcion.children[1].classList.contains('btn-success')){
                        hayCorrecta=true;
                    }
                    datosPregunta.opciones.push({
                        texto:texto,
                        correcta:opcion.children[1].classList.contains('btn-success')
                    });
                }
                //Debe haber por lo menos una respuesta correcta
                if(!hayCorrecta){
                    response.state=1;
                    const error_opcion_correcta="Todas las opciones no pueden ser incorrectas";
                    $(pregunta).addClass('border-danger');
                    if(response.errores.indexOf(error_opcion_correcta)===-1){
                        response.errores.push(error_opcion_correcta);
                    }
                } else {
                    
                }
            }
            response.data.preguntas.push(datosPregunta);
        }
    }
    
    return response;
}

window.onload = () => {
    document.nextId = $("[name='pregunta']").length+1;
    //Recoge el cambio de un tipo de pregunta para cambiar las posibles respuestas
    $("[name='tipo']").change(cambiarTipoRespuestas);
    //Cambia la opción valida de una respuesta unica
    $(".opcion").click(accionRespuesta);
    $("[name=eliminarPregunta]").click(eliminarPregunta);
    //Agrega una pregunta
    $("#agregar").click(function(){
        const id = document.nextId;
        var nuevo = crearPregunta(id);
        $("#lista-preguntas").append(nuevo);
        $(nuevo).find("[name='enunciado']")[0].focus();
        document.nextId+=1;
    });

    $("#formExamen").on("submit",function(event){
        try {
            var validacion = validarExamen();
            if(validacion.state==0){
                $('#datos').val(JSON.stringify(validacion.data));
                $('input').attr('disabled',true);
                $('textarea').attr('disabled',true);
                $('#datos').removeAttr('disabled');
                $('#id').removeAttr('disabled');
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
    })
}