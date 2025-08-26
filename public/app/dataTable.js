/**
 * DATATABLE PENSADO PARA LARAVEL
 * @param {id} id_html 
 * @param {string} url 
 * @param {object} data 
 */
function dataTable(id_html, url, data = {}, columnDefs = [], sumColumn = null, activeSum = false) {
    let tokenLaravel = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    $('#' + id_html).DataTable({
        "searchDelay": 500,
        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        deferRender: true,
        buttons: [
            'excelHtml5',
        ],

        "ajax": {
            url: url,
            type: "POST",
            data: data,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': tokenLaravel // Agrega el token CSRF de Laravel
            },
            cache: false,
            error: function(e) {
                console.log(e)
            },
        },
        drawCallback: function() {
            //Validacion para sumar columnas
            if (activeSum) {
                var api = this.api();
                let sumTotal = api.column(sumColumn).data().sum()
                let sumCurrentPage = api.column(sumColumn, { page: 'current' }).data().sum()
                $(api.column().footer()).html(`Total:  ${sumCurrentPage} de ${sumTotal}`)
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 20, //Por cada 10 registros hace una paginación
        "order": [
            [0, "desc"]
        ], //Ordenar (columna,orden)
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 al 0 de de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"

            }
        }, //cerrando language
        "columnDefs": columnDefs,
        //"scrollX": true
    });
}

//DataTable para catalogo de cuentas customizado
function dataTableCatalogo(id_html, url, data = {}, columnDefs = [], sumColumn = null, activeSum = false) {
    let tokenLaravel = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    $('#' + id_html).DataTable({
        "searchDelay": 500,
        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        deferRender: true,
        buttons: [
            'excelHtml5',
        ],

        "ajax": {
            url: url,
            type: "POST",
            data: data,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': tokenLaravel // Agrega el token CSRF de Laravel
            },
            cache: false,
            error: function(e) {
                console.log(e)
            },
        },
        drawCallback: function() {
            //Validacion para sumar columnas
            if (activeSum) {
                var api = this.api();
                let sumTotal = api.column(sumColumn).data().sum()
                let sumCurrentPage = api.column(sumColumn, { page: 'current' }).data().sum()
                $(api.column().footer()).html(`Total:  ${sumCurrentPage} de ${sumTotal}`)
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 40, //Por cada 10 registros hace una paginación
        "order": [
            [0, "desc"]
        ], //Ordenar (columna,orden)
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 al 0 de de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"

            }
        }, //cerrando language
        "columnDefs": columnDefs,
        //"scrollX": true
    });
}