<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

// Define app routes
$app->get('/', 'HomeController:index')->setName('home');


$app->group('', function(){
    
    // SIGN THE USER OUT
    $this->get('/auth/signout', 'AuthController:getSignout')->setName('auth.signout');

    // Reset password page
    $this->get('/auth/reset', 'AuthController:getResetPassword')->setName('auth.reset');

    // POST reset password page
    $this->post('/auth/reset', 'AuthController:postResetPassword');

})->add(new AuthMiddleware($container));

$app->group('', function(){
    // SIGN UP PAGE
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');

    // SIGN UP ACTION FORM
    $this->post('/auth/signup', 'AuthController:postSignup');

    // SIGN IN PAGE
    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');

    // SIGN IN ACTION FORM
    $this->post('/auth/signin', 'AuthController:postSignIn');
})->add(new GuestMiddleware($container));



