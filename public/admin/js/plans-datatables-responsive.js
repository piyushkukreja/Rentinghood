var TableDatatablesEditable = function () {

    var handlePlansTable = function (pid, csrf_token, base_url, today) {

        var table = $('#plans-table');
        var tableWrapper = $("#plans-table_wrapper");

        var nEditing = null;
        var nNew = false;
        var patient_id = pid;
        var id = null;
        var discount_type = null;
        var units = null;
        var unit_count = null;
        var csrf = csrf_token;
        var notes = null;
        var invoice_id = null;

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
                url : base_url + "/plans/show-all/" + patient_id,
                type : "GET",
                dataType : "JSON",
                dataSrc : function (response) {

                    var json = response.data;
                    var return_data = [];
                    for(var i = 0; i < json.length; i++) {
                        return_data.push([
                            json[i].invoice_id === null ?
                                '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">' +
                                '<input type="checkbox" class="checkboxes" value="' + json[i].id + '" />' +
                                '<span></span>' +
                                '</label>' : '',
                            json[i].date,
                            json[i].name + '<br/><a href="javascript:;" class="notes btn btn-info"> Notes </a>',
                            json[i].unit_count + '<span id="teeth-text">Teeth : ' + (json[i].units == null ? '' : json[i].units) + '</span>',
                            json[i].unit_cost,
                            json[i].discount + ' ' + (json[i].discount_type == 0 ? '%' : 'INR'),
                            json[i].total,
                            '<a href="javascript:;" class="edit btn blue btn-outline btn-action">' +
                            '<i class="fa fa-edit"></i></a> ' +
                            '<a href="javascript:;" class="delete btn red btn-outline btn-action">' +
                            '<i class="fa fa-remove"></i></a>',
                            id = json[i].id,
                            discount_type = json[i].discount_type,
                            units = (json[i].units == null ? '' : json[i].units),
                            unit_count = json[i].unit_count,
                            notes = json[i].notes,
                            invoice_id = (json[i].invoice_id ? parseInt(json[i].invoice_id) : null)
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

            "order": [
                [1, "asc"]
            ], // set second column as a default sort by asc

            "lengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "All"] // change per page values here
            ],

            // set the initial value
            "pageLength": 5,

            "columnDefs": [{ // set default column settings
                'orderable': false,
                'targets': [0]
            }, {
                "searchable": false,
                "targets": [0]
            }]
        });

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function changePrice() {
            var unit_cost = $('#unit_cost');
            var unit_count =$('#unit_count');
            var discount = $('#discount');
            if(unit_count.val().trim() != '' && unit_cost.val().trim() != '' && discount.val().trim() != '') {
                var cost = parseInt(unit_cost.val()) * parseInt(unit_count.val());
                if (parseInt($('#discount_type').val()) == 0) {
                    cost = cost - cost * parseInt(discount.val()) / 100;
                } else {
                    cost = cost - parseInt(discount.val());
                }
                cost = cost < 0 ? 0 : cost;
                $('#total').val(cost);
            } else {
                $('#total').val('');
            }
        }
        
        function isAdultTeeth(teethNumber) {
            return (teethNumber > 10 && teethNumber < 49);
        }

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            var teethNumbers = aData[10].split(',');
            var procedures = [];
            var proceduresArray = [];
            if(teethNumbers.length === 1 && teethNumbers[0] === '')
                teethNumbers = [];
            id = aData[8];
            discount_type = aData[9];
            units = aData[10];
            unit_count = aData[11];
            notes = aData[12];
            invoice_id = aData[13];

            $.ajax({
                url: base_url + '/procedures/show-all',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    $.each(response.data, function (i, procedure) {
                        procedures.push([procedure.name, procedure.cost]);
                        proceduresArray.push(procedure.name);
                    });
                    $('#procedure').autocomplete({
                        source: proceduresArray,
                        minLength: 0,
                        position: { my : "left top", at: "left bottom" }
                    });
                }
            });

            jqTds[0].innerHTML = '';
            jqTds[1].innerHTML = '<input type="date" class="no-arrows form-control input-small" id="date" value="' + (nNew ? today : aData[1]) + '">';
            jqTds[2].innerHTML = '<input type="text" class="form-control input-small" id="procedure" value="' + aData[2].substring(0, aData[2].indexOf('<br/>')) + '">' +
                                    '<button id="editNotes" class="btn btn-info" style="margin-top: 5px;">Notes</button>';
            jqTds[3].innerHTML = '<input type="number" class="form-control input-small affects-total" id="unit_count" value="' + unit_count + '" >' +
                                    '<input type="text" style="margin-top: 5px;" class="form-control input-small" id="units" value="' + units + '" readonly>';
            jqTds[4].innerHTML = '<input type="number" class="no-arrows form-control input-small affects-total" id="unit_cost" value="' + aData[4] + '">';
            jqTds[5].innerHTML = '<input type="number" class="no-arrows form-control affects-total" id="discount" value="' + (aData[5] !== '' ? aData[5].substring(0, aData[5].indexOf(' ')) : '') + '">' +
                                        '<select id="discount_type" class="form-control affects-total" style="margin-top: 5px;">' +
                                    '<option value="0">%</option>' +
                                    '<option value="1">INR</option>' +
                                    '</select>';
            jqTds[6].innerHTML = '<input type="number" class="no-arrows form-control input-small" disabled id="total" value="' + aData[6] + '">';
            jqTds[7].innerHTML = '<a class="edit btn blue btn-outline btn-action" href="javascript:;"><i class="fa fa-save"></i></a>'
                + ' <a class="cancel btn red btn-outline btn-action" href="javascript:;" ><i class="fa fa-remove"></i></a>';

                $('#discount_type').find('option[value="' + aData[9] + '"]').attr('selected', true);

            $('.affects-total').on('input', function () {
                changePrice();
            });

            $('#editNotes').on('click', function () {
                swal({
                    title: 'Notes',
                    html: 'Count : <span id="notesLengthCount">' + notes.length + '</span>',
                    input: 'textarea',
                    inputValue : notes,
                    inputClass : 'notesTextArea',
                    inputPlaceholder : 'Add notes here',
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value.length <= 2000) {
                                notes = value;
                                resolve();
                            } else {
                                resolve('Notes should be less than 2000 characters');
                            }
                        });
                    }
                });
                $('.notesTextArea').on('input', function () {
                    var count = $(this).val().length;
                    if(count > 2000)
                        $(this).addClass('swal2-inputerror');
                    else
                        $(this).removeClass('swal2-inputerror');
                    $('#notesLengthCount').html(count);
                })
            });

            $('#procedure').on('change', function () {
                var procedure_index = proceduresArray.indexOf($(this).val());
                if(procedure_index !== -1) {
                    $('#unit_cost').val(procedures[procedure_index][1]);
                    changePrice();
                }
            });

            $('#discount_type').on('change', function () {
                discount_type = $('#discount_type').val();
            });

            $('#unit_count').on('change', function () {
                unit_count = $('#unit_count').val() !== '' ? parseInt($('#unit_count').val()) : 0;
            });

            $('#units').on('click', function (e) {
                var ul1Items = '';
                var ul2Items = '';
                var ul3Items = '';
                var ul4Items = '';
                var ul5Items = '';
                var ul6Items = '';
                var ul7Items = '';
                var ul8Items = '';
                var i;
                for(i = 18; i > 10; i--) {
                    ul1Items += '<li class="' + (teethNumbers.indexOf(i.toString()) === -1 ? '' : 'active') + '" data-placeholder="' +  i + '" style="display: inline-block;"><a>' +  i + '</a></li>';
                }
                for(i = 21; i < 29; i++) {
                    ul2Items += '<li class="' + (teethNumbers.indexOf(i.toString()) === -1 ? '' : 'active') + '" data-placeholder="' +  i + '" style="display: inline-block;"><a>' +  i + '</a></li>';
                }
                for(i = 48; i > 40; i--) {
                    ul3Items += '<li class="' + (teethNumbers.indexOf(i.toString()) === -1 ? '' : 'active') + '" data-placeholder="' +  i + '" style="display: inline-block;"><a>' +  i + '</a></li>';
                }
                for(i = 31; i < 39; i++) {
                    ul4Items += '<li class="' + (teethNumbers.indexOf(i.toString()) === -1 ? '' : 'active') + '" data-placeholder="' +  i + '" style="display: inline-block;"><a>' +  i + '</a></li>';
                }
                for(i = 55; i > 50; i--) {
                    ul5Items += '<li class="' + (teethNumbers.indexOf(i.toString()) === -1 ? '' : 'active') + '" data-placeholder="' +  i + '" style="display: inline-block;"><a>' +  i + '</a></li>';
                }
                for(i = 61; i < 66; i++) {
                    ul6Items += '<li class="' + (teethNumbers.indexOf(i.toString()) === -1 ? '' : 'active') + '" data-placeholder="' +  i + '" style="display: inline-block;"><a>' +  i + '</a></li>';
                }
                for(i = 85; i > 80; i--) {
                    ul7Items += '<li class="' + (teethNumbers.indexOf(i.toString()) === -1 ? '' : 'active') + '" data-placeholder="' +  i + '" style="display: inline-block;"><a>' +  i + '</a></li>';
                }
                for(i = 71; i < 76; i++) {
                    ul8Items += '<li class="' + (teethNumbers.indexOf(i.toString()) === -1 ? '' : 'active') + '" data-placeholder="' +  i + '" style="display: inline-block;"><a>' +  i + '</a></li>';
                }

                swal({
                    title: '<h3 id="adult-teeth-title">Adult Teeth</h3>' +
                    '<div class="no-wrap">' +
                    '<ul class="nav nav-pills adult">' + ul1Items + '</ul>' +
                    '<ul class="nav nav-pills adult">' + ul2Items + '</ul>' +
                    '</div>' +
                    '<div class="no-wrap">' +
                    '<ul class="nav nav-pills adult">' + ul3Items + '</ul>' +
                    '<ul class="nav nav-pills adult">' + ul4Items + '</ul>' +
                    '</div>' +
                    '<h3>Child Teeth</h3>' +
                    '<div class="no-wrap">' +
                    '<ul class="nav nav-pills align-right child">' + ul5Items + '</ul>' +
                    '<ul class="nav nav-pills child">' + ul6Items + '</ul>' +
                    '</div>' +
                    '<div class="no-wrap">' +
                    '<ul class="nav nav-pills align-right child">' + ul7Items + '</ul>' +
                    '<ul class="nav nav-pills child">' + ul8Items + '</ul>' +
                    '</div>',
                    customClass: 'swal-wide',
                });

                $('ul.nav-pills').find('li').on('click', function () {
                    var teethNumber = $(this).attr('data-placeholder');
                    var toRemove = [];
                    if((teethNumbers.indexOf(teethNumber)) === -1) {
                        teethNumbers.push(teethNumber);
                        if(isAdultTeeth(teethNumber)) {
                            $('ul.nav-pills.child').find('li.active').removeClass('active');
                            $.each(teethNumbers, function (i, current) {
                                if(!isAdultTeeth(current))
                                    toRemove.push(current)
                            });
                        }
                        else {
                            $('ul.nav-pills.adult').find('li.active').removeClass('active');
                            $.each(teethNumbers, function (i, current) {
                                if(isAdultTeeth(current))
                                    toRemove.push(current)
                            });
                        }
                        $.each(toRemove, function (i, current) {
                            teethNumbers.splice(teethNumbers.indexOf(current), 1);
                        })
                        $(this).addClass('active');
                    } else {
                        teethNumbers.splice(teethNumbers.indexOf(teethNumber), 1);
                        $(this).removeClass('active');
                    }
                    units = teethNumbers.join(',');
                    $('#units').val(units);
                    unit_count = teethNumbers.length
                    $('#unit_count').val(unit_count);
                    changePrice();
                });
            });

        }

        function saveRow(oTable, nRow) {
            var date = $('#date');
            var unit_cost = $('#unit_cost');
            var discount = $('#discount');
            var total = $('#total');
            var name = $('#procedure');
            var discount_type = $('#discount_type').find(':selected').html();
            var discount_type_id = parseInt($('#discount_type').val());

            var aData = oTable.fnGetData(nRow);
            if(nNew) {
                aData[8] = id;
                nNew = false;
                oTable.fnUpdate('<td>' +
                    '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">' +
                    '<input type="checkbox" class="checkboxes" value="' + id + '" />' +
                    '<span></span>' +
                    '</label>' +
                    '</td>', nRow, 0, false);
            }
            aData[9] = discount_type_id;
            aData[10] = units;
            aData[11] = unit_count;
            aData[12] = notes;

            oTable.fnUpdate(date.val(), nRow, 1, false);
            oTable.fnUpdate(name.val() + '<br/><a href="javascript:;" class="notes btn btn-info"> Notes </a>', nRow, 2, false);
            oTable.fnUpdate(unit_count + '<span id="teeth-text">Teeth : ' + units + '</span>', nRow, 3, false);
            oTable.fnUpdate(unit_cost.val(), nRow, 4, false);
            oTable.fnUpdate(discount.val() + " " + discount_type, nRow, 5, false);
            oTable.fnUpdate(total.val(), nRow, 6, false);
            oTable.fnUpdate('<a class="edit btn blue btn-outline btn-action" href="javascript:;" style=""><i class="fa fa-edit"></i></a>' +
                ' <a class="delete btn red btn-outline btn-action" href="javascript:;" style=""><i class="fa fa-remove"></i></a>', nRow, 7, false);
            oTable.fnDraw();
        }

        $('#plans-table_new').on('click', function (e) {
            e.preventDefault();
            if (nNew || nEditing) {
                swal({
                    title : 'Please Save or Cancel the previous row.',
                    type : 'warning'
                });
            } else {
                var aiNew = oTable.fnAddData(['', '', '', '1', '', '0 %', '', '', '', '', '', 0, '', null]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                nNew = true;
                id = null;
                discount_type = 1;
                units = null;
                unit_count = 0;
                invoice_id = null;
                notes = '';
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });

        table.on('click', '.delete', function (e) {
            e.preventDefault();

            var nRow = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(nRow);

            swal({
                title: 'Are you sure you want to delete this treatment plan?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                allowEnterKey: false,
                confirmButtonClass: 'btn btn-warning',
                cancelButtonClass: 'btn btn-info',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: base_url + '/plans/' + aData[8],
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

        table.on('click', '.notes', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];
            swal({
                title : 'Notes',
                input : 'textarea',
                inputClass : 'not-disabled',
                inputValue : oTable.fnGetData(nRow)[12],
            }).disableInput();

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

                var date = $('#date').val().trim();
                var name = $('#procedure').val();
                var unit_count = $('#unit_count').val().trim();
                var unit_cost = $('#unit_cost').val().trim();
                var discount = $('#discount').val().trim();
                var discount_type = $('#discount_type').val();
                var total = $('#total').val().trim();
                var units = $('#units').val().trim();

                /* Editing this row and want to save it */
                if (date != '' && name != '' && unit_cost != '' && unit_count != '' && discount != '' && total != '') {
                    if (nNew) {
                        $.ajax({
                            "url": base_url + "/plans",
                            "type": "POST",
                            "dataType": "JSON",
                            "data": {
                                _token: csrf,
                                date : date,
                                patient_id : patient_id,
                                name : name,
                                unit_count : unit_count,
                                unit_cost : unit_cost,
                                discount : discount,
                                discount_type : discount_type,
                                total : total,
                                units : units,
                                notes : notes,
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
                            "url": base_url + "/plans/" + id,
                            "type": "PUT",
                            "dataType" : "JSON",
                            "data": {
                                _token: csrf,
                                date : date,
                                name : name,
                                unit_count : unit_count,
                                unit_cost : unit_cost,
                                discount : discount,
                                discount_type : discount_type,
                                total : total,
                                units : units,
                                notes : notes,
                            },
                            "success": function (response) {
                                saveRow(oTable, nEditing);
                                nEditing = null;
                            },
                        });
                    }
                } else {
                    if (date == '') {
                        swal({
                            title: 'Please provide a date',
                            type: 'warning',
                        });
                    }
                    if (name == '') {
                        swal({
                            title: 'Please provide a procedure name',
                            type: 'warning',
                        });
                    }
                    if (unit_cost == '') {
                        swal({
                            title: 'Please provide a unit cost',
                            type: 'warning',
                        });
                    }
                    if (unit_count == '') {
                        swal({
                            title: 'Please provide a unit count',
                            type: 'warning',
                        });
                    }
                    if (discount == '') {
                        swal({
                            title: 'Please provide a discount',
                            type: 'warning',
                        });
                    }
                    if (discount == '') {
                        swal({
                            title: 'Please provide a discount',
                            type: 'warning',
                        });
                    }
                }
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });

        var checked = [];

        table.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var isChecked = jQuery(this).is(":checked");
            jQuery(set).each(function (i, object) {
                var checkbox = $(object);
                if (isChecked) {
                    if(!checkbox.is(':checked'))
                        checked.push(parseInt(checkbox.attr('value')));
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    if(checkbox.is(':checked'))
                        checked.splice(checked.indexOf(parseInt(checkbox.attr('value'))), 1);
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            showOrHideInvoice();
        });

        table.on('change', 'tbody tr .checkboxes', function () {
            if($(this).is(':checked'))
                checked.push(parseInt($(this).attr('value')));
            else
                checked.splice(checked.indexOf(parseInt($(this).attr('value'))), 1);
            $(this).parents('tr').toggleClass("active");
            showOrHideInvoice();
        });

        $('#plans-table_invoice').on('click', function () {
            $.ajax({
                url: base_url + '/invoices',
                type: 'POST',
                data: { _token: csrf, plans: checked, patient_id: pid },
                success: function (response) {
                    if(response.status == 'success') {
                        $.each(response.invoiced, function (i, plan_id) {
                            var nRow = $('input.checkboxes[type="checkbox"][value="' + plan_id + '"]').parents('tr');
                            var aData = oTable.fnGetData(nRow);
                            aData[13] = response.user_id;
                            oTable.fnUpdate('', nRow, 0, false);
                        });
                        oTable.fnDraw();
                    }
                }
            });
        });

        function showOrHideInvoice() {
            var invoiceButton = $('#plans-table_invoice');
            if(checked.length === 0) {
                invoiceButton.hide();
            } else if(checked.length !== 0) {
                invoiceButton.show();
            }
        }
        return oTable;
    };

    var handleInvoicesTable = function (pid, base_url) {
        var table = $('#invoices-table');
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
                "url": base_url + "/invoices/show-all/" + pid,
                "type": "GET",
                "dataType": "JSON",
                "dataSrc": function (response) {
                    var json = response.data;
                    var return_data = [];
                    for (var i = 0; i < json.length; i++) {
                        return_data.push([
                            json[i].number,
                            json[i].created_at,
                            json[i].user.first_name + ' ' + json[i].user.last_name,
                            '<a target="_blank" href="' + base_url + '/invoices/' + json[i].id + '" class="view btn blue btn-outline btn-action"><i class="fa fa-eye"></i></a>',
                            json[i].plans
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
        var tableWrapper = jQuery('#invoices-table_wrapper');
        return oTable;
    };

    return {
        //main function to initiate the module
        init: function (pid, csrf_token, base_url, today) {
            return [handlePlansTable(pid, csrf_token, base_url, today), handleInvoicesTable(pid, base_url)];
        }
    };


}();