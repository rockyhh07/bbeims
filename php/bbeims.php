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
            ["id", "username", "fullname", "contact", "category", "active", "birthday"],
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

        if(count($result) === 0) return ["result"=>false, "message"=>"Invalid username or password"];
        if($result[0]["active"] === "0") return ["result"=>false, "message"=>"Account is deactivated"];

        $result[0]["result"] = true;
        return $_SESSION["user"] = $result[0];
    }

    public static function user_update(array $post_result) {
        QUERY::escape_str_all($post_result);
        $uid = $post_result['uid'];
        $id = $post_result['id'];
        $o_pass = $post_result['o_password'];
        $n_pass = $post_result['n_password'];
        $active = empty($post_result['active']) ? "0" :"1";
        $username = strtoupper($post_result['username']);

        $query = "UPDATE `users` SET";

        foreach($post_result as $key=>$val) {
            if($key === "id") continue;
            if($key === "uid") continue;
            if($key === "o_password") continue;
            if($key === "n_password") continue;
            if($key === "active") continue;

            $query .= " `{$key}`='".strtoupper($val)."',";
        }

        $query .= "
            	`active` = '{$active}',
                `updated_by`='{$uid}',
                `updated_date`=CURRENT_TIMESTAMP
            WHERE
                `id`='{$id}'
        ";

        $r1 = QUERY::run($query);

        $matched = QUERY::run("SELECT `id` FROM `users` WHERE `username`='{$username}' AND `password`=PASSWORD('{$o_pass}')");

        $query2 = "UPDATE `users` SET";
        if(count($matched) > 0) {
            $query2 .= "
                `password`=PASSWORD('{$n_pass}')
            WHERE 
                `id` = '{$id}'
            ";
            array_push($r1, QUERY::run($query2)[0]);
        }else {
            array_push($r1, ["result"=>false]);
        }
        return $r1;
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
                        e.`deletedflag` = 0 AND
                        e.`incident_date` IS NOT NULL AND
                        e.`incident_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
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
                        e.`incident_date` IS NOT NULL AND
                        e.`incident_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
                    ) `female`,
                    (SELECT 
                        count(`id`)
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0 AND
                        e.`incident_date` IS NOT NULL AND
                        e.`incident_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
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
                        e.`deletedflag` = 0 AND
                        e.`incident_date` IS NOT NULL AND
                        e.`incident_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
                    ) `evacuees`,
                    (SELECT 
                        COUNT(`rep_tbl`.`rep`)
                    FROM (
                        SELECT
                            e.`representative` `rep`
                        FROM `evacuee` e
                        WHERE
                            e.`deletedflag` = 0 AND
                            e.`incident_date` IS NOT NULL AND
                            e.`incident_id` IS NOT NULL AND
                            e.`evac_id` IS NOT NULL
                        GROUP BY `representative`
                        ) `rep_tbl`
                    ) `family`,
                    (SELECT 
                        count(*)
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
                    e.`incident_date` IS NOT NULL AND
                    e.`incident_id` IS NOT NULL AND
                    e.`evac_id` IS NOT NULL
                ";
        return QUERY::run($query);
    }

    public static function report_by_age(array $post_result) {
        QUERY::escape_str_all($post_result);
        $query = "SELECT 
                    e.`birthday`
                FROM `evacuee` e
                WHERE
                    e.`deletedflag` = 0 AND
                    e.`incident_date` IS NOT NULL AND
                    e.`incident_id` IS NOT NULL AND
                    e.`evac_id` IS NOT NULL
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
                        e.`incident_date` IS NOT NULL AND
                        e.`incident_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
                    ) `male`,
                    (SELECT 
                        count(
                            CASE 
                            WHEN e.`gender` = 'F' THEN 1
                            END
                            )
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0  AND
                        e.`incident_date` IS NOT NULL AND
                        e.`incident_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
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

        $query .= " FROM `evacuee` e WHERE
            e.`deletedflag` = 0 AND
            e.`incident_date` IS NOT NULL AND
            e.`incident_id` IS NOT NULL AND
            e.`evac_id` IS NOT NULL
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

        $query .= " FROM `evacuee` e WHERE 
            e.`deletedflag` = 0 AND
            e.`incident_date` IS NOT NULL AND
            e.`incident_id` IS NOT NULL AND
            e.`evac_id` IS NOT NULL
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

    public static function archive(array $post_result) {
        QUERY::escape_str_all($post_result);

        $uid = $post_result['uid'];

        $result = [];

        // PHASE 1, Insert all evacuee in the archive
        {
            $query_allEvac = "SELECT 
                            e.`id`,
                            e.`incident_id`,
                            e.`incident_date`,
                            e.`evac_id`
                        FROM `evacuee` e
                        WHERE
                            e.`deletedflag` = 0 AND
                            e.`incident_date` IS NOT NULL AND
                            e.`incident_id` IS NOT NULL AND
                            e.`evac_id` IS NOT NULL
            ";
    
            $query = "INSERT INTO `incident_archive` 
                (`evacuee_id`, `incident_id`, `incident_date`,`evac_id`, `created_by`, `created_date`) VALUES";
            foreach(QUERY::run($query_allEvac) as $obj) {
                $evacuee_id = $obj["id"];
                $incident_id = $obj["incident_id"];
                $incident_date = $obj["incident_date"];
                $evac_id = $obj["evac_id"];
    
                $query .= "('{$evacuee_id}', '{$incident_id}', '{$incident_date}', '{$evac_id}', '{$uid}', CURRENT_TIMESTAMP),";
            }
            $query = rtrim($query, ",");
    
            array_push($result, QUERY::run($query)[0]);
        }

        // PHASE 2, Clearing incident record
        {
            $query_repEvacuee = "SELECT 
                        e.`representative`
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0 AND
                        e.`incident_date` IS NOT NULL AND
                        e.`incident_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
                    GROUP BY e.`representative`
            ";
    
            foreach(QUERY::run($query_repEvacuee) as $obj) {
                $repID = $obj["representative"];
    
                $query = "UPDATE `evacuee` 
                        SET 
                            `evac_id`=NULL,
                            `incident_id`=NULL,
                            `incident_date`=NULL,
                            `updated_by`='{$uid}',
                            `updated_date`=CURRENT_TIMESTAMP
                        WHERE 
                            `representative`={$repID} 
                ";
                array_push($result, QUERY::run($query)[0]);
            }
        }
        return $result;
    }

    public static function generate_report_all(array $post_result) {
        QUERY::escape_str_all($post_result);

        $type = !empty($post_result['incident-type']) ? $post_result['incident-type'] : null;
        $date = !empty($post_result['incident-date']) ? $post_result['incident-date'] : null;
        $center = !empty($post_result['evac-center']) ? $post_result['evac-center'] : null;

        $con = "";
        switch ($post_result['condition']) {
            case 'type': $con = ($type==null) ? "" : "AND i.`id` = '{$type}'"; break;
            case 'date': $con = ($date==null) ? "" : "AND i.`incident_date` = '{$date}'"; break;
            case 'center': $con = ($center==null) ? "" : "AND c.`id` = '{$center}'"; break;
            default: $con = ""; break;
        }

        $query = "SELECT
                    e.incident_date `date`,
                    e.lname,
                    e.fname,
                    e.mname,
                    e.contact,
                    e.birthday,
                    e.gender,
                    e.civil_status,
                    e.address,
                    i.name `center`,
                    c.name `incident`,
                    r.lname `repLname`,
                    r.fname `repFname`,
                    r.mname `repMname`
                FROM `evacuee`              e
                INNER JOIN `incident`       i
                    ON i.id = e.`incident_id`
                INNER JOIN `evac_center`    c
                    ON c.id = e.`evac_id`
                INNER JOIN `evacuee`        r
                    ON r.`id` = e.`representative`
                WHERE e.`deletedflag` = '0' {$con}
                UNION (
                    SELECT
                        a.`incident_date` `date`,
                        e.`lname`,
                        e.`fname`,
                        e.`mname`,
                        e.`contact`,
                        e.`birthday`,
                        e.`gender`,
                        e.`civil_status`,
                        e.`address`,
                        i.`name` `center`,
                        c.`name` `incident`,
                        r.lname `repLname`,
                        r.fname `repFname`,
                        r.mname `repMname`
                    FROM `incident_archive`     a
                    INNER JOIN `evacuee`        e
                        ON e.`id` = a.`evacuee_id`
                    INNER JOIN `incident`       i
                        ON i.id = a.`incident_id`
                    INNER JOIN `evac_center`    c
                        ON c.id = a.`evac_id`
                    INNER JOIN `evacuee`        r
                    ON r.`id` = e.`representative`
                    WHERE a.`deletedflag` = '0' {$con}
                )
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
