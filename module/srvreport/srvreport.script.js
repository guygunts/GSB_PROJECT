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

		me.LoadDataReport(me.action.menu,1,page_size,start+' 00:00:00',stop+' 23:59:59',compare,txtsearch,1);
	});

};
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	me.SetDateTime();
	me.Search();
	me.LoadDataReport(me.action.menu,1,25,'','','day','');
	// me.LoadCbo('project','getprojects','project_id','project_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});