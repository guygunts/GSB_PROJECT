<form id="frm_addedit" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmit"></button>
    <input type="hidden" name="code" id="code">
    <input type="hidden" name="role_id" id="role_id">
    <input type="hidden" name="menu_action" id="menu_action">
    <div class="box box-danger">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="role_name" class="control-label">Role Name</label>
                    <input id="role_name" name="role_name" type="text" required="required" maxlength="100"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="role_desc" class="control-label">Role Description</label>
                    <input id="role_desc" name="role_desc" type="text" required="required" maxlength="255"
                           class="form-control">
                </div>

            </div>

        </div>
    </div>

</form>