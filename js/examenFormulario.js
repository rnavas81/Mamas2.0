/* 
 * @author Rodrigo Navas
 * Contiene las funciones necesarias para crear/modificar el formulario de preguntas de un examen
 * 
 */

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
                id:$(pregunta).data("id"),
                almacenar:$(pregunta).data("almacenar"),
                enunciado:enunciado[0].value.trim(),
                tipo:$(pregunta).find("[name='tipo']")[0].value,
                opciones:[]
            };
            //Comprueba las opciones
            if(tipo==2 || tipo==3){
                var opciones = $(pregunta).find(".opciones div");
                var hayCorrecta=false;
                for (var j = 0; j < opciones.length; j++) {
                    var opcion = opciones[j];
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
/**
 * Agrega preguntas de la ventana modal
 * @returns {undefined}
 */
function agregarDelModal(){
    var marcados = $("input[name='pregunta-marcada']:checked");
    for (var i = 0; i < marcados.length; i++) {
        var $item = $(marcados[i]);
        //var datos = JSON.parse($item.data('datos').replaceAll("'",'"'));
        var datos = JSON.parse($("#data-"+$item.attr('id')).text());
        const index = document.nextId;
        var opciones = [];
        for (var j = 0; j < datos.opciones.length; j++) {
            var opcion = datos.opciones[j];
            opciones.push(new Opcion(opcion.texto,opcion.correcta,j+1));
        }
        var p = new Pregunta(index,0,0,datos.enunciado,datos.tipo,opciones);
        $("#lista-preguntas").append(p.html);
        document.nextId+=1;
        document.preguntas.push(p);
    }    
}
/**
 * Selecciona todas las preguntas de la ventana modal
 * @returns {undefined}
 */
function seleccionarTodas() {
    $("[name='pregunta-marcada']").attr('checked',false);
    
    var preguntas = $(".pregunta-blk");
    for (var i = 0; i < preguntas.length; i++) {
        var $pregunta = $(preguntas[i]);
        if($pregunta.hasClass("d-flex")){
            $pregunta.find("input").attr('checked',true);
        }
    }
}
/**
 * Muestra las preguntas que contengan el texto buscado
 * @returns {undefined}
 */
function filtrarPreguntas(){
    var buscado = $(this).val();
    var preguntas = $(".pregunta-blk");
    var enunciado,datos;
    for (var i = 0; i < preguntas.length; i++) {
        var $pregunta = $(preguntas[i]);
        enunciado = $pregunta.find("p").text();
        if(enunciado.search(buscado)==-1){//No encontrado
            $pregunta.addClass("d-none").removeClass("d-flex");
        } else { //Encontrado
            $pregunta.removeClass("d-none").addClass("d-flex");
        }
    }
}
window.onload = () => {
    document.nextId = $("[name='pregunta']").length+1;
    document.preguntas = [];
    for (var index in data) {
        var datos=data[index];
        var opciones = [];
        for (var j = 0; j < datos.opciones.length; j++) {
            var opcion = datos.opciones[j];
            opciones.push(new Opcion(opcion.texto,opcion.correcta,j+1));
        }
        var p = new Pregunta(document.nextId,0,datos.id,datos.enunciado,datos.tipo,opciones);
        $("#lista-preguntas").append(p.html);
        document.nextId+=1;
        document.preguntas.push(p);       
    }
    //Agrega una pregunta
    $("#agregar").click(function(){
        const index = document.nextId;
        var p = new Pregunta(index,1);
        $("#lista-preguntas").append(p.html);
        document.nextId+=1;
        document.preguntas.push(p);
    });

    $("#formExamen").on("submit",function(event){
        if(event.originalEvent.submitter.name=='volver'){
            return true;
        }
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
    $("#agregarPreguntas").click(agregarDelModal);
    $("#marcarTodas").change(seleccionarTodas);
    $("#filtroPreguntas").keyup(filtrarPreguntas);
}