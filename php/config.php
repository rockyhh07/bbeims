<?php

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




