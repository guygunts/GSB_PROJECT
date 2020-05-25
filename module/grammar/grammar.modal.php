<div class="modal fade addmodalform" id="add-modal-form" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalCenterTitle">Add New Category</h5>
            </div>
            <div class="modal-body">
                <form id="frm_addcategory" method="post" onsubmit="return false;">
                    <button type="submit" style="display: none" id="btnsubmitadd"></button>
                    <input type="hidden" name="category_id">
                    <input type="hidden" name="menu_action" value="addcategory">
                    <input type="hidden" name="parentcategory_id">
                    <input type="hidden" name="active" value="1">
                    <div class="box box-danger">
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="category_name" class="control-label">Category Name</label>
                                    <input id="category_name" name="category_name" type="text" required="required"
                                           maxlength="100"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <?php if (isset($permiss)) {
                    if ($permiss[1]) { ?>
                        <button type="button" id="btn_add" class="btn_add btn btn-danger btn-rounded btn-sm"
                                onclick="me.AddSub();" title="Add"><i class="fa fa-plus"></i> Add
                        </button>
                    <?php }
                }
                ?>
            </div>
        </div>
    </div>
</div>

