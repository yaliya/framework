<?php

namespace Tau\Http;

class Response
{
  public static function response($type, $message, $code)
  {
    header_remove();

    http_response_code($code);

    header('Status: '.$code);

    header('Content-Type: '.$type);

    return $message;
  }

  public static function error($message, $code = 500) {
    header_remove();

    http_response_code($code);

    header($_SERVER['SERVER_PROTOCOL'] .' '. $code.' '.$message);
  }

  public static function text($message, $code = 200) {
    return self::response("text/plain", $message, $code);
  }

  public static function html($message, $code = 200) {
    return self::response("text/html", $message, $code);
  }

  public static function json($message, $code = 200) {
    return json_encode(self::response("application/json", $message, $code));
  }

  public static function pdf($file, $filename="PDFDocument") {
    $content = file_get_contents($file);

    header_remove();

    header('Content-type: application/pdf');

    header('Content-Disposition: inline; filename="' . $filename . '"');

    header('Content-Transfer-Encoding: binary');

    header('Accept-Ranges: bytes');

    return $content;
  }
}
