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
// var ctx  = document.getElementById('pieChart').getContext('2d');
// var pieChart = '';
// var ctxs = document.getElementById('barChart').getContext('2d');
// var barChart = '';
var footerhtml = $('#tbView').clone();
// console.log(footerhtml);

var buttonCommon = {
    exportOptions: {
        format: {
            body: function ( data, row, column, node ) {
                //var canvas  = node.childNodes["0"].$chartjs.toBase64Image();
                // var canvas  = node.childNodes["0"];
                // var imgdata =  canvas.toDataURL("image/png", 1.0);
                // var contentType = 'image/png';
                // var b64Data = imgdata.replace(/^data:image\/\w+;base64,/, "");
                // var blob = b64toBlob(b64Data, contentType);
                // const objectURL = URL.createObjectURL(blob);
                return objectURL;
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
        // me.table.clear().destroy();
        // $('#tbView').empty();
        // pieChart.destroy();
        // barChart.destroy();
        me.table.clear();
        me.LoadDataReport(me.action.menu, 1, page_size, start + ' 00:00:00', stop + ' 23:59:59', 1);
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

                    if (data.data.length == 0) {
                        // alertify.alert('No data, Please select other date');
                    }
                    var datafooter = data.datafooter;
                    var pipechart = data.pipechart;
                    var barchart = data.barchart;
                    var mytext = data.text;

                    if (readd) {

                        me.applyData(me.table, data.data, false);
                        me.table.on('draw', function (e, settings, json, xhr) {
                            initCompleteFunction(settings, json);
                        });


                        // me.table.clear().draw();
                        // me.table.rows.add(data.data).draw();

                    } else {


                        me.table = $('#tbView')
                            .addClass('nowrap')
                            .removeAttr('width')
                            .DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                        $.extend( true, {}, buttonCommon, {
                                            extend: 'excelHtml5'
                                        } )
                                    ],
                                    searching: false,
                                    retrieve: true,
                                    deferRender: true,
                                    stateSave: false,
                                    iDisplayLength: page_size,
                                    responsive: true,
                                    scrollX: true,
                                    pageLength: page_size,
                                    paging: false,
                                    lengthChange: false,
                                    bLengthChange: false,
                                    bPaginate: false,
                                    bInfo: false,
                                    data: data.data,
                                    columns: data.columns,
                                    initComplete: initCompleteFunction

                                }
                            );

                    }

                function initCompleteFunction(settings, json) {
                    var api = new $.fn.dataTable.Api(settings);
                    var lastRow = api.rows().count();
                    if (lastRow > 0) {
                        var footer_data = datafooter;
                        console.log(datafooter);
                        api.columns().every(function (i) {
                            this.footer().innerHTML = footer_data[i];
                        });
                    }
                };


                    me.table.columns.adjust().draw('true');

                    if (data.name) {
                        $('title').text(data.name);
                    }


                    var options = {
                        title: {
                            text: pipechart.capture
                        },
                        animationEnabled: true,

                        data: [{
                            type: "pie",

                            startAngle: 240,
                            radius: "90%",
                            showInLegend: true,
                            // xValueType : "number",
                            yValueFormatString: "##0.00\"%\"",
                            legendText: "{label}-{y}",
                            indexLabel: "{label}({name})-{y}",

                            toolTipContent: "<b>{label}</b>({name})-{y}",
                            dataPoints: pipechart.data
                        }]
                    };
                    // var piechart = $("#pieChart").CanvasJSChart(options);
                    var piechart = new CanvasJS.Chart("pieChart", options);
                    piechart.render();


                    var options1 = {
                        animationEnabled: true,
                        title: {
                            text: barchart.capture
                        },
                        data: [{
                            type: "column",
                            yValueFormatString: "#,##0.0#" % "",
                            indexLabel: "{y}",
                            indexLabelPlacement: "outside",
                            indexLabelOrientation: "horizontal",
                            dataPoints: barchart.data
                        }]
                    };
                    // var barchart =  $("#barChart").CanvasJSChart(options1);
                    var barchart = new CanvasJS.Chart("barChart", options1);
                    barchart.render();
                    // barchart.exportChart({format: "jpg"});

                    // var config = {
                    //     type: 'bar',
                    //     data: {
                    //         datasets: [{
                    //             data: barchart.data,
                    //             backgroundColor: [
                    //                 'red',
                    //                 'orange',
                    //                 'blue',
                    //                 'green',
                    //
                    //             ]
                    //         }],
                    //         labels: barchart.label
                    //     },
                    //     options: {
                    //         plugins: {
                    //             datalabels: {
                    //                 align: 'end',
                    //                 anchor: 'end',
                    //                 color: function(context) {
                    //                     return context.dataset.backgroundColor;
                    //                 },
                    //                 font: function(context) {
                    //                     var w = context.chart.width;
                    //                     return {
                    //                         size: w < 512 ? 12 : 14
                    //                     };
                    //                 },
                    //                 formatter: function(value, context) {
                    //                     // console.log(value);
                    //                     return value.y;
                    //                 }
                    //             }
                    //         },
                    //         scales: {
                    //             xAxes: [{
                    //                 display: true,
                    //                 offset: true
                    //             }],
                    //             yAxes: [{
                    //                 stacked: true,
                    //                 ticks: {
                    //                     beginAtZero: true
                    //                 }
                    //             }]
                    //         },
                    //         title: {
                    //             display: true,
                    //             text: barchart.capture
                    //         },
                    //         legend: {
                    //             display: false
                    //         },
                    //     }
                    // };
                    //
                    //
                    //
                    // barChart = new Chart(ctxs,config);
                    // barChart.update();

                    $('#mydata').html(mytext);


                    $('a.toggle-vis').on('click', function (e) {
                        e.preventDefault();

                        // Get the column API object
                        var column = me.table.column($(this).attr('data-column'));

                        // Toggle the visibility
                        column.visible(!column.visible());
                    });

                    me.table.columns.adjust().draw('true');
                    break;
                default :
                    alertify.alert(data.msg);
                    break;
            }
        }
    });
};


me.Export = function () {

    function submitFORM(path, params, method) {
        method = method || "post";

        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);

        //Move the submit function to another variable
        //so that it doesn't get overwritten.
        form._submit_function_ = form.submit;

        for (var key in params) {
            if (params.hasOwnProperty(key)) {
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

    html2canvas(document.querySelector('#allbox')).then(canvas => {
        var start = $('#start_date').data().date;
        var stop = $('#end_date').data().date;
        var dataUrl = canvas.toDataURL();
        var newDataURL = dataUrl.replace(/^data:image\/png/, "data:application/octet-stream"); //do this to clean the url.

        submitFORM('module/' + me.mod + '/excel.php', {
            img: newDataURL,
            start_date: start + ' 00:00:00',
            end_date: stop + ' 23:59:59'
        }, 'POST');


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