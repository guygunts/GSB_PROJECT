<div class="modal fade" id="buildgrammar" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalCenterTitle">Build Grammar</h5>
            </div>
            <div class="modal-body">
                <?php include "module/$mod/$mod.buildgrammar.php"; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-rounded btn-sm pull-left" data-dismiss="modal">ปิด
                </button>
                <?php if ($permiss[1]) { ?>
                    <button type="button" id="btn_add" class="btn_add btn btn-danger btn-rounded btn-sm"
                            onclick="me.AddGrammar();" title="เพิ่มข้อมูล"><i class="fa fa-plus"></i> เพิ่มข้อมูล
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="nowupload">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body text-center">
                <p>โปรดรอสักครู่ ...</p>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


