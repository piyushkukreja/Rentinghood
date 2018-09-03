var TableDatatablesResponsive = function () {

    var initTable1 = function (base_url) {
        var table = $('#users-table');
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
                "url" : base_url + "/users/get-all",
                "type" : "GET",
                "dataType" : "JSON",
                "dataSrc": function (response) {
                    var json = response.data;
                    var return_data = [];
                    for(var i = 0; i < json.length; i++){
                        return_data.push([
                            '<a href="' + base_url + '/users/' + json[i].id + '">' + json[i].first_name + ' ' + json[i].last_name + '</a>',
                            json[i].email,
                            json[i].contact,
                            json[i].address,
                            json[i].lat,
                            json[i].lng,
                            json[i].verified,
                            '<a href="javascript:;" onclick="deleteUser(' + json[i].id + ')" class="btn red btn-outline" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-remove"></i></a>'
                        ]);
                    }
                    return return_data;
                }
            },

            buttons: [],

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

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
        var tableWrapper = jQuery('#sample_1_wrapper');
    }

    return {

        //main function to initiate the module
        init: function (base_url) {

            if (!jQuery().dataTable) {
                return;
            }

            initTable1(base_url);
        }

    };

}();