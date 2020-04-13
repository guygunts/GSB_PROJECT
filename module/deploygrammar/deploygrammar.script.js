/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'user_id';
me.action.menu = 'getdatadeploy';
me.action.add = 'adddatadeploy';
me.action.edit = 'deployactive';
me.action.del = 'submitdatadeploy';

/*================================================*\
  :: FUNCTION ::
\*================================================*/
me.SetDateTime = function () {
    $('#start_date').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: moment()
    });
    $('#end_date').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: moment()
    });


};

me.Search = function () {
    $('form#frmsearch').submit(function () {
        me.loading = true;
        $('#frmresult').css('display', 'none');
        var page_size = $('#page_size').val();
        var compare = $('#compare').val();
        var txtsearch = $('#text_search').val();
        var start = $('#start_date').data().date;
        var stop = $('#end_date').data().date;
        var cnt = 0;

        if (start !== undefined) {
            ++cnt;
        }
        if (stop !== undefined) {
            ++cnt;
        }

        if (cnt != 2) return false;
        // me.table.clear().destroy();
        // $('#tbView').empty();
        me.table.clear();
        me.LoadDataReport(me.action.menu, 1, page_size, start + ' 00:00:00', stop + ' 23:59:59', compare, txtsearch,1);
    });

};


me.LoadDataReport = function (menu, page_id, page_size, start, stop, compare = '', search = '', readd = '') {

    $.ajax({
        url: me.url + '-View',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {
            menu_action: menu,
            page_id: page_id,
            page_size: 10000,
            start_date: start,
            end_date: stop,
            compare: compare,
            text_search: search
        },
        success: function (data) {
            var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {

                            if (column === 8) {
                                data = '';
                            }
                            return data;

                        }
                    }
                }
            };

            switch (data.success) {
                case 'COMPLETE' :
                    if(data.data.length == 0){
                        alertify.alert('No data, Please select other date');
                    }
                    if (readd) {
                        me.applyData(me.table,data.data,false);
                        // me.table.clear().draw();
                        // me.table.rows.add(data.data).draw();

                    } else {
                        me.table = $('#tbView')
                            .addClass('nowrap')
                            .removeAttr('width')
                            .DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    // 'excelHtml5',
                                    $.extend(true, {}, buttonCommon, {
                                        text: 'ย้อนกลับ',
                                        className: 'float-left hidden',
                                        attr: {
                                            title: 'Copy',
                                            id: 'btnback',
                                            disabled: 'disabled'
                                        }
                                    }),
                                    $.extend(true, {}, buttonCommon, {
                                        extend: 'print',
                                        orientation: 'landscape',
                                        pageSize: 'LEGAL',
                                        className: 'float-right',
                                    }),
                                    $.extend(true, {}, buttonCommon, {
                                        extend: 'excelHtml5',
                                        text: 'Excel',
                                        className: 'float-right'
                                    }),
                                    $.extend(true, {}, buttonCommon, {
                                        extend: 'csvHtml5',
                                        text: 'CSV',
                                        className: 'float-right'
                                    }),
                                    $.extend(true, {}, buttonCommon, {
                                        extend: 'pdfHtml5',
                                        orientation: 'landscape',
                                        pageSize: 'LEGAL',
                                        className: 'float-right'
                                    })
                                ],
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
                                    }
                                ],
                                searching: false,
                                retrieve: true,
                                deferRender: true,
                                stateSave: true,
                                iDisplayLength: page_size,
                                responsive: false,
                                scrollX: true,
                                pageLength: page_size,
                                paging: true,
                                lengthChange: false,
                                data: data.data,
                                columns: data.columns
                            });

                    }
                    me.table.page.len(page_size).draw();
                    me.table.columns.adjust().draw('true');

                    me.table.buttons(0, null).container().addClass('col');

                    if (data.name) {
                        $('title').text(data.name);
                    }


                    $('a.toggle-vis').on('click', function (e) {
                        e.preventDefault();

                        // Get the column API object
                        var column = me.table.column($(this).attr('data-column'));

                        // Toggle the visibility
                        column.visible(!column.visible());
                    });


                    break;
                default :
                    alertify.alert(data.msg);
                    break;
            }
        }
    });
};


me.LoadDataCHNN = function (menu, page_id, page_size, start, stop, readd = '') {

    $.ajax({
        url: me.url + '-ViewCHNN',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {menu_action: menu, page_id: page_id, page_size: 10000, start_date: start, end_date: stop},
        success: function (data) {
            switch (data.success) {
                case 'COMPLETE' :
                    if (readd) {
                        me.table.clear().draw();
                        me.table.rows.add(data.data).draw();

                    } else {

                        me.table = $('#tbView')
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
                                            $('#btnsearchsubmit').click();
                                        }
                                    },

                                    {
                                        extend: 'print',
                                        orientation: 'landscape',
                                        pageSize: 'LEGAL',
                                        className: 'float-right'
                                    },
                                    {
                                        extend: 'excelHtml5',
                                        text: 'Excel',
                                        className: 'float-right'
                                    },
                                    {
                                        extend: 'csvHtml5',
                                        text: 'CSV',
                                        className: 'float-right'
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        orientation: 'landscape',
                                        pageSize: 'LEGAL',
                                        className: 'float-right'
                                    },
                                ],
                                columnDefs: [
                                    {
                                        "width": "5%",
                                        "targets": 0,
                                        "searchable": false
                                    }
                                ],
                                searching: false,
                                retrieve: true,
                                deferRender: true,
                                stateSave: true,
                                iDisplayLength: page_size,
                                responsive: false,
                                scrollX: true,
                                pageLength: page_size,
                                paging: true,
                                lengthChange: false,
                                data: data.data,
                                columns: data.columns
                            });

                    }


                    me.table.columns.adjust().draw('true');

                    me.table.buttons(0, null).container().addClass('col');


                    if (data.name) {
                        $('title').text(data.name);
                    }

                    me.LoadCbo('grammar', data.grammar);
                    me.LoadCbo('confiden', data.confiden);
                    me.LoadCbo('intent', data.intent);

                    $('a.toggle-vis').on('click', function (e) {
                        e.preventDefault();

                        // Get the column API object
                        var column = me.table.column($(this).attr('data-column'));

                        // Toggle the visibility
                        column.visible(!column.visible());
                    });

                    break;
                default :
                    alertify.alert(data.msg);
                    break;
            }
        }
    });
};

me.LoadCbo = function (val, data) {
    $("#" + val + ' option').remove();
    $("<option>")
        .attr("value", '')
        .text('== ' + val.toUpperCase() + ' ==')
        .appendTo("#" + val);
    $.each(data, function (i, result) {
        $("<option>")
            .attr("value", result.code)
            .text(result.name)
            .appendTo("#" + val);
    });

};

me.OpenVOICE = function (code) {
    alertify.alert('<audio preload="auto" autobuffer controls><source src="' + code + '" type="audio/wav"></audio>');

};

me.OpenCHNN = function (code, page_id, page_size, start, stop) {
    me.table.clear().destroy();
    $('#tbView').empty();
    me.LoadDataCHNN(code, page_id, page_size, start, stop);

};

me.OpenPopup = function (code, method, myvalue = '') {

    alertify.prompt('Add ' + method, '', myvalue, function (evt, value) {
            if (!value) {
                $('.' + code).val('');
                $('#' + code).html('<i class="glyphicon glyphicon-edit"></i>');
            } else {
                $('.' + code).val(value);
                $('#' + code).text(value);
            }

            alertify.success('You entered: ' + value)

        }
        , function () {
            alertify.error('Cancel Add ' + method)
        });
};

me.QcStatus = function () {
    $('#frmsearch select').on('change', function () {
        $('#btnsearchsubmit').click();
    })
};

me.ChangeTop = function () {
    $('#top_project_id').on('change', function () {
        $.ajax({
            url: 'api.inc.php?mode=3065C7AFA89118D8B3CCF100573553DE',
            type: "POST",
            dataType: "json",
            cache: false,
            data: {code: $(this).val()},
            success: function (data) {
                var page_size = $('#page_size').val();
                var confiden = $('#confiden').val();
                var grammar = $('#grammar').val();
                var qc_status = $('#qc_status').val();
                var random_num = $('#random_num').val();
                var text_search = $('#text_search').val();
                var intent = $('#intent').val();
                var start = $('#start_date').data().date;
                var stop = $('#end_date').data().date;
                me.table.clear().destroy();
                $('#tbView').empty();
                me.LoadDataReport(me.action.menu, 1, page_size, start + ' 00:00:00', stop + ' 23:59:59', random_num, qc_status, grammar, intent, confiden, text_search);
            }
        });
    })
};

me.UpdateVoice = function (e) {
    var code = $(e).attr('data-code');
    if (!me.CheckFormClass('empty' + code)) {

        setTimeout('me.ClearError();', 5000);
        return;
    }

    var myData = {
        data: ft.LoadForm('row' + code)
    }

    myData.data.rec_id = code;

    console.log(myData);

    $('.modal').modal('hide');
    alertify.confirm("Do you want Update.",
        function () {
            $.ajax({
                url: me.url + '-Add',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: myData.data,
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE':
                            $('.modal').modal('hide');
                            alertify.success(data.msg);
                            $('input[name="pass"]').prop('checked', false);
                            $('#result').val('');
                            break;
                        default:
                            alertify.error(data.msg);
                            break;
                    }
                }
            });
        },
        function () {
            alertify.error('Cancel Update');
        });
};

me.UpdateBtn = function (e) {


    var pro = 0;
    var pre = 0;
    var code = $(e).attr('data-code');
    var thisval = $(e).attr('data-this');
    var val = $(e).attr('data-val');
    var attr = JSON.parse($(e).attr('data-item'));
    var curdate = attr.schedule_deploy;
    if (code == 'pro') {
        pro = 1;
        pre = val;
    } else if (code == 'pre') {
        pre = 1;
        pro = val;
    }

    var myData = {
        menu_action: me.action.edit,
        build_version: attr.build_version,
        deploy_desc: attr.deploy_desc,
        pre_active: pre,
        pro_active: pro,
        type: code
    };

    $('.modal').modal('hide');

    alertify.promptnew('Before Change Please Select Date and Time','Start Date : ','',
        function (evt,value) {
            if (value) {

                myData.startdate = value;

                $.ajax({
                    url: me.url + '-Edit',
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: myData,
                    success: function (data) {
                        switch (data.success) {

                            case 'COMPLETE':
                                $('.modal').modal('hide');
                                alertify.success(data.msg);
                                $('#btnsearchsubmit').click();
                                break;
                            default:
                                alertify.error(data.msg);
                                break;
                        }
                    }
                });
            }else{
                alertify.error('No Date and Time');
            }
        },
        function () {
            alertify.error('Cancel Change');
        });

    if(thisval == 2){
            $('#startdate').datetimepicker('date', moment(curdate).format("YYYY-MM-DD HH:mm:ss"));
    }else{
        $('#startdate').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true,
            minDate: moment().format("YYYY-MM-DD HH:mm:ss")
        });
    }


};

me.New = function () {
    me.ClearData();
    $('.btn_edit').hide();
    $('.btn_add').show();
    $('#frm_addedit input[name="menu_action"]').val(me.action.add);
    if(me.tablesub.length == 0){
        me.tablesub.clear().destroy();
        $('#tbViewSub').empty();
        me.LoadDataSub(me.action.add, 1, 25);
    }else{
        me.LoadDataSub(me.action.add, 1, 25);
    }
    $('#tbViewSub_filter').find('input[type="search"]').val('');
    $('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

};

me.LoadDataSub = function (menu, page_id, page_size, readd = '') {

    $.ajax({
        url: me.url + '-ViewSub',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {menu_action: menu, page_id: page_id, page_size: page_size},
        success: function (data) {
            switch (data.success) {
                case 'COMPLETE' :
                    if (readd) {
                        // me.table.clear().draw();
                        me.tablesub.rows.add(data.data).draw();
                    } else {
                            me.tablesub = $('#tbViewSub')
                            .addClass('nowrap')
                            .removeAttr('width')
                            .DataTable({
                                searching: true,
                                paging: false,
                                columnDefs: [
                                    {
                                        "width": "5%",
                                        "targets": 0,
                                        "searchable": false
                                    }
                                ],
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

                    me.tablesub.columns.adjust().draw('true');
                    me.CheckSelect();
                    break;
                default :
                    alertify.alert(data.msg);
                    break;
            }
        }
    });
};

me.CheckSelect = function () {
    $('input[name="build_version"]').on('click', function () {
        var val = $(this).is(':checked');
        if (val) {
            $('input[name="build_version"]').prop('checked', false);
            $(this).prop('checked', true);
        }
    })

    $('#tbViewSub tbody').on( 'click', 'tr', function () {
        $('input[name="build_version"]').prop('checked', false);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
            $('input[name="build_version"]',this).prop('checked', false);
        }
        else {
            me.tablesub.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('input[name="build_version"]',this).prop('checked', true);
        }
    } );
};

me.Add = function () {

    var myData = {};

    $('input[name="build_version"]').each(function (i) {
        var val = $(this).is(':checked');
        var name = $(this).attr('ref');
        if (val) {
            myData = {
                'menu_action': me.action.del,
                'build_version': name
            };

        }

    });

    console.log(myData);

    $('.modal').modal('hide');
    alertify.confirm("Do you want Submit.",
        function () {
            $.ajax({
                url: me.url + '-Add',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: myData,
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE':
                            $('.modal').modal('hide');
                            alertify.success(data.msg);
                            $('#btnsearchsubmit').click();
                            break;
                        default:
                            alertify.error(data.msg);
                            break;
                    }
                }
            });
        },
        function () {
            alertify.error('Cancel Submit');
        });
};



/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function () {
    me.SetUrl();
    // me.HideMenu();
    me.SetDateTime();
    me.Search();


    // $('#btnsearchsubmit').click();
    // me.LoadDataReport(me.action.menu,1,25,'','','1','');
    // me.LoadCbo('grammar','grammar','grammar_id','grammar_name');
    // me.LoadCbo('confiden','confiden','conf_id','conf_name');
    // me.LoadCbo('intent','intent','intent_id','intent_tag');
    me.LoadDataReport(me.action.menu, 1, 25, moment().format("YYYY-MM-DD") + ' 00:00:00', moment().format("YYYY-MM-DD") + ' 23:59:59', '', '');

});