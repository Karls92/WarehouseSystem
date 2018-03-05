$(document).ready(function()
{
    var length_change = true;
    var info = true;
    var paging = true;
    var offset = registers_qty;
    var last_consult_qty = registers_qty;

    if(registers_qty <= 10)
    {
        length_change = false;
        info = false;
        paging = false;
    }

    var table_selector = $("#table_list");

    var table = table_selector.DataTable({
        "paging"      : paging,
        "lengthChange": length_change,
        "searching"   : true,
        "ordering"    : false,
        "info"        : info,
        "autoWidth"   : false,
        "language": {
            "lengthMenu"    : "Mostrando _MENU_ elementos por página",
            "zeroRecords"   : "No se encontraron resultados para su búsqueda",
            "info"          : "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty"     : "No hay registros",
            "infoFiltered"  : "(filtrado a un total de _MAX_ entradas)",
            "oPaginate": {
                "sPrevious" : "Previous",
                "sNext"     : "Next"
            },
            "sSearch"       : "Search:",
        },
    });

    if(per_page != 'all' && registers_qty >= per_page)
    {
        var page_length = table.page.len();

        function has_changed()
        {
            var table_info = table.page.info();
            var current_page = table_info.page + 1;

            if(current_page == table_info.pages && last_consult_qty >= per_page)
            {
                $('#charging_modal').modal();

                $.post(ajax_registers,{'offset':offset,'_token':_token}, function(data){

                    if(data != '[]')
                    {
                        var new_registers = JSON.parse(data);

                        new_registers.forEach(function (register, index) {
                            table.row.add(register);
                        });

                        table.draw(false);

                        offset += new_registers.length;
                        last_consult_qty = new_registers.length;

                        //e.stopPropagation();
                    }
                    else
                    {
                        last_consult_qty = 0;
                    }

                    $('#charging_modal').modal('hide');
                });
            }
        }

        /*
         * Evento de cambio de pagina
         */
        table_selector.on( 'page.dt', function(e)
            {
                has_changed();
            }
        );

        /*
         *  Evento de cambio de longitud de pagina
         */
        table_selector.on( 'length.dt', function ( e, settings, len )
            {
                page_length = len;
                has_changed();
            }
        );
    }
});