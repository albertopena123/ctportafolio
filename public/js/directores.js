var tabla;
var editItem = 0;

$( document ).ready(function() {

    tabla = $("#t_directores").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [],
        "ajax": {
            "url":  default_server+"/json/directores",
            "type": "POST",
            "data": function ( d ) { },
            "error": default_error_handler        
        },
        "columns": [    
            { "data": "prt_dept_academico.prt_facultad.nombre" },
            { "data": "prt_dept_academico.nombre" },
            { "data": "usr_persona.nombre",
                render: function ( data, type, full ) {                      
                    return full.usr_persona.nombre+' '+full.usr_persona.apaterno+' '+full.usr_persona.amaterno;
                }        
            },
            { "data": "usr_persona.apaterno", "orderable": false, "searchable": true, "visible": false},
            { "data": "usr_persona.amaterno", "orderable": false, "searchable": true, "visible": false}, 
            { "data": "estado", "searchable": false, className: "w-1 text-center",
                render: function ( data, type, full ) {     
                    var html_data = '';                    
                    if(data == 1){
                        html_data +=
                        '<button class="btn btn-success btn-icon" onclick="modificar('+full.id+',0);" title="ESTADO">'+
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checkbox" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 11l3 3l8 -8"></path><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path></svg>'+
                        '</button>';
                    } else {
                        html_data +=
                        '<button class="btn btn-warning btn-icon" onclick="modificar('+full.id+',1);" title="ESTADO">'+
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z"></path><path d="M9 9l6 6m0 -6l-6 6"></path></svg>'+
                        '</button>';
                    }
                    return html_data;
                }        
            }, 
            { 
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                    render: function ( data, type, full ) {   
                    var res = '<div class="btn-list flex-nowrap">'+                               
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

function nuevo() 
{
    editItem = 0;
    $("#titulo_editar").html("Nuevo registro"); 
    limpiar('#form_editar');
    vaciar('#form_editar');
    $("#prt_dept_academico_id").val(0);   
    $("#usr_persona_id").val(0);   
    $("#usr_persona_id").trigger('change'); 
    $('#estado').prop('checked', true);
    $("#editar").modal("show");
}

function modificar(iditem, estadoitem) 
{
    $("#cargando_pagina").show();

    $.ajax({
        type: "POST",
        url: default_server+"/json/directores/"+iditem+"/estado",
        data: {                       
            estado: estadoitem
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


function guardar() 
{
    if(validar('#form_editar'))
    {
        $("#editar").modal("hide");
        $("#cargando_pagina").show();
        
        $.ajax({
            type: "POST",
            url: default_server+"/json/directores/nuevo",
            data: {
                prt_dept_academico_id: $("#prt_dept_academico_id").val(),
                usr_persona_id: $("#usr_persona_id").val(),                               
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


function eliminar(iditem) {
    if(confirm('Esta seguro que desea eliminar?'))
    {
        $("#cargando_pagina").show();  

        $.ajax({
            type: "POST",
            url: default_server+"/json/directores/"+iditem+"/eliminar",        
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

