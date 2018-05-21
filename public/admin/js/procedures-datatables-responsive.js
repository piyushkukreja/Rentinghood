var TableDatatablesEditable = function () {

    var handleTable = function (base_url) {

        var table = $('#procedures_table');
        var tableWrapper = $("#procedures_table_wrapper");

        var nEditing = null;
        var nNew = false;
        var procedure_id = null;
        var id = null;

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            jqTds[0].innerHTML = '<input type="text" class="form-control input-small" id="name" value="' + aData[0] + '">';
            jqTds[1].innerHTML = '<input type="number" class="no-arrows form-control input-small" id="cost" value="' + aData[1] + '">';
            jqTds[2].innerHTML = '<a class="edit btn blue btn-outline" href="" style="padding: 3px 6px 3px 6px; margin-right: 8px;"><i class="fa fa-save"></i></a>'
             + '<a class="cancel btn red btn-outline" href="" style="padding: 3px 6px 3px 6px;"><i class="fa fa-remove"></i></a>';
            id = aData[3];
            procedure_id = aData[4];
        }

        function saveRow(oTable, nRow) {
            var name = $('#name').val();
            var cost = $('#cost').val();
            oTable.fnUpdate(name, nRow, 0, false);
            oTable.fnUpdate(cost, nRow, 1, false);
            oTable.fnUpdate('<a class="edit btn blue btn-outline" href="" style="padding: 3px 6px 3px 6px; margin-right: 8px;"><i class="fa fa-edit"></i></a>'
                + '<a class="cancel btn red btn-outline" href="" style="padding: 3px 6px 3px 6px;"><i class="fa fa-remove"></i></a>', nRow, 2, false);

            if(nNew) {
                var aData = oTable.fnGetData(nRow);
                aData[3] = id;
                aData[4] = procedure_id;
                nNew = false;
            }

            oTable.fnDraw();
        }

        var oTable = table.dataTable({

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js).
            // So when dropdowns used the scrollable div should be removed.
            //"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

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
                "url" : base_url + "/procedures/show-all",
                "type" : "GET",
                "dataType" : "JSON",
                "dataSrc": function (response) {
                    var json = response.data;
                    var return_data = [];
                    for(var i = 0; i < json.length; i++) {
                        return_data.push([
                            json[i].name,
                            json[i].cost,
                            '<a href="javascript:;" class="edit btn blue btn-outline" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-edit"></i></a> ' +
                            '<a href="javascript:;" class="delete btn red btn-outline" style="padding: 3px 6px 3px 6px;">' +
                            '<i class="fa fa-remove"></i></a>',
                            id = json[i].id,
                            procedure_id = json[i].procedure_id,
                        ]);
                    }
                    return return_data;
                }
            },

            buttons:    [
                {extend: 'print', className: 'btn dark btn-outline', exportOptions: {
                    columns: [0, 1, 2]
                    }},
                {extend: 'pdf', className: 'btn green btn-outline', exportOptions: {
                        columns: [0, 1, 2]
                    }},
                {extend: 'csv', className: 'btn purple btn-outline', exportOptions: {
                        columns: [0, 1, 2]
                    }}
            ],

            responsive: {
                details: {

                }
            },

            "order": [
                [0, "asc"]
            ], // set first column as a default sort by asc

            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 5,

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},


            /*"columnDefs": [{ // set default column settings
                'orderable': true,
                'targets': [0]
            }, {
                "searchable": true,
                "targets": [0]
            }],*/
        });

        $('#procedures_table_new').on('click', function (e) {
            e.preventDefault();
            if (nNew || nEditing) {
                swal({
                    title : 'Please Save or Cancel the previous row.',
                    type : 'warning'
                });
            } else {
                var aiNew = oTable.fnAddData(['', '', '', '']);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                nNew = true;
                id = null;
                procedure_id = null;
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });

        table.on('click', '.delete', function (e) {
            e.preventDefault();

            var nRow = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(nRow);

            swal({
                title: 'Are you sure you want to delete this procedure?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                allowEnterKey: false,
                confirmButtonClass: 'btn btn-warning',
                cancelButtonClass: 'btn btn-info',
            }).then((result) => {
                if (result.value) {
                $.ajax({
                    url: base_url + '/procedures/' + aData[3],
                    type: 'DELETE',
                    dataType: 'JSON',
                    data: { _token: csrf },
                    success: function (response) {
                        oTable.fnDeleteRow(nRow);
                    }
                });
            }
            });
        });

        table.on('click', '.cancel', function (e) {
            e.preventDefault();
            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        table.on('click', '.edit', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */
                swal({
                    title : 'Please Save or Cancel the previous row.',
                    type : 'warning'
                });
            } else if (nEditing == nRow && this.innerHTML == '<i class="fa fa-save"></i>') {

                var name = $('#name').val();
                var cost = $('#cost').val();
                /* Editing this row and want to save it */
                if (name.trim() != '' && cost.trim() != '') {
                    if (nNew) {
                        $.ajax({
                            "url": base_url + "/procedures",
                            "type": "POST",
                            "dataType": "JSON",
                            "data": {
                                _token: csrf,
                                name : name,
                                cost : cost
                            },
                            "success": function (response) {
                                id = response.id;
                                saveRow(oTable, nEditing);
                                nEditing = null;
                            }
                        })
                    }
                    else {
                        $.ajax({
                            "url": base_url + "/procedures/" + id,
                            "type": "PUT",
                            "dataType" : "JSON",
                            "data": {
                                _token: csrf,
                                name : name,
                                cost : cost
                            },
                            "success": function (response) {
                                saveRow(oTable, nEditing);
                                nEditing = null;
                            },
                        });
                    }
                } else {
                    if (cost == '') {
                        swal({
                            title: 'Please provide a procedure cost',
                            type: 'warning',
                        });
                    }
                    if (name == '') {
                        swal({
                            title: 'Please provide a procedure name',
                            type: 'warning',
                        });
                    }
                }
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        })};

    return {

        //main function to initiate the module
        init: function (base_url) {
            handleTable(base_url);
        }

    };

}();