$(function (){
    var respuesta ={};    
    var multiResp =[];
    var pregAct = 0;
    $('#terminarExamen').on('click', function(){
        $('.sortable').each(function(){
            multiResp = [];
            pregAct = $(this).parent().parent().attr('id');
            $(this).children().each(function(){
                console.log($(this).text().trim());
                multiResp.push($(this).text().trim());
            });
            respuesta[pregAct] = multiResp;
        });        
        $('.respuestaText').each( function(){
            respuesta[$(this).parent().attr('id')]=$(this).val();
        })          
        var respuestas_json = JSON.stringify(respuesta);
        console.log('Final: \n'+respuestas_json); 
        
        $('#respuestasFin').val(respuestas_json);
     });
});

