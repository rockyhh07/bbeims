<?php

function base_url(){
    return "http://localhost/bbeims/";
}

function validate_POST(Array $post_result){
    if(empty($post_result)){
        echo json_encode(["result"=>["Bad request"]]);
        die();
    }
    return null;
}

if (session_status() === PHP_SESSION_NONE) session_start();

function ERROR_MESSAGE(String $error){
    echo '  <div style="padding: .5rem; border: 1px solid rgba(0,0,0,0.3); border-radius: 5px;">
                <b>Error:</b> <span style="color: red;">'.$error.'</span>
            </div>';
    exit;
}

class QUERY {

    private static function connection(){
        return new mysqli("localhost", "root", "", "bbeims_db");
    }
    
    private static function perform(String $query)
    {
        $con = QUERY::connection();
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
        $con = QUERY::connection();
        $str = $con->real_escape_string($str);
        $con->close();
        return $con;
    }

    public static function escape_str_all(Array & $post_result) : Array {
        $con = QUERY::connection();
        foreach($post_result as $key => $val) $post_result[$key] = $con->real_escape_string($val);
        $con->close();
        return $post_result;
    }

    public static function run(String $query)
    {
        $result = QUERY::perform($query);
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
};

define("QUERY_SELECT", "SELECT");
define("QUERY_INSERT", "INSERT");
define("QUERY_UPDATE", "UPDATE");
define("QUERY_DELETE", "DELETE");

class QueryBuilder {

    public $errors = [];
    public $sql = "";

    public function get_error_result() {
        return [["result" => false, "error" => $this->errors]];
    }

    public function __construct(String $type, String $table, Array $post_result, Array $required = [], Array $conditions = [], Array $specials = []) {
        QUERY::escape_str_all($post_result);
        QUERY::escape_str_all($required);
        QUERY::escape_str_all($conditions);
        QUERY::escape_str_all($specials);
        switch ($type) {
            case "SELECT" : QueryBuilder::select($table, $required, $conditions, $specials); break;
            case "INSERT" : QueryBuilder::insert($table, $post_result, $required); break;
            case "UPDATE" : QueryBuilder::update($table, $post_result, $required, $conditions); break;
            case "DELETE" : QueryBuilder::delete($table, $post_result, $conditions); break;
            default : break;
        }
    }

    private function select(String $table, Array $required = [], Array $conditions = [], $specials = []) {
        $condition = $this->as_valid_condition($conditions);
        $data = $this->as_valid_required_select($required);
        $special = $this->as_valid_special($specials);
        $this->sql = "SELECT {$data} FROM {$table} WHERE {$condition} {$special}";
    }

    private function insert(String $table, Array $post_result, Array $required = []) {
        $data = $this->as_valid_data($post_result, $required);
        $this->sql = "INSERT INTO `{$table}` SET {$data}";
    }

    private function update(String $table, Array $post_result, Array $required = [], Array $conditions = []) {
        $condition = $this->as_valid_condition($conditions);
        $data = $this->as_valid_data($post_result, $required);
        $this->sql = "UPDATE `{$table}` SET {$data} WHERE {$condition}";
    }

    private function delete(String $table, $conditions = []) {
        trigger_error("Method: <b>". __METHOD__ ."</b> is deprecated");

        $this->update($table, ["deletedflag"=>"1"], ["deletedflag"], $conditions);
    }


    private function as_valid_required_select($required) {
        $data = '';
        foreach ($required as $key => $val) $data .= (is_int($key)) ? "`{$val}`," : "`{$key}` `{$val}`,";
        return rtrim($data, ',');
    }

    private function as_valid_condition(Array $conditions) {
        if (count($conditions) === 0) return "";
        $data = '1';
        foreach ($conditions as $key => $val) {
            if($key === "password") $data .= " AND `{$key}`=PASSWORD('{$val}')";
            else $data .= " AND `{$key}`='{$val}'";
        }
        return rtrim($data, ',');
    }

    private function as_valid_data(Array $post_result, Array $required){
        $data = '';
        foreach ($post_result as $key => $val) {
            if(in_array($key, $required) && empty($val)) array_push($this->errors, "'".QueryBuilder::to_valid_text($key)."' cannot be empty!");
            if($key === 'password') $data .= "`{$key}`=PASSWORD('{$val}'),";
            else $data .= "`{$key}`='{$val}',";
        }
        return rtrim($data, ','); 
    }

    private function as_valid_special(Array $specials) {
        $data = '';
        foreach($specials as $val) $data.= " {$val}";
        return $data;
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
            case 'representative' : $str = 'Representative'; break;
            case 'address' : $str = 'Address'; break;
            case 'age' : $str = 'Age'; break;
            case 'contact' : $str = 'Contact'; break;
            default : break;
        }
        return $str;
    }
}


