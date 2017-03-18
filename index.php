<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "vendor/autoload.php";

use Tau\Router\Router;
use Tau\Http\Response;
use Tau\Http\Request;
use Tau\Database\DBAL\MySQL;
use Tau\Database\ORM\Model;

Router::get("/", function() {
  $html = "<form action='/send' method='POST' enctype='multipart/form-data'>";
  $html .= "<input type='file' name='file'>";
  $html .= "<input type='submit'>";
  $html .= "</form>";

  return Response::html($html);
});

Router::post("/send", function() {
  return Request::files('file')->name;
});

Router::route();
