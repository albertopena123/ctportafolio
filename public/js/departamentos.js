var tabla;
var editItem = 0;

$( document ).ready(function() {

    tabla = $("#t_departamentos").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [],
        "ajax": {
            "url":  default_server+"/json/departamentos",
            "type": "POST",
            "data": function ( d ) {
                d.prt_facultad_id = $("#facultad_select").val(); 
            },
            "error": default_error_handler        
        },
        "columns": [            
            { "data": "prt_facultad.nombre",
                render: function ( data, type, full ) {                      
                    return data;
                }        
            },
            { "data": "nombre",
                render: function ( data, type, full ) {                      
                    return data;
                }        
            },
            { "data": "descripcion",
                render: function ( data, type, full ) {                      
                    return '<div title="'+data+'">'+textoMax(data,30)+'</div>';
                }        
            },
            { "data": "prt_prof_escuelas_count", "searchable": false, className: "w-1",
                render: function ( data, type, full ) {                      
                    return data;
                }        
            },  
            { "data": "prt_asignaturas_count", "searchable": false, className: "w-1",
                render: function ( data, type, full ) {       
                    return data;
                }        
            },                    
            { "data": "estado", "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {                      
                    if(data == 1)
                        return '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checkbox text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 11l3 3l8 -8"></path><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path></svg>';
                    else
                        return '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-x text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z"></path><path d="M9 9l6 6m0 -6l-6 6"></path></svg>';
                }        
            }, 
            { 
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                    render: function ( data, type, full ) {   
                    var res = '<div class="btn-list flex-nowrap">'+                                
                                '<button class="btn btn-white btn-icon" onclick="modificar('+full.id+');" title="MODIFICAR">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>'+
                                '</button>'+
                                '<button class="btn btn-danger btn-icon" onclick="eliminar('+full.id+');" title="ELIMINAR">'+
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                '</button>'+
                            '</div>';
                    return res;
                }
            }
        ],
        "dom": default_datatable_dom,
        "language": default_datatable_language,
        "initComplete" : default_datatable_buttons
    }); 

    $('#facultad_select').on('change', function() {
        tabla.ajax.reload();
    });
    
});

function nuevo() 
{
    editItem = 0;
    $("#titulo_editar").html("Nuevo registro"); 
    limpiar('#form_editar');
    vaciar('#form_editar');
    $("#prt_facultad_id").val(0);
    $('#estado').prop('checked', true);
    $("#editar").modal("show");
}

function modificar(iditem) 
{
    editItem = iditem;
    limpiar('#form_editar');
    vaciar('#form_editar');

    var data = tabla.rows().data();
    var objitem = elementId(iditem, data);

    if(objitem != null)
    {      
        $("#prt_facultad_id").val(objitem.prt_facultad_id);  
        $("#codigo").val(safeText(objitem.codigo));
        $("#abreviatura").val(safeText(objitem.abreviatura));
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
                url: default_server+"/json/departamentos/nuevo",
                data: {
                    prt_facultad_id: $("#prt_facultad_id").val(),
                    codigo: $("#codigo").val(),
                    abreviatura: $("#abreviatura").val(),
                    nombre: $("#nombre").val(),
                    descripcion: $("#descripcion").val(),                    
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
                },
                success: function(result){  
                    $("#cargando_pagina").hide(); 
                    alerta(result.message, true); 
                    tabla.ajax.reload();
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
                url: default_server+"/json/departamentos/"+editItem+"/modificar",
                data: {                   
                    prt_facultad_id: $("#prt_facultad_id").val(),
                    codigo: $("#codigo").val(),
                    abreviatura: $("#abreviatura").val(),
                    nombre: $("#nombre").val(),
                    descripcion: $("#descripcion").val(),                    
                    estado: ($("#estado").is(':checked') ? 1 : 0 )
                },
                success: function(result){  
                    $("#cargando_pagina").hide(); 
                    alerta(result.message, true); 
                    tabla.ajax.reload();
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
            url: default_server+"/json/departamentos/"+iditem+"/eliminar",        
            success: function(result){  
                $("#cargando_pagina").hide(); 
                alerta(result.message, true); 
                tabla.ajax.reload();
            },
            error: function(error) { 
                $("#cargando_pagina").hide();                
                alerta(response_helper(error), false);                       
            }
        });
    }
}

