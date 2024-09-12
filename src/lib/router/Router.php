<?php

require_once "./src/lib/router/HttpMethods.php";

class Router extends HttpMethods
{
  public static function run()
  {
    $requestUri = self::normalizeRoute(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $routes = HttpMethods::getRoutes();

    if (strpos($requestUri, BASE_FOLDER) === 0) {
      $requestUri = substr($requestUri, strlen(BASE_FOLDER));
    }

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    foreach ($routes as $route) {

      $paramNames = [];
      if (str_contains($route['route'], ':')) {
        $paramNames = self::getParamNames($route['route']);
      }
      $pattern = self::buildPattern($route['route']);

      if (preg_match($pattern, $requestUri, $matches) && strtoupper($route['method']) === strtoupper($requestMethod)) {
        array_shift($matches);

        $params = !empty($paramNames) ? array_combine($paramNames, array_pad($matches, count($paramNames), '')) : [];

        $body = self::getBody();
        $queryParams = self::getQueryParams();
        $controllerName = ucfirst($route['controller']) . 'Controller';
        $actionName = $route['action'] . 'Action';

        self::loadController($controllerName, $actionName, [
          'params' => $params,
          'body' => $body,
          'queryParams' => $queryParams
        ]);
        return;
      }
    }

    echo json_encode(["ERROR" => "RUTA NO ENCONTRADA"]);
  }

  private static function getParamNames($route)
  {
    preg_match_all('/:([a-zA-Z0-9_]+)/', $route, $matches);
    return $matches[1];
  }

  private static function buildPattern($route)
  {
    $pattern = preg_replace('/:([a-zA-Z0-9_]+)/', '([^/]+)', $route);
    $pattern = rtrim($pattern, '/');
    $pattern = str_replace('/', '\/', $pattern);
    return "@^" . $pattern . "$@";
  }

  private static function getBody()
  {
    $body = file_get_contents('php://input');
    return json_decode($body, true) ?? [];
  }

  private static function getQueryParams()
  {
    $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    $queryParams = [];
    if ($queryString) {
      parse_str($queryString, $queryParams);
    }
    return $queryParams;
  }

  private static function loadController($controllerName, $actionName, $params = [])
  {
    $controllerFile = "./src/controller/" . $controllerName . ".php";

    if (is_file($controllerFile)) {
      require_once $controllerFile;

      if (class_exists($controllerName)) {
        $controller = new $controllerName;

        if (method_exists($controller, $actionName)) {
          call_user_func([$controller, $actionName], $params);
        } else {
          echo json_encode(["ERROR" => "MÉTODO NO ENCONTRADO"]);
        }
      } else {
        echo json_encode(["ERROR" => "CONTROLADOR NO ENCONTRADO"]);
      }
    } else {
      echo json_encode(["ERROR" => "ARCHIVO DE CONTROLADOR NO ENCONTRADO"]);
    }
  }

  private static function normalizeRoute($route)
  {
    return rtrim($route, '/');
  }
}
