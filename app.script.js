
var me = {
	debug: [],
	site: '',
	url: '',
	mod: '',
	modsub: '',
	lang: 'th',
	menu: '',
	submenu: '',
	task: '',
	parent: '',
	code: '',
	sub: 0,
	action: {
		main:'',
		menu:'',
		add:'',
		edit:'',
		del:''
	},
	data: {},
	main: {},
	get:ft.GetParam(),
	permission:{
		add:'',
		edit:'',
		del:''
	},
	viewcolumn:[],
	viewdata:[],
	viewcolumnsub:[],
	table: {},
	tablesub: {},
	tablesentence: {},
	define: {},
	alert: {},
	loading:false,
	dataold: {},
	category_id : ''
};


me.Init = function () {

	$.fn.dataTable.ext.errMode = 'throw';
	me.SetLoading();
	// me.CharLoading();
	me.PutStar();
	me.Expire();
	me.Number();
	me.TextSerch();
	me.LoadCboMain('top_project_id','getprojects','project_id','project_name');
	$('.readonly').attr('readonly', true);
	$('.disabled').attr('disabled', true);

	$('#menu-' + me.menu).addClass('active');
	if($('#menu-' + me.menu).hasClass('active')){
		var val = $('#menu-' + me.menu).data('parent');
		console.log(val);
		if(val){
			$('#' + val).addClass('menu-open');
			$('#' + val+' ul').css('display','block');
		}
	}
	// $('#submenu-' + me.menu).addClass('active');
	// if (me.submenu != '') {
	// 	$('#submenu-' + me.submenu).addClass('active');
	// }

	 $('input[type="checkbox"], input[type="radio"]').iCheck({
		 checkboxClass: 'icheckbox_square-blue',
		 radioClass: 'iradio_square-blue',
		 labelHover: true,
		 increaseArea: '20%' // optional
	 });

	me.SetFocus();
	
};

me.Expire = function () {
	$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
		if (jqxhr.status != 200) {
			alertify.error(jqxhr.responseJSON.msg);
			setTimeout(function () {
				window.location.replace('app.logout.php');
			}, 3000);
		}
	});
};

me.SetFocus = function () {
	if ($('#lyAddEdit input').first().hasClass('dpk')) {

	} else if ($('#lyAddEdit input').first().hasClass('dtpk')) {

	} else if ($('#lyAddEdit input').first().hasClass('tpk')) {

	} else {
		$('#lyAddEdit input').first().focus();
	}
};

me.CharLoading = function(){

		var textWrapper = document.querySelector('.ml2');
		textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

		anime.timeline({loop: true})
			.add({
				targets: '.ml2 .letter',
				scale: [4,1],
				opacity: [0,1],
				translateZ: 0,
				easing: "easeOutExpo",
				duration: 950,
				delay: (el, i) => 70*i
			}).add({
			targets: '.ml2',
			opacity: 0,
			duration: 1000,
			easing: "easeOutExpo",
			delay: 1000
		});


};

me.SetLoading=function(){
	$(document).ajaxStart(function(){
		if(me.loading){
			$('.loading-container').removeClass('loading-inactive');
		}
	});
	$(document).ajaxStop(function(){
		$('.loading-container').addClass('loading-inactive');
	});
};

me.SetUrl = function () {
	me.url = 'module/' + me.mod + '/' + me.mod;
	$('.readonly').attr('readonly', true);
};

me.Search=function(){
	$('form#frmsearch').submit(function () {
		me.loading = true;
		var page_size = $('#page_size').val();
		me.table.page.len(page_size).draw();
	});
};

me.SearchSub=function(){
	$('#frmSearchSub').submit(function(){
		me.tablesub.draw('true');
//		me.View();
		return false;
	});
};

me.applyFilter = function(data, filter) {
	return $.grep(data, function(item) {
		// return true/false depending on filter condition
		return (!filter.Name || item.Name.indexOf(filter.Name) > -1) 
	});
};

me.LoadCboMain = function(val,menu,code,name) {
	$.ajax({
		url: 'api.inc.php?mode=0EDD66A058D4DB6F46372EA907EEDDD8',
		type: "POST",
		dataType: "json",
		cache: false,
		data: {menu_action : menu , code : code , name : name},
		success: function(data) {
			$("#"+val+' option').remove();
			switch (data.success) {
				case "COMPLETE":
					// $("<option>")
					// 	.attr("value", '')
					// 	.text('==  Project List = =')
					// 	.appendTo("#" + val);
					$.each(data.item, function(i, result) {
						$("<option>")
							.attr("value", result.code)
							.text(result.name)
							.appendTo("#" + val);
					});

					$("#" + val).val(data.project_id);
					break;
				default:
					alertify.alert(data.msg);
					break;
			}
			me.ChangeTop();
		}
	});
};

me.ChangeTop = function(){
	$('#top_project_id').on('change',function () {
		$.ajax({
			url: 'api.inc.php?mode=3065C7AFA89118D8B3CCF100573553DE',
			type: "POST",
			dataType: "json",
			cache: false,
			data: { code : $(this).val() },
			success: function(data) {

			}
		});
	})
};

me.LoadCbo = function(val,menu,code,name) {
	$.ajax({
		url: 'api.inc.php?mode=32DB5371F29FB2E482986955597E001D',
		type: "POST",
		dataType: "json",
		cache: false,
		data: {menu_action : menu , code : code , name : name},
		success: function(data) {
			$("#"+val+' option').remove();
			switch (data.success) {
				case "COMPLETE":
					$("<option>")
						.attr("value", '')
						.text('==  List = =')
						.appendTo("#" + val);
					$.each(data.item, function(i, result) {
						$("<option>")
							.attr("value", result.code)
							.text(result.name)
							.appendTo("#" + val);
					});
					break;
				default:
					alertify.alert(data.msg);
					break;
			}
		}
	});
};

me.LoadData = function(menu,page_id,page_size,readd=''){

	$.ajax({
		url: me.url + '-View',
		type:'POST',
		dataType:'json',
		cache:false,
		data:{ menu_action : menu , page_id : page_id , page_size : page_size},
		success:function(data){
			switch(data.success){
				case 'COMPLETE' :
					if(data.data.length == 0){
						alertify.alert('No data, Please select other date');
					}
					if(readd){
						// me.table.clear();
						// var dataold = me.table.rows().data();
						me.applyData(me.table,data.data,false);
						// me.table.clear().draw();
						// me.table.rows.add(data.data).draw();
					}else{
						me.table = $('#tbView')
							.addClass('nowrap')
							.removeAttr('width')
							.DataTable({
								dom: 'Bfrtip',
								buttons: [{
									extend: 'colvis',
									columnText: function ( dt, idx, title ) {
										return (idx+1)+': '+(title?title:'Action');
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
									}
								],
								retrieve: true,
								deferRender: true,
								stateSave: true,
								iDisplayLength : page_size,
								responsive: false,
								scrollX: true,
								pageLength: page_size,
								lengthMenu: [[ page_size, (page_size * 2), -1 ],[ page_size, (page_size * 2), 'All' ]],
								data: data.data,
								columns: data.columns
							});


					}
					me.dataold = data.data;
					me.table.columns.adjust().draw('true');

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

me.LoadDataReport = function(menu, page_id, page_size, start, stop, compare ='',search = '', readd=''){
	$.ajax({
		url: me.url + '-View',
		type:'POST',
		dataType:'json',
		cache:false,
		data:{ menu_action : menu , page_id : page_id , page_size : page_size , start_date : start , end_date : stop , compare : compare , text_search : search},
		success:function(data){
			switch(data.success){
				case 'COMPLETE' :

					me.table = $('#tbView')
						.addClass('nowrap')
						.removeAttr('width')
						.DataTable({
							destroy: true,
							bFilter: false,
							dom: 'Bfrtip',
							buttons: [
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
											fontSize:12
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
							stateSave: false,
							responsive: false,
							scrollX: true,
							pageLength: page_size,
							paging: true,
							lengthChange:false,
							columns: data.columns,
							serverSide: true,
							ajax: {
								"url": me.url + "-View",
								"type": "POST",
								"data": function (d) {
									d.page_id = (d.start / d.length) + 1;
									d.page_size = $('#page_size').val();
									d.compare = $('#compare').val();
									d.start_date = $('#start_date').data().date+' 00:00:00';
									d.end_date = $('#end_date').data().date+' 23:59:59';
									d.text_search = $('#text_search').val();
									d.menu_action = me.action.menu;
								}
							}
						});


					me.table.buttons(0, null).container().addClass('col');

					if(data.data.length == 0){
						alertify.alert('No data, Please select other date');
					}

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

me.View = function () {

	me.table = $('#tbView')
	.addClass('nowrap')
	.removeAttr('width')
	.DataTable({
		columnDefs: [{
			"width": "20%",
			"targets": 1
		}],
		retrieve: true,
		deferRender: true,
		stateSave: true,
		pageLength: 10,
		data: me.viewdata,
		columns: me.viewcolumn
	});

//	$('.dataTables_length').addClass('bs-select');
//	$('#tbView thead').addClass('blue lighten-3');
	me.table.columns.adjust().draw('true');

	
};

me.ViewList = function () {
	$('#content-addedit').css('display', 'none');
	$('#content-viewlist').fadeIn('slow');
};


me.New = function () {
	me.ClearData();
	$('.btn_edit').hide();
	$('.btn_add').show();
	$('#frm_addedit input[name="menu_action"]').val(me.action.add);
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
};

me.Enable = function (code) {
	var dialog = bootbox.confirm({
		size: "small",
		title: "Enable",
		message: "Do you confirm to enable ?",
		centerVertical: true,
		callback: function (result) {
			if (!result) return;
			var myData = {
				code: code,
				enable: 'Y'
			};

			$.ajax({
				url: me.url + '-Enable',
				type: 'POST',
				dataType: 'json',
				cache: false,
				data: myData,
				success: function (data) {
					switch (data.success) {
						case 'COMPLETE':

							me.table.ajax.reload(null, false);
							me.MsgSuccessNew(data.msg, '');
							break;
						default:
							me.MsgFail(data.msg);
							break;
					}
				}
			});
		}
	})

	dialog.on('hidden.bs.modal', function (e) {
		if ($('.modal').hasClass('show')) {
			if (!$('body').hasClass('modal-open')) {
				$('body').addClass('modal-open');
			}
		}
	});
};

me.Disable = function (code) {
	var dialog = bootbox.confirm({
		size: "small",
		title: "Disable",
		message: "Do you confirm to disable ?",
		centerVertical: true,
		callback: function (result) {
			if (!result) return;
			var myData = {
				code: code,
				enable: 'N'
			};

			$.ajax({
				url: me.url + '-Enable',
				type: 'POST',
				dataType: 'json',
				cache: false,
				data: myData,
				success: function (data) {
					switch (data.success) {
						case 'COMPLETE':

							me.table.ajax.reload(null, false);
							me.MsgSuccessNew(data.msg, '');
							break;
						default:
							me.MsgFail(data.msg);
							break;
					}
				}
			});
		}
	})

	dialog.on('hidden.bs.modal', function (e) {
		if ($('.modal').hasClass('show')) {
			if (!$('body').hasClass('modal-open')) {
				$('body').addClass('modal-open');
			}
		}
	});
};

me.Clear = function () {
	me.ClearData();
	$('#lyAddEdit input').first().focus();
};

me.applyDatanew = function(table, data, append){

	if(append == true){
		table.rows.add(data);

		//Locate and update rows by rowId or add if new
	}else {
		var index;
		var indexes;
		$.each(data, function (i, v) {
			index = table.row('#' + v.DT_RowId);
			if(index.length > 0){
				table.row(index[0]).data(data[i]).invalidate();

			}else{
				indexes = table.rows().eq(0).filter(function (idx) {
					return table.cell(idx, 1).data() === v.DT_RowId ? true : false;
				});
				console.log(indexes);
				table.rows(indexes).remove();
				table.row.add(data[i]);
			}


		});
	}
	table.draw(true);
};

me.applyData = function(table, data, append){

	//Quickly appends new data rows.  Does not update rows
	if(append == true){
		table.rows.add(data);

		//Locate and update rows by rowId or add if new
	}else{
		var index;
		var chk;
		var indexes;
		// $.each(dataold, function (i, v) {
		// 	indexes = table.row('#' + v.DT_RowId);
		// });
			for (var x = 0;x < data.length;x++){
			//Find row index by rowId if row exists
			index = table.row('#' + data[x].DT_RowId);

			//Update row data if existing, and invalidate for redraw
			if(index.length > 0){
				table.row(index[0]).data(data[x]).invalidate();

				//Add row data if new
			}else{
				// var indexes = table.rows().eq(0).filter(function(idx){
				// 	return table.cell(idx, 1).data() === v.id ? true : false;
				// });
				// table.rows(indexes).remove();
				table.row.add(data[x]);

			}
		}
	}

	//Redraw table maintaining paging
	table.draw(true);
};

me.Load = function (e) {
	me.ClearData();
	var code = $(e).attr('data-code');
	var attr = JSON.parse($(e).attr('data-item'));
	var result = [];

	for(var i in attr)
		result.push({name : i,value : attr [i]});

	ft.PutFormID('frm_addedit',result);
	$('#frm_addedit input[name="code"]').val(code);
	$('#frm_addedit input[name="menu_action"]').val(me.action.edit);

	$('.btn_edit').show();
	$('.btn_add').hide();
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

};

me.Add = function () {
	$('#btnsubmit').click(function (e) {
		e.stopPropagation();
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
									// $('#btnsearchsubmit').click();
									// me.table.clear().draw();
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

me.Edit = function () {
	$('#btnsubmit').click(function (e) {
		e.stopPropagation();
		$('form#frm_addedit').submit(function () {
			var form = $(this);
			$('.modal').modal('hide');
			alertify.confirm("Do you want Update.",
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
									// $('#btnsearchsubmit').click();
									// me.table.clear().draw();
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
					alertify.error('Cancel Update');
				});

		});

	}).click();
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
				data: { 'code' : code , 'menu_action' : me.action.del , 'main' : me.action.main },
				success: function (data) {
					switch (data.success) {
						case 'COMPLETE':
							$('.modal').modal('hide');
							alertify.success(data.msg);
							// $('#btnsearchsubmit').click();
							me.table.row('#'+attr.DT_RowId).remove().draw();
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
			alertify.error('Cancel Delete');
		});
};

me.TextEditor = function () {
	$('.editor').summernote({height: 300});
};

me.MsgSuccess = function (method) {

	var dialog = bootbox.alert({
		size: "small",
		title: "Success",
		message: "Update successfully!",
		centerVertical: true
	})
	dialog.init(function () {
		setTimeout(function () {
			dialog.modal('hide');
		}, 5000);
	});

	dialog.on('hidden.bs.modal', function (e) {
		if (method === 'hide') {
			$('.modal').modal('hide');
		}
		if ($('.modal').hasClass('show')) {
			if (!$('body').hasClass('modal-open')) {
				$('body').addClass('modal-open');
			}
		}
	});
};

me.MsgSuccessNew = function (msg, method) {
	
	alertify
  .alert(msg, function(){
    alertify.message('OK');
		if (method === 'hide') {
			$('.modal').modal('hide');
		}
  });

};

me.Messenger=function(msg, id){

	
	Messenger.options = {
		extraClasses: "messenger-fixed messenger-on-top messenger-on-right",
		theme: 'flat'
	}
	
	var msgs = Messenger().post({
		message: msg,
		type: "error",
		showCloseButton: false,
		hideAfter :false,
		id: id,
		singleton: true,
		actions : {
			cancel: {
				label: "Read",
				action: function(){
					$.ajax({
						url:'app.ajax.php?mode=ReadAlert',
						type:'POST',
						dataType:'json',
						cache:false,
						data:{
							code:id
						},
						success:function(data){
							msgs.hide();
						}
					});
					
				}
			}
		}
	})	
};

me.MsgFail = function (msg) {
	alertify
	.alert(msg, function(){
		alertify.message('OK');
	});
};


me.MsgInsert = function () {
	
	alertify
  .alert(me.alert.PLEASEINSERT, function(){
    alertify.message('OK');
  });

};

me.Msg = function (title, text, icon) {
	var dialog = bootbox.alert({
		size: "small",
		title: title,
		message: text,
		centerVertical: true
	})
	dialog.init(function () {
		setTimeout(function () {
			dialog.modal('hide');
		}, 3000);
	});
};

me.RemoveCbo = function (id) {
	$("#" + id + " option[value!='']").each(function () {
		$(this).remove();
	});
};

me.SetDateTime=function(){

	$('.dtpk').daterangepicker({
		autoUpdateInput: false,
		singleDatePicker: true,
		timePicker: true,
		timePicker24Hour: true,
		timePickerIncrement: 10,
		showDropdowns: true,
		locale: {
			format: 'DD/MM/YYYY H:mm',
			cancelLabel: 'Clear'
		},
		minYear: 1901,
		maxYear: parseInt(moment().format('YYYY'),10)
	}, function(start, end, label) {
//		var years = moment().diff(start, 'years');
//		alert("You are " + years + " years old!");
	});
	$('.dtpk').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY H:mm'));
  });
	$('.dtpk').on('cancel.daterangepicker', function(ev, picker) {
		//do something, like clearing an input
		$(this).val('');
	});
	
	$('.dtpk').on('show.daterangepicker', function(ev, picker) {
      $('.daterangepicker').removeClass('auto-apply');
  });

};

me.SetDate=function(){

	$('.dpk').daterangepicker({
		autoUpdateInput: false,
		singleDatePicker: true,
		showDropdowns: true,
		locale: {
			format: 'DD/MM/YYYY',
			cancelLabel: 'Clear'
		},
		minYear: 1901,
		maxYear: parseInt(moment().format('YYYY'),10)
	}, function(start, end, label) {
//		var years = moment().diff(start, 'years');
//		alert("You are " + years + " years old!");
	});
	$('.dpk').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
	$('.dpk').on('cancel.daterangepicker', function(ev, picker) {
		//do something, like clearing an input
		$(this).val('');
	});
	
	$('.dpk').on('show.daterangepicker', function(ev, picker) {
      $('.daterangepicker').removeClass('auto-apply');
  });

};

me.SetTime=function(){
	var lang = 'uk';
	if(me.lang == 'TH'){
		lang = 'th';
	}
	$('.tpk').timepicker({ 
		timeFormat: 'HH:mm',
		dynamic:false,
		zindex:10,
		interval:15 ,
		change: function(time) {
//			$(this).trigger('focus');
		}
        
	});
};

me.DataMask = function () {
	$('[data-mask]').inputmask();
};

me.Preview = function (code) {

	var myData = {
		code: code
	};

	$.ajax({
		url: me.url + '-Load',
		type: 'GET',
		dataType: 'json',
		cache: false,
		data: myData,
		success: function (data) {
			$('#codedata').val(code);
			me.code = code;

			$.each(data.row, function (i, attr) {
				$('#tag-' + attr.name).html(attr.value);
			});

			$('#modal-preview').modal('show');
		}
	});
};

me.EditPreview = function () {

	var code = $('#codedata').val();

	$('.modal').modal('hide');

	me.Load(code);

};

me.PutStar = function () {
	var text = '';
	$( '#frm_addedit' ).find( 'select, textarea, input' ).each(function () {
		if($(this).prop('required')){
			text = $(this).parent().find('label').text();
			$(this).parent().find('label').html(text + ' <small style="color:red">*</small>');
		}
	});

};

me.AddStar = function (id) {
	var text = '';
	text = $('#'+id).parent().find('label').text();
	$('#'+id).parent().find('label').html(text + ' <small style="color:red">*</small>');
};

me.DelStar = function (id) {
	var text = '';
	text = $('#'+id).parent().find('label').text();
	text = text.split('*').join('');
	$('#'+id).parent().find('label').html(text);
};

me.ClearData = function () {
	$('#frm_addedit input').val('');
	$('#frm_addedit select option:eq(0)').prop("selected", true);
	$('#frm_addedit textarea').val('');
	$('#frm_addedit input[type="checkbox"]').iCheck('uncheck');
	$('#frm_addedit input[type="checkbox"].active').val(1);
	$('#frm_addedit input[type="checkbox"].active').iCheck('check');
};

me.ClearDataID = function (id) {
	$('#'+id+' input').val('');
	$('#'+id+' select option:eq(0)').prop("selected", true);
	$('#'+id+' textarea').val('');
	$('#'+id+' input[type="checkbox"]').iCheck('uncheck');
	$('#'+id+' input[type="checkbox"].active').val(1);
	$('#'+id+' input[type="checkbox"].active').iCheck('check');
};

me.ClearError = function () {
	$('input , select , textarea').removeClass('is-invalid');
};

me.CheckForm = function () {
	me.ClearError();
	var chk = ft.CheckEmpty('empty');
	if (chk) {
		return true;
	} else {
		me.MsgFail(me.alert.PLEASEINSERT);
		return false;
	}
};

me.CheckFormClass = function (cls) {
	me.ClearError();
	var chk = ft.CheckEmpty(cls);
	if (chk) {
		return true;
	} else {

		return false;
	}
};

me.CheckEmpty = function (selector) {
	var chk = true;
	var self;
	$('.' + selector).each(function () {
		self = $(this);
		if (self.val() == '') {
			if ((self.is('input:text')) || (self.is('input[type="number"]')) || (self.is('input[type="email"]')) || (self.is('input:password')) || (self.is('textarea')) || (self.is('select'))) {
				chk = false;
				self.addClass('is-invalid');
			}
		}
	});

	setTimeout('me.ClearError();', 5000);
	return chk;
};

me.ModalUpload = function () {
	$('#ModalUpload').modal('show');
};

me.SearchMenu = function () {
	$('#SearchMenu').keyup(function () {
		var text = $(this).val();
		var value = '';
		if (text == '') {
			$('.menuitem').css('display', '');
			$('.menusubitem').css('display', '');
			$('.menusearch').css('display', 'none');
		} else {
			$('.menuitem').css('display', 'none');
			$('.menusubitem').css('display', 'none');
			$('.menusearch').css('display', 'none');
			$(".menusearch[rel*='" + text + "']").css('display', '');
			$(".menusearch[rel*='" + text + "']").each(function () {
				value = $(this).attr('rel');
				value = value.split(text).join('<b class="info">' + text + '</b>');
				$(this).find('.menu-text').html(value);
			});
		}
	});
};

me.LoadCbo_ = function () {
	$.getJSON(me.url + '?mode=LoadCbo&mod=' + me.mod + '&' + new Date().getTime(), {}, function (data) {
		$.each(data.xxx, function (i, result) {
			$('<option>').attr('value', result.code).text(result.name).appendTo('#xxx_code');
			$('<option>').attr('value', result.code).text(result.name).appendTo('#search_xxx_code');
		});
	});
};

me.CheckDisplay = function (chk) {
	if (chk == 'Y') {
		return '<i class="fa fa-check success"></i>';
	} else {
		return '<i class="fa fa-times danger"></i>';
	}
};

me.Modal = function (id, option) {

	if (option == 'show') {
		$(id).modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});
	} else if (option == 'hide') {
		$(id).modal('hide');
	}
};

me.ExportExcel=function(){
	var p={
		limit:$('#cboLimit').val(),
		page:$('.pagenow').val()
	};
	
	var v = '';
	var id = '';
	$('.searchdata').each(function(i){
		id = $(this).attr('name');
		if($(this).hasClass('dpk')){
			if($(this).val()!=''){
				v = ft.DateFormat($(this).val());
			}else{
				v = '';
			}
		}else if($(this).hasClass('dtpk')){
			if($(this).val()!=''){
				v = ft.DateTimeFormat($(this).val());
			}else{
				v = '';
			}
		}else if($(this).hasClass('tpk')){
			if($(this).val()!=''){
				v = ft.TimeFormat($(this).val());
			}else{
				v = '';
			}
		}else if($(this).hasClass('getrel')){ 
			if($(this).val()!=''){
				if($(this).attr('rel')!=''){
					v = $(this).attr('rel');
				}else{
					v = '';
				}
			}else{
				v = '';
			}
		}else{
			v = $(this).val();
		}
		if(v!=''){
			p[id]=v;
		}
	});
	var para = $.param(p);
//	window.open('app.excel.php?file=print&mod='+me.mod+'&'+para, '_blank');
	window.location.href = 'app.excel.php?file=excel&mod='+me.mod+'&'+para;
//	window.location.href = 'app.excel2.php?mod='+me.mod;
};

me.UploadFile = {
	init: function () {
		$('.btnFileUpload').on('click', function () {
			$(this).css('display', 'none');
			var id = $(this).attr('id');
			$('#loading_' + id).css('display', '');
		})

	},
	Complete: function (pic, id) {
		$('#' + id).val(pic);
		$('#upload_' + id).css('display', '');
		$('#loading_upload_' + id).css('display', 'none');
		me.MsgSuccessNew('Upload complete!!');

		return true;

	}
};

me.Number = function () {
	$('.num').bind('keypress', function (event) {
		var regex = new RegExp("^[0-9]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
			event.preventDefault();
			return false;
		}
	});
	
	$('.txtnum').bind('keypress', function (event) {
		var regex = new RegExp("^[0-9]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
			event.preventDefault();
			return false;
		}
	});

	$('.num2').bind('keypress', function (event) {
		var regex = new RegExp("^[.0-9]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
			event.preventDefault();
			return false;
		}
	});

	$('.digit').bind('keypress', function (event) {
		var regex = new RegExp("^[0-9]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
			event.preventDefault();
			return false;
		}
	});
	
	$('.char').bind('keypress', function (event) {
		var regex = new RegExp("^[a-zA-Z0-9]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
			event.preventDefault();
			return false;
		}
	});
};

me.HideMenu = function () {
	//	$('body.sidebar-collapse').click();
	if (!$('body').hasClass('sidebar-collapse')) {
		$('body').addClass('sidebar-collapse');
	}

};

me.Alert={
	Load:function(){
		me.loading = false;
		$.ajax({
			url:'app.ajax.php?mode=LoadAlert',
			type:'POST',
			dataType:'json',
			cache:false,
			data:{},
			success:function(data){
				me.loading = true;
				if(data.success=='LOGOUT'){
					window.location.href = data.url;
				}else{
					me.Alert.Append(data);
					setTimeout('me.Alert.Load()', 5000);
				}
			}
		});
	},
	Append:function(data){
		var html = '';
		html += '<li>';
		html += '    <a href="readinbox.php?id={code}">';
//		html += '        <div class="clearfix">';
//		html += '            <div class="notification-icon">';
//		html += '                <i class="fa {icon} bg-themeprimary white"></i>';
//		html += '            </div>';
//		html += '            <div class="notification-body">';
//		html += '                <span class="title">{name}</span>';
//		html += '                <span class="description">{time}</span>';
//		html += '            </div>';
//		html += '            <div class="notification-extra">';
//		html += '                <i class="fa fa-user themeprimary"></i>';
//		html += '                <span class="description">{contact}</span>';
//		html += '            </div>';
//		html += '        </div>';
		html += '    <i class="fa fa-warning text-yellow"></i>{message}';
		html += '    </a>';
		html += '</li>';

		var temp = '';
		$('#zone_alert').text('');
		$.each(data.row, function(i, attr){
			temp = html;
//			temp = temp.split('{icon}').join('fa-file-text-o');
			temp = temp.split('{code}').join(attr.code);
			temp = temp.split('{message}').join(attr.message);
//			temp = temp.split('{project_name}').join(attr.project_name);
//			temp = temp.split('{time}').join(attr.date_create);
//			temp = temp.split('{contact}').join(attr.user_create);
			$('#zone_alert').append(temp);
		});		
		
		$.each(data.alert, function(i, attr){
			me.Messenger(attr.message,attr.code);
		});	
		
//		html  = '<li class="dropdown-footer" style="cursor:pointer;" onclick="window.location=\'FEAE57551F7DA68DC340D755B98A0B4D.html?call='+data.cnt+'\';">';
//		html += '    <span>';
//		html += '        ขอมูลการแจ้งลบ';
//		html += '    </span>';
//		html += '    <span class="pull-right">';
//		html += '        แสดงทั้งหมด';
//		html += '        <i class="fa fa-search"></i>';
//		html += '    </span>';
//		html += '</li>';		
		
//		$('#zone_alert').append(html);
		
		$('#alert_no').text(data.cnt);
	}
};

me.Inbox = {
	Load: function () {

		me.loading = false;

		$.ajax({

			url: 'app.ajax.php?mode=LoadInbox',

			type: 'POST',

			dataType: 'json',

			cache: false,

			data: {},

			success: function (data) {

				me.loading = true;

				me.Inbox.Append(data);

				//				setTimeout('me.Inbox.Load()', 30000);

			}

		});

	},
	Append: function (data) {

		var html = '';

		html += '<li>';

		html += '    <a href="#">';

		html += '        <img src="../img/?pic={filepic}&w=50" class="message-avatar" alt="{name}">';

		html += '        <div class="message">';

		html += '            <span class="message-sender">';

		html += '                {name}';

		html += '            </span>';

		html += '            <span class="message-time">';

		html += '                {date_create}';

		html += '            </span>';

		html += '            <span class="message-subject">';

		html += '                {title}';

		html += '            </span>';

		html += '            <span class="message-body">';

		html += '                {detail}';

		html += '            </span>';

		html += '        </div>';

		html += '    </a>';

		html += '</li>';



		var temp = '';

		$('#zone_inbox').text('');

		$.each(data.row, function (i, attr) {

			temp = html;

			temp = temp.split('{filepic}').join(attr.filepic);

			temp = temp.split('{name}').join(attr.name);

			temp = temp.split('{date_create}').join(ft.DateTimeDisplay(attr.date_create));

			temp = temp.split('{title}').join(attr.surname);

			temp = temp.split('{detail}').join(attr.email);

			$('#zone_inbox').append(temp);

		});



		$('#inbox_no').text(data.cnt);

	}
};

me.Tasks = {
	Load: function () {

		me.loading = false;

		$.ajax({

			url: 'app.ajax.php?mode=LoadTasks',

			type: 'POST',

			dataType: 'json',

			cache: false,

			data: {},

			success: function (data) {

				me.loading = true;

				me.Tasks.Append(data);

				//				setTimeout('me.Tasks.Load()', 30000);

			}

		});

	},
	Append: function (data) {

		var html = '';

		html += '<li>';

		html += '  <a href="#">';

		html += '    <div class="clearfix">';

		html += '      <span class="pull-left">{name}</span>';

		html += '      <span class="pull-right">{pertask}%</span>';

		html += '    </div>';

		html += '';

		html += '    <div class="progress progress-xs">';

		html += '      <div style="width:{pertask}%" class="progress-bar"></div>';

		html += '    </div>';

		html += '  </a>';

		html += '</li>';



		var temp = '';

		$('#zone_tasks').text('');

		$.each(data.row, function (i, attr) {

			temp = html;

			temp = temp.split('{name}').join(attr.name);

			temp = temp.split('{pertask}').join(attr.pertask);

			$('#zone_tasks').append(temp);

		});



		$('#tasks_no').text(data.cnt);

		$('#tasks_no2').text(data.cnt);

	}
};

me.Auto={
	Load:function(){
		me.loading = false;
		$.ajax({
			url:'app.auto.php',
			type:'POST',
			dataType:'json',
			cache:false,
			data:{},
			success:function(data){
				me.loading = true;
				setTimeout('me.Auto.Load()', 5000);
			}
		});
	}
};


me.ConvertJson = function () {

	data = document.getElementById('ifmService').contentWindow.document.body.innerHTML;

	//	var str = data;

	//	var newStr = str.substring(0, str.length-4);

	if (data == '') {

		$('#json-renderer').text('please Load JSON RESULT');


	} else {

		var json_use = eval('(' + data + ')');

		$('#json-renderer').jsonViewer(json_use);

	}

};

me.SumbitConvertJson = function () {

	$('#submit_api').click(function () {

		setTimeout(function () {
			me.ConvertJson();
		}, 600);

	});

};

me.TextSerch = function () {

	$('#text_search').on('keyup',function (e) {
		me.loading = false;
		var myData = [];
		myData = ft.LoadForm('searchdata');
		myData.start_date = $('#start_date').data().date+' 00:00:00';
		myData.end_date = $('#end_date').data().date+' 23:59:59';
		myData.page_id = 1;
		myData.page_size = 10000;
		myData.menu_action = me.action.menu;

		$.ajax({
			url: me.url + '-View',
			type:'POST',
			dataType:'json',
			cache:false,
			data: myData,
			success:function(data){
				switch(data.success){
					case 'COMPLETE' :
						me.table.clear().draw();
						me.table.rows.add(data.data).draw();

						break;
					default :
						alertify.alert(data.msg);
						break;
				}
			}
		});
	});
};
