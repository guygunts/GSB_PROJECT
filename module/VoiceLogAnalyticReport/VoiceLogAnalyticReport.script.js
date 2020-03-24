/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'user_id';
me.action.menu = 'QC';
me.action.add = 'adduser';
me.action.edit = 'updateuser';
me.action.del = 'deleteuser';
var ctx  = document.getElementById('pieChart').getContext('2d');
var pieChart = '';
var ctxs = document.getElementById('barChart').getContext('2d');
var barChart = '';
var footerhtml = $('#tbView').clone();
// console.log(footerhtml);
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
        var page_size = $('#page_size').val();
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
        me.table.clear().destroy();
        // $('#tbView').empty();
        pieChart.destroy();
        barChart.destroy();
        me.LoadDataReport(me.action.menu, 1, page_size, start + ' 00:00:00', stop + ' 23:59:59');
    });

};

me.LoadDataReport = function (menu, page_id, page_size, start, stop, readd = '') {

    $.ajax({
        url: me.url + '-View',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {menu_action: menu, page_id: page_id, page_size: 10000, start_date: start, end_date: stop},
        success: function (data) {

            switch (data.success) {

                case 'COMPLETE' :
                    if(data.data.length == 0){
                        alertify.alert('ไม่มีข้อมูล โปรดเลือกช่วงวันอื่น');
                    }
                    var datafooter = data.datafooter;
                    var pipechart = data.pipechart;
                    var barchart = data.barchart;
                    var mytext = data.text;

                    if (readd) {
                        me.table.clear().draw();
                        me.table.rows.add(data.data).draw();

                    } else {


                        me.table = $('#tbView')
                            .addClass('nowrap')
                            .removeAttr('width')
                            .DataTable({


                                    searching: false,
                                    retrieve: true,
                                    deferRender: true,
                                    stateSave: true,
                                    iDisplayLength: page_size,
                                    responsive: false,
                                    scrollX: true,
                                    pageLength: page_size,
                                    paging: false,
                                    lengthChange: false,
                                    bLengthChange: false,
                                    bPaginate: false,
                                    bInfo: false,
                                    data: data.data,
                                    columns: data.columns,
                                    initComplete: function(settings, data) {
                                        // $('#tbView').createTFoot().insertRow(0);
                                        // var footer = $(this).append('<tfoot><tr></tr></tfoot>');
                                        // $('<tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>').appendTo('#tbView');
                                        var api = this.api();
                                        var lastRow = api.rows().count();
                                        if(lastRow>0) {
                                            var footer_data = datafooter ;
                                            console.log(datafooter);
                                            api.columns().every( function (i) {
                                                this.footer().innerHTML = footer_data[i];
                                            });
                                        }
                                    }
                                }
                            );

                    }


                    me.table.columns.adjust().draw('true');

                    if (data.name) {
                        $('title').text(data.name);
                    }
                    Chart.defaults.global.elements.point.borderWidth = 0;
                    Chart.defaults.global.elements.arc.borderWidth = 2;

                    var configs = {

                        type: 'doughnut',
                        data: {
                            labels: pipechart.label,
                            datasets: [{
                                weight : 1,
                                borderWidth : 2,
                                data: pipechart.data,
                                borderColor :  [
                                    'red',
                                    'orange',
                                    'blue',
                                    'green',

                                ],
                                backgroundColor: [
                                    'red',
                                   'orange',
                                    'blue',
                                   'green',

                                ]

                            }]

                        },

                        options: {
                            responsive: true,

                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: pipechart.capture,
                                padding : 30
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },

                            plugins: {
                                datalabels: {
                                    align: 'end',
                                    anchor: 'end',
                                    clamp : true,
                                    offset : 1,

                                    color: function (context) {
                                        return context.dataset.backgroundColor;
                                    },
                                    font: function (context) {
                                        var w = context.chart.width;
                                        return {
                                            size: w < 512 ? 12 : 14
                                        };
                                    },
                                    formatter: function (value, context) {
                                        console.log(context);
                                        return context.chart.data.labels[context.dataIndex]+' '+value;
                                    }
                                }
                            }
                        }
                    };
                    pieChart = new Chart(ctx,configs);
                    pieChart.update();

                    var config = {
                        type: 'bar',
                        data: {
                            datasets: [{
                                data: barchart.data,
                                backgroundColor: [
                                    'red',
                                    'orange',
                                    'blue',
                                    'green',

                                ]
                            }],
                            labels: barchart.label
                        },
                        options: {
                            plugins: {
                                datalabels: {
                                    align: 'end',
                                    anchor: 'end',
                                    color: function(context) {
                                        return context.dataset.backgroundColor;
                                    },
                                    font: function(context) {
                                        var w = context.chart.width;
                                        return {
                                            size: w < 512 ? 12 : 14
                                        };
                                    },
                                    formatter: function(value, context) {
                                        // console.log(value);
                                        return value.y;
                                    }
                                }
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    offset: true
                                }],
                                yAxes: [{
                                    stacked: true,
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            },
                            title: {
                                display: true,
                                text: barchart.capture
                            },
                            legend: {
                                display: false
                            },
                        }
                    };



                    barChart = new Chart(ctxs,config);
                    barChart.update();

                    $('#mydata').html(mytext);


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


me.Export = function(){

    function submitFORM(path, params, method) {
        method = method || "post";

        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);

        //Move the submit function to another variable
        //so that it doesn't get overwritten.
        form._submit_function_ = form.submit;

        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form._submit_function_();
    }

    html2canvas(document.querySelector('#allbox')).then(function(canvas) {
        var start = $('#start_date').data().date;
        var stop = $('#end_date').data().date;
        var dataUrl = canvas.toDataURL();
        var newDataURL = dataUrl.replace(/^data:image\/png/, "data:application/octet-stream"); //do this to clean the url.

        submitFORM('module/' + me.mod + '/excel.php', {img : newDataURL , start_date : start , end_date : stop},'POST');



            // console.log(canvas);
            // saveAs(canvas.toDataURL(), 'VoiceLogAnalyticReport.png');
    });



};

function saveAs(uri, filename) {

    var link = document.createElement('a');

    if (typeof link.download === 'string') {

        link.href = uri;
        link.download = filename;

        //Firefox requires the link to be in the body
        document.body.appendChild(link);

        //simulate click
        // link.click();
        window.location.href = 'module/' + me.mod + '/excel.php';
        //remove the link when done
        document.body.removeChild(link);

    } else {

        window.open(uri);

    }
}
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function () {
    me.SetUrl();
    me.SetDateTime();
    me.Search();
    me.LoadDataReport(me.action.menu, 1, 100000, moment().format("YYYY-MM-DD") + ' 00:00:00', moment().format("YYYY-MM-DD") + ' 23:59:59');
    // me.LoadCbo('project','getprojects','project_id','project_name');
    // me.LoadCbo('role_id','getroles','role_id','role_name');
});