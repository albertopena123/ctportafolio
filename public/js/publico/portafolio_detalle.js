var seccion_seleccionada = 0;
var carpeta_seleccionada = 0;
var elementos = [];
var rutas = [];

$( document ).ready(function() {
    obtener_elementos();
});

function obtener_elementos() {
    $("#cargando_pagina").show();  
    $.ajax({
        type: "POST",
        url: default_server+"/json/publico/navegar",
        data: { 
            prt_portafolio_id: elPortafolio,
            prt_seccion_id: seccion_seleccionada,
            prt_carpeta_id: carpeta_seleccionada
        },
        success: function(result){  
            elementos = result.elementos;
            rutas = result.rutas;
            render_elementos();
            render_rutas();
            $("#cargando_pagina").hide();             
        },
        error: function(error) {   
            $("#cargando_pagina").hide();              
            alerta(response_helper(error), false);      
        }
    });
}

function render_elementos() {   
    $("#lista_elementos").html('');
    if(elementos.length > 0) {
        var html_elemento = '';
        for (let i = 0; i < elementos.length; i++) {            
            if(elementos[i].tipo == 2) {
                html_elemento += 
                '<div class="list-group-item pt-3 ps-3 pe-3 pb-2">'+
                    '<div class="row">'+
                        '<div class="col-md-8 mb-2">'+
                            '<div class="d-flex align-items-center">'+
                                '<span class="avatar bg-blue-lt me-2">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 3v4a1 1 0 0 0 1 1h4"></path><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path><line x1="9" y1="9" x2="10" y2="9"></line><line x1="9" y1="13" x2="15" y2="13"></line><line x1="9" y1="17" x2="15" y2="17"></line></svg>'+
                                '</span>'+
                                '<div class="flex-fill">'+
                                    '<div class="font-weight-medium lh-1">'+
                                        '<span>'+elementos[i].nombre+'</span>'+
                                        '<div class="text-muted">'+elementos[i].formato.toUpperCase()+' &#183; '+elementos[i].format_size+'</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-4 mb-2">'+
                            '<div class="d-flex align-items-center justify-content-between justify-content-md-end">'+
                                '<div class="text-muted pe-3 text-md-end">'+
                                    dis_fecha(elementos[i].created_at)+'<small class="d-block">'+dis_solo_hora(elementos[i].created_at)+' h</small>'+
                                '</div>'+
                                '<div>'+
                                    '<a href="'+default_server+'/archivos/'+elementos[i].codigo+'/descargar" target="_blank" class="btn btn-primary btn-icon">'+
                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>'+
                                    '</a>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>';
            } else {
                html_elemento += 
                '<div class="list-group-item pt-3 ps-3 pe-3 pb-2">  '+
                    '<div class="row">'+
                        '<div class="col-md-8 mb-2">'+
                            '<div class="d-flex align-items-center">'+
                                '<span class="avatar '+(elementos[i].tipo == 0 ? 'bg-blue-lt':'bg-yellow-lt')+' me-2">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled avatar-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>'+
                                '</span>'+
                                '<div class="flex-fill">'+
                                    '<div class="font-weight-medium lh-1">'+
                                        '<a href="javascript:void(0);" onclick="navegar_ir('+elementos[i].prt_seccion_id+','+elementos[i].prt_carpeta_id+')"; title="'+elementos[i].nombre+'">'+elementos[i].nombre+'</a>'+
                                        '<div class="text-muted">'+elementos[i].archivos_count+' archivos &#183; '+elementos[i].carpetas_count+' carpetas</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>     '+
                        '</div>'+
                        '<div class="col-md-4 mb-2">'+
                            '<div class="text-muted text-md-end">'+
                                dis_fecha(elementos[i].created_at)+'<small class="d-md-block ms-2">'+dis_solo_hora(elementos[i].created_at)+' h</small>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>';
            }             
        }

        $("#lista_elementos").html(html_elemento);

    } else {
        $("#lista_elementos").html('<div class="p-3"><div class="card" style="background: #ebebeb;"><div class="card-body px-3 py-2 text-center text-muted">No se encontraron registros</div></div></div>');        
    }
}

function render_rutas() {
    $("#lista_rutas").html('<li class="breadcrumb-item"><a href="javascript:void(0);" onclick="navegar_ir(0,0);">INICIO</a></li>');
    if(rutas.length > 0) {
        var html_ruta = '';
        for (let i = 0; i < rutas.length; i++) {  
            html_ruta = '<li class="breadcrumb-item '+(i == 0 ? 'active':'')+'" '+(i == 0 ? 'aria-current="page"':'')+'><a href="javascript:void(0);" onclick="navegar_ir('+rutas[i].prt_seccion_id+', '+rutas[i].prt_carpeta_id+');">'+rutas[i].nombre+'</a></li>'+html_ruta;
        }
        $("#lista_rutas").append(html_ruta);
    }
}

function navegar_ir(prt_seccion_id, prt_carpeta_id) {
    seccion_seleccionada = prt_seccion_id;
    carpeta_seleccionada = prt_carpeta_id;
    obtener_elementos();
}