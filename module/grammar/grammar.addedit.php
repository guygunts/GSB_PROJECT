<form id="frm_addedit" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmit"></button>
    <input type="hidden" name="code" id="code">
    <input type="hidden" name="category_id">
    <input type="hidden" name="menu_action">
    <div class="box box-danger">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="user_question" class="control-label">User Question</label>
                    <input id="user_question" name="user_question" type="text" required="required" maxlength="100"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="intent_tag" class="control-label">Intent Tag</label>
                    <input id="intent_tag" name="intent_tag" type="text" required="required" maxlength="255"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" class="active" name="active" id="active" value="1"> &nbsp&nbsp&nbsp
                        Active
                    </label>
                </div>
                <div class="form-group">
                    <button type="button" onclick="me.OpenPopup();" class="btn btn-info btn_add btn-xs"
                            id="addvariation"><i
                                class="fa fa-plus"></i> Add
                        Sub
                    </button>
                </div>
            </div>

            <div class="col-md-12 sub" id="variation">
                <div style="margin-left: 20px;margin-right: 20px;padding:20px;border: 1px dashed;" id="dvsubintent"
                     class="variationsub row">
                    <div class="col-md-12">
                        <div class="form-group col-md-6">
                            <!--                            <input name="variation[zero][concept_variation_id]" id="mconcept_variation_id" type="hidden">-->
                            <label for="subintent-subintent_tag" class="control-label">Intent TAG <small
                                        style="color:red">*</small></label>
                            <input id="msubintent-subintent_tag" name="subintent[zero][subintent_tag]" type="text"
                                   maxlength="100"
                                   class="form-control empty" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="subintent-type" class="control-label">Type <small
                                        style="color:red">*</small></label>
                            <select id="msubintent-type" name="subintent[zero][type]"
                                    class="form-control subintent-type empty" required>
                                <option value="0">Robust</option>
                                <option value="1" selected>Static</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>
                                <input type="checkbox" class="active" name="subintent[zero][active]"
                                       id="msubintent-active"
                                       value="1"> &nbsp&nbsp&nbsp Active
                            </label>
                            <button type="button" class="btn btn-danger btn-xs btn_add" style="float: right;"
                                    data-code="dvsubintent" onclick="me.RemoveSub(this)"><i class="fa fa-trash-o"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


</form>