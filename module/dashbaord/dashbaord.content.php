<section class="content" id="content-viewlist">

    <div class="row">

        <div class="col-xs-12">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <div class="box-tools pull-right">
                        <form id="frmsearch" class="form-inline" method="post" onsubmit="return false;">
                            <div class="form-group">
                                <div class="input-group date" id="start_date" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                           data-target="#start_date" placeholder="Start Date"/>
                                    <span class="input-group-addon" data-target="#start_date"
                                          data-toggle="datetimepicker">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group date" id="end_date" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input"
                                           data-target="#end_date" placeholder="End Date"/>
                                    <span class="input-group-addon" data-target="#end_date"
                                          data-toggle="datetimepicker">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-default" id="btnsubmit">Search</button>

                        </form>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">

                    <div class="row" id="box1">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3 class="totalcall">0</h3>

                                    <p>Total Call</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-phone"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3 class="recog">0</h3>

                                    <p>Recognize</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                           </div>
                        </div>
                        <!-- ./col -->

                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3 class="nonrecog">0</h3>

                                    <p>Non-Recognize</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->

                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3><span class="peraccuracy">0</span><sup style="font-size: 20px">%</sup></h3>

                                    <p>Accuracy Rate</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->

                    </div>




                    <div class="row" id="box2">

                        <div class="col-md-12">

                            <!-- AREA CHART -->

                            <div class="box box-primary">

                                <div class="box-header with-border">

                                    <h3 class="box-title" id="box2_name">Weekly Accuracy Trend</h3>

                                </div>

                                <div class="box-body chart-responsive">

                                    <div class="chart" id="line-chart" style="height: 300px;"></div>

                                </div>

                                <!-- /.box-body -->

                            </div>

                            <!-- /.box -->


                        </div>


                    </div>

                    <div class="row" id="box3">

                        <div class="col-md-12">

                            <!-- AREA CHART -->

                            <div class="box box-primary">

                                <div class="box-header with-border">

                                    <h3 class="box-title" id="box3_name">Top 5 Event Statistic</h3>

                                </div>

                                <div class="box-body chart-responsive">
<!--                                    <div class="chart" id="chartContainer" style="height: 300px;"></div>-->
                                    <div class="chart" id="revenue-chart" style="height: 500px;"></div>
<!--                                    <div class="ct-chart ct-perfect-fourth" id="revenue-chart"></div>-->

                                </div>

                                <!-- /.box-body -->

                            </div>

                            <!-- /.box -->


                        </div>


                    </div>

                    <div class="row" id="box4">

                        <div class="col-md-12">

                            <!-- Bar chart -->

                            <div class="box box-primary">

                                <div class="box-header with-border">
                                    <h3 class="box-title" id="box4_name">Top 5 Event</h3>

                                </div>

                                <div class="box-body">

                                    <div id="bar-chart" style="height: 300px;"></div>

                                </div>

                                <!-- /.box-body-->

                            </div>

                            <!-- /.box -->


                        </div>

                    </div>

                    <div class="row" id="box5">

                        <div class="col-md-12">

                            <!-- Bar chart -->

                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title" id="box5_name">Service Overview</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <ct-visualization id="tree-container"></ct-visualization>
                                    </div>

<!--                                    <div id="chart-container" class="google-visualization-orgchart-table"></div>-->

                                </div>

                                <!-- /.box-body-->

                            </div>

                            <!-- /.box -->


                        </div>

                    </div>

                </div>

                <!-- /.box-body -->

            </div>

            <!-- /.box -->

        </div>

    </div>

</section>