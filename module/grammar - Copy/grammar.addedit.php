<form id="frm_addedit" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmit"></button>
    <input type="hidden" name="code" id="code">
    <input type="hidden" name="function_id" id="function_id">
    <input type="hidden" name="menu_action" id="menu_action">
    <div class="box box-danger">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="function_name" class="control-label">function Name</label>
                    <input id="function_name" name="function_name" type="text" required="required" maxlength="100"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="function_desc" class="control-label">function Description</label>
                    <input id="function_desc" name="function_desc" type="text" required="required" maxlength="255"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" class="active" name="function_active" id="function_active" value="1"> &nbsp&nbsp&nbsp Active
                    </label>
                </div>

            </div>

        </div>
    </div>

</form>