

me.LoadDashBoard = function () {

    var start = $('#start_date').data().date;
    var stop = $('#end_date').data().date;
    var cnt = 0;



    if(start != ''){
        ++cnt;
    }
    if(stop != ''){
        ++cnt;
    }

    if(cnt > 0 && cnt != 2)return false;

    var myData = {
        'start' : start,
        'end' : stop
    };

    $('#box1').css('display','');
    $('#box2').css('display','');
    $('#box3').css('display','');
    $('#box4').css('display','');
    $('#box5').css('display','');

    $.ajax({
        url: me.url + '-View',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: myData,
        success: function (data) {
            if(!data.box5s){
                alertify.alert('No data, Please select other date');
                return false;
            }

            if (data.box1) {
                $('#box1').css('display','');
                $.each(data.box1, function (i, result) {
                    //console.log(result);
                    $(".totalcall").text(result.totalcall);
                    $(".peraccuracy").text(result.peraccuracy);
                    $(".recog").text(result.recog);
                    $(".nonrecog").text(result.nonrecog);
                });
            }

            if (data.box2) {
                $('#box2').css('display','');
                $('#box2_name').text(data.name[1]);
                var line = new Morris.Line({
                    element: 'line-chart',
                    resize: true,
                    data: data.box2,
                    xkey: 'datetime',
                    xLabels: 'day',
                    xLabelAngle:'90',
                    ykeys: ['peraccuracy'],
                    labels: ['Accuracy'],
                    lineColors: ['#3c8dbc'],
                    hideHover: 'auto',
                    gridTextWeight: 'bolder',
                    gridTextColor: '#000'

                });
            };

            if (data.box3) {
                $('#box3').css('display','');
                $('#box3_name').text(data.name[2]);

                var area = new Morris.Bar({
                    element: 'revenue-chart',
                    resize: true,
                    data: data.box3.data,
                    xkey: 'date_time',
                    xLabels: 'day',
                    xLabelAngle:'90',
                    gridTextWeight: 'bolder',
                    gridTextColor: '#000',
                    ykeys: data.box3.label,
                    labels: data.box3.label,
                    hideHover: 'auto',
                    hoverCallback: function (index, options, content, row) {
                        return row.date_time+'<br>'+row.TOP1NAME+' : '+row.TOP1+'<br>'+row.TOP2NAME+' : '+row.TOP2+'<br>'+row.TOP3NAME+' : '+row.TOP3+'<br>'+row.TOP4NAME+' : '+row.TOP4+'<br>'+row.TOP5NAME+' : '+row.TOP5;
                    }
                });



                // new Chartist.Line('#revenue-chart', {
                //     labels:data.box3.label,
                //     series:[pvsum1,pvsum2]
                // }, {
                //     distributeSeries: true,
                //     fullWidth: true,
                //     high: 100,
                //     plugins: [
                //         Chartist.plugins.tooltip(),
                //         Chartist.plugins.ctPointLabels({
                //             textAnchor: 'middle'
                //         })
                //     ]
                // });


            };

			if (data.box4.length > 0) {
                $('#box4').css('display','');
                $('#box4_name').text(data.name[3]);
				var bar_data = {
					data: data.box4,
					color: '#3c8dbc'
				};

				$.plot('#bar-chart', [bar_data], {
                    colors: "#000",
					grid: {
						borderWidth: 1,
						borderColor: '#f3f3f3',
						tickColor: '#f3f3f3',
                        colors: "#000"

					},
					series: {
						bars: {
							show: true,
							barWidth: 0.5,
							align: 'center'
						}
					},
					xaxis: {
						mode: 'categories',
						tickLength: 0,
                        colors: "#000"
					}
				});
			}

			if(data.box5s){
                $('#box5').css('display','');
                $('#box5_name').text(data.name[4]);
                var datascource = data.box5;

                d3.json("data-example.json", function(error, json) {
                    treeBoxes('', json.tree);
                });

                $('#chart-container').orgchart({
                    'data': datascource,
                    'nodeContent': 'title',
                    'direction': 'l2r',
                    'zoom': false
                });
            }
            $('.orgchart').addClass('noncollapsable');

        }

    });

};

me.Search = function(){
    $('form#frmsearch').submit(function () {
        me.loading = true;
        me.LoadDashBoard();
    });

};


/*================================================*\

  :: DEFAULT ::

\*================================================*/

$(document).ready(function () {

    $('#start_date').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    $('#end_date').datetimepicker({
        format: 'YYYY-MM-DD'
    });

    me.SetUrl();
    me.Search();
    me.LoadDashBoard();
	$(".knob").knob({readOnly : true , angleOffset : 100});


});


function labelFormatter(label, series) {

    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'

        + label

        + '<br>'

        + Math.round(series.percent) + '%</div>'

}

