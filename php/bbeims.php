<?php
require_once "config.php";

class BBEIMS {

    

    public static function login(Array $post_result){
        QUERY::escape_str($post_result);
        extract($post_result);
        $query = "SELECT 
                    `username`,
                    `fullname`,
                    `designation`,
                    `contact`,
                    `category`,
                    `active`
                FROM `users` u
                WHERE
                    u.`username`= '{$username}' AND
                    u.`password` = PASSWORD('{$password}') AND
                    u.`deletedflag` = 0
                ";
        return $_SESSION['user'] = json_encode(QUERY::run($query));
    }

    public static function new_user(Array $post_result){
        $data = '';
        foreach($post_result as $key => $val){
            if($key === 'password') $data .= "`{$key}`=PASSWORD('{$val}'),";
            else $data .= "`{$key}`='{$val}',";
        }
        $data = rtrim($data, ',');
        
        $query = "INSERT INTO `users` SET {$data}";

        return json_encode(QUERY::run($query));
    }
}