/* 
 * @author Rodrigo Navas
 * 
 * Clase pregunta
 * El examen contiene preguntas
 */
/**
 * 
 * @type type
 * @argument {Number} index Indice de posición de la pregunta
 * @argument {Number 1|0} almacenar Variable para guardar la pregunta en el almacen
 * @argument {Number} id Identificador de la pregunta. 0 para preguntas nuevas
 * @argument {String} enunciado Enunciado que propone la pregunta
 * @argument {Number 1|2|3} tipo Tipo de respuesta para la pregunta. 
 *  1=>Respuesta a desarrollar. 
 *  2=>Respuesta con opciones. Solo una correcta.
 *  3=>Respuesta con opciones. Varias correctas
 * @argument {Array de Opcion} opciones Opciones para las preguntas de tipo 2 y 3
 */
class Pregunta {
    constructor(index=0,almacenar=0,id=0,enunciado="",tipo=2,opciones=null){
        this._id=id;
        this._enunciado=enunciado;
        this._tipo=tipo;
        if(Array.isArray(opciones)){
            this._opciones=opciones;            
        } else {
            this._opciones=[];
            this._opciones.push(new Opcion("",true,1));
            this._opciones.push(new Opcion("",false,2));
            this._opciones.push(new Opcion("",false,3));
            this._opciones.push(new Opcion("",false,4));
        }
        this._html = this.createElementHtml(index,almacenar);
    }
    get id(){
        return this._id;
    }
    set id(id){
        this._id=id;
        $(this._html).data("id",id);
    }
    get enunciado(){
        return this._enunciado;
    }
    set enunciado(enunciado){
        this._enunciado=enunciado;
        $(this._html).find("[name='enunciado']").text(enunciado);
    }
    get tipo(){
        return this._tipo;
    }
    set tipo(tipo){
        this._tipo=tipo;
        if(this._tipo==2 || this._tipo==3){
            $(this._html).find(".opciones").removeClass('d-none');
        } else {
            $(this._html).find(".opciones").addClass('d-none');
        }
    }
    get html(){
        return this._html;
    }
    set html(html){
        this._html=html;
    }
    get opciones(){
        return this._opciones;
    }
    set opciones(opciones){
        this._opciones=opciones;
        $(this._elem)
    }
    getOpcion(index){
        return this._opciones[index];
    }
    setOpcion(index,opcion){
        this._opciones[index]=opcion;
    }
    addOpcion(opcion){
        this._opciones.push(opcion);
    }    
    createElementHtml(index,almacenar){
        var mostrar = this._tipo==1?"d-none":"";
        var nuevo = $.parseHTML(
            `<li class="list-group-item border p-2 mb-3" name="pregunta" data-id="${this._id}" data-almacenar="${almacenar}">
                <div class="d-flex mb-2">
                    <p name="titulo" class="h5 col text-left">Pregunta ${index}</p>
                    <p class="h6 align-self-center mr-2">Tipo</p>
                    <select class="form-control w-auto" name="tipo">
                        <option value="1">A desarrollar</option>
                        <option value="2">Respuesta única</option>
                        <option value="3">Respuesta multiple</option>
                    </select>
                    <button type="button" class="btn btn-opcion m-0 ml-2 primary-dark-color white-text" name="eliminarPregunta" title="Eliminar pregunta">
                        <i class="fas fa-trash-alt"></i>
                    </button>  
                </div>
                <div class="form-group">
                    <input class="form-control" name="enunciado" type="text" placeholder="Enunciado" value="${this._enunciado}"/>
                </div>
                <div class="form-row opciones ${mostrar}">
                </div>
            </li>`);
        $(nuevo).find("select[name='tipo']").val(this._tipo);
        for (var i = 0; i < this._opciones.length; i++) {
            var opcion = this.getOpcion(i);
            $(nuevo).find(".opciones").append(opcion.html);
        }
        
        var parent = this;
        $(nuevo).find("[name='tipo']").on('change',function(){
            parent.tipo=this.value;
            parent.cambiarTipoRespuestas();
        });
        $(nuevo).find(".opcion").on('click',function(){
            parent.accionRespuesta(this);
        });
        $(nuevo).find("[name='eliminarPregunta']").on('click',function(){
            parent.eliminarPregunta();
        });
        $(nuevo).find("[name='enunciado']")[0].focus();
        return nuevo[0];
    }
    
    cambiarTipoRespuestas(){
        if(this._tipo==2 || this._tipo==3){
            $(this._html).find(".opciones").removeClass('d-none');
        } else {
            $(this._html).find(".opciones").addClass('d-none');
        }
    }
    accionRespuesta(activador){
        var index = $(activador).attr("opcion");
        if(this._tipo==1){
            this.cambiarTipoRespuestas();
        } else if(this._tipo==2){
            for (var i = 0; i < this._opciones.length; i++) {
                this._opciones[i].correcta=i==index-1;
            }     
        } else if (this._tipo==3){
            this._opciones[index-1].correcta = !this._opciones[index-1].correcta;
        }
    }
    eliminarPregunta() {
        var buscando=true,i=0,pregunta;
        for (i = 0; buscando && i < document.preguntas.length; i++) {
            var pregunta = document.preguntas[i];
            if(pregunta.html==this._html){
                buscando=false;
            }
        }
        i--;
        document.preguntas.splice(i,1);
        for(i;i<document.preguntas.length;i++){
            pregunta = document.preguntas[i];
            pregunta.setIndex(i+1);
        }
        $(this._html).remove();
    }
    setIndex(index){
        $(this._html).find("[name='titulo']").text(`Pregunta ${index}`);
    }
    toJson(){
        var json = {
            id:0,
            enunciado:this._enunciado,
            tipo:this._tipo,
            opciones:[]
        }
        for (var opcion in this._opciones) {
            json.opciones.push(opcion.toJson());
        }
        return json;
    }

}
/**
 * @author Rodrigo Navas
 * @type Clase Opciones 
 * Las preguntas del examen contienen opciones
 */
class Opcion {
    constructor(texto="",correcta=false,index=0){
        this._texto=texto;
        this._correcta=correcta;
        this._html=this.createElementHtml(index);
    }
    get texto(){
        return this._texto;
    }
    set texto(texto){
        this._texto=texto;
    }
    get correcta(){
        return this._correcta;
    }
    set correcta(correcta){
        this._correcta=correcta;
        this.setCorrectaHtml();
    }    
    get html(){
        return this._html;
    }   
    createElementHtml(index=0){
        var btn_class=this._correcta?"btn-success":"btn-danger";
        var i_class = this._correcta?"fa-check":"fa-times";
        var nuevo = $.parseHTML(`
            <div class="form-group d-flex col-12 col-sm-6">
                <input class="form-control" name="opcion_${index}" type="text" placeholder="Opción ${index}" value="${this._texto}"/>
                <button type="button" class="opcion btn btn-opcion m-0 ${btn_class}" opcion="${index}">
                    <i class="fas ${i_class}"></i>
                </button>                                                    
            </div>`);
        return nuevo;
    }
    setCorrectaHtml(){
        if(this._correcta){
            $(this._html).find("button").removeClass("btn-danger").addClass("btn-success");
            $(this._html).find("i").removeClass("fa-times").addClass("fa-check");
        } else {
            $(this._html).find("button").removeClass("btn-success").addClass("btn-danger");
            $(this._html).find("i").removeClass("fa-check").addClass("fa-times");
        }        
    }
    toJson(){
        return {
            texto:this._texto,
            correcta:this._correcta
        }
    }
}

