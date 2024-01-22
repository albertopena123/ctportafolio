var lista;
var editItem = 0;
var pagina = 1;

$( document ).ready(function() {

    obtener_lista();

    $('#year_select').on('change', function() {
        actualizar();
    });

    $('#semestre_select').on('change', function() {
        actualizar();
    });

    $('#escuelas_select').on('change', function() {
        actualizar();
    });

    $('#estado_select').on('change', function() {
        actualizar();
    });

    $("#usr_persona_id").select2({
        dropdownParent: $('#editar'),
        width: '100%',
        theme: 'bootstrap4',
        minimumInputLength: 3,
        language: "es",
        ajax: {
            url: default_server+"/json/personas/buscar",
            dataType: 'json',
            type: "POST",
            quietMillis: 50,
            data: function (term) {
                return term;
            },
            processResults: function (data) {
                return {
                  results: $.map(data, function(obj) {
                    return { 
                        id: obj.id, 
                        text: obj.nombre+' '+obj.apaterno+' '+obj.amaterno
                    };
                  })
                };
            }
        }
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
        url: default_server+"/json/portafolios",
        data: { 
            year: $('#year_select').val(),
            semestre: $('#semestre_select').val(),
            estado: $('#estado_select').val(),
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
                        '<div class="col-md-4">'+
                            '<dl class="mb-0">'+
                                '<dt>ASIGNATURA</dt>'+
                                '<dd>'+lista.data[i].prt_asignatura.codigo+' - '+lista.data[i].prt_asignatura.nombre+'</dd>'+
                            '</dl>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<dl class="mb-0">'+
                                '<dt>CICLO</dt>'+
                                '<dd>'+format_romano(lista.data[i].ciclo)+'</dd>'+
                            '</dl>'+
                        '</div>'+
                        '<div class="col-md-2">'+
                            '<div class="d-flex align-items-center justify-content-end">'+
                                '<div>'+(lista.data[i].prt_portafolios.length > 0 ? '<span class="badge bg-green-lt">ASIGNADO</span>':'<span class="badge bg-red-lt">SIN ASIGNAR</span>')+'</div>'+
                                '<div class="ps-3">'+
                                    '<button class="btn btn-primary btn-icon" onclick="agregar('+lista.data[i].id+')">'+
                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>'+
                                    '</button>'+
                                '</div>'+
                            '</div>'+
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
                                    '<dd>'+(lista.data[i].prt_portafolios[j].avance > 0 ? '<a href="'+default_server+'/portafolios/'+lista.data[i].prt_portafolios[j].id+'" target="_blank" class="text-success">Iniciado</a>':'<span class="text-warning">Pendiente</span>')+'</dd>'+   
                                '</dl>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                                '<div class="d-flex align-items-center justify-content-end">'+
                                    '<div>'+(lista.data[i].prt_portafolios[j].estado == 1 ? '<span class="badge bg-green-lt">ACTIVO</span></div>':'<span class="badge bg-red-lt">INACTIVO</span></div>' )+
                                    '<div class="ps-3">'+
                                        '<div class="btn-list">'+
                                            '<button class="btn btn-icon" onclick="modificar('+lista.data[i].id+','+lista.data[i].prt_portafolios[j].id+')">'+
                                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>'+
                                            '</button>'+
                                            '<button class="btn btn-danger btn-icon" onclick="eliminar('+lista.data[i].prt_portafolios[j].id+');">'+
                                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                            '</button>'+
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

function agregar(iditem) {
    editItem = 0;    
    limpiar('#form_editar');
    vaciar('#form_editar');

    var objitem = elementId(iditem, lista.data);

    if(objitem != null)
    {     
        $("#prt_academ_carga_id").val(objitem.id);
        $("#prt_prof_escuela").val(objitem.prt_prof_escuela.nombre);
        $("#year").val(objitem.year);
        $("#semestre").val(format_romano(objitem.semestre));
        $("#ciclo").val(format_romano(objitem.ciclo));
        $("#prt_asignatura").val(objitem.prt_asignatura.codigo+' - '+objitem.prt_asignatura.nombre);
        $("#usr_persona_id").val(0);   
        $("#usr_persona_id").trigger('change'); 
        $('#estado').prop('checked', true);
        $("#titulo_editar").html("Agregar registro"); 
        $("#editar").modal("show");
    }
    else
        alert("No se encontro el item");  
}

function modificar(carga_id, portafolio_id) 
{
    editItem = portafolio_id;
    limpiar('#form_editar');
    vaciar('#form_editar');

    var carga_sel = elementId(carga_id, lista.data);

    if(carga_sel != null)
    {      
        var portafolio_sel = null;
        for (let i = 0; i < carga_sel.prt_portafolios.length; i++) {
            if(carga_sel.prt_portafolios[i].id == portafolio_id) {
                portafolio_sel = carga_sel.prt_portafolios[i];
                break;
            }            
        }

        if(portafolio_sel != null)
        {
            $("#prt_academ_carga_id").val(carga_sel.id);
            $("#prt_prof_escuela").val(carga_sel.prt_prof_escuela.nombre);
            $("#year").val(carga_sel.year);
            $("#semestre").val(format_romano(carga_sel.semestre));
            $("#ciclo").val(format_romano(carga_sel.ciclo));
            $("#prt_asignatura").val(carga_sel.prt_asignatura.codigo+' - '+carga_sel.prt_asignatura.nombre);
            $("#grupo").val(portafolio_sel.grupo);
            $("#usr_persona_id").select2("trigger", "select", {
                data: { id: portafolio_sel.usr_persona_id, text: portafolio_sel.usr_persona.nombre+' '+portafolio_sel.usr_persona.apaterno+' '+portafolio_sel.usr_persona.amaterno }
            });
            $("#observaciones").val(safeText(portafolio_sel.observaciones));            
            $('#estado').prop('checked', (portafolio_sel.estado == 1));
            $("#titulo_editar").html("Modificar registro"); 
            $("#editar").modal("show");
        }
        else
            alert("No se encontro el item");   
    }
    else
        alert("No se encontro el item");          
}

function guardar() 
{
    if(validar('#form_editar'))
    {
        $("#editar").modal("hide");
        $("#cargando_pagina").show();  

        if(editItem == 0)//nuevo
        {
            $.ajax({
                type: "POST",
                url: default_server+"/json/portafolios/nuevo",
                data: {
                    prt_academ_carga_id: $("#prt_academ_carga_id").val(),
                    grupo: $("#grupo").val(),
                    usr_persona_id: $("#usr_persona_id").val(),
                    observaciones: $("#observaciones").val(),                    
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
                },
                success: function(result){  
                    alerta(result.message, true); 
                    actualizar();           
                },
                error: function(error) {   
                    $("#cargando_pagina").hide();              
                    alerta(response_helper(error), false);      
                }
            });
        }
        else
        {
            $.ajax({
                type: "POST",
                url: default_server+"/json/portafolios/"+editItem+"/modificar",
                data: {                   
                    grupo: $("#grupo").val(),
                    usr_persona_id: $("#usr_persona_id").val(),
                    observaciones: $("#observaciones").val(),                    
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
                },
                success: function(result){  
                    alerta(result.message, true); 
                    actualizar();           
                },
                error: function(error) {  
                    $("#cargando_pagina").hide();               
                    alerta(response_helper(error), false);            
                }
            });
        }
    }    
}

function eliminar(iditem) {
    if(confirm('Esta seguro que desea eliminar?'))
    {
        $("#cargando_pagina").show();  

        $.ajax({
            type: "POST",
            url: default_server+"/json/portafolios/"+iditem+"/eliminar",        
            success: function(result){  
                alerta(result.message, true); 
                actualizar();           
            },
            error: function(error) { 
                $("#cargando_pagina").hide();                
                alerta(response_helper(error), false);                       
            }
        });
    }
}