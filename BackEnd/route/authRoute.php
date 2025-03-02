<?php
function authRoutes($router) {
    $router->post('/register', 'register');  // Register route
    $router->post('/login', 'login');  // Login route
}
?>
