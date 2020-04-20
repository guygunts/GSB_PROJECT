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
/*================================================*\
  :: DEFAULT ::
\*================================================*/
$(document).ready(function(){
	me.SetUrl();

	me.LoadData(me.action.menu,1,30);
});