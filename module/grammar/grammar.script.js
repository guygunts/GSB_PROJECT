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
		url: me.url + '-LoadCbo',
		type: "POST",
		dataType: "json",
		cache: false,
		data: {menu_action : menu , code : code , name : name},
		success: function(data) {

			switch (data.success) {
				case "COMPLETE":
					$('#'+val).treeview({

						data: data.item,
						onNodeSelected: function(event, data) {
							console.log(event)
							console.log(data)
							// console.log( _.size($('#'+val).treeview('getParents', $('#'+val).treeview('getSelected'))))
							if(data.level == 1){
								// if($('#'+val).treeview('getSelected')[0].nodes === 'undefined'){
									me.LoadCboSub('tree','getsubcategory',data.id,data.index);
								// }

							}

						}

					});

					break;
				default:
					alertify.alert(data.msg);
					break;
			}
		}
	});
};

me.LoadCboSub = function(val,menu,code,index) {
	$.ajax({
		url: me.url + '-LoadCboSub',
		type: "POST",
		dataType: "json",
		cache: false,
		data: {menu_action : menu , code : code},
		success: function(data) {

			switch (data.success) {
				case "COMPLETE":
					// $('#'+val).treeview(true).addNode(data.item, $('#'+val).treeview('getSelected'))
					// $('#'+val).treeview('removeNode', [ $('#'+val).treeview('getSelected'), { silent: true } ]);
					$('#'+val).treeview('addNode', [ data.item, $('#'+val).treeview('getSelected'), index, { silent: true, ignoreChildren: true } ]);
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
	me.LoadCbo('tree','getcategory','category_id','category_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});