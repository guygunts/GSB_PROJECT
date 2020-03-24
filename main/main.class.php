<?php
/*=========================================
*  Author : Tirapant Tongpann
*  Created Date : 27/6/2552 0:49
*  Module : Class Main
*  Description : -
*  Involve People : -
*  Last Updated : 27/6/2552 0:49
=========================================*/

class SuperClass
{
    public $table = '';
    public $attr = array();
    public $error = array();
    public $sql = '';
    public $lang = 'th';
    public $db = '';

    public function __construct()
    {
        global $mysqli;
        $this->db = $mysqli;
    }

    public function Log($mode, $menu, $record, $user, $item = array())
    {
        if (empty($item)) {
            $log = array();
        }
        $log = serialize($item);
        $log = $this->real_escape($log);
//    echo $log;
//    print_r($item);

        $sql = "
      INSERT INTO logs (mode, menu, record, item, ip, user_create, date_create)
      VALUES(
        '$mode', '$menu', '$record', '$log', '" . IP() . "', '$user', NOW()
      )
    ";
//    echo $sql;
        $this->db_query($sql);
    }

    public function Add($table = '', $data = array())
    {
        if (!empty($data)) {
            $attribute_arr = array();
            $values_arr = array();

            foreach ($data as $fields => $val) {
                $attribute_arr[] = $fields;
                $values_arr[] = "'" . $this->real_escape($val) . "'";
            }
            $attribute = implode(',', $attribute_arr);
            $values = implode(',', $values_arr);
            $sql = "
        INSERT INTO $table ($attribute)
        VALUES($values);
      ";
            $this->sql = $sql;

            $query = $this->db_query($sql);
//      $query=$this->db->query($sql) or die($this->db->connect_error);
            if ($query) {
                $result['success'] = 'COMPLETE';
                $result['code'] = $this->db->insert_id;
            } else {
                $result['success'] = 'FAIL';
                $this->error[] = 'QUERY ERROR';
            }
        } else {
            $result['success'] = 'FAIL';
            $this->error[] = 'NOT FOUND DATA';
        }

        return $result;
    }

    public function Edit($table = '', $data = array(), $where = array())
    {
        if (!empty($data)) {
            $attribute_arr = array();
            $where_arr = array();

            foreach ($data as $fields => $value) {
//        $value = $this->db->real_escape_string($value);
                $attribute_arr[] = " $fields = '" . $this->real_escape($value) . "' ";
            }
            foreach ($where as $fields => $value) {
//        $value = $this->db->real_escape_string($value);
                $where_arr[] = " $fields = '" . $this->real_escape($value) . "' ";
            }
            $attribute = implode(', ', $attribute_arr);
            $whereqry = implode(' AND ', $where_arr);

            $sql = "SELECT * FROM $table WHERE $whereqry ";
            $query = $this->db_query($sql);
            $log = array();
            while ($row = $this->db_fetch_assoc($query)) {
                $log[] = $row;
            }

            $sql = "
        UPDATE $table SET
          $attribute
        WHERE 
          $whereqry
      ";
            $this->sql = $sql;

            $query = $this->db_query($sql);
//      $query=$this->db->query($sql) or die($this->db->connect_error);
            if ($query) {
                $result['success'] = 'COMPLETE';
                $result['log'] = $log;
            } else {
                $result['success'] = 'FAIL';
                $this->error[] = 'QUERY ERROR';
            }
        } else {
            $result['success'] = 'FAIL';
            $this->error[] = 'NOT FOUND DATA';
        }

        return $result;
    }

    public function Del($table = '', $where = array())
    {
        if (!empty($where)) {
            $where_arr = array();

            foreach ($where as $fields => $value) {
                $value = $this->real_escape($value);
                $where_arr[] = " $fields = '$value' ";
            }
            $whereqry = implode(' AND ', $where_arr);

            $sql = "SELECT * FROM $table WHERE $whereqry ";
            $query = $this->db_query($sql);
            $log = array();
            while ($row = $this->db_fetch_assoc($query)) {
                $log[] = $row;
            }

            $sql = "
        DELETE FROM
          $table
        WHERE
          $whereqry
      ";
            $this->sql = $sql;

            $query = $this->db_query($sql);
            if ($query) {
                $result['success'] = 'COMPLETE';
                $result['log'] = $log;
            } else {
                $result['success'] = 'FAIL';
                $this->error[] = 'QUERY ERROR';
            }
        } else {
            $result['success'] = 'FAIL';
            $this->error[] = 'NOT FOUND DATA';
        }

        return $result;
    }

    public function Load($table, $where = array(), $orderby = '', $limit = '')
    {
        $qrywhere = '';
        if (!empty($where)) {
            foreach ((array)$where as $i => $item) {
                $item = $this->real_escape($item);
                $qrywhere .= "$i = '$item' AND ";
            }
        }
        if (!empty($orderby)) {
            $orderby = "ORDER BY $orderby";
        }
        if (!empty($limit)) {
            $limit = "LIMIT $limit";
        }
        $sql = "
      SELECT * 
      FROM $table 
      WHERE
        $qrywhere
        1
      $orderby
      $limit
    ";
//    echo $sql;

        $this->sql = $sql;

        $query = $this->db_query($sql);
        $result = array();
        while ($row = $this->db_fetch_assoc($query)) {
            $result[] = $row;
        }
        return $result;
    }

    public function LoadOne($table, $where = array(), $orderby = '')
    {
        $qrywhere = '';
        if (!empty($where)) {
            foreach ((array)$where as $i => $item) {
                $item = $this->real_escape($item);
                $qrywhere .= "$i = '$item' AND ";
            }
        }
        if (!empty($orderby)) {
            $orderby = "ORDER BY $orderby";
        }
        $sql = "
      SELECT 
        * 
      FROM 
        $table 
      WHERE
        $qrywhere
        1
      $orderby
      LIMIT 0, 1
    ";
        $this->sql = $sql;

        $query = $this->db_query($sql);
        $result = array();
        if ($row = $this->db_fetch_assoc($query)) {
            $result = $row;
        }
        return $result;
    }

    public function LoadCbo($table, $code, $name, $where = array(), $orderby = 'code')
    {
        $qrywhere = '';
        if (!empty($where)) {
            foreach ((array)$where as $i => $item) {
                $item = $this->real_escape($item);
                $qrywhere .= "$i = '$item' AND ";
            }
        }
        $sql = "
			SELECT
				$code AS code, 
        $name AS name
			FROM
				$table
      WHERE
        $qrywhere
        $code <> 0
      ORDER BY
        $orderby
		";
        // echo $sql;
        $result = array();
        $query = $this->db_query($sql);
        while ($row = $this->db_fetch_object($query)) {
            $result[] = array(
                'code' => $row->code,
                'name' => $row->name
            );

        }
        $this->freeresult($query);
        $this->dbclose();

        return $result;
    }

    public function LoadMatch($table, $code, $name, $where = array(), $order = '')
    {
        $qrywhere = '';
        if (!empty($where)) {
            foreach ((array)$where as $i => $item) {
                $item = $this->real_escape($item);
                $qrywhere .= "$i = '$item' AND ";
            }
        }

        if (!$order) {
            $order = $name;
        }

        $sql = "
			SELECT
				$code as code, 
        $name AS name
			FROM
				$table
      WHERE
        $qrywhere
        $code <> 0
      ORDER BY
        $order
		";
//		echo $sql;
        $result = array();
        $result[0] = '';
        $query = $this->db_query($sql);
        while ($row = $this->db_fetch_object($query)) {
            $result[$row->code] = $row->name;
        }
        $this->freeresult($query);
        $this->dbclose();

        return $result;
    }

    public function query($sql)
    {
        $sql = str_replace(";", "", $sql);
        $this->sql = $sql;
        return $this;
    }

    public function sql($sql)
    {
        $sql = str_replace(";", "", $sql);
        $this->sql = $sql;
        $this->db_query($sql);
    }

    public function select($field = '*')
    {
        $this->select = array();
        $this->from = array();
        $this->where = array();
        $this->groupby = array();
        $this->having = array();
        $this->orderby = array();
        $this->sql = "";

        $this->select[] = $field;
        return $this;
    }

    public function from($table, $shortname = "")
    {
        if ($shortname == '') {
            $this->from[] = $table;
        } else {
            $this->from[] = "$table $shortname";
        }

        return $this;
    }

    public function where($field, $arg1, $arg2 = '')
    {
        if ($arg2 == '') {
            $this->where[] = "$field = '$arg1'";
        } else {
            $this->where[] = "$field $arg1 '$arg2'";
        }

        return $this;
    }

    public function groupby($field)
    {
        $this->groupby[] = $field;

        return $this;
    }

    public function having($field, $arg1, $arg2 = '')
    {
        if ($arg2 == '') {
            $this->where[] = "$field = '$arg1'";
        } else {
            $this->where[] = "$field $arg1 '$arg2'";
        }

        return $this;
    }

    public function orderby($field, $order = 'ASC')
    {
        $this->orderby[] = "$field $order";

        return $this;
    }

    public function join($table1, $table2)
    {
        $this->where[] = "$table1 = $table2";

        return $this;
    }

    public function all()
    {

        $this->selectSql();
        $query = $this->db_query($this->sql);
        $this->result = array();

        while ($row = $this->db_fetch_assoc($query)) {
            $this->result[] = $row;
        }

        $this->freeresult($query);
        $this->dbclose();
        return $this;
    }

    public function one()
    {

        $this->selectSql();

        $query = $this->db_query($this->sql);
        $this->result = array();
        if ($row = $this->db_fetch_assoc($query)) {
            $this->result = $row;
        }

        $this->freeresult($query);
        $this->dbclose();

        return $this;
    }

    public function limit($start, $stop = '')
    {
        $this->selectSql();

        if ($stop == '') {
            $this->sql .= "\nLIMIT\n\t0, $start";
        } else {
            $this->sql .= "\nLIMIT\n\t$start, $stop ";
        }

        $query = $this->db->query($this->sql) or die("Error query : \n" . $this->sql . "\n => " . $this->db->connect_error);
        $this->result = array();
        while ($row = $query->fetch_assoc()) {
            $this->result[] = $row;
        }
        $query->free();

        return $this;
    }

    private function selectSql()
    {
        if (empty($this->sql)) {
            $select = implode(", ", (array)$this->select);
            $from = implode(", ", (array)$this->from);
            $where = implode(" AND \n\t", (array)$this->where);
            $groupby = implode(", ", (array)$this->groupby);
            $having = implode(" AND \n\t", (array)$this->having);
            $orderby = implode(", ", (array)$this->orderby);

            $where = (empty($where)) ? "" : "\nWHERE\n\t$where";
            $groupby = (empty($groupby)) ? "" : "\nGROUP BY\n\t$groupby";
            $having = (empty($having)) ? "" : "\nHAVING\n\t$having";
            $orderby = (empty($orderby)) ? "" : "\nORDER BY\n\t$orderby";

            $this->sql = "\nSELECT\n\t$select\nFROM\n\t$from$where$groupby$having$orderby";
        }
    }

    public function real_escape($str)
    {
        return $this->db->real_escape_string($str);
    }

    public function db_query($sql)
    {
        return $this->db->query($sql);
    }

    public function db_fetch_assoc($query)
    {
        return $query->fetch_assoc();
    }

    public function db_fetch_object($query)
    {
        return $query->fetch_object();
    }

    public function freeresult($query)
    {
        $query->close();
    }

    public function dbclose()
    {
//    $mysqli->close();
    }

}

?>