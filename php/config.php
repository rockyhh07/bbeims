<?php

function base_url(){
    return "http://172.0.3.71/ojt/bbeims";
}

function validate_POST(Array $post_result){
    return empty($post_result) ? header("location: ".base_url()) : null;
}

if (session_status() === PHP_SESSION_NONE) session_start();

function ERROR_MESSAGE(String $error){
    echo '  <div style="padding: .5rem; border: 1px solid rgba(0,0,0,0.3); border-radius: 5px;">
                <b>Error:</b> <span style="color: red;">'.$error.'</span>
            </div>';
    exit;
}

class MY_QUERY {

    private static function connection(){
        return new mysqli("localhost", "root", "", "bbeims_db");
    }
    
    private static function perform(String $query)
    {
        $con = MY_QUERY::connection();
        if (!$con) {
            ERROR_MESSAGE("Failed to connect ot database.");
            return false;
        }

        $result = $con->query($query);
        if ($result === TRUE) {
            $id = isset($con->insert_id) ? $con->insert_id : 0;
            $con->close();
            return [["result" => true, "id" => $id]];
        }

        $con->close();
        return $result;
    }

    public static function escape_str(String $str){
        $con = MY_QUERY::connection();
        $str = $con->real_escape_string($str);
        $con->close();
        return $con;
    }

    public static function escape_str_all(Array & $post_result) : Array {
        $con = MY_QUERY::connection();
        foreach($post_result as $key => $val) $post_result[$key] = $con->real_escape_string($val);
        $con->close();
        return $post_result;
    }

    public static function run(String $query)
    {
        $result = MY_QUERY::perform($query);
        if (gettype($result) === gettype([])) return $result;
        $ret = [];
        while ($row = $result->fetch_assoc()) {
            $k = array_keys($row);
            $r = [];
            foreach ($k as $col) {
                $r[$col] = $row[$col];
            }
            array_push($ret, $r);
        }
        return $ret;
    }
}


enum __QUERY_BUILDER__ {
    case QUERY_SELECT;
    case QUERY_INSERT;
    case QUERY_UPDATE;
    case QUERY_DELETE;
}

define("QUERY_SELECT", __QUERY_BUILDER__::QUERY_SELECT);
define("QUERY_INSERT", __QUERY_BUILDER__::QUERY_INSERT);
define("QUERY_UPDATE", __QUERY_BUILDER__::QUERY_UPDATE);
define("QUERY_DELETE", __QUERY_BUILDER__::QUERY_DELETE);

class QueryBuilder {

    public $errors = [];
    public $query = "";

    public function __construct(__QUERY_BUILDER__ $type, String $table, Array $post_result, Array $required = [], Array $conditions = []) {
        MY_QUERY::escape_str_all($post_result);
        MY_QUERY::escape_str_all($required);
        MY_QUERY::escape_str_all($conditions);
        switch ($type) {
            case __QUERY_BUILDER__::QUERY_SELECT : QueryBuilder::select($table, $required, $conditions); break;
            case __QUERY_BUILDER__::QUERY_INSERT : QueryBuilder::insert($table, $post_result, $required); break;
            case __QUERY_BUILDER__::QUERY_UPDATE : QueryBuilder::update($table, $post_result, $required, $conditions); break;
            case __QUERY_BUILDER__::QUERY_DELETE : QueryBuilder::delete($table, $post_result, $conditions); break;
            default : break;
        }
    }

    private function select(String $table, Array $required = [], Array $condition = []) {

    }

    private function insert(String $table, Array $post_result, Array $required = []) {
        $data = '';
        foreach ($post_result as $key => $val) {
            if(in_array($key, $required) && empty($val)) array_push($this->errors, "'".QueryBuilder::to_valid_text($key)."' cannot be empty!");
            if($key === 'password') $data .= "`{$key}`=PASSWORD('{$val}'),";
            else $data .= "`{$key}`='{$val}',";
        }
        $data = rtrim($data, ',');
        $this->query = "INSERT INTO `{$table}` SET {$data}";
    }

    private function update(String $table, Array $post_result, Array $required = [], Array $conditions = []) {
        $condition = '1';
        foreach ($conditions as $key => $val) $condition += " AND `{$key}`={$val}";
        $data = '';
        foreach ($post_result as $key => $val) {
            if(in_array($key, $required) && empty($val)) array_push($this->errors, "'".QueryBuilder::to_valid_text($key)."' cannot be empty!");
            if($key === 'password') $data .= "`{$key}`=PASSWORD('{$val}'),";
            else $data .= "`{$key}`='{$val}',";
        }
        $data = rtrim($data, ',');
        $this->query = "UPDATE `{$table}` SET {$data} WHERE {$condition}`";
    }

    private function delete(String $table, $conditions = []) {

    }

    public static function to_valid_text(String & $str) {
        switch ($str) {
            case 'password' : $str = 'Password'; break;
            case 'username' : $str = 'User Name'; break;
            case 'fullname' : $str = 'Full Name'; break;
            case 'name' : $str = 'Name'; break;
            case 'lname' : $str = 'Last Name'; break;
            case 'fname' : $str = 'First Name'; break;
            case 'mname' : $str = 'Middle Name'; break;
            case 'id' : $str = 'ID'; break;
            case 'civil_status' : $str = 'Civil Status'; break;
            case 'head_of_the_family' : $str = 'Head of the Family'; break;
            case 'address' : $str = 'Address'; break;
            case 'age' : $str = 'Age'; break;
            case 'contact' : $str = 'Contact'; break;
            default : break;
        }
        return $str;
    }
}


