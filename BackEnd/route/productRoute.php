<?php
function productRoutes($router) {
    // Get all products (Admins only)
    $router->get('/products', 'getAllProducts');

    // Get a single product by ID
    $router->get('/products/{id}', 'getProductById');

    // Create a new product (Admins only)
    $router->post('/products', 'createProduct');

    // Edit a product (Admins only)
    $router->post('/products/{id}', 'editProduct');

    // Delete a product (Admins only)
    $router->delete('/products/{id}', 'deleteProduct');
}
?>
