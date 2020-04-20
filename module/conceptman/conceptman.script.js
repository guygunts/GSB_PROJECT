/*================================================*\
*  Author : BoyBangkhla
*  Created Date : 24/01/2015 09:09
*  Module : Script
*  Description : Backoffice javascript
*  Involve People : MangEak
*  Last Updated : 24/01/2015 09:09
\*================================================*/
me.action.main = 'concept_id';
me.action.menu = 'getconcepts';
me.action.add = 'addconcept';
me.action.edit = 'updateconcept';
me.action.del = 'deleteconcept';
me.variation = $('div#dvvariation').clone();
me.childEditors = {};  // Globally track created chid editors

/*================================================*\
  :: FUNCTION ::
\*================================================*/
me.ClearData = function () {
	$('input[name="variation-active"]').iCheck('destroy');
	$('#frm_addedit input').val('');
	$('#frm_addedit select option:eq(0)').prop("selected", true);
	$('#frm_addedit textarea').val('');
	$('#frm_addedit input[type="checkbox"]').iCheck('uncheck');
	$('#frm_addedit input[type="checkbox"].active').val(1);
	$('#frm_addedit input[type="checkbox"].active').iCheck('check');
	$('div#variation').html('');

	// $('#frm_addedit .sub').css('display','');
	// me.DelStar('variation-concept_result');
	// me.DelStar('variation-variation_text');
	// $('#variation-concept_result').attr('required',false);
	// $('#variation-variation_text').attr('required',false);

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
	$.each(attr.variation, function (i, result) {
		me.OpenPopupItem(result);
	});
	$('.btn_edit').show();
	$('.btn_add').hide();
	$('#modal-form').modal({backdrop: 'static', keyboard: true, show: true, handleUpdate: true});

};

me.OpenPopup_ = function (){
	if($('#frm_addedit .sub').css('display') == 'none'){
		$('#frm_addedit .sub').css('display','block');
		$('#variation-concept_result').attr('required',true);
		$('#variation-variation_text').attr('required',true);
		me.AddStar('variation-concept_result');
		me.AddStar('variation-variation_text');
	}else{
		$('#frm_addedit .sub').css('display','none');
		$('#variation-concept_result').val('');
		$('#variation-concept_result').attr('required',false);
		$('#variation-variation_text').tagsinput('removeAll');
		$('#variation-variation_text').attr('required',false);
		me.DelStar('variation-concept_result');
		me.DelStar('variation-variation_text');
	}
};

me.OpenPopup = function(){
	var cloneCount = $('div.variationsub').length;
	var cloneCount2 = $('input[name="variation-active"]').length;
	var maininput = me.variation;
	console.log(maininput);
	var mapObj = {
		'dvvariation':"dvvariation",
		'mvariation-variation_text':"mvariation-variation_text",
		'mvariation-concept_result':"mvariation-concept_result",
		'mvariation-active':"mvariation-active",
		'mconcept_variation_id':"mconcept_variation_id",
		'zero':"",
	};
	maininput = maininput[0].outerHTML.replace(/dvvariation|mvariation-variation_text|mvariation-concept_result|mvariation-active|zero|mconcept_variation_id/g, function(matched){
		return mapObj[matched]+cloneCount;
	});

	if(cloneCount == 0){

	$('div[id=variation]').append(maininput);
	}else{
		$('div[id^=dvvariation]').last().after(maininput);

	}
	// console.log('after');
	// console.log(maininput);
	$("#mvariation-variation_text"+cloneCount).tagsinput({
		trimValue: true
	});

	$('#dvvariation'+cloneCount+' input[type="checkbox"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		labelHover: true,
		increaseArea: '20%' // optional
	});
	$('#dvvariation'+cloneCount+' input[type="checkbox"]').val(1);
	$('#dvvariation'+cloneCount+' input[type="checkbox"]').iCheck('check');



};

me.OpenPopupItem = function(data){
	var cloneCount = $('div.variationsub').length;
	var cloneCount2 = $('input[name="variation-active"]').length;
	var maininput = me.variation;
	console.log(maininput);
	var mapObj = {
		'dvvariation':"dvvariation",
		'mvariation-variation_text':"mvariation-variation_text",
		'mvariation-concept_result':"mvariation-concept_result",
		'mvariation-active':"mvariation-active",
		'mconcept_variation_id':"mconcept_variation_id",
		'zero':"",
	};
	maininput = maininput[0].outerHTML.replace(/dvvariation|mvariation-variation_text|mvariation-concept_result|mvariation-active|zero|mconcept_variation_id/g, function(matched){
		return mapObj[matched]+cloneCount;
	});

	if(cloneCount == 0){

		$('div[id=variation]').append(maininput);
	}else{
		$('div[id^=dvvariation]').last().after(maininput);

	}
	// console.log('after');
	// console.log(maininput);


	$('#mconcept_variation_id'+cloneCount).val(data.concept_variation_id);
	$('#mvariation-concept_result'+cloneCount).val(data.concept_result);
	$('#mvariation-variation_text'+cloneCount).val(data.variation_text);
	$('#mvariation-active'+cloneCount).val(data.active);
	if(data.active == 1){
		$('#mvariation-active'+cloneCount).iCheck('check');
	}

	$("#mvariation-variation_text"+cloneCount).tagsinput({
		trimValue: true
	});

	$('#dvvariation'+cloneCount+' input[type="checkbox"]').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
		labelHover: true,
		increaseArea: '20%' // optional
	});


};

me.RemoveSub = function (e){
	var code = $(e).attr('data-code');
	$('#'+code).remove();
}

me.Add = function () {
	$('#btnsubmit').click(function (e) {
		e.stopPropagation();
		if($('#variation-variation_text').attr('required') == 'required'){
			if(!$('#variation-variation_text').val()){
				$('#variation-variation_text').tagsinput('focus');
				return false;
			}
		}
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
					alertify.error('Cancel Add');
				});

		});

	}).click();
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
										"searchable": false,
										"orderable": false
									},
									{
										"width": "5%",
										"targets": 1,
										"searchable": false
									},
									{
										"width": "5%",
										"targets": -1,
										"searchable": false,
										"orderable": false
									}
								],
								createdRow: function( row, data, dataIndex ) {
									// Set the data-status attribute, and add a class
									$( row ).find('td:eq(0)')
										.attr('data-name', data.variation);
								},
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

					$('#tbView tbody').on('click', 'td.details-control', function () {
						var tr = $(this).closest('tr');
						var row = me.table.row( tr );
						var rowData = row.attr('data-name');
						console.log(rowData);

						if ( row.child.isShown() ) {
							// This row is already open - close it
							row.child.hide();
							tr.removeClass('shown');

							// Destroy the Child Datatable
							$('#' + rowData.name.replace(' ', '-')).DataTable().destroy();
						}
						else {
							// Open this row
							row.child(me.format(rowData)).show();
							var id = rowData.name.replace(' ', '-');


							$('#' + id).DataTable({
								dom: "t",
								data: [rowData],
								columns: [
									{ data: "concept_result", title: 'Concept Result' },
									{ data: "variation_text", title: 'Variation Text' },
									{ data: "active", title: 'Active' },
								],
								scrollY: '100px',
								select: true,
							});

							tr.addClass('shown');
						}
					} );

					break;
				default :
					alertify.alert(data.msg);
					break;
			}
		}
	});
};

me.format = function (rowData) {
	return '<table id="' + rowData.name.replace(' ', '-') + '" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
		'</table>';
}


/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();

	me.LoadData(me.action.menu,1,30);
});