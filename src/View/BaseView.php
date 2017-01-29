<?php

namespace Tau\View;

abstract class BaseView
{
  protected static $engine;

  abstract public function render($file, $content);
}
