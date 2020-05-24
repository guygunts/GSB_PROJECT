/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'function_id';
me.action.menu = 'getfunctions';
me.action.add = 'addfunction';
me.action.edit = 'updatefunction';
me.action.del = 'deletefunction';
/*================================================*\
  :: FUNCTION ::
\*================================================*/
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
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	// me.CheckBox();
	// me.SetDateTime();
	me.LoadData(me.action.menu,1,30);
	me.LoadCbo('category','getcategory','category_id','category_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});