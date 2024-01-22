var tabla;

$( document ).ready(function() {

    tabla = $("#t_portafolios").DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 50,
        "order": [],
        "ajax": {
            "url":  default_server+"/json/docente/portafolios",
            "type": "POST",
            "data": function ( d ) { 
                d.year = $("#year_select").val(); 
                d.semestre = $("#semestre_select").val();
            },
            "error": default_error_handler        
        },
        "columns": [            
            { "data": "prt_academ_carga.prt_prof_escuela.nombre", "orderable": false, "searchable": false,
                render: function ( data, type, full ) {                      
                    return data;
                }        
            },
            { "data": "prt_academ_carga.ciclo", "searchable": false, "orderable": false, className: "w-1",
                render: function ( data, type, full ) {
                    return format_romano(data);
                }        
            },
            { "data": "prt_academ_carga.prt_asignatura.nombre", "orderable": false, "searchable": false,
                render: function ( data, type, full ) {                      
                    return full.prt_academ_carga.prt_asignatura.codigo+' - '+full.prt_academ_carga.prt_asignatura.nombre;
                }        
            },                       
            { "data": "grupo", "searchable": false, className: "w-1",
                render: function ( data, type, full ) {       
                    return data;
                }        
            },       
            { "data": "created_at", "searchable": false,
                render: function ( data, type, full ) {       
                    return dis_fecha(data);
                }        
            },
            { "data": "avance", "searchable": false, className: "",
                render: function ( data, type, full ) {                      
                    if(data > 0)
                        return '<span class="badge bg-blue-lt">INICIADO</span>';
                    else
                        return '<span class="badge bg-yellow-lt">PENDIENTE</span>';
                }        
            }, 
            { 
                "data": null,
                "searchable": false, "orderable": false, className: "w-1",
                    render: function ( data, type, full ) {   
                    var res =                                
                            '<a href="'+default_server+'/admin/docente/portafolios/'+full.id+'" class="btn btn-primary btn-white" title="EDITAR">'+
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>'+
                                'Editar'+
                            '</a>';
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
    
});