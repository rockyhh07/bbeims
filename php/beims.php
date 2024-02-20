<?php

const hostname = 'localhost';
const username = 'root';
const password = '';
const database = 'bbeims_db';

// if (session_status() === PHP_SESSION_NONE) session_start();

class beims
{

  public static function validate_POST()
  {
    if (empty($_POST)) {
      echo json_encode(["result" => "badrequest"]);
      die();
    }
  }

  public static function asResult(array $result): string
  {
    return json_encode([
      "result" => $result,
      "status" => 200,
      "url" => __DIR__
    ]);
  }

  public static function escape_str_all(array &$post_result): array
  {
    $con = beims::connection();
    foreach ($post_result as $key => $val) $post_result[$key] = $con->real_escape_string($val);
    $con->close();
    return $post_result;
  }

  public static function runQuery(string $query)
  {
    $result = beims::perform($query);
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

  public static function queryBuilder(
    string $type,
    string $table,
    array $post_result,
    array $required = [],
    array $conditions = [],
    array $specials = []
  ): string {
    beims::escape_str_all($required);
    beims::escape_str_all($conditions);
    beims::escape_str_all($specials);
    beims::escape_str_all($post_result);
    $type = strtoupper($type);
    switch ($type) {
      case "SELECT": return beims::select($table, $required, $conditions, $specials); 
      case "INSERT": return beims::insert($table, $post_result, $required); 
      case "UPDATE": return beims::update($table, $post_result, $required, $conditions); 
      case "DELETE": return beims::delete($table, $post_result, $conditions); 
      default: return "";
    }
  }


  // ******************************
  // *          Privates          *
  // ******************************

  private function __construct()
  {
  }

  private static function connection()
  {
    return new mysqli(hostname, username, password, database);
  }

  private static function perform(String $query)
  {
    $con = beims::connection();
    if (!$con) {
      ERROR_MESSAGE("Failed to connect ot database.");
      return false;
    }

    $result = $con->query($query);
    if ($result === TRUE) {
      $id = isset($con->insert_id) ? $con->insert_id : 0;
      $con->close();
      return [["performed" => true, "id" => $id]];
    }

    $con->close();
    return $result;
  }


  // ******************************
  // *       QueryFunction        *
  // ******************************

  private static function select(String $table, array $required = [], array $conditions = [], $specials = []): string
  {
    $condition = beims::as_valid_condition($conditions);
    $data = beims::as_valid_required_select($required);
    $special = beims::as_valid_special($specials);
    return "SELECT {$data} FROM {$table} WHERE {$condition} {$special}";
  }

  private static function insert(String $table, array $post_result, array $required = []): string
  {
    $data = beims::as_valid_data($post_result, $required);
    return "INSERT INTO `{$table}` SET {$data}";
  }

  private static function update(String $table, array $post_result, array $required = [], array $conditions = []): string
  {
    $condition = beims::as_valid_condition($conditions);
    $data = beims::as_valid_data($post_result, $required);
    return "UPDATE `{$table}` SET {$data} WHERE {$condition}";
  }

  private static function delete(String $table, $conditions = [])
  {
    trigger_error("Method: <b>" . __METHOD__ . "</b> is deprecated");

    return beims::update($table, ["deletedflag" => "1"], ["deletedflag"], $conditions);
  }


  private static function as_valid_required_select($required)
  {
    $data = '';
    foreach ($required as $key => $val) $data .= (is_int($key)) ? "`{$val}`," : "`{$key}` `{$val}`,";
    return rtrim($data, ',');
  }

  private static function as_valid_condition(array $conditions)
  {
    if (count($conditions) === 0) return "";
    $data = '1';
    foreach ($conditions as $key => $val) {
      if ($key === "password") $data .= " AND `{$key}`=PASSWORD('{$val}')";
      else $data .= " AND `{$key}`='{$val}'";
    }
    return rtrim($data, ',');
  }

  private static function as_valid_data(array $post_result, array $required)
  {
    $erros = [];
    $data = '';
    foreach ($post_result as $key => $val) {
      if (in_array($key, $required) && empty($val)) array_push($erros, "'" . beims::to_valid_text($key) . "' cannot be empty!");
      if ($key === 'password') $data .= "`{$key}`=PASSWORD('{$val}'),";
      else $data .= "`{$key}`='{$val}',";
    }
    return rtrim($data, ',');
  }

  private static function as_valid_special(array $specials)
  {
    $data = '';
    foreach ($specials as $val) $data .= " {$val}";
    return $data;
  }

  public static function to_valid_text(String &$str)
  {
    switch ($str) {
      case 'password':
        $str = 'Password';
        break;
      case 'username':
        $str = 'User Name';
        break;
      case 'fullname':
        $str = 'Full Name';
        break;
      case 'name':
        $str = 'Name';
        break;
      case 'lname':
        $str = 'Last Name';
        break;
      case 'fname':
        $str = 'First Name';
        break;
      case 'mname':
        $str = 'Middle Name';
        break;
      case 'id':
        $str = 'ID';
        break;
      case 'civil_status':
        $str = 'Civil Status';
        break;
      case 'representative':
        $str = 'Representative';
        break;
      case 'address':
        $str = 'Address';
        break;
      case 'age':
        $str = 'Age';
        break;
      case 'contact':
        $str = 'Contact';
        break;
      default:
        break;
    }
    return $str;
  }
}
