<?php

namespace App\Core;

use AltoRouter;
use App\Core\Middlewares\Middleware;
use App\Exceptions\RegisterRouteException;
use App\Exceptions\RouteNotMatchException;
use PHPUnit\Logging\Exception;

class Router {

    protected AltoRouter $router;
    protected array $routes = [];

    public function __construct() 
    {
        $this->router = new AltoRouter();
    }

    /**
     * @throws RegisterRouteException
     */
    public function add($uri, $controller, $method, $name = null): static
    {
        if (! is_array($controller)) {
            throw new RegisterRouteException("controller should be an array");
        }

        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null,
            'name' => $name
        ];

        return $this;
    }

    public function resource($base_url, $controller, $name_prefix = null, $middleware = null)
    {
        $this->get("$base_url", [$controller, 'index'], "{$name_prefix}.index")->only($middleware);
        $this->get("$base_url/create",  [$controller, 'create'], "{$name_prefix}.create")->only($middleware);
        $this->post("$base_url",  [$controller, 'store'], "{$name_prefix}.store")->only($middleware);
        $this->get("$base_url/[i:id]/edit",  [$controller, 'edit'], "{$name_prefix}.edit")->only($middleware);
        $this->get("$base_url/[i:id]",  [$controller, 'show'], "{$name_prefix}.show")->only($middleware);
        $this->put("$base_url/[i:id]",  [$controller, 'update'], "{$name_prefix}.update")->only($middleware);
        $this->delete("$base_url/[i:id]",  [$controller, 'destroy'], "{$name_prefix}.destroy")->only($middleware);
    }

    public function get($uri, $controller, $name = null)
    {
        return $this->add($uri, $controller, 'GET', $name);
    }

    public function post($uri, $controller, $name = null)
    {
        return $this->add($uri, $controller, 'POST', $name);
    }

    public function put($uri, $controller, $name = null)
    {
        return $this->add($uri, $controller, 'PUT', $name);
    }

    public function patch($uri, $controller, $name = null)
    {
        return $this->add($uri, $controller, 'PATCH', $name);
    }

    public function delete($uri, $controller, $name = null)
    {
        return $this->add($uri, $controller, 'DELETE', $name);
    }

    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key; 
    }

    /**
     * @throws RouteNotMatchException
     */
    public function match($method, $currentUrl = null)
    {
        foreach($this->routes as $route) {
            $this->router->map($route['method'], $route['uri'], [...$route['controller'], $route['middleware']], $route['name']);
        }

        $match = $this->router->match(requestUrl: $currentUrl, requestMethod: $method);

        if (!$match || !is_array($match)) {
            throw new RouteNotMatchException("Route Does not Exists");
        }

        $class = $match['target'][0] ?? null;
        $method = $match['target'][1] ?? null;
        $middleware = $match['target'][2] ?? null;
        $params = $match['params'];

        if (! class_exists($class) || ! method_exists($class, $method)) {
            throw new RouteNotMatchException("Controller or Method Not Found");
        }

        if ($middleware) {
            Middleware::handle($middleware);
        }   

        return (new $class)->$method(...$params);
    }


    public function abort($code = Request::NOT_FOUND) {
        abort($code);
    }

    public function route($name, $params) {
        return $this->router->generate($name, $params);
    }

    public function routes(): array
    {
        return $this->routes;
    }
}