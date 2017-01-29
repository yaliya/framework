<?php

namespace Tau\Http;

class Request
{
  protected static $input;
  protected static $query;

  private static function handle() {

    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
      $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
    }

    if($_SERVER["REQUEST_METHOD"] == "GET") {
      self::$query = new \stdClass;

      foreach($_GET as $param_name => $param_value) {
        self::$query->$param_name = $param_value;
      }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      self::$input = new \stdClass;

      foreach($_POST as $param_name => $param_value) {
        self::$input->$param_name = $param_value;
      }
    }
  }

  public static function input($param) {
    self::handle();
    return self::$input->$param;
  }

  public static function query($param) {
    self::handle();
    return self::$query->$param;
  }
}
