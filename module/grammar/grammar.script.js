/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'category_id';
me.action.menu = 'getintentbycateid';
me.action.add = 'addintent';
me.action.edit = 'updateintent';
me.action.del = 'deletecategory';
me.variation = $('div#dvsubintent').clone();
me.childEditors = {};  // Globally track created chid editors
/*================================================*\
  :: FUNCTION ::
\*================================================*/
me.LoadCbo = function (val, menu, code, name) {
    $.ajax({
        url: me.url + '-LoadCbo',
        type: "POST",
        dataType: "json",
        cache: false,
        data: {menu_action: menu, code: code, name: name},
        success: function (data) {

            switch (data.success) {
                case "COMPLETE":
                    var tree = $('#' + val).treeview({

                        enableLinks: true,
                        preventUnselect: true,
                        allowReselect: true,
                        data: data.item,
                        onNodeSelected: function (event, data) {
                            // console.log(event)
                            // console.log(data)
                            // console.log(data.state.selected)
                            // console.log(data.state.expanded)
                            // if(data.state.expanded){
                            // 	$('#'+val).treeview('toggleNodeExpanded', [ data , { silent: true } ]);
                            // }
                            tree.treeview(true).revealNode(data, {silent: true});
                            // $('#'+val).treeview('toggleNodeExpanded', [ $('#'+val).treeview('getSelected'), { silent: true } ]);
                            // $('#'+val).treeview('toggleNodeSelected', [ $('#'+val).treeview('getSelected'), { silent: true } ]);
                            // console.log( _.size($('#'+val).treeview('getParents', $('#'+val).treeview('getSelected'))))
                            if (data.level == 1) {
                                me.category_id = data.id;
                                me.subintent_id = 0;
                                if (!data.nodes) {
                                    me.LoadCboSub('tree', 'getsubcategory', data.id, data.index);
                                } else {
                                    $('#' + val).treeview('removeNode', [data.nodes, {silent: true}]);
                                    me.LoadCboSub('tree', 'getsubcategory', data.id, data.index);
                                }
                                me.LoadData(me.action.menu, data.id, 1, 30);
                            } else if (data.level == 2) {
                                me.category_id = data.main;
                                me.subintent_id = data.id
                                me.LoadData(me.action.menu, data.id, 1, 30);
                            }

                        },
                        onAddButtonClicked: function (event, node) {
                            console.log(event)
                            console.log(node)
                            // if (node.level == 1) {
                            //     $('#frm_addcategory input[name="parentcategory_id"]').val('');
                            //     $('#add-modal-form').modal({
                            //         backdrop: 'static',
                            //         keyboard: true,
                            //         show: true,
                            //         handleUpdate: true
                            //     });
                            // }

                            if (node.level == 1) {
                                $('#frm_addcategory input[name="category_id"]').val('');
                                $('#frm_addcategory input[name="parentcategory_id"]').val(node.id);
                                $('#add-modal-form').modal({
                                    backdrop: 'static',
                                    keyboard: true,
                                    show: true,
                                    handleUpdate: true
                                });

                            }
                        },
                        onEditButtonClicked: function (event, node) {
                            console.log(event)
                            console.log(node)
                            if (node.level == 1) {
                                $('#frm_editcategory input[name="category_name"]').val(node.text);
                                $('#frm_editcategory input[name="category_id"]').val(node.id);
                                $('#frm_editcategory input[name="parentcategory_id"]').val('');
                                $('#frm_editcategory input[name="active"]').val(node.active);
                                $('#edit-modal-form').modal({
                                    backdrop: 'static',
                                    keyboard: true,
                                    show: true,
                                    handleUpdate: true
                                });
                            } else if (node.level == 2) {
                                $('#frm_editcategory input[name="category_name"]').val(node.text);
                                $('#frm_editcategory input[name="category_id"]').val(node.id);
                                $('#frm_editcategory input[name="parentcategory_id"]').val(node.main);
                                $('#frm_editcategory input[name="active"]').val(node.active);
                                $('#edit-modal-form').modal({
                                    backdrop: 'static',
                                    keyboard: true,
                                    show: true,
                                    handleUpdate: true
                                });

                            }
                        },
                        onDelButtonClicked: function (event, node) {
                            console.log(event)
                            console.log(node)
                            if (node.level == 1) {
                                alertify.confirm("Do you want Delete Category.",
                                    function () {
                                        $.ajax({
                                            url: me.url + '-DelSub',
                                            type: 'POST',
                                            dataType: 'json',
                                            cache: false,
                                            data: {
                                                'code': node.id,
                                                'menu_action': me.action.del,
                                                'main': me.action.main,
                                                'category_name': node.text,
                                                'parentcategory_id': '',
                                                'active': node.active
                                            },
                                            success: function (data) {
                                                switch (data.success) {
                                                    case 'COMPLETE':
                                                        $('.modal').modal('hide');
                                                        alertify.success(data.msg);
                                                        me.LoadCbo('tree', 'getcategory', 'category_id', 'category_name');
                                                        break;
                                                    default:
                                                        alertify.error(data.msg);
                                                        break;
                                                }
                                            }
                                        });
                                    },
                                    function () {
                                        alertify.error('Cancel Delete');
                                    });
                            } else if (node.level == 2) {
                                alertify.confirm("Do you want Delete Sub Category.",
                                    function () {
                                        $.ajax({
                                            url: me.url + '-DelSub',
                                            type: 'POST',
                                            dataType: 'json',
                                            cache: false,
                                            data: {
                                                'code': node.id,
                                                'menu_action': me.action.del,
                                                'main': me.action.main,
                                                'category_name': node.text,
                                                'parentcategory_id': node.main,
                                                'active': node.active
                                            },
                                            success: function (data) {
                                                switch (data.success) {
                                                    case 'COMPLETE':
                                                        $('.modal').modal('hide');
                                                        alertify.success(data.msg);
                                                        me.LoadCbo('tree', 'getcategory', 'category_id', 'category_name');
                                                        break;
                                                    default:
                                                        alertify.error(data.msg);
                                                        break;
                                                }
                                            }
                                        });
                                    },
                                    function () {
                                        alertify.error('Cancel Delete');
                                    });

                            }
                        }

                    });

                    break;
                default:
                    alertify.alert(data.msg);
                    break;
            }
        }
    });
};

me.LoadCboSub = function (val, menu, code, index) {
    $.ajax({
        url: me.url + '-LoadCboSub',
        type: "POST",
        dataType: "json",
        cache: false,
        data: {menu_action: menu, code: code},
        success: function (data) {

            switch (data.success) {
                case "COMPLETE":
                    // $('#'+val).treeview(true).addNode(data.item, $('#'+val).treeview('getSelected'))
                    // $('#'+val).treeview('removeNode', [ $('#'+val).treeview('getSelected'), { silent: true } ]);
                    $('#' + val).treeview('addNode', [data.item, $('#' + val).treeview('getSelected'), index, {
                        silent: true,
                        ignoreChildren: false
                    }]);
                    $.each(data.item, function (i, result) {
                        $('li#' + result.id).find('.btn-add').css('display', 'none')
                    });


                    break;
                case "FAIL":
                    break;
                default:
                    alertify.alert(data.msg);
                    break;
            }
        }
    });
};

me.LoadData = function (menu, id, page_id, page_size, readd = '') {

    $.ajax({
        url: me.url + '-View',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {menu_action: menu, category_id: id, page_id: page_id, page_size: page_size},
        success: function (data) {
            switch (data.success) {
                case 'COMPLETE' :
                    me.table = $('#tbView')
                        .addClass('nowrap')
                        .removeAttr('width')
                        .DataTable({
                            destroy: true,
                            bFilter: false,
                            dom: 'Bfrtip',
                            buttons: [{
                                extend: 'colvis',
                                columnText: function (dt, idx, title) {
                                    if (idx == 0) {
                                        return (idx + 1) + ': Variation';
                                    } else {
                                        return (idx + 1) + ': ' + (title ? title : 'Action');
                                    }
                                }
                            }],
                            columnDefs: [
                                {
                                    "width": "5%",
                                    "targets": 0,
                                    "searchable": false
                                },
                                {
                                    "width": "5%",
                                    "targets": -1,
                                    "searchable": false,
                                    "orderable": false
                                },
                                {
                                    "width": "5%",
                                    "targets": -2,
                                    "searchable": false,
                                    "orderable": false
                                }
                            ],
                            createdRow: function (row, data, dataIndex) {
                                // Set the data-status attribute, and add a class
                                $(row).find('td:eq(0)')
                                    .attr('data-name', data.variation);
                            },
                            retrieve: true,
                            deferRender: true,
                            stateSave: false,
                            responsive: false,
                            scrollX: true,
                            pageLength: page_size,
                            paging: true,
                            lengthChange: false,
                            columns: data.columns,
                            serverSide: true,
                            ajax: {
                                "url": me.url + "-View",
                                "type": "POST",
                                "data": function (d) {
                                    d.page_id = (d.start / d.length) + 1;
                                    d.page_size = 30;
                                    d.category_id = me.category_id;
                                    d.menu_action = me.action.menu;
                                }
                            }
                        });

                    $('a.toggle-vis').on('click', function (e) {
                        e.preventDefault();

                        // Get the column API object
                        var column = me.table.column($(this).attr('data-column'));

                        // Toggle the visibility
                        column.visible(!column.visible());
                    });

                    $('#tbView tbody').on('click ', 'td.details-control', function () {
                        var tr = $(this).closest('tr');
                        me.dataold = tr;
                        var row = me.table.row(tr);
                        var rowData = JSON.parse($(tr).find('td:eq(0)').attr('data-name'));

                        if (row.child.isShown()) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');

                            // Destroy the Child Datatable
                            $('#' + rowData[0].name.replace(' ', '-')).DataTable().destroy();
                        } else {
                            // Open this row

                            if(rowData[0]){


                            row.child(me.format(rowData[0])).show();
                            var id = rowData[0].name.replace(' ', '-');


                            me.tablesub = $('#' + id)
                                .addClass('nowrap')
                                .removeAttr('width').DataTable({
                                    dom: "t",
                                    data: rowData,
                                    columns: [
                                        {data: "sub_intent_tag", title: 'Intent TAG', className: 'text-center'},
                                        {data: "intent_type_name", title: 'Type', className: 'text-center'},
                                        {data: "active", title: 'Active', className: 'text-center'},
                                        {data: "btn", title: '', className: 'text-center'},
                                        {data: "sentence", title: 'Sentence', className: 'text-center'},
                                    ],
                                    iDisplayLength: page_size,
                                    ordering: false,
                                    retrieve: true,
                                    deferRender: true,
                                    stateSave: true,
                                    scrollX: true,
                                    pageLength: page_size,
                                    lengthMenu: [[page_size, (page_size * 2), -1], [page_size, (page_size * 2), 'All']]

                                });

                            tr.addClass('shown');
                            me.tablesub.columns.adjust().draw('true');
                            }
                        }
                    });
                    break;
                default :
                    alertify.alert(data.msg);
                    break;
            }
        }
    });
};


me.LoadData_ = function (menu, id, page_id, page_size, readd = '') {

    $.ajax({
        url: me.url + '-View',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {menu_action: menu, category_id: id, page_id: page_id, page_size: page_size},
        success: function (data) {
            switch (data.success) {
                case 'COMPLETE' :
                    if (data.data.length == 0) {
                        // alertify.alert('No data, Please select other date');
                    }
                    if (readd) {
                        // me.table.clear();
                        // var dataold = me.table.rows().data();
                        $('td.details-control').each(function () {
                            var tr = $(this).closest('tr');
                            var row = me.table.row(tr);
                            if (row.child.isShown()) {
                                // This row is already open - close it
                                row.child.hide();
                                tr.removeClass('shown');
                            }
                        })

                        me.applyData(me.table, data.data, false);

                        me.applyData(me.tablesub, data.data, false);
                        // me.table.clear().draw();
                        // me.table.rows.add(data.data).draw();
                    } else {
                        me.table = $('#tbView')
                            .addClass('nowrap')
                            .removeAttr('width')
                            .DataTable({
                                dom: 'Bfrtip',
                                buttons: [{
                                    extend: 'colvis',
                                    columnText: function (dt, idx, title) {
                                        if (idx == 0) {
                                            return (idx + 1) + ': Variation';
                                        } else {
                                            return (idx + 1) + ': ' + (title ? title : 'Action');
                                        }
                                    }
                                }],
                                columnDefs: [
                                    {
                                        "width": "5%",
                                        "targets": 0,
                                        "searchable": false
                                    },
                                    {
                                        "width": "5%",
                                        "targets": -1,
                                        "searchable": false,
                                        "orderable": false
                                    },
                                    {
                                        "width": "5%",
                                        "targets": -2,
                                        "searchable": false,
                                        "orderable": false
                                    }
                                ],
                                createdRow: function (row, data, dataIndex) {
                                    // Set the data-status attribute, and add a class
                                    $(row).find('td:eq(0)')
                                        .attr('data-name', data.variation);
                                },
                                retrieve: true,
                                deferRender: true,
                                stateSave: true,
                                iDisplayLength: page_size,
                                responsive: false,
                                scrollX: true,
                                pageLength: page_size,
                                lengthMenu: [[page_size, (page_size * 2), -1], [page_size, (page_size * 2), 'All']],
                                data: data.data,
                                columns: data.columns
                            });


                    }
                    me.dataold = data.data;
                    me.table.columns.adjust().draw('true');

                    $('a.toggle-vis').on('click', function (e) {
                        e.preventDefault();

                        // Get the column API object
                        var column = me.table.column($(this).attr('data-column'));

                        // Toggle the visibility
                        column.visible(!column.visible());
                    });

                    $('#tbView tbody').on('click', 'td.details-control', function () {
                        var tr = $(this).closest('tr');
                        var row = me.table.row(tr);
                        var rowData = JSON.parse($(tr).find('td:eq(0)').attr('data-name'));

                        if (row.child.isShown()) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');

                            // Destroy the Child Datatable
                            $('#' + rowData[0].name.replace(' ', '-')).DataTable().destroy();
                        } else {
                            // Open this row
                            row.child(me.format(rowData[0])).show();
                            var id = rowData[0].name.replace(' ', '-');


                            me.tablesub = $('#' + id)
                                .addClass('nowrap')
                                .removeAttr('width').DataTable({
                                    dom: "t",
                                    data: rowData,
                                    columns: [
                                        {data: "sub_intent_tag", title: 'Intent TAG', className: 'text-center'},
                                        {data: "intent_type_name", title: 'Type', className: 'text-center'},
                                        {data: "active", title: 'Active', className: 'text-center'},
                                        {data: "btn", title: '', className: 'text-center'},
                                        {data: "sentence", title: 'Sentence', className: 'text-center'},
                                    ],
                                    iDisplayLength: page_size,
                                    ordering: false,
                                    retrieve: true,
                                    deferRender: true,
                                    stateSave: true,
                                    scrollX: true,
                                    pageLength: page_size,
                                    lengthMenu: [[page_size, (page_size * 2), -1], [page_size, (page_size * 2), 'All']]

                                });

                            tr.addClass('shown');
                            me.tablesub.columns.adjust().draw('true');
                        }
                    });

                    break;
                default :
                    alertify.alert(data.msg);
                    break;
            }
        }
    });
};

me.LoadDataSub = function (menu, category_id, intent_id, subintent_id, page_id, page_size, readd = '') {

    $.ajax({
        url: me.url + '-ViewSub',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {
            menu_action: menu,
            category_id: category_id,
            intent_id: intent_id,
            subintent_id: subintent_id,
            page_id: page_id,
            page_size: 25
        },
        success: function (datas) {
            switch (datas.success) {
                case 'COMPLETE' :
                    me.tablesentence = $('#tbViewSub')
                        .addClass('nowrap')
                        .removeAttr('width')
                        .DataTable({
                            destroy: true,
                            bFilter: false,
                            dom: 'Bfrtip',
                            buttons: [
                                // 'excelHtml5',
                                {
                                    text: 'ย้อนกลับ',
                                    className: 'float-left',
                                    action: function (e, dt, node, config) {
                                        me.loading = true;
                                        $('#tbViewSub_wrapper').css('display', 'none');
                                        $('#tbView_wrapper').css('display', '');
                                        $('#btnadd').css('display', '');
                                        $('#btnaddsentense').css('display', 'none');
                                    }
                                }
                            ],
                            columnDefs: [
                                {
                                    "width": "5%",
                                    "targets": 0,
                                    "searchable": false
                                }
                            ],
                            retrieve: true,
                            deferRender: true,
                            stateSave: false,
                            responsive: false,
                            scrollX: true,
                            pageLength: page_size,
                            paging: true,
                            lengthChange: false,
                            columns: datas.columns,
                            serverSide: true,
                            ajax: {
                                "url": me.url + "-ViewSub",
                                "type": "POST",
                                "data": function (d) {
                                    d.page_id = (d.start / d.length) + 1;
                                    d.page_size = 30;
                                    d.category_id = me.category_id;
                                    d.intent_id = me.intent_id;
                                    d.subintent_id = (me.subintent_id ? me.subintent_id : 0);
                                    d.menu_action = 'getsentencebyintent';
                                }
                            }
                        });

                    me.tablesentence.buttons(0, null).container().addClass('col');

                    $('#tbViewSub_wrapper').css('display', '');
                    $('#tbView_wrapper').css('display', 'none');
                    break;
                default :
                    alertify.alert(datas.msg);
                    break;
            }
        }
    });
};

me.LoadDataSub_ = function (menu, category_id, intent_id, subintent_id, page_id, page_size, readd = '') {

    $.ajax({
        url: me.url + '-ViewSub',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {
            menu_action: menu,
            category_id: category_id,
            intent_id: intent_id,
            subintent_id: subintent_id,
            page_id: page_id,
            page_size: 25
        },
        success: function (datas) {
            switch (datas.success) {
                case 'COMPLETE' :
                    // $('#tbViewSub_wrapper').css('display','');
                    // $('#tbView_wrapper').css('display','none');
                    // $('#frmsearch').css('display','none');
                    // console.log(datas.data.length);
                    // if(data.data.length == 0){
                    //     alertify.alert('No data, Please select other date');
                    //     return false;
                    // }

                    if (Object.entries(me.tablesentence).length > 0) {
                        readd = true;
                        console.log('here');
                    }

                    if (readd) {
                        console.log('here readd');
                        me.tablesentence.clear().draw();
                        me.tablesentence.rows.add(datas.data).draw();
                    } else {
                        me.tablesentence = $('#tbViewSub')
                            .addClass('nowrap')
                            .removeAttr('width')
                            .DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'excelHtml5',
                                    {
                                        text: 'ย้อนกลับ',
                                        className: 'float-left',
                                        action: function (e, dt, node, config) {
                                            me.loading = true;
                                            $('#tbViewSub_wrapper').css('display', 'none');
                                            $('#tbView_wrapper').css('display', '');
                                            $('#btnadd').css('display', '');
                                            $('#btnaddsentense').css('display', 'none');
                                        }
                                    }
                                ],
                                columnDefs: [
                                    {
                                        "width": "5%",
                                        "targets": 0,
                                        "searchable": false
                                    }
                                ],
                                bFilter: false,
                                searching: false,
                                retrieve: true,
                                deferRender: true,
                                stateSave: false,
                                iDisplayLength: page_size,
                                responsive: false,
                                scrollX: true,
                                pageLength: page_size,
                                paging: false,
                                bInfo: false,
                                lengthChange: false,
                                data: datas.data,
                                columns: datas.columns
                            });

                    }

                    me.tablesentence.columns.adjust().draw('true');
                    me.tablesentence.buttons(0, null).container().addClass('col');

                    if (datas.name) {
                        $('title').text(datas.name);
                    }
                    // $('#frmresult').css('display','');
                    // $('#chnn').val(data.chnn);


                    $('a.toggle-vis').on('click', function (e) {
                        e.preventDefault();

                        // Get the column API object
                        var column = me.tablesentence.column($(this).attr('data-column'));

                        // Toggle the visibility
                        column.visible(!column.visible());
                    });
                    $('#tbViewSub_wrapper').css('display', '');
                    $('#tbView_wrapper').css('display', 'none');
                    break;
                default :
                    alertify.alert(datas.msg);
                    break;
            }
        }
    });
};


me.format = function (rowData) {
    return '<div class="col-md-10" style="margin: 0 auto;float: none;padding: 10px;"><table id="' + rowData.name.replace(' ', '-') + '" class="table table-yellow table-bordered table-striped table-condensed dataTable" style="width: 100%;"></table></div>';
}

me.Add = function () {
    $('#btnsubmit').click(function (e) {
        e.stopPropagation();
        // if($('#variation-variation_text').attr('required') == 'required'){
        //     if(!$('#variation-variation_text').val()){
        //         $('#variation-variation_text').tagsinput('focus');
        //         return false;
        //     }
        // }
        $('form#frm_addedit').submit(function () {
            var form = $(this);
            $('.modal').modal('hide');
            alertify.confirm("Do you want Add.",
                function () {
                    $.ajax({
                        url: me.url + '-Add',
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: form.serialize({
                            checkboxesAsBools: true
                        }),
                        success: function (data) {
                            switch (data.success) {
                                case 'COMPLETE':
                                    $('.modal').modal('hide');
                                    alertify.success(data.msg);
                                    me.table.draw(true);
                                    // $('#btnsearchsubmit').click();
                                    // me.table.clear().draw();
                                    // me.LoadData(me.action.menu, me.category_id, 1, 30, 1);
                                    break;
                                default:
                                    alertify.error(data.msg);
                                    break;
                            }
                        }
                    });
                },
                function () {
                    alertify.error('Cancel Add');
                });

        });

    }).click();
};

me.Edit = function () {
    $('#btnsubmit').click(function (e) {
        e.stopPropagation();
        // if($('#variation-variation_text').attr('required') == 'required'){
        //     if(!$('#variation-variation_text').val()){
        //         $('#variation-variation_text').tagsinput('focus');
        //         return false;
        //     }
        // }
        $('form#frm_addedit').submit(function () {
            var form = $(this);
            $('.modal').modal('hide');
            alertify.confirm("Do you want Edit.",
                function () {
                    $.ajax({
                        url: me.url + '-Edit',
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: form.serialize({
                            checkboxesAsBools: true
                        }),
                        success: function (data) {
                            switch (data.success) {
                                case 'COMPLETE':
                                    $('.modal').modal('hide');
                                    alertify.success(data.msg);
                                    me.table.draw(true);
                                    // $('#btnsearchsubmit').click();
                                    // me.table.clear().draw();
                                    // me.LoadData(me.action.menu, me.code, 1, 30, 1);
                                    break;
                                default:
                                    alertify.error(data.msg);
                                    break;
                            }
                        }
                    });
                },
                function () {
                    alertify.error('Cancel Add');
                });

        });

    }).click();
};

me.AddSub = function () {
    $('#btnsubmitadd').click(function (e) {
        e.stopPropagation();
        $('form#frm_addcategory').submit(function () {
            var form = $(this);
            $('.modal').modal('hide');
            alertify.confirm("Do you want Add.",
                function () {
                    $.ajax({
                        url: me.url + '-AddSub',
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: form.serialize({
                            checkboxesAsBools: true
                        }),
                        success: function (data) {
                            switch (data.success) {
                                case 'COMPLETE':
                                    $('.modal').modal('hide');
                                    alertify.success(data.msg);
                                    me.LoadCbo('tree', 'getcategory', 'category_id', 'category_name');
                                    // $('#btnsearchsubmit').click();
                                    // me.table.clear().draw();

                                    break;
                                default:
                                    alertify.error(data.msg);
                                    break;
                            }
                        }
                    });
                },
                function () {
                    alertify.error('Cancel Add');
                });

        });

    }).click();
};

me.EditSub = function () {
    $('#btnsubmitedit').click(function (e) {
        e.stopPropagation();
        $('form#frm_editcategory').submit(function () {
            var form = $(this);
            $('.modal').modal('hide');
            alertify.confirm("Do you want Edit.",
                function () {
                    $.ajax({
                        url: me.url + '-EditSub',
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: form.serialize({
                            checkboxesAsBools: true
                        }),
                        success: function (data) {
                            switch (data.success) {
                                case 'COMPLETE':
                                    $('.modal').modal('hide');
                                    alertify.success(data.msg);
                                    me.LoadCbo('tree', 'getcategory', 'category_id', 'category_name');
                                    // $('#btnsearchsubmit').click();
                                    // me.table.clear().draw();

                                    break;
                                default:
                                    alertify.error(data.msg);
                                    break;
                            }
                        }
                    });
                },
                function () {
                    alertify.error('Cancel Add');
                });

        });

    }).click();
};

me.AddSentense = function () {
    $('#btnsubmitaddsentense').click(function (e) {
        e.stopPropagation();
        // if($('#variation-variation_text').attr('required') == 'required'){
        //     if(!$('#variation-variation_text').val()){
        //         $('#variation-variation_text').tagsinput('focus');
        //         return false;
        //     }
        // }
        $('form#frm_addsentenseedit').submit(function () {
            var form = $(this);
            $('.modal').modal('hide');
            alertify.confirm("Do you want Add New Sentense.",
                function () {
                    $.ajax({
                        url: me.url + '-AddSentense',
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: form.serialize({
                            checkboxesAsBools: true
                        }),
                        success: function (data) {
                            switch (data.success) {
                                case 'COMPLETE':
                                    $('.modal').modal('hide');
                                    alertify.success(data.msg);
                                    me.tablesentence.draw(true);
                                    // $('#btnsearchsubmit').click();
                                    // me.table.clear().draw();
                                    // me.LoadData(me.action.menu, me.category_id, 1, 30, 1);
                                    break;
                                default:
                                    alertify.error(data.msg);
                                    break;
                            }
                        }
                    });
                },
                function () {
                    alertify.error('Cancel Add Sentense');
                });

        });

    }).click();
};

me.Enable = function (e) {
    var code = $(e).attr('data-code');
    var active = $(e).attr('data-type');
    var typename = ['Inactive', 'Active'];
    $('.modal').modal('hide');
    alertify.confirm("Do you want " + typename[active],
        function () {
            $.ajax({
                url: me.url + '-Enable',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {intent_id: code, active: active, subintent_id: '', menu_action: 'updateIntentActiveStatus'},
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE':
                            $('.modal').modal('hide');
                            alertify.success(data.msg);
                            me.table.draw(true);
                            // me.LoadData(me.action.menu, me.code, 1, 30, 1);
                            break;
                        default:
                            alertify.error(data.msg);
                            break;
                    }
                }
            });
        },
        function () {
            alertify.error('Cancel Active');
        });
};

me.EnableSub = function (e) {
    var code = $(e).attr('data-code');
    var subcode = $(e).attr('data-subcode');
    var active = $(e).attr('data-type');
    var typename = ['Inactive', 'Active'];
    $('.modal').modal('hide');
    alertify.confirm("Do you want " + typename[active],
        function () {
            $.ajax({
                url: me.url + '-Enable',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {intent_id: code, active: active, subintent_id: subcode, menu_action: 'updateIntentActiveStatus'},
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE':
                            $('.modal').modal('hide');
                            alertify.success(data.msg);
                            me.table.draw(true);

                            break;
                        default:
                            alertify.error(data.msg);
                            break;
                    }
                }
            });
        },
        function () {
            alertify.error('Cancel Active');
        });
};

me.OpenPopup = function () {
    var cloneCount = $('div.subintentsub').length;
    var cloneCount2 = $('input[name="subintent-active"]').length;
    var maininput = me.variation;
    console.log(maininput);
    var mapObj = {
        'dvsubintent': "dvsubintent",
        'msubintent-subintent_tag': "msubintent-subintent_tag",
        'msubintent-type': "msubintent-type",
        'msubintent-active': "msubintent-active",
        'zero': "",
    };
    maininput = maininput[0].outerHTML.replace(/dvsubintent|msubintent-subintent_tag|msubintent-type|msubintent-active|zero/g, function (matched) {
        return mapObj[matched] + cloneCount;
    });

    if (cloneCount == 0) {

        $('div[id=subintent]').append(maininput);
    } else {
        $('div[id^=dvsubintent]').last().after(maininput);

    }
    // console.log('after');
    // console.log(maininput);
    // $("#mvariation-variation_text"+cloneCount).tagsinput({
    //     trimValue: true
    // });

    $('#dvsubintent' + cloneCount + ' input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        labelHover: true,
        increaseArea: '20%' // optional
    });
    $('#dvsubintent' + cloneCount + ' input[type="checkbox"]').val(1);
    $('#dvsubintent' + cloneCount + ' input[type="checkbox"]').iCheck('check');

};

me.OpenPopupItem = function (data) {
    var cloneCount = $('div.subintentsub').length;
    var cloneCount2 = $('input[name="variation-active"]').length;
    var maininput = me.variation;
    console.log(data);
    var mapObj = {
        'dvsubintent': "dvsubintent",
        'msubintent-subintent_id': "msubintent-subintent_id",
        'msubintent-subintent_tag': "msubintent-subintent_tag",
        'msubintent-type': "msubintent-type",
        'msubintent-active': "msubintent-active",
        'zero': "",
    };
    maininput = maininput[0].outerHTML.replace(/dvsubintent|msubintent-subintent_id|msubintent-subintent_tag|msubintent-type|msubintent-active|zero/g, function (matched) {
        return mapObj[matched] + cloneCount;
    });

    if (cloneCount == 0) {

        $('div[id=subintent]').append(maininput);
    } else {
        $('div[id^=dvsubintent]').last().after(maininput);

    }
    // console.log('after');
    // console.log(maininput);

    if (data.intent_type_name == 'Static') {
        data.type = 1
    } else if (data.intent_type_name == 'Robust') {
        data.type = 2
    }

    $('#msubintent-subintent_id' + cloneCount).val(data.sub_intent_id);
    $('#msubintent-type' + cloneCount).val(data.type);
    $('#msubintent-subintent_tag' + cloneCount).val(data.sub_intent_tag);
    $('#msubintent-active' + cloneCount).val(data.active);
    if (data.active == 1) {
        $('#msubintent-active' + cloneCount).iCheck('check');
    }

    // $("#mvariation-variation_text"+cloneCount).tagsinput({
    //     trimValue: true
    // });

    $('#dvsubintent' + cloneCount + ' input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        labelHover: true,
        increaseArea: '20%' // optional
    });


};

me.NewCat = function () {
    $('#frm_addcategory input[name="parentcategory_id"]').val('');
    $('#frm_addcategory input[name="category_id"]').val('');
    $('#add-modal-form').modal({
        backdrop: 'static',
        keyboard: true,
        show: true,
        handleUpdate: true
    });
};

me.New = function () {
    if (me.category_id == '') return false;

    me.ClearData();
    $('.btn_edit').hide();
    $('.btn_add').show();
    $('#frm_addedit input[name="menu_action"]').val(me.action.add);
    $('#frm_addedit input[name="category_id"]').val(me.category_id);
    $('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
};

me.NewSentense = function () {
    if (me.category_id == '') return false;

    me.ClearData();
    $('.btn_edit').hide();
    $('.btn_add').show();
    $('#frm_addsentenseedit input[name="category_id"]').val(me.category_id);
    $('#frm_addsentenseedit input[name="intent_id"]').val(me.intent_id);
    $('#frm_addsentenseedit input[name="subintent_id"]').val(me.subintent_id);
    $('#frm_addsentenseedit input[type="checkbox"]').val(1);
    $('#frm_addsentenseedit input[type="checkbox"]').iCheck('check');
    $('#addsentense-modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
};

me.ClearData = function () {
    $('input[name="subintent-active"]').iCheck('destroy');
    $('#frm_addedit input').val('');
    $('#frm_addedit select option:eq(0)').prop("selected", true);
    $('#frm_addedit textarea').val('');
    $('#frm_addedit input[type="checkbox"]').iCheck('uncheck');
    $('#frm_addedit input[type="checkbox"].active').val(1);
    $('#frm_addedit input[type="checkbox"].active').iCheck('check');
    $('div#subintent').html('');

    // $('#frm_addedit .sub').css('display','');
    // me.DelStar('variation-concept_result');
    // me.DelStar('variation-variation_text');
    // $('#variation-concept_result').attr('required',false);
    // $('#variation-variation_text').attr('required',false);

};

me.RemoveSub = function (e) {
    var code = $(e).attr('data-code');
    $('#' + code).remove();
};

me.Load = function (e) {
    me.ClearData();
    var code = $(e).attr('data-code');
    var attr = JSON.parse($(e).attr('data-item'));
    console.log(attr);
    var result = [];

    for (var i in attr)
        result.push({name: i, value: attr [i]});

    ft.PutFormID('frm_addedit', result);
    $('#frm_addedit input[name="code"]').val(code);
    $('#frm_addedit input[name="category_id"]').val(me.category_id);
    $('#frm_addedit input[name="intent_id"]').val(attr.intent_id);
    $('#frm_addedit input[name="menu_action"]').val(me.action.edit);
    $.each(attr.variation, function (i, result) {
        me.OpenPopupItem(result);
    });
    $('.btn_edit').show();
    $('.btn_add').hide();
    $('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

};

me.Del = function (e) {
    var code = $(e).attr('data-code');
    var attr = JSON.parse($(e).attr('data-item'));
    alertify.confirm("Do you want Delete.",
        function () {
            $.ajax({
                url: me.url + '-Del',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {'code': code, 'menu_action': 'deleteintent', 'main': 'intent_id', 'category_id': me.category_id},
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE':
                            $('.modal').modal('hide');
                            alertify.success(data.msg);
                            // $('#btnsearchsubmit').click();
                            me.table.row('#' + attr.DT_RowId).remove().draw();

                            // me.LoadData(me.action.menu, me.category_id, 1, 30, 1);
                            break;
                        default:
                            alertify.error(data.msg);
                            break;
                    }
                }
            });
        },
        function () {
            alertify.error('Cancel Delete');
        });
};

me.LoadSentence = function (e) {
    var intent_id = $(e).attr('data-intent_id');
    var subintent_id = $(e).attr('data-subintent_id');
    // var attr = JSON.parse($(e).attr('data-item'));
    me.intent_id = intent_id;
    me.subintent_id = subintent_id;
    me.LoadDataSub('getsentencebyintent', me.category_id, me.intent_id, (subintent_id ? subintent_id : 0), 1, 30);
    $('#btnadd').css('display', 'none');
    $('#btnaddsentense').css('display', '');
};
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function () {
    me.SetUrl();
    // me.CheckBox();
    // me.SetDateTime();
    // me.LoadData(me.action.menu,1,30);
    me.LoadCbo('tree', 'getcategory', 'category_id', 'category_name');
    $("#sentence_process").tagsinput({
        trimValue: true
    });
    // me.LoadCbo('role_id','getroles','role_id','role_name');
});