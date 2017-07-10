<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller {

    public function getSignUp($request, $response){
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignup($request, $response){

        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->EmailAvailable(),
            'name' => v::notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);      

        if ($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }  

        $user = User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        $this->flash->addMessage('info', 'Account created successfully!');

        return $response->withRedirect($this->router->pathFor('home'));

    }

    public function getSignIn($request, $response){
        return $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn($request, $response){
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (!$auth){

            $this->flash->addMessage('error', 'Credentials did not match our records!');

            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignout($request, $response){

        $this->auth->signout();

        return $response->withRedirect($this->router->pathFor('home'));

    }

    public function getResetPassword($request, $response){
        return $this->view->render($response, 'auth/reset.twig');
    }

    public function postResetPassword($request, $response){
        
        $validation = $this->validator->validate($request, [
            'oldpassword' => v::noWhitespace()->notEmpty()->PasswordCheck(),
            'newpassword' => v::noWhitespace()->notEmpty()
        ]);

        if ($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.reset'));
        }

        $user = User::find($_SESSION['user'])->first();
        $user->password = password_hash($request->getParam('newpassword'), PASSWORD_DEFAULT);
        $user->save();

        $this->flash->addMessage('info', 'Password successfully changed!');

        return $response->withRedirect($this->router->pathFor('home'));

    }

}