var TableDatatablesResponsive = function () {

    var initTable1 = function (base_url, csrf) {
        var table = $('#new-orders-table');
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
                "url" : base_url + "/vendor/products/get-new-orders",
                "type" : "GET",
                "dataType" : "JSON",
                "dataSrc": function (response) {
                    var json = response.data;
                    var return_data = [];
                    for(var i = 0; i < json.length; i++){
                        return_data.push([
                            json[i].renter.first_name + " " + json[i].renter.last_name,
                            json[i].renter.address,
                            json[i].product.name + ' (' + json[i].product.id + ')',
                            '<img style="height: 80px;" src="../../img/uploads/products/small/'+json[i].product.image+'" alt="product image" />',
                            json[i].from_date,
                            json[i].to_date,
                            //'<a href="' + base_url + '/a/products/' + json[i].id + '/edit" class="btn blue" style="padding: 3px 6px 3px 6px;">' +
                            //'<i class="fa fa-check-square"></i></a> ' +
                            '<a href="'+base_url+'/vendor/products/new-orders/accept'+json[i].id+'" class="accept btn blue" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-check-square"></i></button> ' +
                            '<a href="'+base_url+'/vendor/products/new-orders/reject'+json[i].id+'" class="reject btn red" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-remove"></i></a>',
                            json[i].id,
                        ]);
                    }
                    return return_data;
                }
            },

            buttons: [],

            responsive: { details: { } },

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
        var tableWrapper = jQuery('#sample_1_wrapper');

        table.on('click', '.accept', function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure, You want to accept the transaction?',
                text: 'You cannot revert this action!!',
                type: 'success',
                showCancelButton: true,
                confirmButtonColor: '#4684EF',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No.'
            }).then((result) => {
                if (result.value) {
                    // handle Confirm button click
                    var currentRow = $(this).parents('tr')[0];
                    $.ajax({
                        url: base_url + '/account/messages/answer_request',
                        type : 'POST',
                        data: { _token: csrf, reply : 1, tid : oTable.fnGetData(currentRow)[7] },
                        success: function (response) {
                            oTable.fnDeleteRow(currentRow);
                        }
                    });
                }
            });
        });

        table.on('click', '.reject', function (e) {
            e.preventDefault();
            swal({
                title: 'Are you sure, You want to reject this transaction?',
                text: 'You Cannot Revert This Action! .',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No.'
            }).then((result) => {
                if (result.value) {
                    // handle Confirm button click
                    var currentRow = $(this).parents('tr')[0];
                    $.ajax({
                        url: base_url + '/account/messages/answer_request',
                        type : 'POST',
                        data: { _token: csrf, reply : 0, tid : oTable.fnGetData(currentRow)[7] },
                        success: function (response) {
                            oTable.fnDeleteRow(currentRow);
                        }
                    })
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