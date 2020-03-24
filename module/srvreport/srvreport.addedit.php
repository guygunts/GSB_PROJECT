<form id="frm_addedit" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmit"></button>
    <input type="hidden" name="code" id="code">
    <input type="hidden" name="user_id" id="user_id">
    <input type="hidden" name="menu_action" id="menu_action">
    <div class="box box-danger">
        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prefix" class="control-label">Prefix</label>
                    <input id="prefix" name="prefix" type="text" required="required" maxlength="100"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="first_name" class="control-label">First Name</label>
                    <input id="first_name" name="first_name" type="text" required="required" maxlength="100"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="last_name" class="control-label">Last Name</label>
                    <input id="last_name" name="last_name" type="text" required="required" maxlength="100"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input id="email" name="email" type="email" required="required" maxlength="100"
                           class="form-control">
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <label for="expire_date" class="control-label">Expire Date</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input id="expire_date" name="expire_date" type="text"
                                   class="form-control dtpk datetimepicker-input" data-toggle="datetimepicker"
                                   data-target="#expire_date">
                            <span class="input-group-addon">
                            <input type="checkbox" id="expire_date_status" name="expire_date_status" value="1">
                          </span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label for="user_name" class="control-label">User Name</label>
                    <input id="user_name" name="user_name" type="text" class="form-control" maxlength="40"
                           required="required" pattern="^[a-zA-Z0-9]{0,20}$">
                </div>

                <div class="form-group">
                    <label for="password" class="control-label">Password</label>
                    <input id="password" name="password" type="text" class="form-control" maxlength="20"
                           required="required" pattern="^[a-zA-Z0-9]{0,20}$">
                </div>

                <div class="form-group">
                    <label for="user_type" class="control-label">User Type</label>
                    <select id="user_type" name="user_type" class="select form-control" required="required">
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="role_id" class="control-label">Role</label>
                    <select id="role_id" name="role_id" class="select form-control" required="required"></select>
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