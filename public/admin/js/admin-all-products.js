var TableDatatablesResponsive = function () {

    var initTable1 = function (base_url, csrf) {
        var table = $('#products-table');
        var oTable = table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            "ajax": {
                "url" : base_url + "/products/show-all",
                "type" : "GET",
                "dataType" : "JSON",
                "dataSrc": function (response) {
                    var json = response.data;
                    var return_data = [];
                    for(var i = 0; i < json.length; i++){
                        return_data.push([
                            '<a href="' + base_url + '/products/' + json[i].id + '/edit/admin">' + json[i].name + '</a>',
                            json[i].category,
                            json[i].subcategory.name,
                            '<a href="' + base_url + '/users/' + json[i].lender.id + '">' + json[i].lender.first_name + ' ' + json[i].lender.last_name + '</a>',
                            json[i].address,
                            json[i].duration,
                            '<a href="javascript:;" class="delete btn red btn-outline" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-remove"></i></a>',
                            json[i].id
                        ]);
                    }
                    return return_data;
                }
            },

            buttons: [],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: { details: {} },

            "order": [
                [0, 'asc']
            ],

            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,

            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
        });

        table.on('click', '.delete', function () {
            var row = $(this).parents('tr')[0];
            var id = oTable.fnGetData(row)[7];
            swal({
                title: 'Are you sure you want to delete this product?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                allowEnterKey: false,
                confirmButtonClass: 'btn btn-warning',
                cancelButtonClass: 'btn btn-info',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: base_url + '/products/' + id,
                        type: 'DELETE',
                        data: { _token: csrf },
                        success: function (response) {
                            if(response.status === 'success')
                                oTable.fnDeleteRow(row);
                        }
                    });
                }
            });
        });
    };

    return {
        //main function to initiate the module
        init: function (base_url, csrf) {
            if (!jQuery().dataTable) {
                return;
            }
            initTable1(base_url, csrf);
        }
    };
}();