<?php
class SubClass extends AppClass{

  public function __construct($table=''){
    parent::__construct();
    $this->table = $table;
  }

}
?>