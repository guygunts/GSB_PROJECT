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
$('div#variation').html('');
/*================================================*\
  :: FUNCTION ::
\*================================================*/
me.ClearData = function () {
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
	var maininput = me.variation;
	if(cloneCount == 0){
		$('div[id=variation]').append(maininput);

	}else{
		maininput = maininput[0].outerHTML.replace(/dvvariation/g, 'dvvariation' + cloneCount);
		$('div[id^=dvvariation]').last().after(maininput);
	}
	$('#frm_addedit .sub).find(input[type="checkbox"].active').val(1);
	$('#frm_addedit .sub).find(input[type="checkbox"].active').iCheck('check');
};

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
	$("#variation-variation_text").tagsinput({
		trimValue: true
	});
	me.LoadData(me.action.menu,1,30);

});