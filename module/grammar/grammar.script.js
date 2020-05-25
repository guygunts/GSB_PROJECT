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
me.action.add = 'addfunction';
me.action.edit = 'updatefunction';
me.action.del = 'deletefunction';
me.variation = $('div#dvvariation').clone();
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
                                me.code = data.id;
                                if (!data.nodes) {
                                    me.LoadCboSub('tree', 'getsubcategory', data.id, data.index);
                                } else {
                                    $('#' + val).treeview('removeNode', [data.nodes, {silent: true}]);
                                    me.LoadCboSub('tree', 'getsubcategory', data.id, data.index);
                                }
                                me.LoadData(me.action.menu, data.id, 1, 30);
                            } else if (data.level == 2) {

                            }

                        },
                        onAddButtonClicked: function (event, node) {
                            console.log(event)
                            console.log(node)
                            if (node.level == 1) {
                                $('#frm_addcategory input[name="parentcategory_id"]').val('');
                                $('#add-modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
                            }else if(node.level == 2){
                                $('#frm_addcategory input[name="category_id"]').val(node.main);
                                $('#frm_addcategory input[name="parentcategory_id"]').val(node.id);
                                $('#add-modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

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
                    if (data.data.length == 0) {
                        alertify.alert('No data, Please select other date');
                    }
                    if (readd) {
                        // me.table.clear();
                        // var dataold = me.table.rows().data();
                        me.applyData(me.table, data.data, false);
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

me.format = function (rowData) {
    return '<div class="col-md-10" style="margin: 0 auto;float: none;padding: 10px;"><table id="' + rowData.name.replace(' ', '-') + '" class="table table-yellow table-bordered table-striped table-condensed dataTable" style="width: 100%;"></table></div>';
}

me.AddSub = function () {
    $('#btnsubmitadd').click(function (e) {
        e.stopPropagation();
        $('form#frm_addcategory').submit(function () {
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

me.Enable = function (e) {
    var code = $(e).attr('data-code');
    var active = $(e).attr('data-type');
    var typename = ['Inactive','Active'];
    $('.modal').modal('hide');
    alertify.confirm("Do you want "+typename[active],
        function () {
            $.ajax({
                url: me.url + '-Enable',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: { intent_id : code , active : active , subintent_id : '' , menu_action : 'updateIntentActiveStatus'},
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE':
                            $('.modal').modal('hide');
                            alertify.success(data.msg);
                            me.LoadData(me.action.menu, me.code, 1, 30,1);
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

me.OpenPopup = function(){
    var cloneCount = $('div.variationsub').length;
    var cloneCount2 = $('input[name="variation-active"]').length;
    var maininput = me.variation;
    console.log(maininput);
    var mapObj = {
        'dvsubintent':"dvsubintent",
        'msubintent-subintent_tag':"msubintent-subintent_tag",
        'msubintent-type':"msubintent-type",
        'msubintent-active':"msubintent-active",
        'zero':"",
    };
    maininput = maininput[0].outerHTML.replace(/dvsubintent|msubintent-subintent_tag|msubintent-type|msubintent-active|zero|mconcept_variation_id/g, function(matched){
        return mapObj[matched]+cloneCount;
    });

    if(cloneCount == 0){

        $('div[id=variation]').append(maininput);
    }else{
        $('div[id^=dvsubintent]').last().after(maininput);

    }
    // console.log('after');
    // console.log(maininput);
    // $("#mvariation-variation_text"+cloneCount).tagsinput({
    //     trimValue: true
    // });

    $('#dvsubintent'+cloneCount+' input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        labelHover: true,
        increaseArea: '20%' // optional
    });
    $('#dvsubintent'+cloneCount+' input[type="checkbox"]').val(1);
    $('#dvsubintent'+cloneCount+' input[type="checkbox"]').iCheck('check');

};

me.New = function () {
    if(me.code == '')return false;

    me.ClearData();
    $('.btn_edit').hide();
    $('.btn_add').show();
    $('#frm_addedit input[name="menu_action"]').val(me.action.add);
    $('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
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
    // me.LoadCbo('role_id','getroles','role_id','role_name');
});