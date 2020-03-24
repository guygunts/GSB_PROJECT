/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'role_id';
me.action.menu = 'getroles';
me.action.add = 'addrole';
me.action.edit = 'updaterole';
me.action.del = 'deleterole';
/*================================================*\
  :: FUNCTION ::
\*================================================*/
me.SetDateTime = function(){
	$('#expire_date').datetimepicker({
		format: 'DD/MM/YYYY HH:mm',
	});
};

me.LoadPermission = function (e){
	me.ClearData();
	var code = $(e).attr('data-code');
	var attr = JSON.parse($(e).attr('data-item'));
	var result = [];
	// console.log(attr);
	for(var i in attr)
		result.push({name : i,value : attr [i]});

	$('#frm_addeditsub input').iCheck('uncheck');
	$('#frm_addeditsub input.sub').iCheck('disable');
	ft.PutFormClass('frm_addeditsub',result);

	$('#frm_addeditsub input[name="code"]').val(code);
	$('#frm_addeditsub input[name="role_id"]').val(code);
	$('#frm_addeditsub input[name="menu_action"]').val('updatepermission');

	$('.btn_edit').show();
	$('.btn_add').hide();
	$('#modal-form-sub').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
};

me.EditSub = function () {
	$('#btnsubmitsub').click(function (e) {
		e.stopPropagation();
		$('form#frm_addeditsub').submit(function () {
			var form = $(this);
			$('.modal').modal('hide');
			alertify.confirm("Do you want Update.",
				function () {
					$.ajax({
						url: me.url + '-EditSub',
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
					alertify.error('Cancel Update');
				});

		});

	}).click();
};

me.CheckBox = function () {
	$('.main').on('ifChecked', function (event) {
		var val = $(this).val();

		$('.main' + val).iCheck('enable');
		// $('.main' + val).iCheck('check');
	});

	$('.main').on('ifUnchecked', function (event) {
		var val = $(this).val();
		$('.main' + val).iCheck('disable');
		$('.main' + val).iCheck('uncheck');
	});

};
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	me.CheckBox();
	// me.SetDateTime();
	me.LoadData(me.action.menu,1,30);
	// me.LoadCbo('project','getprojects','project_id','project_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});