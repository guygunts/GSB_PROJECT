
<div class="modal fade modalform" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo $mymenu["name_$lang"]; ?></h5>
            </div>
            <div class="modal-body">
                <?php include "module/$mod/$mod.addedit.php"; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-rounded btn-sm pull-left" data-dismiss="modal">Close</button>
                <?php if($permiss[1]){ ?>
                    <button type="button" id="btn_add"  class="btn_add btn btn-danger btn-rounded btn-sm" onclick="me.Add();" title="Add"><i class="fa fa-plus"></i> Add</button>
                <?php }
                if($permiss[2]){ ?>
                    <button type="button" id="btn_edit" class="btn_edit btn btn-danger btn-rounded btn-sm" onclick="me.Edit();" title="Save"><i class="fa fa-edit"></i> Save</button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

