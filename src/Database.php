<?php

namespace Tau;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database extends Capsule
{
  public static function init(array $config) {
    $capsule = new Capsule;

    $capsule->addConnection($config);

    $capsule->setAsGlobal();

    $capsule->bootEloquent();
    
    return $capsule;
  }
}
