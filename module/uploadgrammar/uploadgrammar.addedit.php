<form id="frm_addedit" name="frm_addedit" enctype="multipart/form-data" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmit"></button>
<!--    <input type="hidden" name="menu_action" id="menu_action">-->
    <div class="box box-danger">
        <div class="box-body">

            <div class="col-md-12">





                <div class="form-group">
                    <label for="user_type" class="control-label">Project Name</label>
                    <select id="project_id" name="project_id" class="select form-control" required="required">

                    </select>
                </div>

                <div class="form-group">
                    <label for="file_desc" class="control-label">Description</label>
                    <input id="file_desc" name="file_desc" type="text" required="required" maxlength="100"
                           class="form-control">
                </div>

                <div class="form-group">
                    <label for="file" class="control-label">Upload</label>
                    <input id="file" name="file" type="file" required="required"
                           class="form-control">
                </div>



            </div>
        </div>
    </div>

</form>