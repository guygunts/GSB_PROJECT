<form id="frm_addedit" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmit"></button>
    <input type="hidden" name="code" id="code">
    <input type="hidden" name="menu_id" id="menu_id">
    <input type="hidden" name="menu_action" id="menu_action">
    <div class="box box-danger">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="menu_name" class="control-label">Menu Name</label>
                    <input id="menu_name" name="menu_name" type="text" required="required" maxlength="100"
                           class="form-control">
                </div>
                <div class="form-group main">
                    <label for="menu_icon" class="control-label">Menu Icon</label>
                    <input id="menu_icon" name="menu_icon" type="text"  maxlength="20"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="menu_path" class="control-label">Menu Path</label>
                    <input id="menu_path" name="menu_path" type="text" required="required" maxlength="255"
                           class="form-control">
                </div>
                <div class="form-group sub">
                    <label for="menu_after" class="control-label">After</label>
                    <select id="menu_after" name="menu_after" class="after select form-control"></select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" class="" name="main_menu" id="main_menu" value="1"> &nbsp&nbsp&nbsp Main Menu
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" class="active" name="menu_active" id="menu_active" value="1"> &nbsp&nbsp&nbsp Active
                    </label>
                </div>

            </div>

        </div>
    </div>

</form>