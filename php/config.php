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
}




