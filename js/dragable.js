$(function () {    

    $('.draggable').css('cursor', 'pointer');
    $('.draggable').disableSelection();
    $('.sortable').css('min-height', '50px');
    
    $(".sortable").sortable({
        revert: true,        
        stop: function(event, ui) {
            
            var longitud = $(this).find("li").length;                                                                                                       
            
            var longitud = $(this).find("li").length;                        
            
            if (longitud>$(this).data("max") && ui.item.hasClass("draggable")) {                
                ui.item.remove();
            }

            
            
            
            if(ui.item.hasClass("draggable")==true) {
                ui.item.prepend("<div><a class='deleteBtn px-2 borderline'><i class='fas fa-times'></i></a></div>")
                
                console.log($(this).hasClass("correct"));
                $('.deleteBtn').on('click', function(){
                    console.log('Adios, respuesta '+$(this).parent().parent().attr('name')+' :(');
                    $(this).parent().parent().remove();
                });                
                ui.item.attr("data-idPr",$(this).parent().parent().attr('id'));
                ui.item.removeClass("draggable");
                ui.item.addClass("respuesta");
            }                        
        }
    });
    
    $(".draggable").draggable({
        connectToSortable: ".sortable",
        helper: "clone",
        revert: "invalid"       
    });
});

