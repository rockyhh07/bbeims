<?php
require_once "config.php";

class BBEIMS
{

    public static function session_status(array $post_result) {
        QUERY::escape_str_all($post_result);
        return [[
            "result" => isset($_SESSION['user']) && !empty($_SESSION['user']) ? true : false,
            "data" => isset($_SESSION['user']) ? $_SESSION['user'] : null
        ]];
    }

    public static function user_get_all(array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "users",
            $post_result,
            ["id", "username", "fullname", "contact", "category", "active"],
            ["deletedflag" => "0"]
        );
        return QUERY::run($query->sql);
    }

    public static function user_logout(array $post_result) {
        QUERY::escape_str_all($post_result);
        return [["result" => (session_status() !== PHP_SESSION_NONE) ? session_destroy() : false]];
    }

    public static function user_new(array $post_result) {
        QUERY::escape_str_all($post_result);

        $post_result['username'] = strtoupper($post_result['username']);
        $post_result['fullname'] = strtoupper($post_result['fullname']);

        $uid = $post_result['uid'];
        $username = $post_result['username'];

        $currentUsers = QUERY::run("SELECT `username` FROM `users`");
        
        $alreadyExist = false;
        foreach($currentUsers as $obj) 
            if($obj['username'] == $username) { $alreadyExist = true; break; }

        if($alreadyExist) {
            return false;
        }

        $query = "INSERT INTO `users` SET";
        foreach($post_result as $key=>$val) {
            if($key === "uid") continue;
            if($key === "password") {
                $query .= " `{$key}`=PASSWORD('{$val}'),";
                continue;
            }
            $query .= " `{$key}`='{$val}',";
        }
        $query .= "
                `created_by`='{$uid}',
                `created_date`=CURRENT_TIMESTAMP
        ";
        return QUERY::run($query);    
    }

    public static function user_login(array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "users",
            $post_result,
            ["id", "username", "fullname", "contact", "category", "active"],
            ["username" => $post_result["username"], "password" => $post_result['password'], "deletedflag" => "0"]
        );
        $result = QUERY::run($query->sql);
        return $_SESSION["user"] = count($result) > 0 ? $result : null;
    }

    public static function user_update(array $post_result) {
        $query = new QueryBuilder(
            QUERY_UPDATE,
            "users",
            $post_result,
            ["id", "username", "fullname", "password"],
            ["id" => $post_result["id"], "deletedflag" => 0]
        );
        return count($query->errors) > 0 ? $query->get_error_result() : QUERY::run($query->sql);
    }

    public static function user_delete($post_result) {
        BBEIMS::delete_data($post_result, 'users');
    }

    public static function incident_get_all(array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "incident",
            $post_result,
            ["id", "name"],
            ["deletedflag" => "0"],
            ["ORDER BY `id`", "DESC"]
        );
        return QUERY::run($query->sql);
    }

    public static function incident_new(array $post_result) {
        QUERY::escape_str_all($post_result);

        $uid = $post_result['uid'];
        $name = strtoupper($post_result['name']);
        
        $currentIncidents = QUERY::run("SELECT `name` FROM `incident`");
        
        $alreadyExist = false;
        foreach($currentIncidents as $obj) 
            if($obj['name'] == $name) { $alreadyExist = true; break; }

        if($alreadyExist) {
            $query = "UPDATE `incident` SET 
                    `deletedflag` = 0,
                    `updated_by` = '{$uid}',
                    `updated_date` = CURRENT_TIMESTAMP
                WHERE 
                    `name` = '{$name}'
            ";
        }else {
            $query = "INSERT INTO `incident` SET 
                `name` = '{$name}',
                `created_by` = '{$uid}',
                `created_date` = CURRENT_TIMESTAMP
            ";
        }

        return QUERY::run($query);
    }

    public static function incident_update(array $post_result) {
        QUERY::escape_str_all($post_result);

        $id = $post_result['id'];
        $uid = $post_result['uid'];

        $data = "";
        foreach($post_result as $key => $val) {
            if($key === "uid") continue;
            if($key === "id") continue;

            $data .= " `{$key}`=".strtoupper("'$val',");
        }
        $data = rtrim($data, ",");

        $query = "UPDATE `incident`
            SET {$data},
                `updated_by`='{$uid}',
                `updated_date`=CURRENT_TIMESTAMP
            WHERE
                `id`='{$id}'
        ";

        return QUERY::run($query);
    }

    public static function  incident_delete(array $post_result) {
        return BBEIMS::delete_data($post_result, 'incident');
    }

    public static function evac_center_get_all(array $post_result) {
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

    public static function evac_center_new(array $post_result) {
        QUERY::escape_str_all($post_result);
        
        $uid = $post_result['uid'];
        $name = strtoupper($post_result['name']);
        $address = strtoupper($post_result['address']);
        $contact = strtoupper($post_result['contact']);
        
        $currentEvacCenter = QUERY::run("SELECT `name` FROM `evac_center`");
        
        $alreadyExist = false;
        foreach($currentEvacCenter as $obj) 
            if(
                $obj['name'] == $name
                ) { $alreadyExist = true; break; }

        if($alreadyExist) {
            $query = "UPDATE `evac_center` SET 
                    `address` = '{$address}',
                    `contact` = '{$contact}',
                    `deletedflag` = 0,
                    `updated_by` = '{$uid}',
                    `updated_date` = CURRENT_TIMESTAMP
                WHERE 
                    `name` = '{$name}'
            ";
        }else {
            $query = "INSERT INTO `evac_center` SET 
                `name` = '{$name}',
                `address` = '{$address}',
                `contact` = '{$contact}',
                `created_by` = '{$uid}',
                `created_date` = CURRENT_TIMESTAMP
            ";
        }
        return QUERY::run($query);
    }

    public static function evac_center_update(array $post_result) {
        QUERY::escape_str_all($post_result);

        $id = $post_result['id'];
        $uid = $post_result['uid'];

        $data = "";
        foreach($post_result as $key => $val) {
            if($key === "uid") continue;
            if($key === "id") continue;

            $data .= " `{$key}`=".strtoupper("'$val',");
        }
        $data = rtrim($data, ",");

        $query = "UPDATE `evac_center`
            SET {$data},
                `updated_by`='{$uid}',
                `updated_date`=CURRENT_TIMESTAMP
            WHERE
                `id`='{$id}'
        ";

        return QUERY::run($query);
    }

    public static function evac_center_delete(array $post_result) {
        BBEIMS::delete_data($post_result, 'evac_center');
    }

    public static function evacuee_get_all(array $post_result) {
        QUERY::escape_str_all($post_result);
        $query = "SELECT 
                    e.`id`, 
                    e.`lname`, 
                    e.`fname`, 
                    e.`mname`, 
                    e.`contact`, 
                    e.`birthday`, 
                    CASE
                        WHEN e.`gender` = 'M' THEN 'Male' ELSE 
                        CASE
                            WHEN e.`gender` = 'F' THEN 'Female'
                            ELSE '...'
                        END
                    END `gender`, 
                    e.`civil_status`, 
                    e.`address`, 
                    e.`incident_date`,
                    e.`representative`,
                    e2.`lname` `rep_lname`,
                    e2.`fname` `rep_fname`,
                    e2.`mname` `rep_mname`,
                    ec.`name` `evac_name`,
                    ec.`id` `evac_id`,
                    i.`name` `incident_name`
                FROM `evacuee` e
                INNER JOIN `incident` i
                    ON i.`id` = e.`incident_id`
                INNER JOIN `evac_center` ec
                    ON ec.`id` = e.`evac_id`
                INNER JOIN `evacuee` e2
                    ON e2.`id` = e.`representative`
                WHERE
                    e.`deletedflag` = 0 AND
                    e.`incident_date` IS NOT NULL AND
                    ec.`deletedflag` = 0
        ";
        return QUERY::run($query);
    }

    public static function evacuee_new(array $post_result) {
        QUERY::escape_str_all($post_result);
        
        $uid = $post_result['uid'];

        $data = "";
        foreach($post_result as $key=>$val){
            if($key == "uid") continue;
            if($key == "id") continue;
            if($key == "rep") continue;
            if($key == "bday") {
                $data .= " `age`='".BBEIMS::ageCalculator($val)."',";
                continue;
            }

            $data .= " `{$key}`=".strtoupper("'$val',");
        }
        $data = rtrim($data, ",");

        $query = "INSERT INTO `evacuee` SET 
            {$data},
            `created_by`='{$uid}',
            `created_date`=CURRENT_TIMESTAMP
        ";

        if($post_result['rep'] === "rep") {
            $id = QUERY::run($query)[0]["id"];

            return QUERY::run("UPDATE `evacuee` SET `representative`='{$id}' WHERE `id`='{$id}'");
        }

        return QUERY::run($query);
    }

    public static function evacuee_update(array $post_result) {
        QUERY::escape_str_all($post_result);

        $uid = $post_result['uid'];
        $id = $post_result['id'];

        $data = "";
        foreach($post_result as $key=>$val){
            if($key == "uid") continue;
            if($key == "id") continue;

            $data .= " `{$key}`=".strtoupper("'$val',");
        }
        $data = rtrim($data, ",");

        $query = "UPDATE 
                `evacuee` 
                SET {$data},
                    `updated_by` = '{$uid}',
                    `updated_date` = CURRENT_TIMESTAMP
                WHERE
                    `id` = '{$id}'
        ";
        
        return QUERY::run($query);
    }

    public static function evacuee_new_incident(array $post_result) {
        QUERY::escape_str_all($post_result);

        $uid = $post_result['uid'];

        $ids = (!empty($post_result['ids'])) ? json_decode($post_result['ids']) : [];

        if (count($ids) > 0) {
            $incident_date = $post_result['incident_date'];
            $incident_id = $post_result['incident'];
            $evac_id = $post_result['evac_center'];
            foreach ($ids as $mem_id) {
                $query = "UPDATE 
                            `evacuee` 
                        SET 
                            `evac_id` =  '{$evac_id}',
                            `incident_id` =  '{$incident_id}',
                            `incident_date` =  '{$incident_date}',
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
        $data = rtrim($data, ",");

        $query = "UPDATE `evacuee` SET {$data} 
            `updated_by` = '{$uid}',
            `updated_date` = CURRENT_TIMESTAMP
        WHERE `id` = '{$id}'";
        echo $query;

        return QUERY::run($query);
    }

    public static function evacuee_update_incident(array $post_result) {
        QUERY::escape_str_all($post_result);

        $uid = $post_result['uid'];
        $rep_id = $post_result['representative'];

        $query = "UPDATE `evacuee` SET";

        foreach($post_result as $key=>$val) {
            if($key === "uid") continue;
            if($key === "representative") continue;
            $query .= " `{$key}`='{$val}',";
        }

        $query .= "
                `updated_by`='{$uid}',
                `updated_date`=CURRENT_TIMESTAMP
            WHERE
                `representative`='{$rep_id}'
        ";
        return QUERY::run($query);
    }

    public static function evacuee_delete(array $post_result) {
        BBEIMS::delete_data($post_result, 'evacuee');
    }

    public static function dashboard_get(array $post_result) {
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
                            `representative` `rep`
                        FROM `evacuee`
                        GROUP BY `representative`
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

    public static function report_by_evacuee(array $post_result) {
        QUERY::escape_str_all($post_result);
        $query = "SELECT 
                    e.`id`,
                    e.`fname`,
                    e.`mname`,
                    e.`lname`,
                    e.`address`,
                    e.`birthday`
                FROM `evacuee` e
                WHERE
                    e.`deletedflag` = 0 AND
                    e.`incident_date` IS NOT NULL
                ";
        return QUERY::run($query);
    }

    public static function report_by_age(array $post_result) {
        $query = "SELECT 
                    e.`age`
                FROM `evacuee` e
                WHERE
                    e.`deletedflag` = 0 AND
                    e.`incident_date` IS NOT NULL 
        ";
        return QUERY::run($query);
    }

    public static function report_by_gender(array $post_result) {
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
                        e.`deletedflag` = 0 AND
                        e.`incident_date` IS NOT NULL 
                    ) `male`,
                    (SELECT 
                        count(
                            CASE 
                            WHEN e.`gender` = 'F' THEN 1
                            END
                            )
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0 AND
                        e.`incident_date` IS NOT NULL 
                    ) `female`
        ";
        return QUERY::run($query);
    }

    public static function report_by_incident(array $post_result) {
        QUERY::escape_str_all($post_result);
        $allIncident = QUERY::run("SELECT `id`, `name` FROM incident WHERE `deletedflag` = 0");

        $query = "SELECT";

        foreach ($allIncident as $val) {
            $query .= " count(CASE WHEN `incident_id` = {$val['id']} THEN 1 END) `{$val['name']}`,";
        }
        $query = rtrim($query, ',');;

        $query .= " FROM `evacuee` WHERE
            `deletedflag` = 0 AND
            `incident_date` IS NOT NULL 
        ";

        return QUERY::run($query);
    }

    public static function report_by_center(array $post_result) {
        QUERY::escape_str_all($post_result);
        $allCenter = QUERY::run("SELECT `id`, `name` FROM `evac_center` WHERE `deletedflag` = 0");

        $query = "SELECT";

        foreach ($allCenter as $val) {
            $query .= " count(CASE WHEN `evac_id` = {$val['id']} THEN 1 END) `{$val['name']}`,";
        }
        $query = rtrim($query, ',');;

        $query .= " FROM `evacuee` WHERE 
            `deletedflag` = 0 AND 
            `incident_date` IS NOT NULL 
        ";

        return QUERY::run($query);
    }

    public static function representative_get_all(array $post_result) {
        QUERY::escape_str_all($post_result);
        extract($post_result);
        $query = "SELECT
                    `id`,
                    `address`,
                    `lname`,
                    `fname`,
                    `mname`,
                    `contact`,
                    `birthday`,
                    `gender`,
                    `civil_status`,
                    
                    `representative`
                FROM `evacuee` e
                WHERE 
                    e.`id` = e.`representative` AND
                    e.`deletedflag` = 0
        ";
        return QUERY::run($query);
    }

    public static function house_member_get(array $post_result) {
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
                    `birthday`,
                    `gender`,
                    `civil_status`,
                    '{$rep}' `representative`
                FROM `evacuee` e
                WHERE 
                    e.`representative` = '{$rep_id}' AND
                    e.`deletedflag` = 0
        ";

        return QUERY::run($query);
    }

    // PRIVATES //

    private static function ageCalculator($date) {
        // birthdate
        $bdate = new DateTime($date);

        //date today
        $now = new DateTime();
        
        //get difference
        $age = $now->diff($bdate);

        //return the difference in year
        return $age->y;
    }

    private static function delete_data(array $post_result, string $table) {
        QUERY::escape_str_all($post_result);

        $id = $post_result['id'];
        $uid = $post_result['uid'];

        $query = "UPDATE `{$table}`
            SET
                `updated_by`='{$uid}',
                `updated_date`=CURRENT_TIMESTAMP,
                `deletedflag`='1'
            WHERE
                `id`='{$id}'
        ";
        return QUERY::run($query);
    }
}
