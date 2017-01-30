<?php

namespace Tau\Foundation;

use Tau\View\View;
use Tau\View\Twig;
use Tau\Router\Router;
use Tau\Database\DBAL\MySQL;
use Dontenv\Dontenv;

class Application
{
  public function __construct($rootDir) {
    $dotenv = new \Dotenv\Dotenv($rootDir);
    $dotenv->load();

    MySQL::connect(array(
      "host"      => $_ENV["DB_HOST"],
      "user"      => $_ENV["DB_USER"],
      "password"  => $_ENV["DB_PASSWORD"],
      "database"  => $_ENV["DB_DATABASE"]
    ));

    View::init(new Twig(array(
      "debug" => $_ENV["APP_DEBUG"],
      "views" => __DIR__."/../app/Views/",
      "cache" => __DIR__."/../storage/cache/templates/"
    )));
  }
};
