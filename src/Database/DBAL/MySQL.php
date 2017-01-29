<?php

namespace Tau\Database\DBAL;

class MySQL
{
  protected static $config;
  protected static $connection;

  public static function connect(array $config) {
    if(self::$connection === NULL) {
      if(count($config) !== 4) {
        throw new \InvalidArgumentException("Invalid number of connection parameters");
      }

      extract($config);
      self::$config = $config;

      if(!self::$connection = @mysqli_connect($host, $user, $password, $database)) {
        throw new \RuntimeException("Could not connect to database ".  mysqli_connect_error());
      }
    }

    return self::$connection;
  }

  public static function disconnect() {
    if(self::$connection != NULL) {
      mysqli_close(self::$connection);
      self::$connection = NULL;
      return true;
    }

    return false;
  }

  public static function query($query) {
    if(!$result = self::$connection->query($query)) {
      throw new \mysqli_sql_exception(mysqli_error(self::$connection));
    }

    return $result;
  }

  public static function create($name, $fields) {
    $result = array();

    foreach($fields as $key => $value)
      $result[] = $key." ".$value;

    self::query("CREATE TABLE IF NOT EXISTS ".$name."(".implode(",", $result).")");
  }

  public static function select($name, $fields = array("*"), $where = array(), $limit = 100) {
    $data = array();
    $fields = implode(",", $fields);

    if(count($where) > 0) {
      foreach($where as $field => $value)
        $data[] = $field."=".self::quoteValue($value);

      $data = implode(",", $data);
      $query = "SELECT ".$fields." FROM ".$name." WHERE ".$data." LIMIT ".$limit;
    }
    else {
      $query = "SELECT ".$fields." FROM ".$name." LIMIT ".$limit;
    }

    return self::query($query);
  }

  public static function insert($name, $data) {
    $fields = implode(",", array_keys($data));
    $values = implode(",", array_map('self::quoteValue', array_values($data)));

    $query = "INSERT INTO ".$name."(".$fields.")"." VALUES(".$values.")";
    return self::query($query);
  }

  public static function update($name, array $set, $at = array()) {
    $data  = array();

    foreach($set as $field => $value)
      $data[] = $field."=".self::quoteValue($value);

    $data = implode(",", $data);

    if(count($at) > 0) {
      foreach($at as $field => $value)
        $where[] = $field."=".self::quoteValue($value);

      $where = implode(",", $where);
      $query = "UPDATE ".$name." SET ".$data." WHERE ".$where;
    }
    else {
      $query = "UPDATE ".$name." SET ".$data;
    }

    self::query($query);
    return self::affectedRows();
  }

  public static function delete($name, $at = array()) {
    if(count($at) > 0) {
      $where = array();

      foreach($at as $field => $value)
        $where[] = $field."=".self::quoteValue($value);

      $where = implode(" AND ", $where);
      $query = "DELETE FROM ".$name." WHERE ".$where;
    }
    else {
      $query = "DELETE FROM ".$name;
    }

    self::query($query);
    return self::affectedRows();
  }

  public static function link($whatTable, $toTable) {
    $wTable = explode(".", $whatTable)[0];
    $wField = explode(".", $whatTable)[1];

    $tTable = explode(".", $toTable)[0];
    $tField = explode(".", $toTable)[1];

    $query = "ALTER TABLE ".$wTable." ADD CONSTRAINT fk_".$wField." FOREIGN KEY (".$wField.") REFERENCES ".$tTable."(".$tField.")";
    self::query($query);
  }

  public static function fetch($result) {
    if($result !== NULL) {
      if(($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) !== false) {
        return $row;
      }
    }
  }

  public static function fetchAll($result) {
    if($result !== NULL) {
      return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
  }

  public static function numRows($result) {
    return $result !== NULL ? mysqli_num_rows($result) : 0;
  }

  public static function affectedRows() {
    return self::$connection !== NULL ? mysqli_affected_rows(self::$connection) : 0;
  }

  public static function lastInsertId() {
    return self::$connection !== NULL ? mysqli_insert_id(self::$connection) : NULL;
  }

  public static function drop($name) {
    self::query("DROP TABLE IF EXISTS ".$name);
  }

  public static function quoteValue($value) {
    if($value !== NULL) {
      if(!is_numeric($value)) {
        return "'" . mysqli_real_escape_string(self::$connection, $value) . "'";
      }

      return $value;
    }

    return 'NULL';
  }
};
