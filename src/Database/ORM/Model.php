<?php

namespace Tau\Database\ORM;

use Tau\Database\DBAL\MySQL;

final class Model {
  public static function from($table, $values = array()) {
    $object = new \stdClass;
    $object->_table = $table;

    foreach($values as $field => $value)
      $object->$field = $value;

    return $object;
  }

  public static function get($table, $where = array(), $fields=array("*"), $limit=100) {
    $result = MySQL::select($table, $fields, $where, $limit);

    if(MySQL::numRows($result) > 1) {
      $result = MySQL::fetchAll($result);

      $objects = array();

      foreach($result as $item) {
        $object = (object)$item;
        $object->_table = $table;
        $objects[] = $object;
      }

      return $objects;
    }
    else {
      $object = new \stdClass;
      $object->_table = $table;

      $result = MySQL::fetch($result);

      foreach($result as $key => $value) {
        $object->$key = $value;
      }

      return $object;
    }
  }

  public static function where($table, $val, $fields = array("*"), $limit = 100) {
    $prop = explode(".", $table);
    $table = explode(".", $table)[0];

    $result = MySQL::select($table, $fields, array("$prop" => $val), $limit);

    if(MySQL::numRows($result) > 1) {
      $result = MySQL::fetchAll($result);

      $objects = array();

      foreach($result as $item) {
        $object = (object)$item;
        $object->_table = $table;
        $objects[] = $object;
      }

      return $objects;
    }
    else {
      $object = new \stdClass;
      $object->_table = $table;

      $result = MySQL::fetch($result);

      foreach($result as $key => $value) {
        $object->$key = $value;
      }

      return $object;
    }
  }

  public static function getAll($table, $limit=100, $fields = array("*")) {
    $objects = array();
    $result = MySQL::fetchAll(MySQL::select($table, $fields, null, $limit));

    foreach($result as $item) {
      $object = (object)$item;
      $object->_table = $table;
      $objects[] = $object;
    }

    return $objects;
  }

  public static function save(&$object, $props = array()) {
    $table = $object->_table;

    if(count($props) > 0) {
      foreach($props as $prop => $value)
        $object->$prop = $value;

      $data = $props;
    }
    else {
      $data = (array)$object;
      unset($data["_table"]);
    }

    if(isset($data["id"])) {
      $result = MySQL::update($table, $data, array("id" => $data["id"]));
    }
    else {
      $result = MySQL::insert($table, $data);
      $object->id = MySQL::lastInsertId();
    }

    return $result;
  }

  public static function update($table, $set, $at = null) {
    return MySQL::update($table, $set, $at);
  }

  public static function remove(&$object) {
    $table = $object->_table;
    $data = (array)$object;
    unset($data["_table"]);
    return MySQL::delete($table, $data);
  }

  public static function removeAll($table, $where = array()) {
    return MySQL::delete($table, $where);
  }

  public static function drop(&$object) {
    return MySQL::drop($object->_table);
  }
}
