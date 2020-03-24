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
								iDisplayLength : page_size,
								responsive: false,
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

me.Build = function(e){
	me.ClearDataID('frm_buildgrammar');
	var code = $(e).attr('data-code');
	$('#frm_buildgrammar input[name="project_id"]').val(code);
	$('.btn_edit').hide();
	$('.btn_add').show();
	$('#buildgrammar').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
};

me.Process = function(e){

	var code = $(e).attr('data-code');
	var attr = JSON.parse($(e).attr('data-item'));
	var myData = {
		'project_id' : attr.project_id,
		'file_name' : attr.file_name
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
					$('.modal').modal('hide');
					$('#nowupload').modal('show');

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
							switch (data.success) {
								case 'COMPLETE':
									$('.modal').modal('hide');
									alertify.success(data.msg);
									me.table.clear().draw();
									me.LoadData(me.action.menu, 1, 30, 1);
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

/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	// me.HideMenu();
	me.SetDateTime();
	me.Search();
	me.LoadDataReport(me.action.menu,1,25,'','','','');
	me.LoadCbo('project_id','getprojects','project_id','project_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});