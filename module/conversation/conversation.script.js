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
me.SetDateTime = function(){
	$('#start_date').datetimepicker({
		format: 'YYYY-MM-DD',
		defaultDate: moment()
	});
	$('#end_date').datetimepicker({
		format: 'YYYY-MM-DD',
		defaultDate: moment()
	});
};

me.Search = function(){
	$('form#frmsearch').submit(function () {
		me.loading = true;
		$('#frmresult').css('display','none');
		var page_size = $('#page_size').val();
		var compare = $('#compare').val();
		var txtsearch = $('#text_search').val();
		var start = $('#start_date').data().date;
		var stop = $('#end_date').data().date;
		var cnt = 0;

		if(start !== undefined){
			++cnt;
		}
		if(stop !== undefined){
			++cnt;
		}

		if(cnt != 2)return false;
		me.table.clear().destroy();
		$('#tbView').empty();

		me.LoadDataReport(me.action.menu,1,page_size,start+' 00:00:00',stop+' 23:59:59',compare,txtsearch);
	});

};

me.LoadDataReport = function(menu, page_id, page_size, start, stop, compare ='',search = '', readd=''){

	$.ajax({
		url: me.url + '-View',
		type:'POST',
		dataType:'json',
		cache:false,
		data:{ menu_action : menu , page_id : page_id , page_size : 10000 , start_date : start , end_date : stop , compare : compare , text_search : search},
		success:function(data){
			switch(data.success){
				case 'COMPLETE' :
					// console.log(data.data.length);
					if(data.data.length == 0){
						alertify.alert('ไม่มีข้อมูล โปรดเลือกช่วงวันอื่น');
					}

					if(readd){
						me.table.clear().draw();
						me.table.rows.add(data.data).draw();

					}else{
						me.table = $('#tbView')
							.addClass('nowrap')
							.removeAttr('width')
							.DataTable({
								bFilter: false,
								dom: 'Bfrtip',
								buttons: [
									// 'excelHtml5',
									{
										text: 'ย้อนกลับ',
										className: 'float-left hidden',
										attr:  {
											title: 'Copy',
											id: 'btnback',
											disabled: 'disabled'
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
								iDisplayLength : page_size,
								responsive: false,
								scrollX: true,
								pageLength: page_size,
								paging: true,
								lengthChange:false,
								data: data.data,
								columns: data.columns
							});

					}
					me.table.page.len( page_size ).draw();
					me.table.columns.adjust().draw('true');

					me.table.buttons(0, null).container().addClass('col');

					if(data.name){
						$('title').text(data.name);
					}


					$('a.toggle-vis').on( 'click', function (e) {
						e.preventDefault();

						// Get the column API object
						var column = me.table.column( $(this).attr('data-column') );

						// Toggle the visibility
						column.visible( ! column.visible() );
					} );
					//me.TextSerch();
					break;
				default :
					alertify.alert(data.msg);
					break;
			}
		}
	});
};

me.LoadDataCHNN = function(menu, page_id, page_size, start, stop, readd=''){

	$.ajax({
		url: me.url + '-ViewCHNN',
		type:'POST',
		dataType:'json',
		cache:false,
		data:{ menu_action : menu , page_id : page_id , page_size : 10000 , start_date : start , end_date : stop},
		success:function(data){
			switch(data.success){
				case 'COMPLETE' :
					if(readd){
						me.table.clear().draw();
						me.table.rows.add(data.data).draw();

					}else{

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
										action: function ( e, dt, node, config ) {
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
								iDisplayLength : page_size,
								responsive: false,
								scrollX: true,
								pageLength: page_size,
								paging: true,
								lengthChange:false,
								data: data.data,
								columns: data.columns
							});

					}

					me.table.columns.adjust().draw('true');

					me.table.buttons(0, null).container().addClass('col');



					if(data.name){
						$('title').text(data.name);
					}


					$('a.toggle-vis').on( 'click', function (e) {
						e.preventDefault();

						// Get the column API object
						var column = me.table.column( $(this).attr('data-column') );

						// Toggle the visibility
						column.visible( ! column.visible() );
					} );

					break;
				default :
					alertify.alert(data.msg);
					break;
			}
		}
	});
};

me.LoadDataVOICE = function(menu, page_id, page_size, start, stop, readd=''){

	$.ajax({
		url: me.url + '-ViewVOICE',
		type:'POST',
		dataType:'json',
		cache:false,
		data:{ menu_action : menu , page_id : page_id , page_size : 10000 , start_date : start , end_date : stop},
		success:function(data){
			switch(data.success){
				case 'COMPLETE' :
					console.log(data.data.length);
					if(data.data.length == 0){
						alertify.alert('ไม่มีข้อมูล โปรดเลือกช่วงวันอื่น');
						return false;
					}

					if(readd){
						me.table.clear().draw();
						me.table.rows.add(data.data).draw();

					}else{

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
										action: function ( e, dt, node, config ) {
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
								bFilter: false,
								searching: true,
								retrieve: true,
								deferRender: true,
								stateSave: true,
								iDisplayLength : page_size,
								responsive: false,
								scrollX: true,
								pageLength: page_size,
								paging: false,
								bInfo: false,
								lengthChange:false,
								data: data.data,
								columns: data.columns
							});

					}

					me.table.columns.adjust().draw('true');
					me.table.buttons(0, null).container().addClass('col');

					if(data.name){
						$('title').text(data.name);
					}
					$('#frmresult').css('display','');
					$('#chnn').val(data.chnn);


					$('a.toggle-vis').on( 'click', function (e) {
						e.preventDefault();

						// Get the column API object
						var column = me.table.column( $(this).attr('data-column') );

						// Toggle the visibility
						column.visible( ! column.visible() );
					} );

					break;
				default :
					alertify.alert(data.msg);
					break;
			}
		}
	});
};

me.OpenCHNN = function(code,page_id,page_size,start,stop){
	me.table.clear().destroy();
	$('#tbView').empty();
	me.LoadDataCHNN(code,page_id,page_size,start,stop);

};

me.OpenVOICE = function(code,page_id,page_size,start,stop){
	me.table.clear().destroy();
	$('#tbView').empty();
	me.LoadDataVOICE(code,page_id,page_size,start,stop);
};

me.UpdateVoice = function(){
	var chnn = $('#chnn').val();
	var result = $('#result').val();
	var chk = [];
	$('input[name="pass"]').each(function (i) {
		var val = $(this).is(':checked')?1:0;
		var name = $(this).attr('ref');
		chk.push({'voice_name' : name ,'pass' : val});
	});

	// console.log(chk);

	$('.modal').modal('hide');
	alertify.confirm("Do you want Update.",
		function () {
			$.ajax({
				url: me.url + '-Add',
				type: 'POST',
				dataType: 'json',
				cache: false,
				data: { chnn : chnn , voices : chk , result : result},
				success: function (data) {
					switch (data.success) {
						case 'COMPLETE':
							$('.modal').modal('hide');
							alertify.success(data.msg);
							$('input[name="pass"]').prop('checked',false);
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
$(document).ready(function(){
	me.SetUrl();
	me.SetDateTime();
	me.Search();
	me.LoadDataReport(me.action.menu,1,25,'','','','');
	// me.LoadCbo('project','getprojects','project_id','project_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});