/* 
 * @author Rodrigo Navas
 * Funciones varias 
 */
/**
 * Función para mostrar un popup temporal
 * @param {String} mensaje Texto a mostrar
 * @param {Object} data  Opciones para mensaje
 *  parent {String}header => padre en el agregar el elemento
 *  time {Number}5000 => tiempo que el mensaje permanece mostrado en milisegundos
 *  noheader {Boolean}True => ocultar la cabecera
 *  headerText {String}(vacío) => texto de la cabecera
 *  type {String}success => tipo de mensaje a mostrar ["success","warning","danger"]
 * 
 */
function showPopUp(mensaje="",data=undefined){
    try {
        if(data===undefined){
            data={};
        }
        if(data.parent == undefined)data.parent="header";
        if(data.time == undefined)data.time=5000;
        if(data.type == undefined)data.type="success";
        var header = "";
        if(data.noheader !== true){
            if(data.headerText === undefined)data.headerText='';
            let color = '#28a745';
            switch (data.type){
                case 'success': color = '#28a745'; break;
                case 'warning': color = '#ffc107'; break;
                case 'danger': color = '#dc3545'; break;
                default: color='#fff'; break;
            }
            header = `<div class="toast-header">
                    <svg class="rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice"
                      focusable="false" role="img">
                      <rect fill="${color}" width="100%" height="100%" /></svg>
                    <strong class="mr-auto">${data.headerText}</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>`;
        }
        var nuevo = $.parseHTML(`
            <div id="mensaje-popup" aria-live="polite" aria-atomic="true" style="">
                <div class="toast" style="position: absolute; top: 0; right: 0;" data-autohide="false">
                    ${header}
                    <div class="toast-body">
                        ${mensaje}
                    </div>
                </div>
            </div>`);
        $(data.parent).append(nuevo);
        console.log(data.time);
        $('.toast').toast('show');
        setTimeout(function(){
            $('.toast').toast('hide');
            setTimeout(function(){
                $(nuevo).remove();            
            },1000);
        },data.time);    
    } catch (e) {
        console.log(e);
        
    }

}


