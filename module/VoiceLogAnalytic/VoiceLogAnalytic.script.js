/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'user_id';
me.action.menu = 'SR';
me.action.add = 'adduser';
me.action.edit = 'updateuser';
me.action.del = 'deleteuser';

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
        var confiden = $('#confiden').val();
        var grammar = $('#grammar').val();
        var qc_status = $('#qc_status').val();
        var random_num = 0;
        var text_search = $('#text_search').val();
        var intent = $('#intent').val();
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

        me.LoadDataReport(me.action.menu, 1, page_size, start + ' 00:00:00', stop + ' 23:59:59', random_num, qc_status, grammar, intent, confiden, text_search, 1);
    });

};

me.SearchRandom = function () {
    $('form#frmsearchrandom').submit(function () {
        me.loading = true;
        $('#frmresult').css('display', 'none');
        var page_size = $('#page_size').val();
        var confiden = $('#confiden').val();
        var grammar = $('#grammar').val();
        var qc_status = $('#qc_status').val();
        var random_num = $('#random_num').val();
        var text_search = $('#text_search').val();
        var intent = $('#intent').val();
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

        me.LoadDataReport(me.action.menu, 1, page_size, start + ' 00:00:00', stop + ' 23:59:59', random_num, qc_status, grammar, intent, confiden, text_search, 1);
    });

};

me.LoadDataReport = function (menu, page_id, page_size, start, stop, random_num = 0, qc_status = 0, grammar = '', intent = '', confiden = '', txt_search = '', readd = '') {


    $.ajax({
        url: me.url + '-View',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {
            page_id: page_id,
            page_size: 100000,
            start_date: start,
            end_date: stop,
            random_num: random_num,
            qc_status: qc_status,
            grammar: grammar,
            intent: intent,
            confiden: confiden,
            txt_search: txt_search
        },
        success: function (data) {
            var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {

                            if(column === 4) {
                                data = $(data).attr('href');
                            }else if (column === 6) {
                                console.log('column 6 '+ column + ' node '+ node + ' row : '+row);
                                data = $(data).find('source').attr('src');
                            }else if (column === 12) {
                                if($('option:selected',data).val() != ''){
                                    data = $('option:selected',data).text();
                                }else{
                                    data = '';
                                }
                            }else if (column === 13) {
                                if($('option:selected',data).val() != ''){
                                    data = $('option:selected',data).text();
                                }else{
                                    data = '';
                                }
                            }else if (column === 14 || column === 15) {
                                data = data.toString().replace(/<.*?>/ig, "");
                            }else if (column === 16) {
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
                        me.table.clear().draw();
                        me.table.rows.add(data.data).draw();

                    } else {


                        me.table = $('#tbView')
                            .addClass('nowrap')
                            .removeAttr('width')
                            .DataTable({
                                dom: 'Bfrtip',
                                buttons: [


                                    $.extend( true, {}, buttonCommon, {
                                        extend: 'colvis',
                                        columnText: function (dt, idx, title) {
                                            return (idx + 1) + ': ' + (title ? title : 'Action');
                                        }
                                    } ),
                                    $.extend( true, {}, buttonCommon, {
                                        text: 'ย้อนกลับ',
                                        className: 'float-left hidden',
                                        attr: {
                                            title: 'Copy',
                                            id: 'btnback',
                                            disabled: 'disabled'
                                        }
                                    } ),
                                    $.extend( true, {}, buttonCommon, {
                                        extend: 'print',
                                        orientation: 'landscape',
                                        pageSize: 'LEGAL',
                                        className: 'float-right',
                                        charset: 'utf-8',
                                        bom: true
                                    } ),
                                    $.extend( true, {}, buttonCommon, {
                                        // text: 'Excel',
                                        // className: 'float-right',
                                        // action: function ( e, dt, node, config ) {
                                        //     var start = $('#start_date').data().date;
                                        //     var stop = $('#end_date').data().date;
                                        //     // window.open('module/' + me.mod + '/excel.php?mod='+ me.mod +'&start_date='+ start +'&end_date=' + stop, '_blank');
                                        //     window.location.href = 'module/' + me.mod + '/excel.php?mod='+ me.mod +'&start_date='+ start +'&end_date=' + stop;
                                        //     //window.location.href = 'module/' + me.mod + '/' + me.mod + '.report.php?mod='+ me.mod +'&start_date='+ start +'&end_date=' + stop;
                                        //     // window.location.href = 'module/' + me.mod + '/' + me.mod + '-print/' + start + '/' + stop;
                                        // }
                                        extend: 'excelHtml5',
                                        text: 'Excel',
                                        className: 'float-right',
                                        charset: 'utf-8',
                                        bom: true
                                    } ),
                                    $.extend( true, {}, buttonCommon, {
                                        extend: 'csvHtml5',
                                        text: 'CSV',
                                        className: 'float-right',
                                        charset: 'utf-8',
                                        bom: true
                                    } ),
                                    $.extend( true, {}, buttonCommon, {
                                        extend: 'pdfHtml5',
                                        orientation: 'landscape',
                                        pageSize: 'LEGAL',
                                        className: 'float-right'
                                    } )
                                ],
                                columnDefs: [
                                    {
                                        "width": "5%",
                                        "targets": 0,
                                        "searchable": false
                                    },
                                    {
                                        "targets":'_all',
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                            $(td).attr('id', row+col);
                                        }
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
                    me.table.column(1).visible(false);
                    // me.table.column(3).visible(false);
                    // me.table.column(4).visible(false);

                    me.table.columns.adjust().draw('true');

                    me.table.buttons(0, null).container().addClass('col');

                    if (data.name) {
                        $('title').text(data.name);
                    }
                    if (!readd) {
                        me.LoadCbo('grammar', data.grammar);
                        me.LoadCbo('confiden', data.confiden);
                        me.LoadCbo('intent', data.intent);
                    }

                    $('a.toggle-vis').on('click', function (e) {
                        e.preventDefault();

                        // Get the column API object
                        var column = me.table.column($(this).attr('data-column'));

                        // Toggle the visibility
                        column.visible(!column.visible());
                    });
                    $('.select2').select2();
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
                                        className: 'float-right',
                                        charset: 'utf-8',
                                        bom: true
                                    },
                                    {
                                        extend: 'excelHtml5',
                                        text: 'Excel',
                                        className: 'float-right',
                                        charset: 'utf-8',
                                        bom: true
                                    },
                                    {
                                        extend: 'csvHtml5',
                                        text: 'CSV',
                                        className: 'float-right',
                                        charset: 'utf-8',
                                        bom: true
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        orientation: 'landscape',
                                        pageSize: 'LEGAL',
                                        className: 'float-right',
                                        customize: function ( doc ) {
                                            doc.defaultStyle = {
                                                font:'THSarabunNew',
                                                fontSize:16
                                            };
                                        }
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

me.OpenPopup = function (code, method,myvalue='') {

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

/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function () {
    me.SetUrl();
    // me.HideMenu();
    me.SetDateTime();
    me.Search();
    me.SearchRandom();
    me.QcStatus();
    $('.select2').select2();
    // $('#btnsearchsubmit').click();
    // me.LoadDataReport(me.action.menu,1,25,'','','1','');
    // me.LoadCbo('grammar','grammar','grammar_id','grammar_name');
    // me.LoadCbo('confiden','confiden','conf_id','conf_name');
    // me.LoadCbo('intent','intent','intent_id','intent_tag');
    me.LoadDataReport(me.action.menu, 1, 25, moment().format("YYYY-MM-DD") + ' 00:00:00', moment().format("YYYY-MM-DD") + ' 23:59:59', 0, '', '', '', '');

});