<?php
function userRoutes($router) {
    // Get all users (Admins only)
    $router->get('/users', 'UserController@getAllUsers');

    // Get a single user by ID (User can only see their own data, Admins can see anyone)
    $router->get('/users/{id}', 'UserController@getUserById');

    // Delete user (Admin can delete any user)
    $router->delete('/users/{id}', ['UserController', 'deleteUser']);  

    // Edit user (Users can edit their own, Admins can edit any user)
    $router->put('/users/{id}', 'UserController@editUser');  
}

?>
