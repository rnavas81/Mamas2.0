/* 
 * @author Rodrigo Navas
 * Funciones para gestionar la página de asignación de examenes
 */

function filtrarTabla(){
    var f_dni = $("#filter_dni").val().toString().toUpperCase().trim();
    var f_nombre = $("#filter_nombre").val().toString().toUpperCase().trim();
    var f_apellidos = $("#filter_apellidos").val().toString().toUpperCase().trim();
    var filas = $("[name='fila']");
    filas.removeClass("d-none");
    filas.each((index,fila)=>{
        var dni = $(fila).find("[name='dni']").text().toString().toUpperCase().trim();
        var nombre = $(fila).find("[name='nombre']").text().toString().toUpperCase().trim();
        var apellidos = $(fila).find("[name='apellidos']").text().toString().toUpperCase().trim();
        if( dni.search(f_dni)==-1 || nombre.search(f_nombre)==-1 || apellidos.search(f_apellidos)==-1 ){
            $(fila).addClass("d-none");
        }
        
    })
}
function enviarTodos(event){
    if(event.originalEvent.submitter.name=='volver'){
        return true;
    }
    var ids = [];
    $("[name='fila']:not(.d-none)").each((index,fila)=>{
       ids.push($(fila).find("[name='id']").val());
    });
    if(ids.length>0){
        $("[name='idsUsuario']").val(JSON.stringify(ids));
    }
    return ids.length>0;
}
window.onload = () => {
    $("#filter_dni").keyup(filtrarTabla);
    $("#filter_nombre").keyup(filtrarTabla);
    $("#filter_apellidos").keyup(filtrarTabla);
    $("#formToolbar").on("submit",enviarTodos);    
}


