<?php

namespace Tau;

class View
{
  private static $view;

  public static function init($view)
  {
    self::$view = $view;
  }

  public static function engine()
  {
  	return self::$view->engine();
  }

  public static function render($file, $content = array())
  {
    return self::$view->render($file, $content);
  }
};
