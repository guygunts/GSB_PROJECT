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

<div class="modal fade editmodalform" id="edit-modal-form" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit Category</h5>
            </div>
            <div class="modal-body">
                <form id="frm_editcategory" method="post" onsubmit="return false;">
                    <button type="submit" style="display: none" id="btnsubmitedit"></button>
                    <input type="hidden" name="category_id">
                    <input type="hidden" name="menu_action" value="updatecategory">
                    <input type="hidden" name="parentcategory_id">
                    <input type="hidden" name="active">
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
                    if ($permiss[2]) { ?>
                        <button type="button" id="btn_edit" class="btn_edit btn btn-danger btn-rounded btn-sm"
                                onclick="me.EditSub();" title="Edit"><i class="fa fa-save"></i> Edit
                        </button>
                    <?php }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade addsentensemodalform" id="addsentense-modal-form" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalCenterTitle">Add New Sentense</h5>
            </div>
            <div class="modal-body">
                <form id="frm_addsentenseedit" method="post" onsubmit="return false;">
                    <button type="submit" style="display: none" id="btnsubmitaddsentense"></button>
                    <input type="hidden" name="category_id">
                    <input type="hidden" name="menu_action" value="addsentence">
                    <input type="hidden" name="intent_id">
                    <input type="hidden" name="subintent_id">
                    <input type="hidden" name="sentence_id">
                    <div class="box box-danger">
                        <div class="box-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sentence_origin" class="control-label">Sentense Text</label>
                                    <input id="sentence_origin" name="sentence_origin" type="text" required="required"
                                           maxlength="100"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="sentence_process" class="control-label">Sentense Process</label>
                                    <input id="sentence_process" name="sentence_process" type="text" required="required"
                                           maxlength="100"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="type" class="control-label">Sentense Type</label>
                                    <select id="type" name="type" class="select form-control lang" required="required">
                                        <option value="1" selected>Text</option>
                                        <option value="2">Voice</option>
                                    </select>
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
            </div>
            <div class="modal-footer">

                <?php if (isset($permiss)) {
                    if ($permiss[1]) { ?>
                        <button type="button" id="btn_add" class="btn_add btn btn-danger btn-rounded btn-sm"
                                onclick="me.AddSentense();" title="Add"><i class="fa fa-plus"></i> Add
                        </button>
                    <?php }
                }
                ?>
            </div>
        </div>
    </div>
</div>