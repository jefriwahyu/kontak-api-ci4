<?php

use CodeIgniter\Router\RouteCollection;

    /**
     * @var RouteCollection $routes
     */
    $routes->get('/', 'Home::index');
    
    // Handle preflight requests untuk semua route
    $routes->options('(:any)', 'Options::handle');

    $routes->group('api', function ($routes) {
        $routes->resource('kontak', ['controller' => 'Api\Contacts']);
        $routes->post('upload', 'Api\Contacts::upload');
        $routes->patch('contacts/favorite/(:num)', 'Api\Contacts::toggleFavorite/$1');
    });
