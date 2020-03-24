/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'menu_id';
me.action.menu = 'getmenus';
me.action.add = 'addmenu';
me.action.edit = 'updatemenu';
me.action.del = 'deletemenu';
/*================================================*\
  :: FUNCTION ::
\*================================================*/
me.SetDateTime = function(){
	$('#expire_date').datetimepicker({
		format: 'DD/MM/YYYY HH:mm',
	});
};

me.ClearData = function () {
	$('#frm_addedit input').val('');
	$('#frm_addedit select').val('');
	$('#frm_addedit textarea').val('');
	$('#frm_addedit input[type="checkbox"]').iCheck('uncheck');
	$('#frm_addedit input[type="checkbox"]').iCheck('enable');
	$('#frm_addedit input[type="checkbox"].active').val(1);
	$('#frm_addedit input[type="checkbox"].active').iCheck('check');
	$('#frm_addedit input[name="main_menu"]').val(1);
	$('#frm_addedit input[name="main_menu"]').iCheck('check');
	$('#frm_addedit #menu_after').attr('disabled',false);

};

me.New = function () {
	me.ClearData();
	$('.btn_edit').hide();
	$('.btn_add').show();
	$('#frm_addedit input[name="menu_action"]').val(me.action.add);
	// $('#frm_addedit input[name="main_menu"]').iCheck('disable');
	// $('#frm_addedit input[name="main_menu"]').val(1);
	// $('#frm_addedit input[name="main_menu"]').trigger('ifChecked');

	// $('#frm_addedit input[name="main_menu"]').val(1);
	// $('#frm_addedit input[name="main_menu"]').iCheck('check');

	// $('#frm_addedit input[name="main_menu"]').iCheck('disable');
	// $('#frm_addedit #menu_after').attr('disabled',true);
	// $('input').iCheck('update');
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
};

me.Load = function (e) {
	me.ClearData();
	var code = $(e).attr('data-code');
	var attr = JSON.parse($(e).attr('data-item'));
	var result = [];

	for(var i in attr)
		result.push({name : i,value : attr [i]});


	// $('input[name="main_menu"]').iCheck('enable');
	ft.PutFormID('frm_addedit',result);
	$('#frm_addedit input[name="code"]').val(code);
	$('#frm_addedit input[name="menu_action"]').val(me.action.edit);
	// if(result.main_menu == 1){
	// 	$('#frm_addedit #menu_after').attr('disabled',true);
	// 	$('#frm_addedit #menu_after').attr('required',false);
	// }else{
	//
	// 	$('#frm_addedit #menu_after').attr('disabled',false);
	// 	$('#frm_addedit #menu_after').attr('required',true);
	// }


	$('#frm_addedit input[name="main_menu"]').iCheck('disable');

	$('.btn_edit').show();
	$('.btn_add').hide();
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

};

me.LoadAfter = function (e) {
	me.ClearData();

	var attr = JSON.parse($(e).attr('data-item'));
	var result = [];

	for(var i in attr)
		result.push({name : i,value : attr [i]});



	ft.PutFormID('frm_addedit',result);
	$('#frm_addedit input[name="menu_action"]').val(me.action.add);
	$('#frm_addedit input[name="main_menu"]').iCheck('disable');
	// $('input[name="main_menu"]').val(0);
	// $('input[name="main_menu"]').iCheck('uncheck');
	// me.AddStar('menu_after');
	// $('#frm_addedit #menu_after').attr('required',true);
	$('#frm_addedit #menu_after').attr('disabled',true);
	$('.btn_edit').hide();
	$('.btn_add').show();
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

};

me.LoadAfterSub = function (e) {
	me.ClearData();

	var attr = JSON.parse($(e).attr('data-item'));
	var result = [];

	for(var i in attr)
		result.push({name : i,value : attr [i]});



	ft.PutFormID('frm_addedit',result);
	$('#frm_addedit input[name="menu_action"]').val(me.action.add);
	// $('input[name="main_menu"]').iCheck('enable');
	// $('input[name="main_menu"]').val(0);
	// $('input[name="main_menu"]').iCheck('uncheck');
	// me.AddStar('menu_after');
	// me.DelStar('menu_icon');
	// $('#frm_addedit #menu_after').attr('required',true);
	// $('#frm_addedit #menu_after').attr('disabled',false);
	$('.btn_edit').hide();
	$('.btn_add').show();
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

};

me.Del_ = function (e) {
	if(main){
		var myData = {
			'code' : code ,
			'menu_action' : me.action.del ,
			'main' : me.action.main,
			'menu_after' : main,
			'main_menu' : 0
		};
	}else{
		var myData = {
			'code' : code ,
			'menu_action' : me.action.del ,
			'main' : me.action.main,
			'main_menu' : 1
		};
	}

	alertify.confirm("Do you want Delete.",
		function () {
			$.ajax({
				url: me.url + '-Del',
				type: 'POST',
				dataType: 'json',
				cache: false,
				data: myData,
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
			alertify.error('Cancel Delete');
		});
};


me.ChangeMain  = function(){
	$('input[name="main_menu"]').on('ifChecked', function (event) {
		$('.sub').css('display','none');
		$('.main').css('display','');
		$('#menu_after').val('');
		$('#menu_after').attr('required',false);
		$('#menu_icon').attr('required','required');
		$('input[name="main_menu"]').val(1);
		me.AddStar('menu_icon');
		me.DelStar('menu_after');
		$('input').iCheck('update');
	});

	$('input[name="main_menu"]').on('ifUnchecked', function (event) {
		$('.sub').css('display','');
		$('.main').css('display','none');
		$('#menu_after').attr('required','required');
		$('#menu_icon').attr('required',false);
		$('input[name="main_menu"]').val(0);
		me.AddStar('menu_after');
		me.DelStar('menu_icon');
		$('input').iCheck('update');
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
					if(readd){
						// me.table.clear().draw();
						me.table.rows.add(data.data).draw();
					}else{
						me.table = $('#tbView')
							.addClass('nowrap')
							.removeAttr('width')
							.DataTable({
								dom: 'Bfrtip',
								buttons: [
									'colvis'
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

					me.table.columns.adjust().draw('true');
					me.LoadCbo('menu_after',me.action.menu,'menu_name','menu_name');
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

me.Add = function () {
	$('#btnsubmit').click(function (e) {
		e.stopPropagation();
		$('form#frm_addedit').submit(function () {
			$('input').iCheck('enable');
			$('select').removeAttr('disabled');
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
									// alertify.success(data.msg);
									// me.table.clear().destroy();
									// $('#tbView').empty();
									// me.LoadData(me.action.menu, 1, 30);
									alertify.notify(data.msg, 'success', 1, function(){  window.location.href = window.location; });

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
			$('input').iCheck('enable');
			$('select').removeAttr('disabled');
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
									// alertify.success(data.msg);
									// me.table.clear().destroy();
									// $('#tbView').empty();
									// me.LoadData(me.action.menu, 1, 30);
									alertify.notify(data.msg, 'success', 1, function(){  window.location.href = window.location; });

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
	var result = [];
	var myData = [];

	for(var i in attr)
		result.push({name : i,value : attr [i]});

	if(attr.main_menu == 1){
		myData = {
			'code' : code ,
			'menu_action' : me.action.del ,
			'main' : me.action.main,
			'main_menu' : attr.main_menu,
			'menu_active' : attr.menu_active,
			'menu_name' : attr.menu_name,
			'menu_path' : attr.menu_path,
			'menu_icon' : attr.menu_icon
		};
	}else{
		myData = {
			'code' : attr.main_id ,
			'menu_action' : me.action.del ,
			'main' : me.action.main,
			'main_menu' : attr.main_menu,
			'menu_active' : attr.menu_active,
			'menu_name' : attr.menu_name,
			'menu_path' : attr.menu_path,
			'menu_icon' : attr.menu_icon,
			'menu_after' : attr.menu_after,
			'sub_menu_id' : code
		};
	}



	// console.log(myData);
	// return false;

	alertify.confirm("Do you want Delete.",
		function () {
			$.ajax({
				url: me.url + '-Del',
				type: 'POST',
				dataType: 'json',
				cache: false,
				data: myData,
				success: function (data) {
					switch (data.success) {
						case 'COMPLETE':
							$('.modal').modal('hide');
							// alertify.success(data.msg);
							// me.table.clear().destroy();
							// $('#tbView').empty();
							// me.LoadData(me.action.menu, 1, 30);
							alertify.notify(data.msg, 'success', 1, function(){  window.location.href = window.location; });
							// window.location.href = window.location;
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

/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	me.ChangeMain();
	// me.SetDateTime();
	me.LoadData(me.action.menu,1,30);

});