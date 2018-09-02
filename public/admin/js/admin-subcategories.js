var TableDatatablesResponsive = function () {

    var initTable1 = function (base_url) {
        var table = $('#subcategories-table');
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
                "url" : base_url + "/subcategories/show-all",
                "type" : "GET",
                "dataType" : "JSON",
                "dataSrc": function (response) {
                    var json = response.data;
                    var return_data = [];
                    for(var i = 0; i < json.length; i++){
                        return_data.push([
                            json[i].name,
                            json[i].category.name,
                            '<a href="javascript:;" class="edit blue btn btn-outline" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-pencil"></i></a> ',
                            json[i].id,
                            json[i].category.id
                        ]);
                    }
                    return (return_data);
                }
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [],

                // setup responsive extension: http://datatables.net/extensions/responsive/
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

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
            // So when dropdowns used the scrollable div should be removed.
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });

        table.on('click', '.edit', function () {
            var aData = oTable.fnGetData($(this).parents('tr')[0]);
            var editModal = $('#edit-subcategory-modal');
            editModal.find('[name="category_id"]').val(aData[4]);
            editModal.find('[name="name"]').val(aData[0]);
            $('#edit-form').attr('action', base_url + '/subcategories/' + aData[3]);
            $('#edit-subcategory-modal-trigger').trigger('click');
        });

        $('#add-subcategory-modal-trigger').on('click', function () {
            var addModal = $('#add-subcategory-modal');
            addModal.find('[name="name"]').val('');
            addModal.find('[name="category_id"]').val('');
        });

        $('#add-form,#edit-form').on('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var modal = $(this).parents('.modal-content');
            if(isNaN(parseInt(modal.find('[name="category_id"]').val()))) {
                swal({
                    title: 'Please select a category',
                    type: 'warning'
                });
            } else if(modal.find('[name="name"]').val() === '') {
                swal({
                    title: 'Please provide a name',
                    type: 'warning'
                });
            } else {
                $(this)[0].submit();
            }
        })

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