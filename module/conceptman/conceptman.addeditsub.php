<form id="frm_addeditsub" method="post" onsubmit="return false;">
    <button type="submit" style="display: none" id="btnsubmitsub"></button>
    <input type="hidden" name="code" id="code">
    <input type="hidden" name="role_id" id="role_id">
    <input type="hidden" name="menu_action" id="menu_action">
    <div class="box box-danger">
        <div class="box-body">
            <div class="col-md-6">
                <blockquote>
                    <p class="text-red">Menus</p>
                </blockquote>

                <?php
                foreach ((array)$menu as $i => $item) {
                    if (count($item['sub_menu'][0]) > 0) {
                        ?>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="menu[<?php echo $i?>][menu_id]" class="main menu_<?php echo $item['menu_id']?>"
                                       data-sub="<?php echo $item['menu_id'] ?>"
                                       id="check<?php echo $item['menu_task'] ?>"
                                       value="<?php echo $item['menu_id'] ?>">
                                &nbsp&nbsp&nbsp <?php echo $item['menu_name'] ?>
                            </label>
                        </div>
                        <?php
                        foreach ((array)$item['sub_menu'] as $m => $value) {
                            ?>
                            <div class="form-group">
                                <label>
                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input
                                            data-main="<?php echo $item['menu_id'] ?>"
                                            class="sub sub_<?php echo $value['sub_menu_id']?>_<?php echo $item['menu_id']?> main<?php echo $item['menu_id'] ?>" type="checkbox"
                                            name="menu[<?php echo $i?>][sub_menus][<?php echo $m?>][submenu_id]"
                                            id="check<?php echo $value['sub_menu_task'] ?>"
                                            value="<?php echo $value['sub_menu_id'] ?>">
                                    &nbsp&nbsp&nbsp <?php echo $value['sub_menu_name'] ?>
                                </label>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="menu[<?php echo $i?>][menu_id]" class="main menu_<?php echo $item['menu_id']?>"
                                       id="check<?php echo $item['menu_task'] ?>"
                                       value="<?php echo $item['menu_id'] ?>">
                                &nbsp&nbsp&nbsp <?php echo $item['menu_name'] ?>
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                <?php } ?>

            </div>
            <div class="col-md-6">
                <blockquote>
                    <p class="text-red">Functions</p>
                </blockquote>
                <?php
                foreach ((array)$permission as $i => $item) {
                    ?>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="function[<?php echo $i?>][function_id]" class="func_<?php echo $item['function_id'] ?>"
                                   id="check<?php echo $item['function_id'] ?>"
                                   value="<?php echo $item['function_id'] ?>">
                            &nbsp&nbsp&nbsp <?php echo $item['function_name'] ?>
                        </label>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

</form>