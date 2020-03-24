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

/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	// me.CheckBox();
	// me.SetDateTime();
	me.LoadData(me.action.menu,1,30);
	// me.LoadCbo('project','getprojects','project_id','project_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});