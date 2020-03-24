
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
                <button type="button" class="btn btn-default btn-rounded btn-sm pull-left" data-dismiss="modal">ปิด</button>
                <?php if($permiss[1]){ ?>
                    <button type="button" id="btn_add"  class="btn_add btn btn-danger btn-rounded btn-sm" onclick="me.Add();" title="เพิ่มข้อมูล"><i class="fa fa-plus"></i> เพิ่มข้อมูล</button>
                <?php }
                if($permiss[2]){ ?>
                    <button type="button" id="btn_edit" class="btn_edit btn btn-danger btn-rounded btn-sm" onclick="me.Edit();" title="บันทึก"><i class="fa fa-edit"></i> บันทึก</button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

