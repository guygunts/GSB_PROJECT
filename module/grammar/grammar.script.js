/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'category_id';
me.action.menu = 'getintentbycateid';
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
								if(!$('#'+val).treeview('getSelected')[0].nodes){
									me.LoadCboSub('tree','getsubcategory',data.id,data.index);
								}
								me.LoadData(me.action.menu,data.id,1,30);
							}else if(data.level == 2){

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

me.LoadData = function(menu,id,page_id,page_size,readd=''){

	$.ajax({
		url: me.url + '-View',
		type:'POST',
		dataType:'json',
		cache:false,
		data:{ menu_action : menu , category_id : id , page_id : page_id , page_size : page_size},
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
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();
	// me.CheckBox();
	// me.SetDateTime();
	// me.LoadData(me.action.menu,1,30);
	me.LoadCbo('tree','getcategory','category_id','category_name');
	// me.LoadCbo('role_id','getroles','role_id','role_name');
});