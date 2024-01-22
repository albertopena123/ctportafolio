var editItem = 0;

$( document ).ready(function() {
    obtener_secciones();
});

function obtener_secciones() {
    $("#cargando_pagina").show();  
    $.ajax({
        type: "POST",
        url: default_server+"/json/secciones",
        data: { },
        success: function(result){  
            lista_secciones = result;
            render_secciones();
            $("#cargando_pagina").hide();             
        },
        error: function(error) {   
            $("#cargando_pagina").hide();              
            alerta(response_helper(error), false);      
        }
    });
}

function render_secciones() {
    $("#lista_secciones").html("");
    var html_seccion = obtener_html(lista_secciones);
    $("#lista_secciones").html(html_seccion);
}

function obtener_html(items) {
    var html_seccion = '';

    for (let i = 0; i < items.length; i++) {
        html_seccion +=
        '<div class="seccion">'+
            '<div class="pt-3">'+
                '<div class="card">'+
                    '<div class="card-body p-2">'+
                        '<div class="d-flex align-items-center">'+
                            '<div class="flex-fill ps-1">'+
                                '<h3 class="m-0 lh-1">'+items[i].numero+' : '+items[i].nombre+'</h3>'+
                                '<p class="m-0 text-muted d-block lh-1">'+safeText(items[i].descripcion)+'</p>'+
                            '</div>'+
                            '<div>'+
                                '<div class="dropdown">'+
                                    '<button class="btn btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">'+
                                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-vertical" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>'+
                                    '</button>'+
                                    '<div class="dropdown-menu dropdown-menu-end">'+
                                        '<button class="dropdown-item" type="button" onclick="nuevo('+items[i].id+',\''+items[i].numero+' : '+items[i].nombre+'\');">'+
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>'+
                                            'Agregar'+
                                        '</button>'+
                                        '<button class="dropdown-item" type="button" onclick="modificar('+items[i].id+');">'+
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path><path d="M16 5l3 3"></path></svg>'+
                                            'Modificar'+
                                        '</button>'+
                                        '<button class="dropdown-item" type="button" onclick="mover('+items[i].id+');">'+
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-arrows-move" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 9l3 3l-3 3" /><path d="M15 12h6" /><path d="M6 9l-3 3l3 3" /><path d="M3 12h6" /><path d="M9 18l3 3l3 -3" /><path d="M12 15v6" /><path d="M15 6l-3 -3l-3 3" /><path d="M12 3v6" /></svg>'+
                                            'Mover'+
                                        '</button>'+
                                        '<button class="dropdown-item text-danger" type="button" onclick="eliminar('+items[i].id+');">'+
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon icon-tabler icon-tabler-trash text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                            'Eliminar'+
                                        '</button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="card-footer p-2">'+
                        '<div class="d-flex">'+
                            '<div class="flex-fill ps-1">'+
                                '<span class="text-muted">Carpetas: </span>'+items[i].prt_carpetas_count+'&nbsp;&nbsp;/&nbsp;&nbsp;<span class="text-muted">Archivos:</span> '+items[i].prt_archivos_count+
                            '</div>'+
                            '<div>'+
                                (items[i].estado == 1 ? '<span class="badge bg-green-lt">ACTIVO</span>' : '<span class="badge bg-red-lt">INACTIVO</span>')+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="sub_seccion">'+
                obtener_html(items[i].sub_seccion)+
            '</div>'+
        '</div>';        
    }
    return html_seccion;
}

function nuevo(posicion, texto) 
{
    editItem = 0;
    $("#titulo_editar").html("Nuevo registro"); 
    limpiar('#form_editar');
    vaciar('#form_editar');
    $("#prt_seccion_id").val(posicion);
    $("#prt_seccion_padre").val(texto);
    $('#estado').prop('checked', true);
    $("#editar").modal("show");
}

function buscar_item(idb, lista) { 
    var res = null;
    if(lista !== undefined) {
        for (let i = 0; i < lista.length; i++) {
            if(lista[i].id==idb) {
                res = lista[i];
                break;
            } else {
                var sub = buscar_item(idb, lista[i].sub_seccion);
                if(sub != null) {
                    res = sub;
                    break;
                }
            }
        }
    }
    return res;
}

function modificar(iditem) 
{
    editItem = iditem;
    limpiar('#form_editar');
    vaciar('#form_editar');

    var objitem = buscar_item(iditem, lista_secciones);    

    if(objitem != null)
    {      
        $("#prt_seccion_id").val(objitem.prt_seccion_id);  
        $("#prt_seccion_padre").val("-");
        $("#numero").val(objitem.numero);
        $("#nombre").val(objitem.nombre);
        $("#descripcion").val(safeText(objitem.descripcion));        
        $('#estado').prop('checked', (objitem.estado == 1));
        $("#titulo_editar").html("Modificar registro"); 
        $("#editar").modal("show");
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
                url: default_server+"/json/secciones/nuevo",
                data: {
                    prt_seccion_id: $("#prt_seccion_id").val(),
                    numero: $("#numero").val(),
                    nombre: $("#nombre").val(),
                    descripcion: $("#descripcion").val(),                    
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
                },
                success: function(result){                      
                    alerta(result.message, true); 
                    obtener_secciones();                    
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
                url: default_server+"/json/secciones/"+editItem+"/modificar",
                data: {                   
                    numero: $("#numero").val(),
                    nombre: $("#nombre").val(),
                    descripcion: $("#descripcion").val(),                    
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
                },
                success: function(result){                      
                    alerta(result.message, true); 
                    obtener_secciones();
                },
                error: function(error) {  
                    $("#cargando_pagina").hide();               
                    alerta(response_helper(error), false);            
                }
            });
        }
    }    
}

function obtener_mover_html(items, iditem, inhabilitar) {
    var html_seccion = '';

    for (let i = 0; i < items.length; i++) {
        if(inhabilitar == false) {
            var temp_inhabilitar = (items[i].id == iditem ? true : false);           
        } else {
            var temp_inhabilitar = true;
        }        

        html_seccion +=
        '<li class="item_mover">'+
            '<a '+(temp_inhabilitar ? '' : 'href="javascript:void(0)"')+' '+(temp_inhabilitar ? '' : 'onclick="guardar_mover('+items[i].id+')"')+' class="accion_mover '+(temp_inhabilitar ? 'inactivo_mover':'')+'" >'+
               items[i].numero+' '+items[i].nombre+                            
            '</a>'+
            '<ul class="lista_mover">'+
                obtener_mover_html(items[i].sub_seccion, iditem, temp_inhabilitar)+
            '</ul>'+
        '</li>';        
    }
    return html_seccion;
}

function mover(iditem) {

    editItem = iditem;
    var objitem = buscar_item(iditem, lista_secciones);    

    if(objitem != null)
    {    
        $("#lista_mover").html("");
        var html_seccion = obtener_mover_html(lista_secciones, iditem, false);
        $("#lista_mover").html(html_seccion);
        $("#mover").modal("show");
    }
    else
        alert("No se encontro el item");       
}

function guardar_mover(id_destino) {
    $("#mover").modal("hide");
    $("#cargando_pagina").show();  

    $.ajax({
        type: "POST",
        url: default_server+"/json/secciones/"+editItem+"/mover",
        data: {                   
            prt_seccion_id: (id_destino != 0 ? id_destino : null)
        },
        success: function(result){                      
            alerta(result.message, true); 
            obtener_secciones();
        },
        error: function(error) {  
            $("#cargando_pagina").hide();               
            alerta(response_helper(error), false);            
        }
    });
}

function eliminar(iditem) {
    if(confirm('Esta seguro que desea eliminar?'))
    {
        $("#cargando_pagina").show();  

        $.ajax({
            type: "POST",
            url: default_server+"/json/secciones/"+iditem+"/eliminar",        
            success: function(result){  
                alerta(result.message, true); 
                obtener_secciones();
            },
            error: function(error) { 
                $("#cargando_pagina").hide();                
                alerta(response_helper(error), false);                       
            }
        });
    }
}

