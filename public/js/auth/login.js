$('#password').keypress(function(e) { 
    var s = String.fromCharCode( e.which );
    if (s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey ) 
        $("#may_act").removeClass("oculto");
    else
        $("#may_act").addClass("oculto");    
});

function login(e) {    
    if(!validar("#form_login")){
        e.preventDefault();
        return false;
    }
    else {
        $("#cargando_pagina").show();    
        return true;
    }    
}