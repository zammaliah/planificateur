//$(document).ready(function() {
$(function() {

    let array_event_delete =  {};
    let array_moto_add =  {};
    let array_adresse_add =  {};
    let array_livreur_add =  {};
    

    /* initialize the external events
    -----------------------------------------------------------------*/

    $('.external-events .fc-event').each(function() {
        var cet_div = $(this); 
        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            id: cet_div.attr('id'),
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true, // will cause the event to go back to its
            appendTo: 'body' ,
            containment: 'window',
            scroll: false,
            helper: 'clone',    
            revertDuration: 0,  //  original position after the drag
            start  : function(event, ui){
                $(ui.helper).css("width", "150px");
            }
        });

    });


    /* initialize the calendar
    -----------------------------------------------------------------*/

    $('#calendar-planification').fullCalendar({
        height:790,
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar
        dragRevertDuration: 0,
        eventOrder : "start",
        events: {
            url: planifications,
            type: 'POST',
            error: function() {
              alert('there was an error while fetching events!');
            }
        },
        
        drop: function() {
            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }
        },
        eventDragStop: function( event, jsEvent, ui, view ) {
            
            if(isEventOverDiv(jsEvent.clientX, jsEvent.clientY, event._id)) {
                $('#calendar-planification').fullCalendar('removeEvents', event._id);
                if (event._id.indexOf("moto") > -1){
                    var el = $( "<div class='fc-event' id='"+event._id+"'>" ).appendTo( '#moto-scroll' ).text( event.title );
                }else if(event._id.indexOf("adresse") > -1){
                    var el = $( "<div class='fc-event' id='"+event._id+"'>" ).appendTo( '#adresse-scroll' ).text( event.title );
                }else if(event._id.indexOf("livreur") > -1){
                    var el = $( "<div class='fc-event' id='"+event._id+"'>" ).appendTo( '#livreur-scroll' ).text( event.title );
                }
                
                el.draggable({
                  zIndex: 9999,
                  revert: true, 
                  revertDuration: 0 
                });
                el.data('event', { title: event.title, id :event.id, stick: true });
                
                //event deleted
                let date = new Date(event.start._d);
                let date_format = date.getFullYear()+"-"+(date.getUTCMonth()+1)+"-"+date.getUTCDate();
                if(event._id.indexOf("pl") > -1){
                array_event_delete[event._id] = {'id' : event._id, 'titre' : event.title, 'date' : date_format};
                }

            }
        },
        eventReceive: function(event) {
            //event add
            let date = new Date(event.start._d);
            let date_format = date.getFullYear()+"-"+(date.getUTCMonth()+1)+"-"+date.getUTCDate();
            if (event._id.indexOf("moto") > -1){
                array_moto_add[event._id] = {'id' : event._id, 'titre' : event.title, 'date' : date_format};
            }else if(event._id.indexOf("livreur") > -1){
                array_livreur_add[event._id] = {'id' : event._id, 'titre' : event.title, 'date' : date_format};
            }else if(event._id.indexOf("adresse") > -1){
                array_adresse_add[event._id] = {'id' : event._id, 'titre' : event.title, 'date' : date_format};
            }
            
        },



    });

    var isEventOverDiv = function(x, y, event_id) {
        
        var external_events = $( '#list-event' );
        
        var offset = external_events.offset();
        offset.right = external_events.width() + offset.left;
        offset.bottom = external_events.height() + offset.top;

        // Compare
        if (x >= offset.left
            && y >= offset.top
            && x <= offset.right
            && y <= offset .bottom) { return true; }
        return false;

    }
    $( "#btn-valid-calendar" ).click(function() {
        $.ajax({
            url: validate,
            method: "POST",
            data: { delete : array_event_delete, moto_add : array_moto_add, adresse_add : array_adresse_add, livreur_add : array_livreur_add},
            success : function(data){
                if(data[0] === "success"){
                    location.reload();
                }else{
                    alert('Problème quelque part !!!');
                };
            }
            
        });
    });
});