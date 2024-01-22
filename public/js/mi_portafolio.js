var seccion_seleccionada = 0;
var carpeta_seleccionada = 0;

var archivos = [];
var carpetas = [];
var actual = null;

var carpeta_edit = 0;
var archivo_edit = 0;

$( document ).ready(function() {
    render_secciones();
});

function actualizar() {
    if(seccion_seleccionada != 0) {
        obtener_carpeta_archivos();
    }
}

function render_secciones() {
    $("#lista_secciones").html("");
    var html_seccion = obtener_html(secciones);
    $("#lista_secciones").html(html_seccion);
}

function obtener_html(items) {
    var html_seccion = '';

    for (let i = 0; i < items.length; i++) {
        html_seccion +=
        '<div class="seccion">'+                       
            '<a href="javascript:void(0);" onclick="seleccionar_seccion('+items[i].id+');" class="elemento '+(items[i].id == seccion_seleccionada ? 'activo':'')+'">'+
                '<div class="d-flex align-items-center">'+
                    '<div>'+
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-chevron-down" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 11l-3 3l-3 -3" /><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /></svg>'+
                    '</div>'+
                    '<div class="flex-fill ps-1">'+
                        '<h3 class="m-0 lh-1">'+
                            items[i].nombre+
                        '</h3>'+                                
                    '</div>'+                            
                '</div>'+
            '</a>'+ 
            '<div class="sub_seccion">'+
                obtener_html(items[i].sub_seccion)+
            '</div>'+
        '</div>';        
    }
    return html_seccion;
}

function seleccionar_seccion(seccion_id) {
    seccion_seleccionada = seccion_id;
    carpeta_seleccionada = 0;    
    render_secciones();
    obtener_carpeta_archivos();
}

function obtener_carpeta_archivos() {
    $("#cargando_pagina").show();  
    $.ajax({
        type: "POST",
        url: default_server+"/json/docente/portafolios/archivos",
        data: {
            prt_portafolio_id: elPortafolio,
            prt_seccion_id: seccion_seleccionada,
            prt_carpeta_id: carpeta_seleccionada
        },
        success: function(result){  
            carpetas = result.carpetas;
            archivos = result.archivos;
            actual = result.actual;
            render_actual();
            render_carpetas();
            render_archivos();
            $("#cargando_pagina").hide();
        },
        error: function(error) {   
            $("#cargando_pagina").hide();              
            alerta(response_helper(error), false);      
        }
    });
    
}

function retroceder() {
    if(actual != null) {
        if(actual.anterior_id != null) {
            carpeta_seleccionada = actual.anterior_id;
            obtener_carpeta_archivos();
        }
    }
}

function render_actual() {
    if(actual != null) {
        if(actual.tipo == 2) {//2:seccion
            $("#titulo_icono").html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-chevron-down-filled text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 2a3 3 0 0 1 3 3v14a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-14a3 3 0 0 1 3 -3zm-9.387 8.21a1 1 0 0 0 -1.32 1.497l3 3l.094 .083a1 1 0 0 0 1.32 -.083l3 -3l.083 -.094a1 1 0 0 0 -.083 -1.32l-.094 -.083a1 1 0 0 0 -1.32 .083l-2.293 2.292l-2.293 -2.292z" stroke-width="0" fill="currentColor" /></svg>');
        } else {//1:carpeta
            $("#titulo_icono").html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-folder-filled carpeta_color" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3a1 1 0 0 1 .608 .206l.1 .087l2.706 2.707h6.586a3 3 0 0 1 2.995 2.824l.005 .176v8a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-11a3 3 0 0 1 2.824 -2.995l.176 -.005h4z" stroke-width="0" fill="currentColor" /></svg>');
        }
        if(actual.anterior_id != null) {
            $("#accion_back").prop("disabled", false);
        } else {
            $("#accion_back").prop("disabled", true);
        }
        $("#titulo_texto").html(actual.nombre);
    }
}


/**
 * CARPETAS
 */

function render_carpetas() {
    $("#lista_carpetas").html('');
    if(carpetas.length > 0) {
        var html_carpetas = '';
        for (let i = 0; i < carpetas.length; i++) {
            html_carpetas += 
            '<div class="col-md-4">'+
                '<div class="card mb-2 gris_color">'+
                    '<div class="card-body p-3">'+
                        '<div class="d-flex">'+
                            '<div>'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-folder-filled carpeta_color" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3a1 1 0 0 1 .608 .206l.1 .087l2.706 2.707h6.586a3 3 0 0 1 2.995 2.824l.005 .176v8a3 3 0 0 1 -2.824 2.995l-.176 .005h-14a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-11a3 3 0 0 1 2.824 -2.995l.176 -.005h4z" stroke-width="0" fill="currentColor" /></svg>'+
                            '</div>'+
                            '<a href="javascript:void(0);" onclick="navegar_carpeta('+carpetas[i].id+');" class="d-block flex-fill text-truncate px-2 text-dark">'+
                                carpetas[i].nombre+
                            '</a> '+
                            '<div class="dropdown">'+
                                '<a class="" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-vertical" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>'+
                                '</a>'+
                                '<ul class="dropdown-menu dropdown-menu-end">'+
                                    '<li>'+
                                        '<a class="dropdown-item" href="javascript:void(0);" onclick="modificar_carpeta('+carpetas[i].id+');">'+
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path><path d="M16 5l3 3"></path></svg>'+
                                            'Modificar'+
                                        '</a>'+
                                    '</li>'+
                                    '<li>'+
                                        '<a class="dropdown-item text-danger" href="javascript:void(0);" onclick="eliminar_carpeta('+carpetas[i].id+');">'+
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-trash text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                            'Eliminar'+
                                        '</a>'+
                                    '</li>'+
                                '</ul>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>';            
        }
        $("#lista_carpetas").html(html_carpetas);
    } else {
        $("#lista_carpetas").html('<div class="card" style="background: #ebebeb;"><div class="card-body px-3 py-2 text-center text-muted">No se encontraron registros</div></div>');
    }     
}

function navegar_carpeta(carpeta_id) {
    carpeta_seleccionada = carpeta_id;   
    obtener_carpeta_archivos();
}

function nuevo_carpeta() {
    if(seccion_seleccionada != 0) {       
        $("#titulo_editar_carpeta").html("Nuevo registro"); 
        limpiar('#form_editar_carpeta');
        vaciar('#form_editar_carpeta');    
        $('#estado_carpeta').prop('checked', true);
        $("#editar_carpeta").modal("show");
    } else {
        alerta("Seleccione una sección", false);
    }
}

function modificar_carpeta(iditem) 
{
    carpeta_edit = iditem;
    limpiar('#form_editar');
    vaciar('#form_editar');

    var objitem = elementId(iditem, carpetas);

    if(objitem != null)
    {      
        $("#nombre_carpeta").val(objitem.nombre);        
        $('#estado_carpeta').prop('checked', (objitem.estado == 1));
        $("#titulo_editar_carpeta").html("Modificar registro"); 
        $("#editar_carpeta").modal("show");
    }
    else
        alert("No se encontro el item");          
}

function guardar_carpeta() 
{
    if(validar('#form_editar_carpeta'))
    {
        $("#editar_carpeta").modal("hide");
        $("#cargando_pagina").show();  

        if(carpeta_edit == 0)//nuevo
        {
            $.ajax({
                type: "POST",
                url: default_server+"/json/docente/carpetas/nuevo",
                data: {
                    prt_portafolio_id: elPortafolio,
                    prt_seccion_id: seccion_seleccionada,
                    prt_carpeta_id: carpeta_seleccionada,
                    nombre: $("#nombre_carpeta").val(),                  
                    estado: ($("#estado_carpeta").is(':checked') ? 1 : 0 )                   
                },
                success: function(result){  
                    $("#cargando_pagina").hide(); 
                    alerta(result.message, true); 
                    obtener_carpeta_archivos();
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
                url: default_server+"/json/docente/carpetas/"+carpeta_edit+"/modificar",
                data: {
                    prt_portafolio_id: elPortafolio,
                    nombre: $("#nombre_carpeta").val(),                   
                    estado: ($("#estado_carpeta").is(':checked') ? 1 : 0 )
                },
                success: function(result){  
                    $("#cargando_pagina").hide(); 
                    alerta(result.message, true);
                    obtener_carpeta_archivos();
                },
                error: function(error) {  
                    $("#cargando_pagina").hide();               
                    alerta(response_helper(error), false);            
                }
            });
        }
    }    
}

function eliminar_carpeta(iditem) {
    if(confirm('Esta seguro que desea eliminar?'))
    {
        $("#cargando_pagina").show();  

        $.ajax({
            type: "POST",
            url: default_server+"/json/docente/carpetas/"+iditem+"/eliminar",    
            data: {
                prt_portafolio_id: elPortafolio
            },    
            success: function(result){  
                $("#cargando_pagina").hide(); 
                alerta(result.message, true);
                obtener_carpeta_archivos();
            },
            error: function(error) { 
                $("#cargando_pagina").hide();                
                alerta(response_helper(error), false);                       
            }
        });
    }
}

/**
 * ARCHIVOS
 */
function render_archivos() {
    $("#lista_archivos").html('');
    if(archivos.length > 0) {
        var html_archivos = '';
        for (let i = 0; i < archivos.length; i++) {
            html_archivos += 
            '<tr>'+
                '<td>'+
                    '<div class="d-flex align-items-center">'+
                        '<span class="avatar bg-blue-lt me-2">'+
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon icon-tabler icon-tabler-file" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>'+
                        '</span>'+
                        '<div class="flex-fill">'+
                            '<div class="font-weight-medium lh-1">'+
                                archivos[i].nombre+
                            '</div>'+
                            '<div class="text-muted">'+
                                archivos[i].formato.toUpperCase()+'  &#183; '+archivos[i].format_size+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</td>'+
                '<td class="text-muted">'+
                    dis_fecha(archivos[i].created_at)+
                    '<small class="d-block">'+dis_solo_hora(archivos[i].created_at)+' h</small>'+
                '</td>'+
                '<td>'+
                    '<div class="dropdown dropstart">'+
                        '<a class="btn btn-white btn-icon" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">'+
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-vertical" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>'+
                        '</a>                                                  '+
                        '<ul class="dropdown-menu dropdown-menu-end">'+
                            '<li>'+
                                '<a class="dropdown-item" href="'+default_server+'/archivos/'+archivos[i].codigo+'/descargar" target="_blank">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>'+
                                    'Descargar'+
                                '</a>'+
                            '</li>'+
                            '<li>'+
                                '<a class="dropdown-item" href="javascript:void(0);" onclick="mover_archivo('+archivos[i].id+');">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-arrows-move" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 9l3 3l-3 3" /><path d="M15 12h6" /><path d="M6 9l-3 3l3 3" /><path d="M3 12h6" /><path d="M9 18l3 3l3 -3" /><path d="M12 15v6" /><path d="M15 6l-3 -3l-3 3" /><path d="M12 3v6" /></svg>'+
                                    'Mover'+
                                '</a>'+
                            '</li>'+
                            '<li>'+
                                '<a class="dropdown-item text-danger" href="javascript:void(0);" onclick="eliminar_archivo('+archivos[i].id+');">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-trash text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                    'Eliminar'+
                                '</a>'+
                            '</li>'+
                        '</ul>'+
                    '</div>'+
                '</td>'+
            '</tr> ';
        }
        $("#lista_archivos").html(html_archivos);
    } else {
        $("#lista_archivos").html('<tr><td colspan="3" class="text-center text-muted">No se encontraron registros</td></tr> ');
    }     
}

function nuevo_archivo() {
    if(seccion_seleccionada != 0) {
        document.getElementById("input_subir").click();
    } else {
        alerta("Seleccione una sección", false);
    }
}

function seleccion_archivos(elemento) {
    var t_files = $("#input_subir")[0].files;   

    if (t_files && t_files.length) {

        var res = true;             
        for (let i = 0; i < t_files.length; i++) {
            if(t_files[i].size > size_maximo)   
                res = false;              
        }

        if(res == false)  {
            alerta("El tamaño de alguno de los archivos seleccionados es mayor a "+format_getSize(size_maximo) ,false);
            return;
        }

        $("#cargando_pagina").show();

        var formData = new FormData();
        formData.append('prt_portafolio_id', elPortafolio);
        formData.append('prt_seccion_id', seccion_seleccionada); 
        formData.append('prt_carpeta_id', carpeta_seleccionada);
        for (let i = 0; i < t_files.length; i++) {
            formData.append("archivos[]", t_files[i]);            
        }

        $.ajax({
            type: "POST",
            url: default_server+"/json/docente/archivos/nuevo",
            processData: false,
            contentType: false,
            data: formData,
            success: function(result){
                $("#cargando_pagina").hide(); 
                alerta(result.message, true); 
                obtener_carpeta_archivos();
            },
            error: function(error) {      
                $("#cargando_pagina").hide();               
                alerta(response_helper(error), false);  
            }
        });        
       
    } 
}

function eliminar_archivo(iditem) {
    if(confirm('Esta seguro que desea eliminar?'))
    {
        $("#cargando_pagina").show();  

        $.ajax({
            type: "POST",
            url: default_server+"/json/docente/archivos/"+iditem+"/eliminar",    
            data: {
                prt_portafolio_id: elPortafolio
            },    
            success: function(result){  
                $("#cargando_pagina").hide(); 
                alerta(result.message, true);
                obtener_carpeta_archivos();
            },
            error: function(error) { 
                $("#cargando_pagina").hide();                
                alerta(response_helper(error), false);                       
            }
        });
    }
}
  
function mover_archivo(iditem) {
    if(seccion_seleccionada != 0) {    
        archivo_edit = iditem;
        $("#mover").modal("show");
        $("#cargando_mover").show();

        $.ajax({
            type: "POST",
            url: default_server+"/json/docente/secciones/"+seccion_seleccionada+"/carpetas",
            data: {
                prt_portafolio_id: elPortafolio
            },
            success: function(result){    
                var html_mover =
                '<li class="item_mover" style="list-style: square;"><a href="javascript:void(0)" class="accion_mover" onclick="guardar_mover(0)">'+result.seccion.nombre+'</a>'+
                    '<ul id="lista_mover" class="lista_mover">'+
                        obtener_html_mover(result.carpetas)+
                    '</ul>'+
                '</li>';
                $("#lista_mover").html(html_mover);                
                $("#cargando_mover").hide();             
            },
            error: function(error) {   
                $("#cargando_mover").hide();              
                alerta(response_helper(error), false);      
            }
        });

    } else {
        alerta("Seleccione una sección", false);
    }
}

function obtener_html_mover(items) {
    var html_seccion = '';
    for (let i = 0; i < items.length; i++) {
        html_seccion +=
        '<li class="item_mover">'+
            '<a href="javascript:void(0)" onclick="guardar_mover('+items[i].id+')" class="accion_mover" >'+
               items[i].nombre+                            
            '</a>'+
            '<ul class="lista_mover">'+
                obtener_html_mover(items[i].sub_carpeta)+
            '</ul>'+
        '</li>';        
    }
    return html_seccion;
}


function guardar_mover(id_destino) {
    $("#mover").modal("hide");
    $("#cargando_pagina").show();  

    $.ajax({
        type: "POST",
        url: default_server+"/json/docente/archivos/"+archivo_edit+"/mover",
        data: {                   
            prt_seccion_id: seccion_seleccionada,
            prt_carpeta_id: id_destino
        },
        success: function(result){
            alerta(result.message, true);
            obtener_carpeta_archivos();
        },
        error: function(error) {  
            $("#cargando_pagina").hide();               
            alerta(response_helper(error), false);            
        }
    });
}

