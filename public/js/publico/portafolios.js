var lista;
var pagina = 1;


$( document ).ready(function() {    

    $('#year_select').on('change', function() {
        actualizar();
    });

    $('#semestre_select').on('change', function() {
        actualizar();
    });

    $('#escuelas_select').on('change', function() {
        actualizar();
    });
    
});

function actualizar() {
    pagina = 1;
    obtener_lista();
}

function buscar_texto() {
    actualizar();
}

function obtener_lista() {
    $("#cargando_pagina").show();  
    $.ajax({
        type: "POST",
        url: default_server+"/json/publico/portafolios",
        data: { 
            year: $('#year_select').val(),
            semestre: $('#semestre_select').val(),
            prt_prof_escuela_id: $('#escuelas_select').val(),
            texto: $('#texto_buscar').val(),
            page: pagina
        },
        success: function(result){  
            lista = result;
            render_lista();
            render_paginacion();
            $("#cargando_pagina").hide();             
        },
        error: function(error) {   
            $("#cargando_pagina").hide();              
            alerta(response_helper(error), false);      
        }
    });
}

function reset() {
    //$("#year_select").val($("#year_select option:first").val());
    //$("#semestre_select").val($("#semestre_select option:first").val());
    //$("#escuelas_select").val($("#escuelas_select option:first").val());
    $('#texto_buscar').val("");
    actualizar();
}


function render_lista() {
    $("#lista_portafolios").html("");
    if(lista.data.length > 0){
        var html_contenido = '';
        for (let i = 0; i < lista.data.length; i++) {
            html_contenido +=
            '<div class="card mb-3">'+
                '<div class="card-body">'+
                    '<div class="row">'+
                        '<div class="col-md-4">'+
                            '<dl class="mb-0">'+
                                '<dt>ESCUELA PROFESIONAL</dt>'+
                                '<dd>'+lista.data[i].prt_prof_escuela.nombre+'</dd>'+
                            '</dl>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<dl class="mb-0">'+
                                '<dt>CICLO</dt>'+
                                '<dd>'+format_romano(lista.data[i].ciclo)+'</dd>'+
                            '</dl>'+
                        '</div>'+
                        '<div class="col-md-4">'+
                            '<dl class="mb-0">'+
                                '<dt>ASIGNATURA</dt>'+
                                '<dd>'+lista.data[i].prt_asignatura.codigo+' - '+lista.data[i].prt_asignatura.nombre+'</dd>'+
                            '</dl>'+
                        '</div>'+                        
                        '<div class="col-md-2">'+
                            '<dl class="mb-0">'+
                                '<dt>ESTADO</dt>'+
                                '<div>'+(lista.data[i].prt_portafolios.length > 0 ? '<span class="badge bg-green-lt">ASIGNADO</span>':'<span class="badge bg-red-lt">SIN ASIGNAR</span>')+'</div>'+
                            '</dl>'+                            
                        '</div>'+
                    '</div>'+
                '</div>';

            if(lista.data[i].prt_portafolios.length) {
                html_contenido += '<div class="card-footer py-2">';
                for (let j = 0; j < lista.data[i].prt_portafolios.length; j++) {
                    html_contenido +=
                    '<div class="portafolio py-2">'+
                        '<div class="row">'+
                            '<div class="col-md-2">'+
                                '<dl class="mb-0">'+
                                    '<dt>GRUPO</dt>'+
                                    '<dd>'+lista.data[i].prt_portafolios[j].grupo+'</dd>'+
                                '</dl>'+
                            '</div>'+
                            '<div class="col-md-4">'+
                                '<dl class="mb-0">'+
                                    '<dt>DOCENTE</dt>'+
                                    '<dd>'+lista.data[i].prt_portafolios[j].usr_persona.nombre+' '+lista.data[i].prt_portafolios[j].usr_persona.apaterno+' '+lista.data[i].prt_portafolios[j].usr_persona.amaterno+'</dd>'+
                                '</dl>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<dl class="mb-0">'+
                                    '<dt>FECHA</dt>'+
                                    '<dd>'+dis_fecha(lista.data[i].prt_portafolios[j].created_at)+'</dd>'+
                                '</dl>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<dl class="mb-0">'+
                                    '<dt>AVANCE</dt>'+
                                    (lista.data[i].prt_portafolios[j].avance > 0 ? '<dd class="text-success">Iniciado</dd>':'<dd class="text-warning">Pendiente</dd>')+
                                '</dl>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<div class="d-flex align-items-center justify-content-end">'+                                    
                                    '<div class="ps-3">'+
                                        '<div class="btn-list">'+                                            
                                            '<a href="'+default_server+'/portafolios/'+lista.data[i].prt_portafolios[j].id+'" class="btn btn-info">'+
                                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>'+
                                                'Detalles'+
                                            '</a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div> ';
                }                 
                html_contenido += '</div>';
            }

            html_contenido += '</div>';
        }

        $("#lista_portafolios").html(html_contenido);
    } else {
        $("#lista_portafolios").html('<div class="alert alert-important" role="alert">No se encontraron registros.</div>');
    }
}


function render_paginacion() {
    $("#paginacion").html('');
    
    if(lista.data.length > 0) {      
        var html_paginado = '';

        html_paginado +=
        '<ul class="pagination">'+
            '<li class="page-item '+(lista.prev_page_url == null ? 'disabled':'' )+'">'+
                '<a class="page-link" href="javascript:void(0)" onclick="ir_pagina('+get_page(lista.prev_page_url)+')" '+(lista.prev_page_url == null ? 'tabindex="-1" aria-disabled="true"':'' )+'>'+                    
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 6l-6 6l6 6"></path></svg>'+
                '</a>'+
            '</li>';

        for (let i = 1; i <= lista.last_page; i++) {
            html_paginado += '<li class="page-item '+(lista.current_page == i ? 'active':'' )+'"><a class="page-link" href="javascript:void(0)" onclick="ir_pagina('+(lista.current_page == i ? null : i)+')">'+i+'</a></li>';
        }

        html_paginado +=            
            '<li class="page-item '+(lista.next_page_url == null ? 'disabled':'' )+'">'+
                '<a class="page-link" href="javascript:void(0)" onclick="ir_pagina('+get_page(lista.next_page_url)+')" '+(lista.next_page_url == null ? 'tabindex="-1" aria-disabled="true"':'' )+'>'+                    
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 6l6 6l-6 6"></path></svg>'+
                '</a>'+
            '</li>'+
        '</ul>';

        $("#paginacion").html(html_paginado);  
    }
}

function get_page(url) {
    var res = null;
    if(url != null) {    
        var pos = url.indexOf("page=");
        
        if(pos != -1) {
            res = url.substring(pos + 5);
        }
    }
    return res;
}

function ir_pagina(pag) {
    if(pag) {
        pagina = pag;
        obtener_lista();
    }    
}