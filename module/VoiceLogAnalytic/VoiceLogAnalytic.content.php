<section class="content" id="content-viewlist">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools" style="float: none !important;text-align: right">
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

                            <div class="form-group">
                                <select class="form-control searchdata" name="page_size" id="page_size">
                                    <option value="25" selected>25 / Page</option>
                                    <option value="50">50 / Page</option>
                                    <option value="75">75 / Page</option>
                                    <option value="100">100 / Page</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control searchdata" name="grammar" id="grammar">
                                    <option value="" selected>== GRAMMAR ==</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control searchdata" name="confiden" id="confiden">
                                    <option value="" selected>== CONFIDEN ==</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control searchdata" name="qc_status" id="qc_status">
                                   <option value="" selected>== QC ==</option>
                                    <option value="P">Pass</option>
                                    <option value="F">Fail</option>
                                    <option value="G">Garbage</option>
                                    <option value="O">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control searchdata select2" name="intent" id="intent">
                                    <option value="" selected>== INTENT ==</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control searchdata" type="text" id="text_search" name="text_search" placeholder="KeyWord">
                            </div>
                            <button type="submit" class="btn btn-default" id="btnsearchsubmit">Search</button>

                        </form>
                    </div>

                    <div class="box-tools" style="float: none !important;text-align: center;margin-top: 20px">
                        <form id="frmsearchrandom" class="form-inline" method="post" onsubmit="return false;">

                            <button type="submit" class="btn btn-default" id="btnsearchrandomsubmit">RANDOM</button>
                            <div class="form-group">
                                <input class="form-control" type="text" id="random_num" name="random_num" placeholder="" value="" required>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                        <table id="tbView" class="table table-bordered table-striped dataTable" style="width: 100%"></table>


<!--                        <form id="frmresult" style="display: none;">-->
<!--                            <div class="form-group">-->
<!--                                <label for="result">Result</label>-->
<!--                                <input type="hidden" name="chnn" id="chnn"/>-->
<!--                                <textarea class="form-control" id="result"></textarea>-->
<!--                            </div>-->
<!--                            <button type="button" onclick="me.UpdateVoice();" class="btn btn-default" style="float: right;">Submit</button>-->
<!--                        </form>-->

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>