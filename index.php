<?php

require "vendor/autoload.php";

use Tau\Router\Router;
use Tau\Http\Response;
use Tau\Database\DBAL\MySQL;
use Tau\Database\ORM\Model;

Router::get("/", function() {
  return Response::html("OK");
});

Router::route();
