<?php

require "vendor/autoload.php";

use Tau\Router\Router;
use Tau\Http\Response;

Router::get("/", function() {
  return Response::html("OK");
});

Router::route();
