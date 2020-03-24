<section class="content" id="content-viewlist">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <form id="frmsearch" class="form-inline" method="post" onsubmit="return false;">
                            <div class="form-group">
                                <div class="input-group date" id="start_date" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input searchdata"
                                           data-target="#start_date" name="start_date" placeholder="Start Date" required/>
                                    <span class="input-group-addon" data-target="#start_date"
                                          data-toggle="datetimepicker">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group date" id="end_date" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input searchdata"
                                           data-target="#end_date" name="end_date" placeholder="End Date" required/>
                                    <span class="input-group-addon" data-target="#end_date"
                                          data-toggle="datetimepicker">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-default" id="btnseatchsubmit">Search</button>

                        </form>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="allbox">
                    <button type="button" onclick="me.Export()" class="btn btn-default pull-right">Export</button>
                    <div class="col-xs-12" style="margin-top: 10px" id="mygraph">
                        <div class="col-xs-4"><canvas id="pieChart" height="300px"></canvas></div>
                        <div class="col-xs-4"><canvas id="barChart" height="300px"></canvas></div>
                        <div class="col-xs-4" id="mydata"></div>

                    </div>




                        <table id="tbView" class="table table-bordered table-striped dataTable" style="width: 100%">
                            <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>
                        </table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>