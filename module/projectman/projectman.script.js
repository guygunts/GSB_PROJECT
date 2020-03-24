/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'project_id';
me.action.menu = 'getprojects';
me.action.add = 'addproject';
me.action.edit = 'updateproject';
me.action.del = 'deleteproject';
/*================================================*\
  :: FUNCTION ::
\*================================================*/
me.Load = function (e) {
	me.ClearData();
	var code = $(e).attr('data-code');
	var attr = JSON.parse($(e).attr('data-item'));
	var result = [];
	var res = [];
	var size = 0;
	for(var i in attr)
		if(i == 'channel'){
			res = attr[i].split(",");
		}else{
			result.push({name : i,value : attr [i]});
		}


	$('#frm_addedit input').iCheck('uncheck');
	ft.PutFormID('frm_addedit',result);
	$('#lang').val(attr.language);
	// ft.PutFormID('frm_addedit',result);
	$('#frm_addedit input[name="code"]').val(code);
	$('#frm_addedit input[name="project_id"]').val(code);
	$('#frm_addedit input[name="menu_action"]').val(me.action.edit);
	size = res.length;
	for(i = 0;i<=size;++i){
		$('#frm_addedit [data-value="'+res[i]+'"]').val(res[i]);
		$('#frm_addedit [data-value="'+res[i]+'"]').iCheck('check');
	}



	$('.btn_edit').show();
	$('.btn_add').hide();
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

};

me.CheckBox = function () {
	$('#channel_IVR').on('ifChecked', function (event) {
		$(this).val(event.target.dataset.value);
		$(this).iCheck('update');
		// $('#channel_IVR').iCheck('check');
	});

	$('#channel_IVR').on('ifUnchecked', function (event) {
		$(this).val(0);
		$(this).iCheck('update');
		// var val = $(this).val();
		// $('#channel_IVR').iCheck('uncheck');
	});

	$('#channel_CHAT').on('ifChecked', function (event) {
		$(this).val(event.target.dataset.value);
		$(this).iCheck('update');
		// var val = $(this).val();
		// $('#channel_CHAT').val(val);
	});

	$('#channel_CHAT').on('ifUnchecked', function (event) {
		$(this).val(0);
		$(this).iCheck('update');
		// var val = $(this).val();
		// $('#channel_CHAT').iCheck('uncheck');
	});

	$('#active').on('ifChecked', function (event) {
		$(this).val(1);
		$(this).iCheck('update');
		// var val = $(this).val();
		// $('#channel_CHAT').val(val);
	});

	$('#active').on('ifUnchecked', function (event) {
		$(this).val(0);
		$(this).iCheck('update');
		// var val = $(this).val();
		// $('#channel_CHAT').iCheck('uncheck');
	});

};

me.New = function () {
	me.ClearData();
	$('.btn_edit').hide();
	$('.btn_add').show();
	$('#frm_addedit input[name="menu_action"]').val(me.action.add);
	$('#frm_addedit input[type="checkbox"].main').val(0);
	$('#frm_addedit input[type="checkbox"].main').iCheck('uncheck');
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});
};
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	me.CheckBox();
	me.LoadData(me.action.menu,1,25);
	// me.LoadCbo('project','getprojects','project_id','project_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});