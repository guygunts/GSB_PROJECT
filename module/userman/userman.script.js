/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'user_id';
me.action.menu = 'getusers';
me.action.add = 'adduser';
me.action.edit = 'updateuser';
me.action.del = 'deleteuser';
/*================================================*\
  :: FUNCTION ::
\*================================================*/
me.SetDateTime = function(){
	$('#expire_date').datetimepicker({
		format: 'DD/MM/YYYY HH:mm'
	});
};

me.SetExpire = function (){
	$('#expire_date_status').on('ifChecked', function (event) {
		$(this).val(1);
		$(this).iCheck('check');
		$('#expire_date').datetimepicker('clear');
		$('#expire_date').datetimepicker('disable');
		// $('#expire_date').attr('disabled',true);
	});

	$('#expire_date_status').on('ifUnchecked', function (event) {
		$(this).val(0);
		$(this).iCheck('uncheck');
		$('#expire_date').datetimepicker('enable');
		// $('#expire_date').attr('disabled',false);
	});
};
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	me.SetDateTime();
	me.SetExpire();
	me.LoadData(me.action.menu,1,30);
	// me.LoadCbo('project','getprojects','project_id','project_name');
	me.LoadCbo('role_id','getroles','role_id','role_name');
});