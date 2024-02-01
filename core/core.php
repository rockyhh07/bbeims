<?php
class Core
{
  private function __construct()
  {
  }

  // public static function base_url()
  // {
  //   $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/bbeims';
  //   return rtrim($base_url, '/')  ;
  // }

  // public static function import(String $path, $data = [])
  // {
  //   extract($data);
  //   include_once "../layout/{$path}.php";
  // }


  public static function base_url()
  {
    return '../..';
  }

  /**
   * Keys:
   * `path:string` -> component path,
   * `properties:string[]` -> component  properties
   */
  public static function importComponent($data = [])
  {
    extract($data);

    if ($path) require_once Core::base_url() . "{$path}index.php";

    unset($data);
  }
}
