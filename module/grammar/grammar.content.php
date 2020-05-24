<section class="content" id="content-viewlist">
    <div class="row">
        <div class="col-xs-4">
            <div id="tree"></div>
        </div>
        <div class="col-xs-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <?php if ($permiss[1]) { ?>
                            <button type="button" class="btn btn-primary btn-flat btn-sm" onclick="me.New();"
                                    title="<?php echo $permiss[1]['name']; ?>">
                                <i class="fa fa-plus"></i> <?php echo $permiss[1]['name']; ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                        <table id="tbView" class="table table-bordered table-striped dataTable" style="width: 100%"></table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>