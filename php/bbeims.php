<?php
require_once "config.php";

class BBEIMS {

    public static function session_status(Array $post_result) {
        QUERY::escape_str_all($post_result);
        return [[
            "result"=> isset($_SESSION['user']) && !empty($_SESSION['user']) ? true : false, 
            "data" => isset($_SESSION['user']) ? $_SESSION['user'] : null
        ]];
    }

    public static function user_get_all(Array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT, 
            "users", 
            $post_result, 
            ["id", "username", "fullname", "designation", "contact", "category", "active"], 
            ["deletedflag" => "0"]
        );
        return QUERY::run($query->sql);
    }

    public static function user_logout(Array $post_result) {
        QUERY::escape_str_all($post_result);
        return [["result"=> (session_status() !== PHP_SESSION_NONE) ? session_destroy() : false ]];
    }

    public static function user_new(Array $post_result) {
        $query = new QueryBuilder (
            QUERY_INSERT, 
            "users", 
            $post_result, 
            ["username","fullname", "password"]
        );
        return count($query->errors) > 0 ? [["result" => false, "error" => $query->errors]] : QUERY::run($query->sql);
    }

    public static function user_login(Array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "users",
            $post_result,
            ["username", "fullname", "designation", "contact", "category", "active"],
            ["username" => $post_result["username"], "password" => $post_result['password'], "deletedflag" => "0"]
        );
        $result = QUERY::run($query->sql);
        return $_SESSION["user"] = count($result) > 0 ? $result : null;
    }

    public static function user_update(Array $post_result) {
        $query = new QueryBuilder (
            QUERY_UPDATE, 
            "users", 
            $post_result, 
            ["id", "username", "fullname", "password"],
            ["id" => $post_result["id"], "deletedflag" => 0]
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function calamity_new(Array $post_result) {
        $query = new QueryBuilder(
            QUERY_INSERT,
            "calamity",
            $post_result,
            ["name"]
        );
        return (count($query->errors) > 0) ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function calamity_get_all(Array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "calamity",
            $post_result,
            ["id", "name"],
            ["deletedflag"=>"0"],
            ["ORDER BY `id`", "DESC"]
        );
        return QUERY::run($query->sql);
    }

    public static function calamity_update(Array $post_result){
        $query = new QueryBuilder(
            QUERY_UPDATE,
            'calamity',
            $post_result,
            [],
            []
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function evac_center_new(Array $post_result) {
        $query = new QueryBuilder(
            QUERY_INSERT,
            'evac_center',
            $post_result,
            ["name", "address"]
        );

        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function evac_center_get_all(Array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            'evac_center',
            $post_result,
            ["id", "name", "address", "contact"],
            ["deletedflag" => 0],
            ["ORDER BY `id`", "DESC"]
        );
        return QUERY::run($query->sql);
    }

    public static function evac_center_update(Array $post_result){
        $query = new QueryBuilder(
            QUERY_UPDATE,
            'evac_center',
            $post_result,
            ["id", "name", "address"],
            ["id" => $post_result["id"], "deletedflag" => 0]
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }
    
    public static function evacuee_new(Array $post_result) {
        $query = new QueryBuilder(
            QUERY_INSERT,
            'evacuee',
            $post_result,
            ["lname", "address"]
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }
    
    public static function evacuee_get_all(Array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            'evacuee',
            $post_result,
            ["id", "lname", "fname", "mname", "contact", "age", "gender", "civil_status", "address", "head_of_the_family"],
            ["deletedflag" => 0],
            ["ORDER BY `id`", "DESC"]
        );
        return QUERY::run($query->sql);
    }

    public static function evacuee_update(Array $post_result){
        $query = new QueryBuilder(
            QUERY_UPDATE,
            'evacuee',
            $post_result,
            ["id", "lname", "address"],
            ["id" => $post_result["id"], "deletedflag" => 0]
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }
}