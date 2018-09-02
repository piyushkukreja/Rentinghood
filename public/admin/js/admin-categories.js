var TableDatatablesResponsive = function () {

    var initTable1 = function (base_url) {
        var table = $('#categories-table');
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
                "url" : base_url + "/categories/show-all",
                "type" : "GET",
                "dataType" : "JSON",
                "dataSrc": function (response) {
                    var json = response.data;
                    var return_data = [];
                    for(var i = 0; i < json.length; i++){
                        return_data.push([
                            json[i].name,
                            '<label class="mt-checkbox"><input class="enable" type="checkbox"' + (json[i].is_disabled === 0 ? ' checked' : '') + '><span></span></label>',
                            '<a href="javascript:;" class="edit blue btn btn-outline" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-pencil"></i></a> ',
                            json[i].id
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
        });

        table.on('click', '.edit', function () {
            var aData = oTable.fnGetData($(this).parents('tr')[0]);
            //below line gets the name from the same page instead of fetching from db
            $('#edit-category-modal').find('input[name="name"]').val(aData[0]);//this finds the name with the associated id and sets the value of input in modal
            $('#edit-form').attr('action', base_url + '/categories/' + aData[2]);//to use post method for updating data in db and page
            $('#edit-category-modal-trigger').trigger('click');//triggers modal on click with the specified id
        });

        table.on('change', '.enable', function () {
            var aData = oTable.fnGetData($(this).parents('tr')[0]);
            var disable = 1;
            if($(this).is(':checked'))
                disable = 0;
            $.ajax({
                url: base_url + '/categories/' + aData[3] + '/change-availability/' + disable,
                type: 'GET'
            });
        });

        $('#add-form,#edit-form').on('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var modal = $(this).parents('.modal-content');
            if(modal.find('[name="name"]').val() === '') {
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