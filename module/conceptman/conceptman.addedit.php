<form id="frm_addedit" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmit"></button>
    <input type="hidden" name="code" id="code">
    <input type="hidden" name="concept_id" id="concept_id">
    <input type="hidden" name="menu_action" id="menu_action">
    <div class="box box-danger">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="concept_name" class="control-label">Concept Name</label>
                    <input id="concept_name" name="concept_name" type="text" required="required" maxlength="100"
                           class="form-control concept_name">
                </div>
                <div class="form-group">
                    <label for="type" class="control-label">Type</label>
                    <select id="type" name="type" class="select form-control type" required="required">
                        <option value="1">Normal</option>
                        <option value="2">Build in</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lang" class="control-label">Lang</label>
                    <select id="lang" name="lang" class="select form-control lang" required="required">
                        <option value="Thai" selected>Thai</option>
                        <option value="English">English</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" class="active" name="active" id="active" value="1"> &nbsp&nbsp&nbsp
                        Active
                    </label>
                </div>
                <div class="form-group">
                    <button type="button" onclick="me.OpenPopup();" class="btn btn-info btn_add" id="addvariation"><i class="fa fa-plus"></i> Add
                        Variation
                    </button>
                </div>


            </div>

            <div class="col-md-12 sub" id="variation">
                <div style="margin-left: 20px;margin-right: 20px;padding:20px;border: 1px dashed;" id="dvvariation"
                     class="variationsub row">
                    <div class="col-md-12">
                    <div class="form-group col-md-6">
                        <input name="variation[zero][concept_variation_id]" id="mconcept_variation_id" type="hidden">
                        <label for="variation-concept_result" class="control-label">Concept Result <small
                                    style="color:red">*</small></label>
                        <input id="mvariation-concept_result" name="variation[zero][concept_result]" type="text" maxlength="100"
                               class="form-control empty" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="variation-variation_text" class="control-label">Variation Name <small
                                    style="color:red">*</small></label>
                        <input id="mvariation-variation_text" name="variation[zero][variation_text]" type="text" maxlength="100"
                               class="form-control variation-variation_text empty" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label>
                            <input type="checkbox" class="active" name="variation[zero][active]" id="mvariation-active"
                                   value="1"> &nbsp&nbsp&nbsp Active
                        </label>
                        <button type="button" class="btn btn-danger btn-xs btn_add" style="float: right;" data-code="dvvariation" onclick="me.RemoveSub(this)"><i class="fa fa-minus"></i></button>

                    </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</form>