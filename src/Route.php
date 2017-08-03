<?php

namespace Tau;

class Route
{
  protected static $routes;
  protected static $middleware = null;
  private static $_instance = null;

  public function init($routes)  {
    self::$routes = $routes;
  }

  public static function request($method, $pattern, $callback) {
    self::$routes[] = array(
      "method"      => $method,
      "pattern"     => $pattern,
      "callback"    => $callback,
      "after"       => [self::$middleware],
      "before"      => []
    );

    if(self::$_instance == null)

      self::$_instance = new self();

    return self::$_instance;
  }

  public static function get($pattern, $callback) {
    return self::request("GET", $pattern, $callback);
  }

  public static function post($pattern, $callback) {
    return self::request("POST", $pattern, $callback);
  }

  public static function group($middleware, $callback) {
    self::$middleware = $middleware;
    call_user_func($callback);
    self::$middleware = null;
  }

  public function after($callback) {
    end(self::$routes);

    $lastRouteIndex = key(self::$routes);

    array_unshift(self::$routes[$lastRouteIndex]["after"], $callback);

    return self::$_instance;
  }

  public function before($callback) {
    end(self::$routes);

    $lastRouteIndex = key(self::$routes);

    self::$routes[$lastRouteIndex]["before"][] = $callback;

    return self::$_instance;
  }

  public static function routes() {
    return self::$routes;
  }

  public static function route() {
    foreach(self::$routes as $route) {
      $args = array();
      $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route["pattern"])) . "$@D";

      if($_SERVER["REQUEST_METHOD"] == $route["method"] && preg_match($pattern, $_SERVER["REQUEST_URI"], $args)) {
        array_shift($args);

        //After middleware
        foreach($route["after"] as $middleware) {
          if(is_callable($middleware)) {
            if(!call_user_func_array($middleware, $args)) {
              return;
            }
          }
          else {
            if($middleware != null) {
              $class = "Tau\\Middlewares\\".$middleware;
              if(!call_user_func_array(array(new $class, "request"), $args)) {
                return;
              }
            }
          }
        }

        if(is_callable($route['callback'])) {
          echo call_user_func_array($route['callback'], $args);
        }
        else {
          $class = "Tau\\Controllers\\".explode("@", $route["callback"])[0];
          $method = explode("@", $route["callback"])[1];
          echo call_user_func_array(array(new $class, $method), $args);
        }

        //Before middleware
        foreach($route["before"] as $middleware) {
          if(is_callable($middleware)) {
            if(!call_user_func_array($middleware, $args)) {
              return;
            }
          }
          else {
            if($middleware != null) {
              $class = "Tau\\Middlewares\\".$middleware;
              if(!call_user_func_array(array(new $class, "request"), $args)) {
                return;
              }
            }
          }
        }

        return;
      }
    }
  }
};
