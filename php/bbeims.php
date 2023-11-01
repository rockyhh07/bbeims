<?php
require_once "config.php";

class BBEIMS
{

    public static function session_status(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        return [[
            "result" => isset($_SESSION['user']) && !empty($_SESSION['user']) ? true : false,
            "data" => isset($_SESSION['user']) ? $_SESSION['user'] : null
        ]];
    }

    public static function user_get_all(array $post_result)
    {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "users",
            $post_result,
            ["id", "username", "fullname", "designation", "contact", "category", "active"],
            ["deletedflag" => "0"]
        );
        return QUERY::run($query->sql);
    }

    public static function user_logout(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        return [["result" => (session_status() !== PHP_SESSION_NONE) ? session_destroy() : false]];
    }

    public static function user_new(array $post_result)
    {
        $post_result['fullname'] = strtoupper($post_result['fullname']);

        $query = new QueryBuilder(
            QUERY_INSERT,
            "users",
            $post_result,
            ["username", "fullname", "password"]
        );
        return count($query->errors) > 0 ? [["result" => false, "error" => $query->errors]] : QUERY::run($query->sql);
    }

    public static function user_login(array $post_result)
    {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "users",
            $post_result,
            ["id", "username", "fullname", "designation", "contact", "category", "active"],
            ["username" => $post_result["username"], "password" => $post_result['password'], "deletedflag" => "0"]
        );
        $result = QUERY::run($query->sql);
        return $_SESSION["user"] = count($result) > 0 ? $result : null;
    }

    public static function user_update(array $post_result)
    {
        $query = new QueryBuilder(
            QUERY_UPDATE,
            "users",
            $post_result,
            ["id", "username", "fullname", "password"],
            ["id" => $post_result["id"], "deletedflag" => 0]
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function calamity_new(array $post_result)
    {
        $post_result['name'] = strtoupper($post_result['name']);

        $query = new QueryBuilder(
            QUERY_INSERT,
            "calamity",
            $post_result,
            ["name"]
        );
        return (count($query->errors) > 0) ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function calamity_get_all(array $post_result)
    {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "calamity",
            $post_result,
            ["id", "name"],
            ["deletedflag" => "0"],
            ["ORDER BY `id`", "DESC"]
        );
        return QUERY::run($query->sql);
    }

    public static function calamity_update(array $post_result)
    {
        $query = new QueryBuilder(
            QUERY_UPDATE,
            'calamity',
            $post_result,
            [],
            []
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function evac_center_new(array $post_result)
    {
        $post_result['name'] = strtoupper($post_result['name']);

        $query = new QueryBuilder(
            QUERY_INSERT,
            'evac_center',
            $post_result,
            ["name", "address"]
        );

        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function evac_center_get_all(array $post_result)
    {
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

    public static function evac_center_update(array $post_result)
    {
        $query = new QueryBuilder(
            QUERY_UPDATE,
            'evac_center',
            $post_result,
            ["id", "name", "address"],
            ["id" => $post_result["id"], "deletedflag" => 0]
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function evacuee_new(array $post_result)
    {
        $post_result['fname'] = strtoupper($post_result['fname']);
        $post_result['lname'] = strtoupper($post_result['lname']);
        $post_result['mname'] = strtoupper($post_result['mname']);

        $query = new QueryBuilder(
            QUERY_INSERT,
            'evacuee',
            $post_result,
            ["fname", "lname", "mname", "address", "contact", "age", "gender", "civil_status", "head_of_the_family", "evac_id", "calam_id"]
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function evacuee_get_all(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        $query = "SELECT 
                    e.`id`, 
                    e.`lname`, 
                    e.`fname`, 
                    e.`mname`, 
                    e.`contact`, 
                    e.`age`, 
                    CASE
                        WHEN e.`gender` = 'M' THEN 'Male' ELSE 
                        CASE
                            WHEN e.`gender` = 'F' THEN 'Female'
                            ELSE '...'
                        END
                    END `gender`, 
                    e.`civil_status`, 
                    e.`address`, 
                    e.`head_of_the_family`,
                    ec.`name`
                FROM `evacuee` e
                INNER JOIN `evac_center` ec
                    ON ec.`id` = e.`evac_id`
                WHERE
                    e.`deletedflag` = 0 AND
                    ec.`deletedflag` = 0
        ";
        return QUERY::run($query);
    }

    public static function evacuee_update(array $post_result)
    {
        QUERY::escape_str_all($post_result);

        $uid = $post_result['uid'];

        $ids = (!empty($post_result['ids'])) ? json_decode($post_result['ids']) : [];

        if (count($ids) > 0) {
            $calam_date = $post_result['calam_date'];
            $calam_id = $post_result['calamity'];
            $evac_id = $post_result['evac_center'];
            foreach ($ids as $mem_id) {
                $query = "UPDATE 
                            `evacuee` 
                        SET 
                            `evac_id` =  '{$evac_id}',
                            `calam_id` =  '{$calam_id}',
                            `calam_date` =  '{$calam_date}',
                            `updated_by` = '{$uid}',
                            `updated_date` = CURRENT_TIMESTAMP
                        WHERE 
                            `id` = '{$mem_id}'
                ";
                QUERY::run($query);
            }

            return [[true]];
        }

        $id = $post_result['id'];
        $data = '';
        foreach ($post_result as $key => $val) {
            if ($key === 'id') continue;
            if ($key === 'uid') continue;

            $data .= " `{$key}`='{$val}',";
        }
        rtrim($data, ",");

        $query = "UPDATE `evacuee` SET {$data} 
            `updated_by` = '{$uid}',
            `updated_date` = CURRENT_TIMESTAMP
        WHERE `id` = '{$id}'";
        echo $query;

        return QUERY::run($query);
    }

    public static function dashboard_get(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        $query = "SELECT 
                    (SELECT 
                        count(
                            CASE 
                            WHEN e.`gender` = 'M' THEN 1
                            END
                            )
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0
                    ) `male`,
                    (SELECT 
                        count(
                            CASE 
                            WHEN e.`gender` = 'F' THEN 1
                            END
                            )
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0
                    ) `female`,
                    (SELECT 
                        count(`id`)
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0
                    ) `evacuees`,
                    (SELECT 
                        count(`id`)
                    FROM `evac_center` e
                    WHERE
                        e.`deletedflag` = 0
                    ) `evac_center`,
                    (SELECT 
                        count(`id`)
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0
                    ) `evacuees`,
                    (SELECT 
                        COUNT(`rep_tbl`.`rep`)
                    FROM (
                        SELECT
                            `head_of_the_family` `rep`
                        FROM `evacuee`
                        GROUP BY `head_of_the_family`
                        ) `rep_tbl`
                    ) `family`,
                    (SELECT 
                        count(`id`)
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0
                    ) `brgy`
        ";

        return QUERY::run($query);
    }

    public static function report_by_evacuee(array $post_result)
    {
        $query = new QueryBuilder(
            QUERY_SELECT,
            'evacuee',
            $post_result,
            ["id", "fname", "mname", "lname", "address", "age"],
            ["deletedflag" => 0]
        );

        return QUERY::run($query->sql);
    }

    public static function report_by_age(array $post_result)
    {
        $query = new QueryBuilder(
            QUERY_SELECT,
            'evacuee',
            $post_result,
            ["age"],
            ["deletedflag" => 0]
        );
        return QUERY::run($query->sql);
    }

    public static function report_by_gender(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        $query = "SELECT
                    (SELECT 
                        count(
                            CASE 
                            WHEN e.`gender` = 'M' THEN 1
                            END
                            )
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0
                    ) `male`,
                    (SELECT 
                        count(
                            CASE 
                            WHEN e.`gender` = 'F' THEN 1
                            END
                            )
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0
                    ) `female`
        ";
        return QUERY::run($query);
    }

    public static function report_by_calamity(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        $allCalamity = QUERY::run("SELECT `id`, `name` FROM calamity");

        $query = "SELECT";

        foreach ($allCalamity as $val) {
            $query .= " count(CASE WHEN `calam_id` = {$val['id']} THEN 1 END) `{$val['name']}`,";
        }
        $query = rtrim($query, ',');;

        $query .= " FROM `evacuee` WHERE `deletedflag` = 0";

        return QUERY::run($query);
    }

    public static function report_by_center(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        $allCenter = QUERY::run("SELECT `id`, `name` FROM `evac_center`");

        $query = "SELECT";

        foreach ($allCenter as $val) {
            $query .= " count(CASE WHEN `evac_id` = {$val['id']} THEN 1 END) `{$val['name']}`,";
        }
        $query = rtrim($query, ',');;

        $query .= " FROM `evacuee` WHERE `deletedflag` = 0";

        return QUERY::run($query);
    }

    public static function representative_get_all(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        $query = "SELECT
                    -- CONCAT('H-', LPAD(`id`, 6, '0')) `id`,
                    `id`,
                    `address`,
                    `lname`,
                    `fname`,
                    `mname`,
                    `contact`
                FROM `evacuee` e
                WHERE 
                    e.`id` = e.`head_of_the_family` AND
                    e.`deletedflag` = 0
        ";
        return QUERY::run($query);
    }

    public static function house_member_get(array $post_result)
    {
        QUERY::escape_str_all($post_result);
        extract($post_result);

        $rep = QUERY::run("SELECT `fname`, `lname`, `mname` FROM `evacuee` WHERE `id` = '{$rep_id}' LIMIT 1")[0];
        $rep = "{$rep['lname']}, {$rep['fname']} {$rep['mname']}";

        $query = "SELECT
                    `id`,
                    `address`,
                    `lname`,
                    `fname`,
                    `mname`,
                    `contact`,
                    `age`,
                    `gender`,
                    `civil_status`,
                    '{$rep}' `representative`
                FROM `evacuee` e
                WHERE 
                    e.`head_of_the_family` = '{$rep_id}' AND
                    e.`deletedflag` = 0
        ";

        return QUERY::run($query);
    }
}
