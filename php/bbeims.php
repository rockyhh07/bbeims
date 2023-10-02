<?php
require_once "config.php";

class BBEIMS {

    public static function session_status(Array $post_result){
        QUERY::escape_str_all($post_result);
        return [[
            "result"=> isset($_SESSION['user']) ? true : false, 
            "data" => isset($_SESSION['user']) ? $_SESSION['user'] : null
        ]];
    }

    public static function user_get_all(Array $post_result){
        QUERY::escape_str_all($post_result);
        $query = "SELECT 
                `id`,
                `username`,
                `fullname`,
                `designation`,
                `contact`,
                `category`,
                `active`
            FROM `users`
            ";
        return QUERY::run($query);
    }

    public static function user_logout(Array $post_result){
        QUERY::escape_str_all($post_result);
        return [["result"=> (session_status() !== PHP_SESSION_NONE) ? session_destroy() : false ]];
    }

    public static function user_login(Array $post_result){
        QUERY::escape_str_all($post_result);
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
        return $_SESSION['user'] = QUERY::run($query);
    }
    
    public static function user_new(Array $post_result){
        QUERY::escape_str_all($post_result);
        $required = ['username','fullname', 'password'];
        $errors = [];
        $data = '';
        foreach($post_result as $key => $val){
            if(in_array($key, $required) && empty($val)) array_push($errors, "{$key} cannot be empty!");
            if($key === 'password') $data .= "`{$key}`=PASSWORD('{$val}'),";
            else $data .= "`{$key}`='{$val}',";
        }
        $data = rtrim($data, ',');
        
        $query = "INSERT INTO `users` SET {$data}";
        
        return count($errors) > 0 ? [["result" => false, "error" => $errors]] : QUERY::run($query);
    }

    public static function calamity_new(Array $post_result) {
        QUERY::escape_str_all($post_result);
        
        
    }

    public static function calamity_get_all(Array $post_result){
        QUERY::escape_str_all($post_result);


    }

    public static function evac_center_new(Array $post_result) {
        QUERY::escape_str_all($post_result);
        
        
    }

    public static function evac_center_get_all(Array $post_result){
        QUERY::escape_str_all($post_result);


    }

    public static function evacuee_new(Array $post_result) {
        QUERY::escape_str_all($post_result);
        
        
    }

    public static function evacuee_get_all(Array $post_result){
        QUERY::escape_str_all($post_result);


    }
}