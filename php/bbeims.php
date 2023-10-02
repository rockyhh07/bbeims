<?php
require_once "config.php";

class BBEIMS {

    public static function session_status(Array $post_result) {
        MY_QUERY::escape_str_all($post_result);
        return [[
            "result"=> isset($_SESSION['user']) ? true : false, 
            "data" => isset($_SESSION['user']) ? $_SESSION['user'] : null
        ]];
    }

    public static function user_get_all(Array $post_result) {
        MY_QUERY::escape_str_all($post_result);
        $query = "SELECT 
                `id`,
                `username`,
                `fullname`,
                `designation`,
                `contact`,
                `category`,
                `active`
            FROM `users`
            WHERE 
                `deletedflag` = 0
            ";
        return MY_QUERY::run($query);
    }

    public static function user_logout(Array $post_result) {
        MY_QUERY::escape_str_all($post_result);
        return [["result"=> (session_status() !== PHP_SESSION_NONE) ? session_destroy() : false ]];
    }

    public static function user_login(Array $post_result) {
        MY_QUERY::escape_str_all($post_result);
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
        return $_SESSION['user'] = MY_QUERY::run($query);
    }
    
    public static function user_new(Array $post_result) {
        $query = new QueryBuilder (
            $type = QUERY_INSERT, 
            $table = 'users', 
            $post_result, 
            $required = ['username','fullname', 'password'],
            $conditions = []
        );
        return count($query->errors) > 0 ? [["result" => false, "error" => $query->errors]] : MY_QUERY::run($query->query);
    }

    public static function user_update(Array $post_result) {
        $query = new QueryBuilder (
            $type = QUERY_UPDATE, 
            $table = 'users', 
            $post_result, 
            $required = ['username','fullname', 'password'],
            $conditions = ['id' => $post_result['id'], 'deletedflag' => '0']
        );
        return count($query->errors) > 0 ? [["result" => false, "error" => $query->errors]] : MY_QUERY::run($query->query);
    }

    public static function calamity_new(Array $post_result) {
        MY_QUERY::escape_str_all($post_result);
        $required = ['name'];
        $errors = [];
        $data = '';
        foreach ($post_result as $key => $val) {
            // if(in_array($key, $required) && empty($val)) array_push($errors, "'".BBEIMS::__to_valid_text__($key)."' cannot be empty!");
            //else 
            $data .= "`{$key}`='{$val}',";
        }
        $data = rtrim($data, ',');
        $query = "INSERT INTO `calamity` SET {$data}";
        return count($query->errors) > 0 ? [["result" => false, "error" => $query->errors]] : MY_QUERY::run($query->query);
        
    }

    public static function calamity_get_all(Array $post_result) {
        MY_QUERY::escape_str_all($post_result);
        $query = "SELECT 
                    `id`, 
                    `name` 
                FROM `calamity` 
                WHERE 
                    `deletedflag` = 0
                ";
        return MY_QUERY::run($query);
    }

    public static function calamity_update(Array $post_result){
        MY_QUERY::escape_str_all($post_result);

    }

    public static function evac_center_new(Array $post_result, Array $required) {
        MY_QUERY::escape_str_all($post_result);
        $required = ['name','address'];
        $errors = [];
        $data = '';
        foreach ($post_result as $key => $val) {
            // if(in_array($key, $required) && empty($val)) array_push($errors, "'".BBEIMS::__to_valid_text__($key)."' cannot be empty!");
            //else 
            $data .= "`{$key}`='{$val}',";
        }
        $data = rtrim($data, ',');
        $query = "INSERT INTO `evac_center` SET {$data}";
        return count($query->errors) > 0 ? [["result" => false, "error" => $query->errors]] : MY_QUERY::run($query->query);
    }

    public static function evac_center_get_all(Array $post_result) {
        MY_QUERY::escape_str_all($post_result);
        $query = "SELECT 
                    `id`, 
                    `name`, 
                    `address`, 
                    `contact`, 
                    `deletedflag` 
                FROM `evac_center` 
                WHERE 
                    `deletedflag` = 0
                ";
        return MY_QUERY::run($query);
    }

    public static function evac_center_update(Array $post_result){
        MY_QUERY::escape_str_all($post_result);

    }
    
    public static function evacuee_new(Array $post_result, Array $required) {
        MY_QUERY::escape_str_all($post_result);
        $required = ['lname','address'];
        $errors = [];
        $data = '';
        foreach ($post_result as $key => $val) {
            // if(in_array($key, $required) && empty($val)) array_push($errors, "'".BBEIMS::__to_valid_text__($key)."' can not be empty.");
            //else 
            $data .= "`{$key}`='{$val}',";
        }
        $data = rtrim($data, ',');
        $query = "INSERT INTO `evacuee` SET {$data}";
        return count($query->errors) > 0 ? [["result" => false, "error" => $query->errors]] : MY_QUERY::run($query->query);

    }
    
    public static function evacuee_get_all(Array $post_result) {
        MY_QUERY::escape_str_all($post_result);
        $query = "SELECT 
                    `id`, 
                    `lname`, 
                    `fname`, 
                    `mname`, 
                    `contact`, 
                    `age`, 
                    `gender`, 
                    `civil_status`, 
                    `address`, 
                    `head_of_the_family`
                FROM `evacuee`
                WHERE 
                    `deletedflag` = 0
                ";
        return MY_QUERY::run($query);
    }

    public static function evacuee_update(Array $post_result){
        MY_QUERY::escape_str_all($post_result);

    }
}