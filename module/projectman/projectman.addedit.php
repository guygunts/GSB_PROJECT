<form id="frm_addedit" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmit"></button>
    <input type="hidden" name="code" id="code">
    <input type="hidden" name="project_id" id="project_id">
    <input type="hidden" name="menu_action" id="menu_action">
    <div class="box box-danger">
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="project_name" class="control-label">Project Name</label>
                    <input id="project_name" name="project_name" type="text" required="required" maxlength="100"
                           class="form-control project_name">
                </div>
                <div class="form-group">
                    <label for="lang" class="control-label">Lang</label>
                    <select id="lang" name="lang" class="select form-control lang" required="required">
                        <option value="en">English</option>
                        <option value="th">Thai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" class="channel main mainIVR" name="channels[0][channel]" id="channel_IVR"  data-value="IVR"> &nbsp&nbsp&nbsp IVR
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" class="channel main mainChatbot" name="channels[1][channel]" id="channel_CHAT" data-value="Chat bot"> &nbsp&nbsp&nbsp Chat bot
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" class="active" name="active" id="active" value="1"> &nbsp&nbsp&nbsp Active
                    </label>
                </div>

            </div>

        </div>
    </div>

</form>