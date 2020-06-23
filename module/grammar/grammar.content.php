<section class="content" id="content-viewlist">
    <div class="row">
        <div class="col-xs-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools pull-left">
                        <?php if (isset($permiss)) {
                            if ($permiss[1]) { ?>

                                    <button type="button" class="btn btn-primary btn-flat btn-sm" onclick="me.NewCat();"
                                            title="<?php echo $permiss[1]['name']; ?>">
                                        <i class="fa fa-plus"></i> <?php echo $permiss[1]['name']; ?> Category
                                    </button>

                            <?php }
                        } ?>
<!--                        &nbsp;&nbsp;<button type="button" class="btn btn-primary btn-flat btn-sm" onclick="me.NewCat();"-->
<!--                                title="Move">-->
<!--                            <i class="fa fa-plus"></i> Move-->
<!--                        </button>-->
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <div id="tree"></div>

                </div>
                <!-- /.box-body -->
            </div>

            <div id="tree"></div>
        </div>
        <div class="col-xs-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <?php if (isset($permiss)) {
                            if ($permiss[1]) { ?>
                                <button type="button" class="btn btn-primary btn-flat btn-sm" id="btnadd" onclick="me.New();"
                                        title="<?php echo $permiss[1]['name']; ?>">
                                    <i class="fa fa-plus"></i> <?php echo $permiss[1]['name']; ?>
                                </button>

                                <button type="button" class="btn btn-primary btn-flat btn-sm" id="btnaddsentense" style="display: none" onclick="me.NewSentense();"
                                        title="<?php echo $permiss[1]['name']; ?>">
                                    <i class="fa fa-plus"></i> <?php echo $permiss[1]['name']; ?> Sentense
                                </button>
                            <?php }
                        } ?>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <table id="tbView" class="table table-bordered table-striped dataTable" style="width: 100%"></table>
                    <table id="tbViewSub" class="table table-bordered table-striped dataTable" style="width: 100%;"></table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>