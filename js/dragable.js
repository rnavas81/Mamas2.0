

$( function () {    

    $('.draggable').css('cursor', 'pointer');
    $('.draggable').disableSelection();
    $('.sortable').css('min-height', '50px');
    
    $(".sortable").sortable({
        revert: true,        
        stop: function(event, ui) {
            
            var longitud = $(this).find("li").length;

            console.log("Longitud: "+longitud);
            
            console.log("Nombre dragable: "+ui.item.attr("name"));                                                                                            
            
            var longitud = $(this).find("li").length;                        
            
            if (longitud>$(this).data("max") && ui.item.hasClass("draggable")) {                
                ui.item.remove();
            }

            ui.item.removeClass("draggable");
        }
    });
    
    $(".draggable").draggable({
        connectToSortable: ".sortable",
        helper: "clone",
        revert: "invalid"       
    });

});