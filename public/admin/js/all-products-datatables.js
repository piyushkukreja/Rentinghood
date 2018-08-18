var TableDatatablesResponsive = function () {

    var initTable1 = function (base_url) {
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
                "url" : base_url + "/a/products/show-all",
                "type" : "GET",
                "dataType" : "JSON",
                "dataSrc": function (response) {
                    var json = response.data;
                    var return_data = [];
                    for(var i = 0; i < json.length; i++){
                        return_data.push([
                            json[i].name,
                            json[i].category,
                            json[i].subcategory.name,
                            json[i].lender.first_name + ' ' + json[i].lender.last_name,
                            json[i].address,
                            json[i].duration,
                            '<a href="' + base_url + '/a/products/' + json[i].id + '/edit" class="btn yellow btn-outline" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-eye"></i></a> ' +
                            '<a href="javascript:;" onclick="deleteProduct(' + json[i].id + ')" class="btn red btn-outline" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-remove"></i></a>'
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
        var tableWrapper = jQuery('#sample_1_wrapper');
    };
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