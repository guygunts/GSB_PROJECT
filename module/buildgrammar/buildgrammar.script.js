/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'project_id';
me.action.menu = 'getdatagrammar';
me.action.add = 'addgrammar';
me.action.edit = 'updateuser';
me.action.del = 'deletegrammar';
var date1 = '';
var date2 = '';
var buttonCommon = {
    exportOptions: {
        format: {
            body: function (data, row, column, node) {

                if (column === 13) {
                    data = '';
                }
                return data;

            }
        }
    }
};
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

        me.table.page.len(page_size).draw();
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
            page_size: page_size,
            start_date: start,
            end_date: stop,
            text_search: search
        },
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
                            buttons: [
                                // $.extend(true, {}, buttonCommon, {
                                //     extend: 'colvis',
                                //     columnText: function (dt, idx, title) {
                                //         // return (idx + 1) + ': ' + (title ? title : 'Action');
                                //         if (idx == 0) {
                                //             return (idx + 1) + ': Variation';
                                //         } else {
                                //             return (idx + 1) + ': ' + (title ? title : 'Action');
                                //         }
                                //     }
                                // }),

                                $.extend(true, {}, buttonCommon, {
                                    extend: 'print',
                                    orientation: 'landscape',
                                    pageSize: 'LEGAL',
                                    className: 'float-right'
                                }),
                                $.extend(true, {}, buttonCommon, {
                                    extend: 'excelHtml5',
                                    text: 'Excel',
                                    className: 'float-right',
                                    charset: 'utf-8',
                                    bom: true
                                }),
                                $.extend(true, {}, buttonCommon, {
                                    extend: 'csvHtml5',
                                    text: 'CSV',
                                    className: 'float-right',
                                    charset: 'utf-8',
                                    bom: true
                                }),
                                $.extend(true, {}, buttonCommon, {
                                    extend: 'pdfHtml5',
                                    orientation: 'landscape',
                                    pageSize: 'LEGAL',
                                    className: 'float-right',
                                    customize: function (doc) {
                                        doc.defaultStyle = {
                                            font: 'THSarabunNew',
                                            fontSize: 12
                                        };
                                    }
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
                                    "targets": 1,
                                    "searchable": false
                                },
                                {
                                    "targets": '_all',
                                    'createdCell': function (td, cellData, rowData, row, col) {
                                        $(td).attr('id', row + col);
                                    }
                                }
                            ],
                            createdRow: function (row, data, dataIndex) {
                                // Set the data-status attribute, and add a class
                                $(row).find('td:eq(0)')
                                    .attr('data-name', data.variation);
                            },
                            searching: false,
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
                                    d.page_size = $('#page_size').val();
                                    d.start_date = $('#start_date').data().date;
                                    d.end_date = $('#end_date').data().date;
                                    d.text_search = $('#text_search').val();
                                    d.menu_action = $('#menu_action').val();
                                }
                            }
                        });

                    me.table.column(1).visible(false);
                    me.table.buttons(0, null).container().addClass('col');

                    if (data.data.length == 0) {
                        // alertify.alert('No data, Please select other date');
                    }

                    if (data.name) {
                        $('title').text(data.name);
                    }

                    break;
                default :
                    alertify.alert(data.msg);
                    break;
            }

        }
    });
};


me.LoadDataReport_ = function (menu, page_id, page_size, start, stop, compare = '', search = '', readd = '') {

    $.ajax({
        url: me.url + '-View',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {
            menu_action: menu,
            page_id: page_id,
            page_size: 5,
            start_date: start,
            end_date: stop,
            compare: compare,
            text_search: search
        },
        success: function (data) {


            switch (data.success) {
                case 'COMPLETE' :
                    if (data.data.length == 0) {
                        // alertify.alert('No data, Please select other date');
                    }
                    if (readd) {
                        me.applyData(me.table, data.data, false);
                        // me.table.clear().draw();
                        // me.table.rows.add(data.data).draw();
                        // me.table.rows().invalidate().draw();
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
                                        className: 'float-right',
                                        customize: function (doc) {
                                            doc.defaultStyle = {
                                                font: 'THSarabunNew',
                                                fontSize: 16
                                            };
                                        }
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
                                start: page_id,
                                recordsTotal: data.recnums,
                                recordsFiltered: data.recnums,
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

me.Build = function (e) {
    me.ClearDataID('frm_buildgrammar');
    var code = $(e).attr('data-code');
    var attr = JSON.parse($(e).attr('data-item'));
    $('#frm_buildgrammar input[name="project_id"]').val(code);
    $('#frm_buildgrammar input[name="file_name"]').val(attr.file_name);

    $('.btn_edit').hide();
    $('.btn_add').show();
    $('#buildgrammar').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
};

me.Process = function (e) {

    var code = $(e).attr('data-code');
    var attr = JSON.parse($(e).attr('data-item'));
    var myData = {
        'project_id': attr.project_id,
        'file_name': attr.file_name
    };
    $('.modal').modal('hide');
    alertify.confirm("Do you want Process.",
        function () {
            $.ajax({
                url: me.url + '-Process',
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
            alertify.error('Cancel Process');
        });

};

me.AddGrammar = function () {
    $('#btnsubmitgrammar').click(function (e) {
        e.stopPropagation();
        $('form#frm_buildgrammar').submit(function () {
            var form = $(this);
            $('.modal').modal('hide');
            alertify.confirm("Do you want Add.",
                function () {
                    $.ajax({
                        url: me.url + '-AddGrammar',
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
                                    // me.table.rows().invalidate().draw();
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
                    alertify.error('Cancel Add');
                });

        });

    }).click();
};

me.Add = function () {
    $('#btnsubmit').click(function (e) {
        e.stopPropagation();
        $('form#frm_addedit').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(document.forms.namedItem("frm_addedit"));

            $('.modal').modal('hide');
            alertify.confirm("Do you want Add.",
                function () {
                    alertify.confirm().set('message', 'Loading... กำลังดำเนินการ');

                    me.AddData(formData);
                },
                function () {
                    alertify.error('Cancel Add');
                });

        });

    }).click();
};

me.AddData = function (formData) {
    $.ajax({
        url: me.url + '-Add',
        type: 'POST',
        dataType: 'json',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            switch (data.code) {
                case 200:
                    $('.modal').modal('hide');
                    alertify.success('Upload Success');
                    $('#btnsearchsubmit').click();
                    break;
                default:
                    alertify.error('Upload Fail');
                    break;
            }
        }
    });
};

me.Del = function (code) {
    alertify.confirm("Do you want Delete.",
        function () {
            $.ajax({
                url: me.url + '-Del',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {'code': code, 'menu_action': me.action.del, 'main': me.action.main},
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
            alertify.error('Cancel Delete');
        });
};

me.Checkfile = function () {
    $('input:file').on('change', function () {
        var len = $(this).length;
        if ($(this).val()) {
            var validExts = new Array(".xlsx");
            var fileExt = $(this).val();
            fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
            if (validExts.indexOf(fileExt) < 0) {
                alert("Invalid file selected, valid files are of " +
                    validExts.toString() + " types.");
                $(this).val('');
                return false;
            } else return true;
        }
    })
};

me.AutoReload = function () {

    me.loading = false;
    var myData = [];
    myData = ft.LoadForm('searchdata');
    myData.start_date = $('#start_date').data().date + ' 00:00:00';
    myData.end_date = $('#end_date').data().date + ' 23:59:59';
    myData.page_id = 1;
    myData.page_size = 10000;
    myData.menu_action = me.action.menu;

    $.ajax({
        url: me.url + '-View',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: myData,
        success: function (data) {
            switch (data.success) {
                case 'COMPLETE' :
                    me.applyData(me.table, data.data, false);
                    // me.table.clear().draw();
                    // me.table.rows.add(data.data).draw();
                    // me.table.rows().invalidate().draw();
                    break;
                default :
                    // alertify.alert(data.msg);
                    break;
            }
        }
    });
};

me.Download = function (e) {

    var code = $(e).attr('data-code');
    var name = $(e).attr('data-name');

    $('.modal').modal('hide');
    alertify.confirm("Do you want Download.",
        function () {

            axios({
                url: me.api + '/geniespeech/download/' + code,
                method: 'GET',
                responseType: 'blob', // important
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', name);
                document.body.appendChild(link);
                link.click();
            });
            //
            // $.ajax({
            // 	url: me.api + '/geniespeech/download',
            // 	type: 'GET',
            // 	responseType: 'blob', // important
            // 	cache: false,
            // 	data: { url_patch : code },
            // 	success: function (response) {
            // 		const url = window.URL.createObjectURL(new Blob([response.data]));
            // 		const link = document.createElement('a');
            // 		link.href = url;
            // 		link.setAttribute('download', name);
            // 		document.body.appendChild(link);
            // 		link.click();
            // 	}
            // });
        },
        function () {
            alertify.error('Cancel Download');
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
    me.Checkfile();
    me.AddStar('build_desc');
    // me.AutoReload();
    date1 = $('#start_date').data().date + ' 00:00:00';
    date2 = $('#end_date').data().date + ' 23:59:59';
    me.LoadDataReport(me.action.menu, 1, 25, date1, date2, '', '');
    me.LoadCbo('project_id', 'getprojects', 'project_id', 'project_name');
    // me.LoadCbo('role_id','getroles','role_id','role_name');
    // setInterval(function () {
    //     me.AutoReload();
    // }, 10000);

});