<?php

namespace App\Auth;

use App\Models\User;

class Auth{


    public function user(){
        if (isset($_SESSION['user'])){
            return User::find($_SESSION['user']);
        }
    }

    public function check(){
        return isset($_SESSION['user']);
    }

    public function attempt($email, $password){

        // search the user by the email address
        // if the user doesn't exist, we return false
        $user = User::where('email', $email)->first();

        if (!$user){
            return false;
        }

        // verify the password for that user
        // set into a session
        if (password_verify($password, $user->password)){

            $_SESSION['user'] = $user->id;

            return true;
        }

        return false;
        
    }

    public function signout(){
        unset($_SESSION['user']);
    }

}