<?php

class HttpMethods
{
  private static $routes = [];

  public static function get($route, $controller, $action)
  {
    self::addRoute('GET', $route, $controller, $action);
  }

  public static function post($route, $controller, $action)
  {
    self::addRoute('POST', $route, $controller, $action);
  }

  public static function put($route, $controller, $action)
  {
    self::addRoute('PUT', $route, $controller, $action);
  }

  public static function delete($route, $controller, $action)
  {
    self::addRoute('DELETE', $route, $controller, $action);
  }

  private static function addRoute($method, $route, $controller, $action)
  {
    foreach (self::$routes as $existingRoute) {
      if ($existingRoute['method'] === strtoupper($method) && $existingRoute['route'] === self::normalizeRoute($route)) {
        throw new Exception("Ruta ya definida: $method $route");
      }
    }

    self::$routes[] = [
      'method' => strtoupper($method),
      'route' => self::normalizeRoute($route),
      'controller' => $controller,
      'action' => $action,
    ];
  }

  public static function getRoutes()
  {
    return self::$routes;
  }

  private static function normalizeRoute($route)
  {
    return rtrim($route, '/');
  }
}
