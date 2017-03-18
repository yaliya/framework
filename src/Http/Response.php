<?php

namespace Tau\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class Response extends SymfonyResponse
{
  public static function make($message, $type = "text/plain", $code = 200) {
    return new Response($message, $code, array('content-type' => $type));
  }

  public static function redirect($url) {
    $response = new RedirectResponse($url);
    return $response->send();
  }

  public static function error($message, $code = 500) {
    return self::make($message, "text/plain", $code)->send();
  }

  public static function plain($message, $code = 200) {
    return self::make($message, "text/plain", $code)->send();
  }

  public static function html($message, $code = 200) {
    return self::make($message, "text/html", $code)->send();
  }

  public static function json($message, $code = 200) {
    return self::make(json_encode($message), "application/json", $code)->send();
  }

  public static function stream($callback) {
    $response = new StreamedResponse;
    $response->setCallback($callback);
    return $response->send();
  }

  public static function download($file, $filename = null) {
    $content = file_get_contents($file);

    if($filename == null)
      $filename = $file;

    $response = new Response($content);

    $disposition = $response->headers->makeDisposition(
      ResponseHeaderBag::DISPOSITION_ATTACHMENT,
      $filename
    );

    $response->headers->set('Content-Disposition', $disposition);

    return $response->send();
  }

  public static function binary($file) {
    return new BinaryFileResponse($file);
  }

  public static function file($file, $type) {
    $content = file_get_contents($file);

    $response = new Response($content, 200, array('content-type' => $type));

    return $response->send();
  }

  public static function image($file, $type = "image/jpeg") {
    return self::file($file, $type);
  }

  public static function pdf($file, $type="application/pdf") {
    return self::file($file, $type);
  }
}
