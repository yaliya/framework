<?php

namespace Tau\View;

use Tau\View\BaseView;

class Twig extends BaseView
{
  public function __construct(array $config) {
    $loader = new \Twig_Loader_Filesystem($config["views"]);
    self::$engine = new \Twig_Environment($loader, $config);
  }

  public function render($file, $content) {
    return self::$engine->render($file, $content);
  }
}
