<?php
class Core
{
  private function __construct()
  {
  }


  public static function base_url()
  {
    return '../..';
  }

  /**
   * Keys:
   * `path:string` -> component path,
   * `properties:<string, string>[]` -> component  properties
   */
  public static function importComponent($data = [])
  {
    extract($data);

    if ($path) {
      include Core::base_url() . "{$path}/index.php";
    }

    unset($data);
  }
}
