<?php

require_once "./src/includes/bootstrap.php";
require_once "./src/lib/router/Router.php";

/**
 * TODO: Add middleware callbacks
 * TODO: new command to cli to generate controllers
 */

Router::get('/', 'User', 'showView');
Router::get('/api/users/views', 'User', 'showView');
Router::get('/api/users/:id', 'User', 'show');
Router::get('/api/users', 'User', 'index');
Router::get('/api/users?id=1&name=steve', 'User', 'index');
Router::post('/api/users', 'User', 'create');
Router::put('/api/users/{id}', 'User', 'update');
Router::delete('/api/users/{id}', 'User', 'delete');


Router::run();
