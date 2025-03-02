<?php
class Router {
    private $routes = [];

    // Register GET routes
    public function get($uri, $callback) {
        $this->routes['GET'][$uri] = $callback;
    }

    // Register POST routes
    public function post($uri, $callback) {
        $this->routes['POST'][$uri] = $callback;
    }

    // Register PUT routes
    public function put($uri, $callback) {
        $this->routes['PUT'][$uri] = $callback;
    }

    // Register DELETE routes
    public function delete($uri, $callback) {
        $this->routes['DELETE'][$uri] = $callback;
    }
    public function patch($uri, $callback) {
        $this->routes['PATCH'][$uri] = $callback;
    }

    // Dispatch the request to the appropriate controller
    public function dispatch($uri, $method) {
        // Go through each registered route
        foreach ($this->routes[$method] as $route => $callback) {
            // Match static routes
            if ($uri === $route) {
                $callback();
                return;
            }

            // Handle dynamic routes
            if (preg_match('#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route) . '$#', $uri, $matches)) {
                // Extract the parameters from the URI
                array_shift($matches); // Remove the full match (the URL)
                // Call the callback with the parameters
                $callback(...$matches);
                return;
            }
        }
        
        // If no route found
        echo json_encode(["error" => "Route not found"]);
    }
}
?>
