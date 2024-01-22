var tabla;
var editItem = 0;

$( document ).ready(function() {

    tabla = $("#t_carga").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [],
        "ajax": {
            "url":  default_server+"/json/cargas",
            "type": "POST",
            "data": function ( d ) { 
                d.year = $("#year_select").val(); 
                d.semestre = $("#semestre_select").val(); 
                d.prt_facultad_id = $("#facultad_select").val(); 
                d.prt_prof_escuela_id = $("#escuelas_select").val(); 
            },
            "error": default_error_handler        
        },
        "columns": [            
            { "data": "prt_prof_escuela.nombre", "orderable": false,
                render: function ( data, type, full ) {                      
                    return data;
                }        
            },
            { "data": "ciclo", "searchable": false, className: "w-1",
                render: function ( data, type, full ) {
                    return format_romano(data);
                }        
            },
            { "data": "prt_asignatura.codigo", "orderable": false, "searchable": true, "visible": false},
            { "data": "prt_asignatura.nombre", "orderable": false,
                render: function ( data, type, full ) {                      
                    return '<div title="'+data+'">'+full.prt_asignatura.codigo+' - '+textoMax(data,30)+'</div>';
                }        
            },           
            { "data": "prt_portafolios_count", "searchable": false, className: "w-1",
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

    $('#year_select').on('change', function() {
        tabla.ajax.reload();
    });

    $('#semestre_select').on('change', function() {
        tabla.ajax.reload();
    });
    
    $('#facultad_select').on('change', function() {        
        var item = $('#facultad_select').val();
        $('#escuelas_select').html('<option value="0">TODOS</option>');
        if(item != 0) {
            for (let i = 0; i < escuelas.length; i++) {
                if(escuelas[i].prt_facultad_id == item)
                $('#escuelas_select').append('<option value="'+escuelas[i].id+'">'+escuelas[i].nombre+'</option>');
            }
        }  
        
        $("#escuelas_select").trigger('change');                    
    });

    $('#escuelas_select').on('change', function() {
        tabla.ajax.reload();
    });

    //------------------------

    $('#prt_facultad_id').on('change', function() {        
        var item = $('#prt_facultad_id').val();
        $('#prt_dept_academico_id').html('<option value="0">Seleccione...</option>');
        if(item != 0) {
            for (let i = 0; i < departamentos.length; i++) {
                if(departamentos[i].prt_facultad_id == item)
                $('#prt_dept_academico_id').append('<option value="'+departamentos[i].id+'">'+departamentos[i].nombre+'</option>');
            }
        }  
        $("#prt_dept_academico_id").trigger('change');           
    });

    $('#prt_dept_academico_id').on('change', function() {        
        var item = $('#prt_dept_academico_id').val();
        $('#prt_prof_escuela_id').html('<option value="0">Seleccione...</option>');
        if(item != 0) {
            for (let i = 0; i < escuelas.length; i++) {
                if(escuelas[i].prt_dept_academico_id == item)
                $('#prt_prof_escuela_id').append('<option value="'+escuelas[i].id+'">'+escuelas[i].nombre+'</option>');
            }
        }  
        $("#prt_prof_escuela_id").trigger('change');           
    });
    
    $("#prt_asignatura_id").select2({
        dropdownParent: $('#editar'),
        width: '100%',
        theme: 'bootstrap4',
        minimumInputLength: 3,
        language: "es",
        ajax: {
            url: default_server+"/json/asignaturas/buscar",
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
                            text: obj.codigo+' - '+obj.nombre
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
    $("#prt_facultad_id").val(0);   
    $("#prt_facultad_id").trigger('change'); 
    $("#year").val(0);   
    $("#semestre").val(0);   
    $("#ciclo").val(-1);  
    $("#prt_asignatura_id").val(0);   
    $("#prt_asignatura_id").trigger('change'); 
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
        $("#prt_facultad_id").trigger('change'); 
        $("#prt_dept_academico_id").val(objitem.prt_dept_academico_id);
        $("#prt_dept_academico_id").trigger('change'); 
        $("#prt_prof_escuela_id").val(objitem.prt_prof_escuela_id);
        $("#year").val(objitem.year);
        $("#semestre").val(objitem.semestre);
        $("#ciclo").val(objitem.ciclo);        
        $("#prt_asignatura_id").select2("trigger", "select", {
            data: { id: objitem.prt_asignatura_id, text: objitem.prt_asignatura.codigo+' - '+objitem.prt_asignatura.nombre }
        });
        $("#observaciones").val(safeText(objitem.observaciones));        
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
                url: default_server+"/json/cargas/nuevo",
                data: {
                    year: $("#year").val(),
                    semestre: $("#semestre").val(),
                    prt_prof_escuela_id: $("#prt_prof_escuela_id").val(),
                    prt_dept_academico_id: $("#prt_dept_academico_id").val(),
                    prt_facultad_id: $("#prt_facultad_id").val(),
                    ciclo: $("#ciclo").val(),
                    prt_asignatura_id: $("#prt_asignatura_id").val(),
                    observaciones: $("#observaciones").val(),                    
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
                url: default_server+"/json/cargas/"+editItem+"/modificar",
                data: {                   
                    year: $("#year").val(),
                    semestre: $("#semestre").val(),
                    prt_prof_escuela_id: $("#prt_prof_escuela_id").val(),
                    prt_dept_academico_id: $("#prt_dept_academico_id").val(),
                    prt_facultad_id: $("#prt_facultad_id").val(),
                    ciclo: $("#ciclo").val(),
                    prt_asignatura_id: $("#prt_asignatura_id").val(),
                    observaciones: $("#observaciones").val(),                    
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
            url: default_server+"/json/cargas/"+iditem+"/eliminar",        
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

