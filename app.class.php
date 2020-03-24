<?php

class AppClass extends SuperClass{
  public $lang='th';
  public $permission=array();
  public $alert=array();
  public $define=array();
  public $content=array();

  public function __construct($table=''){
    parent::__construct();
    $this->table = $table;
  }

  public function View(){
    $table=$this->table;
    $lang=$this->lang;
    $search = (array)$this->attr['search'];
    $column=$this->attr['columns'];
    $sortby = 'code';
    $sortorder = 'DESC';
    $where = '';
    $columns = array();
    foreach($search as $i => $value){
      $searchby = $value['searchby'];
      $searchkey = $value['searchkey'];
      if(!empty($searchby) && ($searchkey!='')){
//        $where .= "tb.$searchby LIKE '%$searchkey%' AND ";
        switch($value['searchoption']){
          case 'LIKE' : $where .= "tb.$searchby LIKE '%$searchkey%' AND "; break;
          case '=' : $where .= "tb.$searchby = '$searchkey' AND "; break;
          case '>' : $where .= "tb.$searchby > '$searchkey' AND "; break;
          case '<' : $where .= "tb.$searchby < '$searchkey' AND "; break;
          case '<>' : $where .= "tb.$searchby <> '$searchkey' AND "; break;
        }
      }
    }
    
    foreach($column as $i=>$value){
      if($value['name']){
        $columns[] = $value['name'];
      }
    }
    

    $sql="
    SELECT 
      count(*) as cnt
    FROM (
    
        SELECT 
          tb.*  
        FROM 
          $table tb

    ) tb
    WHERE
      $where
      tb.code <> 0
    ";
    
//    echo $sql;
    $query=$this->db_query($sql);
    $count=0;
    if($row=$this->db_fetch_assoc($query)){
      $count=$row['cnt'];
    }
  
    $start = $this->attr['start'];
    $length = ($this->attr['page'] * $this->attr['length']);
    
    
		$sql="

    SELECT 
      tb.*
    FROM (
    
        SELECT 
          tb.*  
        FROM 
          $table tb

    ) tb
    WHERE
      $where
      tb.code <> 0
    ORDER BY 
        tb.$sortby $sortorder
    LIMIT 
        $start, $length
    ;";

    $tmp=array();
    if($query=$this->db_query($sql)){
      while($row=$query->fetch_assoc()){
        $tmp[] = $row;
      } 
    }
  
    $result["recordsTotal"]=intval($count);
    $result["recordsFiltered"]=intval($count);
    $result["data"]=array();


    $i=0;
    $v=0;
    
    $btn = $this->ViewButton($this->table, $this->permission, array(
      'EDIT' => $this->define['OPEN'],
      'DEL' => $this->define['DEL']
     ));
    
     foreach((array)$tmp as $i => $value){   


      $result['data'][$i]['no'] = ++$start;
      $result['data'][$i]['btn'] = str_replace("{code}", $value['code'], $btn);
      
      foreach((array)$columns as $j => $item){
        $result['data'][$i][$item]=$value[$item];
      }  
      
    }  

 

    $this->freeresult($query);
    $this->dbclose();


    return $result;
  }
  
  public function ViewButton($mod, $permission, $input){
    $btn = array();

    if(array_key_exists("EDIT", $input) && $permission['EDIT'][$mod]){
      $btn[] = "<button class='btn btn-outline-default btn-xs' type='button' onclick='me.Load({code});' title='".$input['EDIT']."'><i class='fa fa-edit text-primary'></i></button>";
//      $btn[] = "<li class='text-left'><a href='javascript:;' onclick='me.LoadEdit({code});'><i class='fa fa-edit success'></i> ".$input['EDIT']."</a></li>";
    }
    if(array_key_exists("DEL", $input) && $permission['DEL'][$mod]){
      $btn[] = "<button class='btn btn-outline-default btn-xs' type='button' onclick='me.Del({code});' title='".$input['DEL']."'><i class='fa fa-trash text-danger'></i></button>";
//      $btn[] = "<li class='text-left'><a href='javascript:;' onclick='me.DelView({code});'><i class='fa fa-trash-o danger'></i> ".$input['DEL']."</a></li>";
    }
    if(array_key_exists("PRINT", $input) && $permission['PRINT'][$mod]){
      $btn[] = "<button class='btn btn-outline-default btn-xs' type='button' onclick='me.Print({code});' title='".$input['PRINT']."'><i class='fa fa-print text-info'></i></button>";
//      $btn[] = "<li class='text-left'><a href='javascript:;' onclick='me.Print({code});'><i class='fa fa-print info'></i> ".$input['PRINT']."</a></li>";
    }
   

    if(empty($btn)){
      $result = "";
    }else{
      $result = implode(" ", $btn);
    }

    return $result;
  }

  public function ViewButton_($type, $permission, $name, $code){
    if($permission){
      switch($type){
        case 'edit' :
          $result="<a class='btn-sm btn-floating my-1 btn-success' onclick='me.Load($code);' title='เปิดเพื่อแก้ไข'><i class='fa fa-edit mt-0'></i></a> ";
          break;
        case 'del' :
          $result="<a class='btn-sm btn-floating my-1 btn-danger' onclick='me.Del($code);' title='ลบ'><i class='fa fa-trash mt-0'></i> $name</a> ";
          break;
        case 'print' :
          $result="<a class='btn-sm btn-floating my-1 btn-primary' onclick='me.Print($code);' title='พิมพ์'><i class='fa fa-print'></i> $name</a> ";
          break;
      }
    }else{
      $result="";
    }

    return $result;
  }
  
  public function ViewButtonSubNew($type, $permission, $name, $code){
    if($permission){
      switch($type){
        case 'edit' :
          $result="<a class='btn-sm btn-floating my-1 btn-success' onclick='me.LoadSub($code);' title='เปิดเพื่อแก้ไข'><i class='fa fa-edit mt-0'></i></a> ";
          break;
        case 'del' :
          $result="<a class='btn-sm btn-floating my-1 btn-danger' onclick='me.DelSub($code);' class='ลบ'><i class='fa fa-trash mt-0'></i> $name</a> ";
          break;
        case 'print' :
          $result="<a class='btn-sm btn-floating my-1 btn-primary' onclick='me.Print($code);' class='พิมพ์'><i class='fa fa-print'></i> $name</a> ";
          break;
      }
    }else{
      $result="";
    }

    return $result;
  }

  public function ViewButtonSub($type, $permission, $name, $code){
    if($permission){
      switch($type){
        case 'edit' :
          $result="<button type='button' class='btn btn-sm btn-success' onclick='sb.Load($code);'><i class='fa fa-edit'></i> $name</button> ";
          break;
        case 'del' :
          $result="<button type='button' class='btn btn-sm btn-danger' onclick='sb.Del($code);'><i class='fa fa-trash'></i> $name</button> ";
          break;
        case 'print' :
          $result="<button type='button' class='btn btn-sm btn-primary' onclick='sb.Print($code);'><i class='fa fa-print'></i> $name</button> ";
          break;
      }
    }else{
      $result="";
    }

    return $result;
  }

  public function ViewEnable($enable, $permission, $name_e, $name_d, $code){
    if($permission){
      if($enable == 'Y'){
        $result="<a type='button' class='btn-sm btn-floating my-1 btn-primary' onclick='me.Disable($code);' title='กดเพื่อปิดการใช้งาน'><i class='fa fa-check-circle'></i></a>";
      }else{
        $result="<a type='button' class='btn-sm btn-floating my-1 btn-default' onclick='me.Enable($code);' title='กดเพื่อเปิดการใช้งาน'><i class='fa fa-ban'></i></a>";
      }
    }else{
      if($enable == 'Y'){
        $result="<label class='label label-xs color-blue'><i class='fa fa-eye'></i> $name_e</label>";
      }else{
        $result="<label class='label label-xs color-gray'><i class='fa fa-eye-slash'></i> $name_d</label>";
      }
    }
    //    if($permission){
    //      if($enable == 'Y'){
    //        $result = "<button type='button' class='btn btn-sm btn-primary' onclick='me.Disable($code);'><i class='fa fa-eye'></i> $name_e</button>";
    //      }else{
    //        $result = "<button type='button' class='btn btn-sm btn-default' onclick='me.Enable($code);'><i class='fa fa-eye-slash'></i> $name_d</button>";
    //      }
    //    }else{
    //      if($enable=='Y'){
    //        $result = "<label class='label label-xs color-blue'><i class='fa fa-eye'></i> $name_e</label>";
    //      }else{
    //        $result = "<label class='label label-xs color-gray'><i class='fa fa-eye-slash'></i> $name_d</label>";
    //      }
    //    }

    return $result;
  }
  
  public function ViewEnableSub($enable, $permission, $name_e, $name_d, $code){
    if($permission){
      if($enable == 'Y'){
        $result="<a type='button' class='btn-sm btn-floating my-1 btn-primary' onclick='me.DisableSub($code);'><i class='fa fa-check-circle'></i></a>";
      }else{
        $result="<a type='button' class='btn-sm btn-floating my-1 btn-default' onclick='me.EnableSub($code);'><i class='fa fa-ban'></i></a>";
      }
    }else{
      if($enable == 'Y'){
        $result="<label class='label label-xs color-blue'><i class='fa fa-eye'></i> $name_e</label>";
      }else{
        $result="<label class='label label-xs color-gray'><i class='fa fa-eye-slash'></i> $name_d</label>";
      }
    }
    //    if($permission){
    //      if($enable == 'Y'){
    //        $result = "<button type='button' class='btn btn-sm btn-primary' onclick='me.Disable($code);'><i class='fa fa-eye'></i> $name_e</button>";
    //      }else{
    //        $result = "<button type='button' class='btn btn-sm btn-default' onclick='me.Enable($code);'><i class='fa fa-eye-slash'></i> $name_d</button>";
    //      }
    //    }else{
    //      if($enable=='Y'){
    //        $result = "<label class='label label-xs color-blue'><i class='fa fa-eye'></i> $name_e</label>";
    //      }else{
    //        $result = "<label class='label label-xs color-gray'><i class='fa fa-eye-slash'></i> $name_d</label>";
    //      }
    //    }

    return $result;
  }

  public function LoadEdit(){
    
    $sql="
      SELECT
        *
      FROM
        ".$this->table."
      WHERE
        code = '".$this->attr["code"]."'
        ";
//        echo $sql;
    $result['row']=array();
    $query=$this->db_query($sql);

    if($row=$this->db_fetch_assoc($query)){
      $data=$row;
    }
    

    foreach((object)$data as $key=>$value){
      $result['row'][]=array(
        'name'=>$key,
        'value'=>$value
      );
    }

    $result['field']=(array)$data;

    $this->freeresult($query);
    $this->dbclose();
    
    return $result;
  }

  public function LoadEditSub(){
    $sql="
      SELECT
        *
      FROM
        ".$this->table."
      WHERE
        code = '".$this->attr["code"]."'
        ";
    //    echo $sql;
    $result['row']=array();
    $query=$this->db_query($sql);
    if($row=$this->db_fetch_assoc($query)){
      $data=$row;
    }
//    mysqli_free_result($query);

    foreach((object)$data as $key=>$value){
      $result['row'][]=array(
        'name'=>"sub_".$key,
        'value'=>$value
      );
    }

    $result['field']=(array)$data;

    $this->freeresult($query);
    $this->dbclose();
    
    return $result;
  }

  public function LoadLanguageDefine(){
    $lang=$this->lang;

    $sql="
      SELECT
        id, name_$lang AS name
      FROM
        languages
      WHERE
        groups = 'DEFINE' AND
        code <> 0
      ORDER BY
        id
        ";
    //    echo $sql;
    $result=array();

    $query=$this->db_query($sql);
    while($row=$this->db_fetch_assoc($query)){
      $this->define[$row['id']]=$row['name'];
    }
    
    
    $this->freeresult($query);
    $this->dbclose();
  }

  public function LoadLanguageAlert(){
    $lang=$this->lang;

    $sql="
          SELECT
            id, name_$lang  AS name
          FROM
            languages
          WHERE
            groups = 'ALERT' AND
            code <> 0
          ORDER BY
            id
            ";
//        echo $sql;

    $query=$this->db_query($sql);
    while($row=$this->db_fetch_assoc($query)){
      $this->alert[$row['id']]= $row['name'];
    }
    $this->freeresult($query);
    $this->dbclose();
  }

  public function LoadLanguageContent(){
    $lang=$this->lang;

    $sql="
      SELECT
        id, name_$lang AS name
      FROM
        languages
      WHERE
        groups = 'CONTENT' AND
        code <> 0
      ORDER BY
        id
        ";
    //    echo $sql;
    $result=array();
    $this->selectdb(DB_NAME);
    $query=$this->db_query($sql);
    while($row=$this->db_fetch_assoc($query)){
      $this->content[$row['id']]=$row['name'];
    }
   $this->freeresult($query);
   $this->dbclose();
  }

  public function LoadLanguage($lang){
    $sql="
      SELECT
        id, groups, name_$lang AS name
      FROM
        languages
      WHERE
        code <> 0
      ORDER BY
        groups, id
        ";
    //    echo $sql;
    $result=array();
    $query=$this->db_query($sql);
    while($row=$this->db_fetch_assoc($query)){
      $result[$row['groups']][$row['id']]=$row['name'];
    }
    $this->freeresult($query);
   $this->dbclose();

    return $result;
  }

  public function LoadConfig(){
    $sql="
      SELECT 
        *
      FROM 
        configs
      WHERE
        code = 1
    ;";
    //    PrintR($sql);

    $query=$this->db_query($sql);
    $result=array();
    if($row=$this->db_fetch_assoc($query)){
      $result=$row;
    }

    $this->freeresult($query);
    $this->dbclose();
    return $result;
  }
  
  public function LoadMyMenu($id){
    $sql="
      SELECT
        *, shortname_{$this->lang} AS name
      FROM
        menus
      WHERE
        id = '$id'
		";
//    echo PrintR($sql);
    $result = array();
		$query=$this->db_query($sql);
    if($row=$this->db_fetch_assoc($query)){
      $result=$row;
		}
		$this->freeresult($query);
    $this->dbclose();
    
		return $result;
	}


  public function LoadMyParentMenu($code){
    $sql="
      SELECT
        *
      FROM
        menus
      WHERE
        sort < (SELECT sort FROM menus WHERE code = '$code') AND
        main_menu = 'Y' AND
        enable = 'Y' AND
        code <> 0
      ORDER BY
        sort DESC
      LIMIT 
        0, 1
        ";
    //    echo $sql;
    $result=array();
    $query=$this->db_query($sql);
    if($row=$this->db_fetch_assoc($query)){
      $result=$row;
    }
    $this->freeresult($query);
    $this->dbclose();

    return $result;
  }
  
  public function LoadMainMenu(){
		$sql="
			SELECT
				*
			FROM
				menus
      WHERE
        main_menu = 'Y' AND
        enable = 'Y' AND
        code <> 0
      ORDER BY
        sort
		";
//		echo $sql;
    $result=array();
		$query=$this->db_query($sql);
    while($row=$this->db_fetch_assoc($query)){
			$result[]=$row;
		}
		$this->freeresult($query);
    $this->dbclose();

    return $result;
  }
  
  public function LoadSubMenu(){
		$sql="
			SELECT
				*
			FROM
				menus
      WHERE
        enable = 'Y' AND
        code <> 0
      ORDER BY
        sort
		";
//		echo $sql;
    $result=array();
		$query=$this->db_query($sql);
    $main_menu = "-";
		 while($row=$this->db_fetch_assoc($query)){
      if($row['main_menu']=='Y'){
        $main_menu = $row['code'];
      }else{
        $result[$main_menu][]=$row;
      }
		}
		$this->freeresult($query);
    $this->dbclose();

    return $result;
  }

  public function CheckTable(){
    $sql="
      check table {$this->table}
        ;";
    //    echo $sql;
    $result=false;
    $query=$this->db_query($sql);
    while($row=$this->db_fetch_object($query)){
      if($row->Msg_text == 'OK'){
        $result=true;
      }
    }
    $this->freeresult($query);
    $this->dbclose();
    return $result;
  }

  public function CreateTable($table='', $data=array()){
    if(!empty($data)){
      $attribute_arr=array();

      unset($data['code']);
      unset($data['user_create']);
      unset($data['user_update']);
      unset($data['date_create']);
      unset($data['date_update']);

      foreach($data as $fields=>$value){
        $value=$this->mssql_escape($value);
        $attribute_arr[]="`$fields` varchar(100) NOT NULL DEFAULT '',";
      }
      $attribute=implode("\n", $attribute_arr);

      $sql="
        CREATE TABLE `$table` (
          `code` int(11) NOT NULL AUTO_INCREMENT,
          $attribute
          `user_create` varchar(100) NOT NULL,
          `user_update` varchar(100) NOT NULL,
          `date_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (`code`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
      ;";
      //      echo $this->sql = $sql;
      $query=$this->db_query($sql);
//      $query=mysqli_query($this->conn, $sql) or die(mysqli_error().$sql);
      if($query){
        $this->db_query("INSERT INTO $table (code) VALUES (0);");
//        mysqli_query("INSERT INTO $table (code) VALUES (0);") or die(mysqli_error().$sql);
        $result['success']='COMPLETE';
      }else{
        $result['success']='FAIL';
        $this->error[]='QUERY ERROR';
      }
    }else{
      $result['success']='FAIL';
      $this->error[]='NOT FOUND DATA';
    }
    
    $this->freeresult($query);
    $this->dbclose();

    return $result;
  }

  public function PushFile($text, $id, $class='', $mod=''){
    echo '
      <form name="frm_upload_'.$id.'" id="frm_upload_'.$id.'" method="post" enctype="multipart/form-data" style="margin:0;" action="module/'.$mod.'/'.$mod.'.upload.php" target="ifm_'.$mod.'">

      <div id="dv'.$id.'" class="form-group">
        <label id="lbl'.$id.'" for="'.$id.'" class="control-label no-padding-right col-md-6 col-sm-12">'.$text.' :</label>
        <div class="col-md-6 col-sm-12">
        <div class="input-group">
        <span class="input-group-btn uploadtool" style="display:none">
        <a class="btn btn-info icon-only" id="show_'.$id.'" target="_blank"><i class="fa fa-file"></i></a>
        <button class="btn btn-danger icon-only" id="del_'.$id.'" onclick="me.Upload.Del(\''.$id.'\')"><i class="fa fa-times"></i></button>
      </span>
          <input class="form-control" id="file_'.$id.'" name="file_'.$id.'" type="file" placeholder="" />
          <input class="form-control '.$class.'" id="'.$id.'" name="'.$id.'" type="hidden"/>
          <span class="input-group-btn">
        <button class="btn btn-primary icon-only" id="upload_'.$id.'" type="submit"><i class="fa fa-upload"></i></button>
      </span>
        </div>
        </div>

        <span id="e'.$id.'" class="help-block err" style="display:none;">Please insert '.$id.'</span>
      </div> 
      </form>
      <iframe id="ifm_'.$mod.'" name="ifm_'.$mod.'" style="width:0px;height:0px;border:0"></iframe>

    ';
  }
  
  public function PushFileBtn($text, $id, $class='',$mod='',$accept=''){
    if(Find('rows', $class)){
      echo '
      <div id="dv'.$id.'" class="fileupload">
              <form name="frm_upload_'.$id.'" id="frm_upload_'.$id.'" method="post" enctype="multipart/form-data" style="margin:0;" action="module/'.$mod.'/'.$mod.'.uploadfile.php" target="ifm_'.$mod.'">
        
        <div class="form-group row">
        <label class="col-sm-4 col-form-label" id="lbl'.$id.'" for="'.$id.'">'.$text.' :</label>
         <div class="col-sm-8">
        <div class="input-group input-group-sm mb-3">
          <div class="custom-file">
            <input type="file" class="custom-file-input form-control-sm fileinput" id="file_'.$id.'" name="file_'.$id.'" aria-describedby="upload_'.$id.'" accept="'.$accept.'">
            <input class="form-control fileinput '.$class.'" id="'.$id.'" name="'.$id.'" type="hidden"/>  
            <label class="custom-file-label" style="white-space: nowrap;overflow-x: hidden;" for="file_'.$id.'">Choose file</label>
          </div>
          <div class="input-group-append" style="padding-top: 2px;">
            <button class="btn btn-outline-secondary btnFileUpload" type="submit" id="upload_'.$id.'" style="margin-top: auto;"><i class="fa fa-upload"></i></button>
            <button class="btn btn-outline-success btnFileLoading" style="display:none;margin-top: auto;margin-left: 6px;" type="button" id="loading_upload_'.$id.'"><i class="fa fa-refresh fa-spin"></i></button>
          </div>
        </div>
  </div> 
        </div>
         </form>
    
      </div> 
    
      <iframe id="ifm_'.$mod.'" name="ifm_'.$mod.'" style="width:0px;height:0px;border:0"></iframe>
    
    ';
    }else{
      echo '
      <div id="dv'.$id.'" class="fileupload form-group">
              <form name="frm_upload_'.$id.'" id="frm_upload_'.$id.'" method="post" enctype="multipart/form-data" style="margin:0;" action="module/'.$mod.'/'.$mod.'.uploadfile.php" target="ifm_'.$mod.'">
        
        <div class="col">
        <label id="lbl'.$id.'" for="'.$id.'">'.$text.' :</label>
        <div class="input-group input-group-sm mb-3">
          <div class="custom-file">
            <input type="file" class="custom-file-input form-control-sm fileinput" id="file_'.$id.'" name="file_'.$id.'" aria-describedby="upload_'.$id.'" accept="'.$accept.'">
            <input class="form-control fileinput '.$class.'" id="'.$id.'" name="'.$id.'" type="hidden"/>  
            <label class="custom-file-label" style="white-space: nowrap;overflow-x: hidden;" for="file_'.$id.'">Choose file</label>
          </div>
          <div class="input-group-append" style="padding-top: 2px;">
            <button data-placement="top" data-content="กดตรงนี้ เพื่ออัพโหลด" class="btn btn-outline-secondary btnFileUpload" type="submit" id="upload_'.$id.'" style="margin-top: auto;"><i class="fa fa-upload"></i></button>
            <button class="btn btn-outline-success btnFileLoading" style="display:none;margin-top: auto;margin-left: 6px;" type="button" id="loading_upload_'.$id.'"><i class="fa fa-refresh fa-spin"></i></button>
          </div>
        </div>
  
        </div>
         </form>
    
      </div> 
    
      <iframe id="ifm_'.$mod.'" name="ifm_'.$mod.'" style="width:0px;height:0px;border:0"></iframe>
    
    ';
    }
    
    
  }

  public function PushFileBtnDel($text, $id, $class='',$mod='',$accept=''){
    if(Find('rows', $class)){
      echo '
      <div id="dv'.$id.'" class="fileupload">
              <form name="frm_upload_'.$id.'" id="frm_upload_'.$id.'" method="post" enctype="multipart/form-data" style="margin:0;" action="module/'.$mod.'/'.$mod.'.uploadfile.php" target="ifm_'.$mod.'">
        
        <div class="form-group row">
        <label class="col-sm-4 col-form-label" id="lbl'.$id.'" for="'.$id.'">'.$text.' :</label>
         <div class="col-sm-8">
        <div class="input-group input-group-sm mb-3">
          <div class="custom-file">
            <input type="file" class="custom-file-input form-control-sm fileinput" id="file_'.$id.'" name="file_'.$id.'" aria-describedby="upload_'.$id.'" accept="'.$accept.'">
            <input class="form-control fileinput '.$class.'" id="'.$id.'" name="'.$id.'" type="hidden"/>  
            <label class="custom-file-label" style="white-space: nowrap;overflow-x: hidden;" for="file_'.$id.'">Choose file</label>
          </div>
          <div class="input-group-append" style="padding-top: 2px;">
            <button data-placement="top" data-content="กดตรงนี้ เพื่ออัพโหลด" class="btn btn-outline-secondary btnFileUpload" type="submit" id="upload_'.$id.'" style="margin-top: auto;"><i class="fa fa-upload"></i></button>
            <button class="btn btn-outline-success btnFileLoading" style="display:none;margin-top: auto;margin-left: 6px;" type="button" id="loading_upload_'.$id.'"><i class="fa fa-refresh fa-spin"></i></button>
            <button class="btn btn-outline-danger btnFileUploadDel" style="margin-top: auto;margin-left: 6px;padding-left: 0.9rem!important;padding-right: 0.9rem!important;" type="button" id="del_upload_'.$id.'"><i class="fa fa-minus"></i></button>
          </div>
        </div>
  </div> 
        </div>
         </form>
    
      </div> 
    
      <iframe id="ifm_'.$mod.'" name="ifm_'.$mod.'" style="width:0px;height:0px;border:0"></iframe>
    
    ';
    }else{
      echo '
      <div id="dv'.$id.'" class="fileupload form-group">
              <form name="frm_upload_'.$id.'" id="frm_upload_'.$id.'" method="post" enctype="multipart/form-data" style="margin:0;" action="module/'.$mod.'/'.$mod.'.uploadfile.php" target="ifm_'.$mod.'">
        
        <div class="col">
        <label id="lbl'.$id.'" for="'.$id.'">'.$text.' :</label>
        <div class="input-group input-group-sm mb-3">
          <div class="custom-file">
            <input type="file" class="custom-file-input form-control-sm fileinput" id="file_'.$id.'" name="file_'.$id.'" aria-describedby="upload_'.$id.'" accept="'.$accept.'">
            <input class="form-control fileinput '.$class.'" id="'.$id.'" name="'.$id.'" type="hidden"/>  
            <label class="custom-file-label" style="white-space: nowrap;overflow-x: hidden;" for="file_'.$id.'">Choose file</label>
          </div>
          <div class="input-group-append" style="padding-top: 2px;">
            <button class="btn btn-outline-secondary btnFileUpload" type="submit" id="upload_'.$id.'" style="margin-top: auto;"><i class="fa fa-upload"></i></button>
            <button class="btn btn-outline-success btnFileLoading" style="display:none;margin-top: auto;margin-left: 6px;" type="button" id="loading_upload_'.$id.'"><i class="fa fa-refresh fa-spin"></i></button>
          </div>
        </div>
  
        </div>
         </form>
    
      </div> 
    
      <iframe id="ifm_'.$mod.'" name="ifm_'.$mod.'" style="width:0px;height:0px;border:0"></iframe>
    
    ';
    }
    
    
  }

  public function PushText($text, $id, $class, $maxlength=100, $guide='', $mask=''){
    $datamask='';
    $required='';
    if(!empty($mask)){
      $datamask="data-mask='$mask'";
    }
    if(Find('empty', $class)){
      $required='required';
    }
    
    

    if(Find('dpk', $class)){
      $input="
        <div class='input-group'>
          <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' readonly='readonly' $datamask $required />
          <div class='input-group-append'><span class='input-group-text'><i class='far fa-calendar-alt'></i></span></div>
        
        </div>
      ";
    }elseif(Find('dtpk', $class)){
      $input="
        <div class='input-group'>
          <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' readonly='readonly' $datamask $required />
          <div class='input-group-append'><span class='input-group-text'><i class='far fa-calendar-alt'></i></span></div>
        
        </div>
      ";
    }elseif(Find('tpk', $class)){
      $input="
        <div class='input-group'>
          <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' readonly='readonly' $datamask $required />
          <div class='input-group-append'><span class='input-group-text'><i class='far fa-calendar-alt'></i></span></div>
        
        </div>
      ";
    }else{
      if(Find('rows', $class)){
        $input="
        <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' maxlength='$maxlength' $datamask $required />
        
      ";
      }else{
        $input="
        <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' maxlength='$maxlength' $datamask $required />
          
      ";
      }
      
    }
    
    if(Find('rows', $class)){
      echo "
      <div id='dv$id' class='form-group row'>
        <label id='lbl$id' for='$id' class='col-sm-4 col-form-label text-right'>$text</label>
        <div class='col-sm-8'>
          $input
        </div>
      </div>
    ";
    }else{
      echo "
      <div id='dv$id' class='form-group'>
        
        <div class='col'>
          <label id='lbl$id' for='$id'>$text :</label>
          $input
        </div>
      </div>
    ";
    }

    
  }
  
  public function PushTextAddon($text, $id, $class, $maxlength=100, $guide='', $mask='',$addon=''){
    $datamask='';
    $required='';
    if(!empty($mask)){
      $datamask="data-mask='$mask'";
    }
    if(Find('empty', $class)){
      $required='required';
    }
    
    if(!empty($addon)){
      $addon = "<div class='input-group-append'>
          <span class='input-group-text'>".$addon."</span>
        </div>";
    }

    
    if(Find('rows', $class)){
      echo "
      <div id='dv$id' class='form-group row'>
        <label id='lbl$id' for='$id' class='col-sm-4 col-form-label'>$text</label>
        <div class='col-sm-8'>
        <div class='input-group input-group-sm'>
        <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' maxlength='$maxlength' $datamask $required />
          $addon
        </div>
        </div>
      </div>
    ";
    }elseif(Find('inline', $class)){
      echo "
      <div id='dv$id' class='form-inline'>
        <label id='lbl$id' for='$id' class='col-sm-4 col-form-label'>$text</label>
        <div class='col-sm-8'>
        <div class='input-group input-group-sm'>
        <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' maxlength='$maxlength' $datamask $required />
          $addon
        </div>
        </div>
      </div>
    ";
    }else{
      echo "
      <div id='dv$id' class='form-group'>
        
        <div class='col'>
          <label id='lbl$id' for='$id'>$text :</label>
          <div class='input-group input-group-sm'>
      <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' maxlength='$maxlength' $datamask $required />
        $addon
      </div>
        </div>
      </div>
    ";
    }

    
  }

  public function PushEmail($text, $id, $class, $maxlength=100, $guide='', $mask=''){
    $datamask='';
    $required='';
    if(!empty($mask)){
      $datamask="data-mask='$mask'";
    }

    if(Find('empty', $class)){
      $required='required';
    }


    $input="
          <input class='form-control form-control-sm $class' id='$id' name='$id' type='email' placeholder='$guide' maxlength='$maxlength' $datamask $required/>
            
        ";

    echo "
        <div id='dv$id' class='form-group'>

          <div class='col'>
            <label id='lbl$id' for='$id'>$text :</label>
            $input
          </div>
        </div>
      ";
  }

  public function PushNumber($text, $id, $class, $min=0, $guide='', $mask=''){
    if(!empty($mask)){
      $datamask="data-mask='$mask'";
    }
    if(Find('dpk', $class)){
      $input="
        <div class='input-group'>
          <input class='form-control $class' id='$id' name='$id' type='text' placeholder='$guide' readonly='readonly' $datamask />
          <span class='input-group-btn'>
            <button id='d$id' class='btn btn-default' type='button' style='padding:5px;'><i class='fa fa-calendar'></i></button>
          </span>
          <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
        </div>
      ";
    }elseif(Find('dtpk', $class)){
      $input="
        <div class='input-group'>
          <input class='form-control $class' id='$id' name='$id' type='text' placeholder='$guide' readonly='readonly' $datamask />
          <span class='input-group-btn'>
            <button id='d$id' class='btn btn-default' type='button' style='padding:5px;'><i class='fa fa-calendar-o'></i></button>
          </span>

        </div>
      <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
      ";
    }elseif(Find('tpk', $class)){
      $input="
        <div class='input-group'>
          <input class='form-control $class' id='$id' name='$id' type='text' placeholder='$guide' readonly='readonly' $datamask />
          <span class='input-group-btn'>
            <button id='d$id' class='btn btn-default' type='button' style='padding:5px;'><i class='fa fa-clock-o'></i></button>
          </span>
          <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
        </div>
      ";
    }else{
      $input="
        <input class='form-control form-control-sm $class' id='$id' name='$id' type='number' placeholder='$guide' min='$min' $datamask />
          <span id='e$id' class='help-block err' style='display:none;'>Please insert $text</span>
      ";
    }

    echo "
      <div id='dv$id' class='form-group'>
        <label id='lbl$id' for='$id' class='col-sm-4 control-label no-padding-right' style='white-space:nowrap'>$text :</label>
        <div class='col-sm-8'>
          $input
        </div>
      </div>
    ";
  }

  public function PushTextSelect($text, $id, $class, $maxlength=100, $icon='fa-search', $select='ค้นหา', $guide=''){
    
    if(Find('rows', $class)){
      echo "
        <div id='dv$id' class='form-group row'>
          <label id='lbl$id' for='$id' class='col-sm-4 col-form-label' style='white-space:nowrap'>$text :</label>
          <div class='col-sm-8'>
            <div class='input-group mb-3'>
              <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' maxlength='$maxlength' />
              <span class='input-group-append'>
                <button id='btn$id' class='btn btn-outline-secondary btn-sm' type='button' style='padding:5px;margin-top: auto;'><i class='fa $icon'></i> $select</button>
              </span>
            </div>
            <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
          </div>
        </div>
      ";
    }else{
      echo "
        <div id='dv$id' class='form-group'>
          <label id='lbl$id' for='$id' class='col-sm-4 control-label' style='white-space:nowrap'>$text :</label>
          <div class='col'>
            <div class='input-group mb-3'>
              <input class='form-control form-control-sm $class' id='$id' name='$id' type='text' placeholder='$guide' maxlength='$maxlength' />
              <span class='input-group-append'>
                <button id='btn$id' class='btn btn-outline-secondary btn-sm' type='button' style='padding:5px;margin-top: auto;'><i class='fa $icon'></i> $select</button>
              </span>
            </div>
            <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
          </div>
        </div>
      ";
    }
    
    
  }

  public function PushSelect($text, $id, $class, $value=array(), $guide='', $default='' ,  $style=1){
    $required='';
    $option='';
    if(Find('empty', $class)){
      $required='required';
    }

    foreach((array)$value as $key=>$txt){
      $option.="<option value='$key'>$txt</option>";
    }

    if($style == 2){
      echo "
          <div id='dv$id' class='form-group bs-select'>
          
          <div class='col'>
            <label id='lbl$id' for='$id'  style='white-space:nowrap'>$text :</label>
            <select id='$id' name='$id' class='browser-default form-control form-control-sm $class' $required>
              <option value='$default' selected>$guide</option>
              $option
            </select>
            <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
          </div>
        </div>
        ";
    }else{
      if(Find('row', $class)){
        echo "
          <div id='dv$id' class='form-group row bs-select'>
          <label id='lbl$id' for='$id' class='col-sm-4 col-form-label text-right' style='white-space:nowrap'>$text</label>
          <div class='col-sm-8'>
            
            <select id='$id' name='$id' class='browser-default form-control form-control-sm $class' $required>
              <option value='$default' selected>$guide</option>
              $option
            </select>
            <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
          </div>
        </div>
        ";
      }else{
        echo "
          <div id='dv$id' class='form-group bs-select'>
          <div class='col'>
            <label id='lbl$id' for='$id' style='white-space:nowrap'>$text :</label>
            <select id='$id' name='$id' class='browser-default form-control form-control-sm $class' $required>
              <option value='$default' selected>$guide</option>
              $option
            </select>
            <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
          </div>
        </div>
        ";
      }
      
    }
  }

  public function PushSelectNew($text, $id, $class, $value=array(), $guide='', $style=1){
    $option = '';
    foreach((array)$value as $key=>$txt){
      $option.="<option value='$key'>$txt</option>";
    }

    if($style == 2){
      echo "
          <div id='dv$id' class='form-group'>
            <label id='lbl$id' for='$id' class='col-sm-12 control-label no-padding-right' style='white-space:nowrap'>$text :</label>
            <div class='col-sm-12'>
              <select id='$id' name='$id' class='form-control form-control-sm $class'>
                <option value=''>$guide</option>
                $option
              </select>
              <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
            </div>
          </div>
        ";
    }else{
      echo "
          <div id='dv$id' class='form-group'>
            <label id='lbl$id' for='$id' class='col-sm-4 control-label no-padding-right' style='white-space:nowrap'>$text :</label>
            <div class='col-sm-8'>
              <select id='$id' name='$id' class='form-control form-control-sm $class'>

                $option
              </select>
              <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
            </div>
          </div>
        ";
    }
  }

  public function PushSelectButton($text, $id, $class, $value=array(), $default='', $icon='fa-search', $select='เลือก', $guide=''){
    $option = '';
    foreach((array)$value as $key=>$txt){
      $option.="<option value='$key'>$txt</option>";
    }

    if(Find('rows', $class)){
      echo "
          <div id='dv$id' class='form-group row bs-select'>
            <label id='lbl$id' for='$id' class='col-sm-4 control-label no-padding-right' style='white-space:nowrap'>$text :</label>
            <div class='col-sm-8'>
              <div class='input-group'>
              <select id='$id' name='$id' class='browser-default form-control form-control-sm $class'>
                <option value='$default'>$guide</option>
                $option
              </select>
             
              <span class='input-group-append'>
                <button id='btn$id' class='btn btn-outline-secondary btn-sm' type='button' style='padding:5px;margin-top: auto;'><i class='fa $icon'></i> $select</button>
              </span>
              </div>

             
            </div>
          </div>
        ";
    }else{
      echo "
          <div id='dv$id' class='form-group bs-select'>
            <label id='lbl$id' for='$id' class='col-sm-4 control-label no-padding-right' style='white-space:nowrap'>$text :</label>
            <div class='col'>
              <div class='input-group'>
              <select id='$id' name='$id' class='browser-default form-control form-control-sm $class'>
                <option value='$default'>$guide</option>
                $option
              </select>
             
              <span class='input-group-append'>
                <button id='btn$id' class='btn btn-outline-secondary btn-sm' type='button' style='padding:5px;margin-top: auto;'><i class='fa $icon'></i> $select</button>
              </span>
              </div>

             
            </div>
          </div>
        ";
    }
  }

  public function PushSelect2($text, $id, $class, $value=array(), $guide='', $style=1){
    $option = '';
    foreach((array)$value as $key=>$txt){
      $option.="<option value='$key'>$txt</option>";
    }

    if($style == 2){
      echo "
          <div id='dv$id' class='form-group'>
            <label id='lbl$id' for='$id' class='col-sm-12 control-label no-padding-right' style='white-space:nowrap'></label>
            <div class='col-sm-12'>
              <select id='$id' name='$id' class='form-control form-control-sm $class'>
                <option value=''>$guide</option>
                $option
              </select>
              <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
            </div>
          </div>
        ";
    }else{
      echo "
          <div id='dv$id' class='form-group'>
            <div class='col-sm-12'>
              <select id='$id' name='$id' class='form-control form-control-sm $class'>
                <option value=''>$guide</option>
                $option
              </select>
              <span id='e$id' class='help-block err' style='display:none;'>Please select $text</span>
            </div>
          </div>
        ";
    }
  }

  public function PushHidden($id, $class, $value=''){
    echo "<input type='hidden' id='$id' name='$id' class='$class' value='$value' />";
  }

  public function PushCheckbox($text, $id, $class){
    $required='';
    if(Find('empty', $class)){
      $required='required';
    }

    echo "
        <div id='dv$id' class='form-group'>
          
          <div class='form-check icheck-primary d-inline'>
            
              <input type='checkbox' id='$id' name='$id' class='form-check-input $class' $required>
              <label class='form-check-label' for='$id'>$text</span>
           
          </div>
        </div>
      ";
  }

  public function PushCheckboxList($text, $id, $class, $item=array()){
    $checkbox = '';
    foreach((array)$item as $ids=>$val){
      $checkbox.="
          <label class='checkbox-inline'><input type='checkbox' class='uniform $class' name='$id$ids' id='$id$ids' /> $val</label>
        ";
    }
    echo "
        <div id='dv$id' class='form-group'>
          <label id='lbl$id' class='col-sm-4 control-label'>$text :</label>
          <div class='col-sm-8'>
            $checkbox
          </div>
        </div>
      ";
  }

  public function PushRadio($text, $name, $class, $value=array(),$checked='N'){
    
    $radio ='';
    foreach((array)$value as $id=>$val){
      $radio.="
      <div class='form-check form-check-inline'>
          <input type='radio' name='$name' id='$name$id' class='$class form-check-input' rel='$id' ".($checked == $id?"checked='checked'":'')." />
          <label class='form-check-label' for='$name$id'>$val</label>
      </div>
        ";
//      $checked='';
      $class='';
    }

    echo "
        <fieldset  id='dv$name' class='form-group'>
          <div class='row'>
           <legend class='col-form-label col-sm-4 pt-0'>$text</legend>
            <div class='col-sm-8'>
              
                $radio
            
            </div>
          </div>
        </fieldset >
      ";
  }

  public function PushRadio2($text, $name, $class, $value=array()){
    $checked="checked='checked'";
    $radio = '';
    foreach((array)$value as $id=>$val){
      $radio.="
          <div class='radiobox'>
            <label>
              <input type='radio' name='$name' id='$name$id' class='$class colored-blueberry' rel='$id' $checked />
              <span class='text'> $val</span>
            </label>
          </div>
        ";
      $checked='';
      $class='';
    }

    echo "
        <div id='dv$name' class='form-group'>
          <label class='col-sm-4 control-label no-padding-right'></label>
          <div class='col-sm-8'>
            $radio
          </div>
        </div>
      ";
  }

  public function PushTextArea($text, $id, $class, $rows=3, $style=1){
    if($style == 2){
      echo "
          <div id='dv$id' class='form-group'>
            <label id='lbl$id' for='$id' class='col-sm-12'>$text :</label>
            <div class='col-sm-12'>
              <textarea class='form-control $class' rows='$rows' id='$id' name='$id'></textarea>
              <span id='e$id' class='help-block err' style='display:none;'>Please insert $text</span>
            </div>
          </div>
        ";
    }else{
      if(Find('row', $class)){
        
        echo "
          <div id='dv$id' class='form-group row'>
            <label id='lbl$id' for='$id' class='col-sm-4 col-form-label'>$text :</label>
            <div class='col-sm-8'>
              
              <textarea class='form-control $class' rows='$rows' id='$id' name='$id'></textarea>
             
            </div>
          </div>
        ";
        
      }else{
        
        echo "
          <div id='dv$id' class='form-group'>
            
            <div class='col'>
              <label id='lbl$id' for='$id'>$text :</label>
              <textarea class='form-control $class' rows='$rows' id='$id' name='$id'></textarea>
             
            </div>
          </div>
        ";
        
      }
      
    }
  }

  public function PushTextEditor($text, $id, $class){
    echo "
        <div id='dv$id' class='form-group'>
          
          <div class='col'>
            <label id='lbl$id' for='$id'>$text :</label>
            <textarea class='$class' id='$id' name='$id'></textarea>

          </div>
        </div>
      ";
  }

  public function PushPassword($text, $id, $class, $maxlength, $guide=''){
    echo "
      <div id='dv$id' class='form-group'>
        <label id='lbl$id' for='$id' class='col-sm-4 control-label'>$text :</label>
        <div class='col-sm-8'>
          <input class='form-control $class' id='$id' name='$id' type='password' placeholder='$guide' maxlength='$maxlength' />
          <span id='e$id' class='help-block err' style='display:none;'>Please insert $text</span>
        </div>
      </div>
    ";
  }

  public function PushButton($text, $id, $class, $icon){
    $value = '';
    $option = '';
    foreach((array)$value as $key=>$txt){
      $option.="<option value='$key'>$txt</option>";
    }

    echo "
        <div id='dv$id' class='form-group'>
          <label id='lbl$id' for='$id' class='col-sm-4 control-label'>&nbsp;</label>
          <div class='col-sm-8'>
            <button id='btn$id' class='btn $class'><i class='fa $icon'></i>$text</button>
          </div>
        </div>
      ";
  }

  public function PushApprove($text, $id, $class){
    echo "
        <div id='dv$id' class='form-group'>
          <label id='lbl$id' class='col-sm-4 control-label no-padding-right'>
            <a href='#' target='_blank' id='applink_$id'>
            <i class='fa fa-external-link'></i>
            $text</a> :</label>
          <div class='col-sm-8'>
            <button type='button' id='appbtn_$id' class='btn btn-default info btn-xs $class' rel='$id'><i class='fa fa-check'></i> อนุมัติ</button>
            <button type='button' id='rejbtn_$id' class='btn btn-default danger btn-xs $class' rel='$id'><i class='fa fa-times'></i> ไม่อนุมัติ</button>
            &nbsp; &nbsp; &nbsp; &nbsp;
            <span id='appcheck_$id'></span> &nbsp; &nbsp;
            <span id='appuser_$id'></span> &nbsp; &nbsp;
            <span id='apptime_$id'></span> &nbsp; &nbsp;
          </div>
        </div>
      ";
  }

  public function RowForm($row){
    $result="";
    foreach((array)$row as $i=>$form){
      $col="";
      foreach((array)$form["col"] as $j=>$value){
        $col .= "col-".$j."-".$value." ";
      }

      switch($form["type"]){
        case "text" :
          $input=$this->PushText(
                  $form['label'], $form['id'], $form['class'], $form['maxlength'], $form['placeholder'], $form['mask']);
          break;
        case "select" :
          $input=$this->PushSelect(
                  $form['label'], $form['id'], $form['class'], $form['option'], $form['placeholder']);
          break;
      }

      $result .= "
            <div class='$col'>
                $input
            </div>
        ";
    }

    return "
        <div class='row'>
            $result
        </div>
      ";
  }

  public function LoadAlert(){
    $convert = $this->getcolumn('alert');
    $sql="
			SELECT
        $convert
			FROM
        alert tb
			WHERE
        tb.enable = 'Y' AND
        tb.reads = 'N' AND tb.user_code = {$_SESSION[OFFICE]['DATA']['code']}
      ORDER BY
        tb.date_create DESC
     
		;";
    //		echo "<pre>$sql</pre>";
    $query=$this->db_query($sql);
    $result=array();
    while($row=$this->db_fetch_assoc($query)){
      $row['totalnet']=NumberDisplay($row['totalnet']);
      $row['date_create']=DateTimeDisplay($row['date_create'], 1);
      $result[]=$row;
    }
    $this->freeresult($query);
    $this->dbclose();
//    mysqli_free_result($query);

    return $result;
  }

  public function LoadAlertCount(){
		$sql="
			SELECT
        COUNT(*) AS cnt
			FROM
        alert
			WHERE
        enable = 'Y' AND
        reads = 'N' AND user_code = {$_SESSION[OFFICE]['DATA']['code']}
		;";
//		echo "<pre>$sql</pre>";
		$query=$this->db_query($sql);
		$result = 0;
		if($row=$this->db_fetch_assoc($query)){
      $result = $row['cnt'];
		}
		$this->freeresult($query);
    $this->dbclose();
 
		return $result;
	}  

  public function LoadAlert2(){
    $sql="
        SELECT
          mc.*,mp.date_create As payment_datetime, mp.payment_total
        FROM
          member_courses mc, member_payments mp
        WHERE
          mc.code <> 0 and
          mc.payment_confirm_status = 'Y' and
          mc.payment_status = 'N' and
          mc.code = mp.member_course_code
        ORDER BY
          mc.code DESC
        LIMIT
          0, 10
      ;";
    //		echo "<pre>$sql</pre>";
    $query=mysqli_query($this->conn, $sql);
    $result=array();
    while($row=mysqli_fetch_assoc($query)){
      $row['totalnet']=NumberDisplay($row['totalnet']);
      $row['date_create']=DateTimeDisplay($row['date_create'], 1);
      $row['payment_datetime']=DateTimeDisplay($row['payment_datetime'], 1);
      $result[]=$row;
    }
    mysqli_free_result($query);

    return $result;
  }

  public function LoadAlertCount2(){
    $sql="
        SELECT
          COUNT(*) AS cnt
        FROM
          member_courses
        WHERE
          code <> 0 and
          payment_confirm_status = 'Y' and
          payment_status = 'N'
        ORDER BY
          code DESC
      ;";
    //		echo "<pre>$sql</pre>";
    $query=mysqli_query($this->conn, $sql);
    $result=0;
    if($row=mysqli_fetch_assoc($query)){
      $result=$row['cnt'];
    }
    mysqli_free_result($query);

    return $result;
  }

  public function LoadInbox(){
    $sql="
        SELECT
          *
        FROM
          employees
        WHERE
          code <> 0
        ORDER BY
          code DESC
        LIMIT
          0, 5
      ;";
    //		echo "<pre>$sql</pre>";
    $query=mysqli_query($this->conn, $sql);
    $result=array();
    while($row=mysqli_fetch_assoc($query)){
      $result[]=$row;
    }
    mysqli_free_result($query);

    return $result;
  }

  public function LoadInboxCount(){
    $sql="
        SELECT
          COUNT(*) AS cnt
        FROM
          employees
        WHERE
          code <> 0
      ;";
    //		echo "<pre>$sql</pre>";
    $query=mysqli_query($this->conn, $sql);
    $result=0;
    if($row=mysqli_fetch_assoc($query)){
      $result=$row['cnt'];
    }
    mysqli_free_result($query);

    return $result;
  }

  public function LoadMenu(){
    $sql="
            SELECT
                mn.*
            FROM
                menus mn
      WHERE
        mn.enable = 'Y' AND
        mn.code <> 0
      ORDER BY
        mn.sort
        ";
    //		echo $sql;
    $result=array();
    $query=$this->db_query($sql);
    $main_menu="";
    while($row=$this->db_fetch_assoc($query)){
      if($row['id'] == ""){
        $row['id']="mn".$row['code'];
      }
      if($row['main_menu'] == 'Y'){
        $main_menu=$row['code'];
        $result[$main_menu]['main']=$row;
      }else{
        $result[$main_menu]['sub'][]=$row;
      }
    }
    $this->freeresult($query);
    $this->dbclose();

    return $result;
  }

  public function LoadMenuPermission($task){
    $sql="
            SELECT
                tp.*
            FROM
                tasks_permission tp
      WHERE
        tp.task_code = '$task' AND
        tp.task_type = 'MENU' AND
        tp.enable = 'Y' AND
        tp.code <> 0
      ORDER BY
        tp.code
        ";
    //		echo $sql;
    $result=array();
    $query=$this->db_query($sql);
    while($row=$this->db_fetch_assoc($query)){
      $result[$row['menu_code']]='Y';
    }
    $this->freeresult($query);
    $this->dbclose();

    return $result;
  }

  public function AddMsg($msg, $emp, $mod, $code){
    $sql="
            INSERT INTO messages 
        (msg, emp_code, module, ref_code, status, user_create, user_update, date_create, date_update)
      VALUES (
        '$msg', 
        '$emp', 
        '$mod', 
        '$code', 
        '1', 
        'SYSTEM', 
        'SYSTEM', 
        NOW(), 
        NOW()
      )
        ";
    //		echo $sql;
    $query=$this->db_query($sql);
  }

  public function LoadEmpPermission($code){
    $sql="
      SELECT
        *
      FROM
        emp_permission
      WHERE
        emp_code = '$code'
        ";
    //    echo $sql;
    $result=array();
    $query=$this->db_query($sql);
    while($row=$this->db_fetch_object($query)){
      $result[$row->task][$row->id]=true;
    }
    $this->freeresult($query);
    $this->dbclose();

    return $result;
  }

  public function LoadEmpPermissionAdmin(){
    $result=array();

    $sql="
      SELECT
        *
      FROM
        menus
      WHERE
        code <> 0
      ORDER BY
        sort
        ";
    //    echo $sql;
    $query=$this->db_query($sql);
    while($row=$this->db_fetch_object($query)){
      $result['VIEW'][$row->id]=true;
      $result['ADD'][$row->id]=true;
      $result['EDIT'][$row->id]=true;
      $result['DEL'][$row->id]=true;
      $result['PRINT'][$row->id]=true;
    }
//    mysqli_free_result($query);

    $sql="
      SELECT
        id
      FROM
        permissions
      WHERE
        code <> 0
      ORDER BY
        id
        ";
    //    echo $sql;
    $query=$this->db_query($sql);
    while($row=$this->db_fetch_object($query)){
      $result['OPEN'][$row->id]=true;
    }
    
    $this->freeresult($query);
    $this->dbclose();

    return $result;
  }

  public function LoginSession($session){
    $sql="
			SELECT
        code, firstname as name,lastname as surname, 
        username, password, IF(level = 6,'Y','N') as superadmin, 
        CONCAT(firstname, ' ', lastname) AS user,
        NOW() AS datenow
			FROM
        members
			WHERE
        seessionID = '$session' AND
        level IN (6) AND  
				enable = 'Y' and online = 'Y'
		";
//		echo $sql;

    $query=$this->db_query($sql);
    $result=array();
    if($row=$this->db_fetch_assoc($query)){
      $result=$row;
    }

    $this->freeresult($query);
    $this->dbclose();
    return $result;
  }

  public function EnableDisplay($enable){
    if($enable == 'Y'){
      $result="<label class='label label-xs color-green'><i class='fa fa-check'></i></label>";
    }else{
      $result="<label class='label label-xs color-red'><i class='fa fa-ban'></i></label>";
    }
    return $result;
  }

  public function MemberLog($member_code, $user_name, $detail, $user, $menu=''){
    $ip=IP();
    $sql="
            INSERT INTO members_log 
                (member_code, user_name, detail, ip, menu, user_create, user_update, date_create, date_update)
            VALUES (
                '$user_name', '$user_name', '$detail', '$ip', '$menu',
                '$user', '$user', NOW(), NOW()
            )
        ";
    //		echo $sql;
    $query=$this->db_query($sql);
  }
}
