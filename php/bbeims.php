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

    // public static function user_logout(array $post_result) {
    //     QUERY::escape_str_all($post_result);
    //     return [["result" => (session_status() !== PHP_SESSION_NONE) ? session_destroy() : false]];
    // }

    public static function user_get_all(array $post_result) {
      $query = "SELECT 
          `id`,
          `username`,
          `fullname`,
          `contact`,
          `category`,
          `barangay_id`,
          `active`,
          `birthday`
        FROM 
          `users`
        WHERE
          `deletedflag` = 0 AND
          NOT `id` = 1
      ";
        return QUERY::run($query);
    }

    public static function user_archived(array $post_result) {
      $query = new QueryBuilder(
          QUERY_SELECT,
          "users",
          $post_result,
          ["id", "fullname"],
          ["deletedflag" => 1]
      );
      return QUERY::run($query->sql);
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
            if($key === 'barangay') $key = 'barangay_id';
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
            ["id", "username", "fullname", "contact", "category", "barangay_id", "active", "protected"],
            ["username" => $post_result["username"], "password" => $post_result['password'], "deletedflag" => "0"]
        );
        $result = QUERY::run($query->sql);

        if(count($result) === 0) return ["result"=>false, "message"=>"Invalid username or password"];
        if($result[0]["active"] === "0") return ["result"=>false, "message"=>"Account is deactivated"];

        $result[0]["result"] = true;
        return $result[0];
    }

    public static function user_updatePassword(array $post_result) {
      QUERY::escape_str_all($post_result);
      $uid = $post_result['uid'];
      $password = $post_result['password'];
      $username = $post_result['username'];
      $query = "UPDATE `users`
        SET 
        `password` = PASSWORD('{$password}'),
        `protected` = 1
        WHERE `id` = {$uid};
      ";
      QUERY::run($query);
      return BBEIMS::user_login(['username' => $username, 'password' => $password]);
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
            if($key === "barangay") $key="barangay_id";

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

    public static function user_resetPassword(array $post_result) {
      QUERY::escape_str_all($post_result);
      $query = "
      UPDATE `users`
      SET
        `password` = password('default'),
        `protected` = 0
      WHERE
        `id` = {$post_result['id']}
      ";
      return ["result" => QUERY::run($query)];
    }

    public static function disaster_archived(array $post_result) {
      $query = new QueryBuilder(
        QUERY_SELECT,
        "disaster",
        $post_result,
        ["id", "name"],
        ["deletedflag" => "1"],
        ["ORDER BY `id`", "DESC"]
      );
      return QUERY::run($query->sql);
    }

    public static function disaster_get_all(array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            "disaster",
            $post_result,
            ["id", "name"],
            ["deletedflag" => "0"],
            ["ORDER BY `id`", "DESC"]
        );
        return QUERY::run($query->sql);
    }

    public static function disaster_new(array $post_result) {
        QUERY::escape_str_all($post_result);

        $uid = $post_result['uid'];
        $name = strtoupper($post_result['name']);
        
        $currentdisasters = QUERY::run("SELECT `name` FROM `disaster`");
        
        $alreadyExist = false;
        foreach($currentdisasters as $obj) 
            if($obj['name'] == $name) { $alreadyExist = true; break; }

        if($alreadyExist) {
            $query = "UPDATE `disaster` SET 
                    `deletedflag` = 0,
                    `updated_by` = '{$uid}',
                    `updated_date` = CURRENT_TIMESTAMP
                WHERE 
                    `name` = '{$name}'
            ";
        }else {
            $query = "INSERT INTO `disaster` SET 
                `name` = '{$name}',
                `created_by` = '{$uid}',
                `created_date` = CURRENT_TIMESTAMP
            ";
        }

        return QUERY::run($query);
    }

    public static function disaster_update(array $post_result) {
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

        $query = "UPDATE `disaster`
            SET {$data},
                `updated_by`='{$uid}',
                `updated_date`=CURRENT_TIMESTAMP
            WHERE
                `id`='{$id}'
        ";

        return QUERY::run($query);
    }

    public static function disaster_delete(array $post_result) {
        return BBEIMS::delete_data($post_result, 'disaster');
    }

    public static function evac_center_get_all(array $post_result) {
        $query = new QueryBuilder(
            QUERY_SELECT,
            'evac_center',
            $post_result,
            ["id", "name", "address", "contact", "capacity"],
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
        $capacity = $post_result['capacity'];
        
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
                    `capacity` = '{$capacity}',
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
                `capacity` = '{$capacity}',
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

    public static function evac_center_archived(array $post_result) {
      $query = new QueryBuilder(
        QUERY_SELECT,
        'evac_center',
        $post_result,
        ["id", "name"],
        ["deletedflag" => 1],
        ["ORDER BY `id`", "DESC"]
      );
      return QUERY::run($query->sql);
    }

    public static function evacuee_archived(array $post_result) {
      QUERY::escape_str_all($post_result);
      $query = "SELECT
                  a.`id`,
                  e.`fname`,
                  e.`lname`,
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
                  a.`disaster_date`,
                  e.`representative`,
                  e2.`lname` `rep_lname`,
                  e2.`fname` `rep_fname`,
                  e2.`mname` `rep_mname`,
                  ec.`name` `evac_name`,
                  ec.`id` `evac_id`,
                  i.`name` `disaster_name`
                FROM `disaster_archive` a
                INNER JOIN `disaster` i
                  ON i.`id` = a.`disaster_id`
                INNER JOIN `evacuee` e
                  ON e.`id` = a.`evacuee_id`
                INNER JOIN `evacuee` e2
                  ON e2.`id` = e.`representative`
                INNER JOIN `evac_center` ec
                  ON ec.`id` = a.`evac_id`
                WHERE
                  a.`deletedflag` = 0 AND
                  a.`disaster_date` IS NOT NULL AND
                  ec.`deletedflag` = 0
      ";
      return QUERY::run($query);
      
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
                    e.`disaster_date`,
                    e.`representative`,
                    e2.`lname` `rep_lname`,
                    e2.`fname` `rep_fname`,
                    e2.`mname` `rep_mname`,
                    ec.`name` `evac_name`,
                    ec.`id` `evac_id`,
                    i.`name` `disaster_name`
                FROM `evacuee` e
                INNER JOIN `disaster` i
                    ON i.`id` = e.`disaster_id`
                INNER JOIN `evac_center` ec
                    ON ec.`id` = e.`evac_id`
                INNER JOIN `evacuee` e2
                    ON e2.`id` = e.`representative`
                WHERE
                    e.`deletedflag` = 0 AND
                    e.`disaster_date` IS NOT NULL AND
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

    public static function evacuee_new_disaster(array $post_result) {
        QUERY::escape_str_all($post_result);

        $uid = $post_result['uid'];

        $ids = (!empty($post_result['ids'])) ? json_decode($post_result['ids']) : [];

        if (count($ids) > 0) {
            $disaster_date = $post_result['disaster_date'];
            $disaster_id = $post_result['disaster'];
            $evac_id = $post_result['evac_center'];
            $rescuer = strtoupper($post_result['rescuer']);
            foreach ($ids as $mem_id) {
                $query = "UPDATE 
                            `evacuee` 
                        SET 
                            `rescuer` =  '{$rescuer}',
                            `evac_id` =  '{$evac_id}',
                            `disaster_id` =  '{$disaster_id}',
                            `disaster_date` =  '{$disaster_date}',
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

        return QUERY::run($query);
    }

    public static function evacuee_update_disaster(array $post_result) {
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
      return BBEIMS::delete_data($post_result, 'evacuee');
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
                        e.`disaster_date` IS NOT NULL AND
                        e.`disaster_id` IS NOT NULL AND
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
                        e.`disaster_date` IS NOT NULL AND
                        e.`disaster_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
                    ) `female`,
                    (SELECT 
                        count(`id`)
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0 AND
                        e.`disaster_date` IS NOT NULL AND
                        e.`disaster_id` IS NOT NULL AND
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
                        e.`disaster_date` IS NOT NULL AND
                        e.`disaster_id` IS NOT NULL AND
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
                            e.`disaster_date` IS NOT NULL AND
                            e.`disaster_id` IS NOT NULL AND
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
                    e.`disaster_date` IS NOT NULL AND
                    e.`disaster_id` IS NOT NULL AND
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
                    e.`disaster_date` IS NOT NULL AND
                    e.`disaster_id` IS NOT NULL AND
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
                        e.`disaster_date` IS NOT NULL AND
                        e.`disaster_id` IS NOT NULL AND
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
                        e.`disaster_date` IS NOT NULL AND
                        e.`disaster_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
                    ) `female`
        ";
        return QUERY::run($query);
    }

    public static function report_by_disaster(array $post_result) {
        QUERY::escape_str_all($post_result);
        $alldisaster = QUERY::run("SELECT `id`, `name` FROM disaster WHERE `deletedflag` = 0");

        $query = "SELECT";

        foreach ($alldisaster as $val) {
            $query .= " count(CASE WHEN `disaster_id` = {$val['id']} THEN 1 END) `{$val['name']}`,";
        }
        $query = rtrim($query, ',');;

        $query .= " FROM `evacuee` e WHERE
            e.`deletedflag` = 0 AND
            e.`disaster_date` IS NOT NULL AND
            e.`disaster_id` IS NOT NULL AND
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
            e.`disaster_date` IS NOT NULL AND
            e.`disaster_id` IS NOT NULL AND
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

    public static function representative_archived(array $post_result) {
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
                  e.`deletedflag` = 1
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

    public static function house_member_archived(array $post_result) {
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
                  e.`deletedflag` = 1
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
                            e.`disaster_id`,
                            e.`disaster_date`,
                            e.`evac_id`,
                            e.`rescuer`
                        FROM `evacuee` e
                        WHERE
                            e.`deletedflag` = 0 AND
                            e.`disaster_date` IS NOT NULL AND
                            e.`disaster_id` IS NOT NULL AND
                            e.`evac_id` IS NOT NULL
            ";
    
            $query = "INSERT INTO `disaster_archive` 
                (`evacuee_id`, `disaster_id`, `disaster_date`,`evac_id`, `created_by`, `rescuer`, `created_date`) VALUES";
            foreach(QUERY::run($query_allEvac) as $obj) {
                $evacuee_id = $obj["id"];
                $disaster_id = $obj["disaster_id"];
                $disaster_date = $obj["disaster_date"];
                $evac_id = $obj["evac_id"];
                $rescuer = strtoupper($obj["rescuer"]);
    
                $query .= "('{$evacuee_id}', '{$disaster_id}', '{$disaster_date}', '{$evac_id}', '{$uid}', '{$rescuer}', CURRENT_TIMESTAMP),";
            }
            $query = rtrim($query, ",");
    
            array_push($result, QUERY::run($query)[0]);
        }

        // PHASE 2, Clearing disaster record
        {
            $query_repEvacuee = "SELECT 
                        e.`representative`
                    FROM `evacuee` e
                    WHERE
                        e.`deletedflag` = 0 AND
                        e.`disaster_date` IS NOT NULL AND
                        e.`disaster_id` IS NOT NULL AND
                        e.`evac_id` IS NOT NULL
                    GROUP BY e.`representative`
            ";
    
            foreach(QUERY::run($query_repEvacuee) as $obj) {
                $repID = $obj["representative"];
    
                $query = "UPDATE `evacuee` 
                        SET 
                            `evac_id`=NULL,
                            `disaster_id`=NULL,
                            `disaster_date`=NULL,
                            `rescuer`=NULL,
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

        $type = !empty($post_result['disaster-type']) ? $post_result['disaster-type'] : null;
        $date = !empty($post_result['disaster-date']) ? $post_result['disaster-date'] : null;
        $center = !empty($post_result['evac-center']) ? $post_result['evac-center'] : null;

        $con = "";
        switch ($post_result['condition']) {
            case 'type': $con = ($type==null) ? "" : "AND i.`id` = '{$type}'"; break;
            case 'date': $con = ($date==null) ? "" : "AND i.`disaster_date` = '{$date}'"; break;
            case 'center': $con = ($center==null) ? "" : "AND c.`id` = '{$center}'"; break;
            default: $con = ""; break;
        }

        $query = "SELECT
                    e.disaster_date `date`,
                    e.lname,
                    e.fname,
                    e.mname,
                    e.contact,
                    e.birthday,
                    e.gender,
                    e.civil_status,
                    e.address,
                    i.name `center`,
                    c.name `disaster`,
                    r.lname `repLname`,
                    r.fname `repFname`,
                    r.mname `repMname`
                FROM `evacuee`              e
                INNER JOIN `disaster`       i
                    ON i.id = e.`disaster_id`
                INNER JOIN `evac_center`    c
                    ON c.id = e.`evac_id`
                INNER JOIN `evacuee`        r
                    ON r.`id` = e.`representative`
                WHERE e.`deletedflag` = '0' {$con}
                UNION (
                    SELECT
                        a.`disaster_date` `date`,
                        e.`lname`,
                        e.`fname`,
                        e.`mname`,
                        e.`contact`,
                        e.`birthday`,
                        e.`gender`,
                        e.`civil_status`,
                        e.`address`,
                        i.`name` `center`,
                        c.`name` `disaster`,
                        r.lname `repLname`,
                        r.fname `repFname`,
                        r.mname `repMname`
                    FROM `disaster_archive`     a
                    INNER JOIN `evacuee`        e
                        ON e.`id` = a.`evacuee_id`
                    INNER JOIN `disaster`       i
                        ON i.id = a.`disaster_id`
                    INNER JOIN `evac_center`    c
                        ON c.id = a.`evac_id`
                    INNER JOIN `evacuee`        r
                    ON r.`id` = e.`representative`
                    WHERE a.`deletedflag` = '0' {$con}
                )
        ";
        return QUERY::run($query);
    }

    public static function barangay_get_all(array $post_result) {
      QUERY::escape_str_all($post_result);
      extract($post_result);
      $query = "SELECT
                  `id`,
                  `name`
              FROM `barangay` e
              WHERE 
                  e.`deletedflag` = 0
      ";
      return QUERY::run($query);
      
    }

    public static function barangay_new(array $post_result) {
      QUERY::escape_str_all($post_result);

      $uid = $post_result['uid'];
      $name = strtoupper($post_result['name']);
      
      $currentBarangay = QUERY::run("SELECT `name` FROM `barangay`");
      
      $alreadyExist = false;
      foreach($currentBarangay as $obj) 
          if($obj['name'] == $name) { $alreadyExist = true; break; }

      if($alreadyExist) {
          $query = "UPDATE `barangay` SET 
                  `deletedflag` = 0,
                  `updated_by` = '{$uid}',
                  `updated_date` = CURRENT_TIMESTAMP
              WHERE 
                  `name` = '{$name}'
          ";
      }else {
          $query = "INSERT INTO `barangay` SET 
              `name` = '{$name}',
              `created_by` = '{$uid}',
              `created_date` = CURRENT_TIMESTAMP
          ";
      }

      return QUERY::run($query);
      
    }

    public static function barangay_update(array $post_result) {
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

      $query = "UPDATE `barangay`
          SET {$data},
              `updated_by`='{$uid}',
              `updated_date`=CURRENT_TIMESTAMP
          WHERE
              `id`='{$id}'
      ";

      return QUERY::run($query);
      
    }

    public static function barangay_delete(array $post_result) {
      return BBEIMS::delete_data($post_result, 'barangay');
    }

    public static function barangay_archived(array $post_result) {
      QUERY::escape_str_all($post_result);
      $query = "SELECT
                  `id`,
                  `name`
                FROM `barangay` e
                WHERE 
                  e.`deletedflag` = 1
      ";

      return QUERY::run($query);
    }

    public static function disaster_archived_getAll() {
      $query = "SELECT * FROM `disaster_archive` WHERE `deletedflag` = 0";
      return QUERY::run($query);
    }

    public static function disaster_archived_getAll_options() {
      $query = "SELECT
          ia.id `id`, 
          i.`name` `name`,
          ia.`disaster_date` `disaster_date`
        FROM `disaster_archive` ia
        INNER JOIN `disaster` i
          ON i.`id` = ia.`disaster_id` 
        WHERE 
          ia.`deletedflag` = 0 
        GROUP BY ia.`disaster_date` 
        ORDER BY ia.`disaster_id`
      ";
      return QUERY::run($query);
    }

    public static function generate_report_by_disaster(array $post_result) {
      // QUERY::escape_str_all($post_result);
      $disaster_date = $post_result['disaster_date'];

      $query = "SELECT
          ia.id `id`,
          ia.`rescuer` `rescuer`,
          e.`fname` `fname`,
          e.`mname` `mname`,
          e.`lname` `lname`,
          e.`birthday` `birthday`,
          e.`gender` `gender`,
          e.`contact` `contact`,
          e2.`fname` `rep_fname`,
          e2.`mname` `rep_mname`,
          e2.`lname` `rep_lname`,
          e.`address` `address`,
          e.`address` `address`,
          c.`name` `center`,
          i.`name` `disaster_name`,
          ia.`disaster_date` `disaster_date`
        FROM `disaster_archive` ia
        INNER JOIN `disaster` i
          ON i.`id` = ia.`disaster_id`
        INNER JOIN `evacuee` e
          ON e.`id` = ia.`evacuee_id`
        INNER JOIN `evacuee` e2
          ON e2.`id` = e.`representative`
        INNER JOIN `evac_center` c
          ON c.`id` = ia.`evac_id`
        WHERE 
          ia.`deletedflag` = 0 AND
          ia.`disaster_date` IN ({$disaster_date})
        ORDER BY ia.`disaster_id`
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

?>