<?php

require "vendor/autoload.php";

use Tau\Route;
use Tau\Http\Request;
use Tau\Http\Response;

Route::get("/", function() {
  $html = "<form action='/send' method='POST' enctype='multipart/form-data'>";
  $html .= "<input type='file' name='file'>";
  $html .= "<input type='submit'>";
  $html .= "</form>";

  return Response::html($html);
});

Route::post("/send", function() {
  return Request::files('file')->name;
});

Route::route();
