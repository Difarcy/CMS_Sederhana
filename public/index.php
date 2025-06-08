<?php
session_start();

require_once '../core/Router.php';
require_once '../core/Controller.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/LoginController.php';
require_once '../app/controllers/RegisterController.php';
require_once '../app/controllers/AdminController.php';
require_once '../app/controllers/CategoryController.php';
require_once '../app/controllers/PostController.php';
require_once '../app/controllers/TagController.php';
require_once '../app/controllers/UserController.php';
require_once '../app/controllers/LogoutController.php';
require_once '../app/controllers/ForgotPasswordController.php';
require_once '../app/controllers/ResetPasswordController.php';
require_once '../app/controllers/UserDashboardController.php';
require_once '../app/models/database.php';

$router = new Router();

// Add routes
$router->add('', 'HomeController', 'index');
$router->add('login', 'LoginController', 'index');
$router->add('login/process', 'LoginController', 'process');
$router->add('register', 'RegisterController', 'index');
$router->add('register/process', 'RegisterController', 'process');
$router->add('admin', 'AdminController', 'index');
$router->add('categories', 'CategoryController', 'index');
$router->add('categories/create', 'CategoryController', 'create');
$router->add('categories/store', 'CategoryController', 'store');
$router->add('categories/edit/{id}', 'CategoryController', 'edit');
$router->add('categories/update/{id}', 'CategoryController', 'update');
$router->add('categories/delete/{id}', 'CategoryController', 'delete');
$router->add('posts', 'PostController', 'index');
$router->add('posts/create', 'PostController', 'create');
$router->add('posts/store', 'PostController', 'store');
$router->add('posts/edit/{id}', 'PostController', 'edit');
$router->add('posts/update/{id}', 'PostController', 'update');
$router->add('posts/delete/{id}', 'PostController', 'delete');
$router->add('tags', 'TagController', 'index');
$router->add('tags/create', 'TagController', 'create');
$router->add('tags/store', 'TagController', 'store');
$router->add('tags/edit/{id}', 'TagController', 'edit');
$router->add('tags/update/{id}', 'TagController', 'update');
$router->add('tags/delete/{id}', 'TagController', 'delete');
$router->add('users', 'UserController', 'index');
$router->add('users/create', 'UserController', 'create');
$router->add('users/store', 'UserController', 'store');
$router->add('users/edit/{id}', 'UserController', 'edit');
$router->add('users/update/{id}', 'UserController', 'update');
$router->add('users/delete/{id}', 'UserController', 'delete');
$router->add('logout', 'LogoutController', 'index');

// Forgot Password & Reset Password
$router->add('forgot-password', 'ForgotPasswordController', 'index');
$router->add('forgot-password/process', 'ForgotPasswordController', 'process');
$router->add('reset-password', 'ResetPasswordController', 'index');
$router->add('reset-password/process', 'ResetPasswordController', 'process');

$router->add('user-dashboard', 'UserDashboardController', 'index');

$url = isset($_GET['url']) ? $_GET['url'] : '';

$router->dispatch($url);