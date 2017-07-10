<?php


namespace App\Validation\Rules;

use App\Models\User;

use Respect\Validation\Rules\AbstractRule;

class PasswordCheck extends AbstractRule{

    public function validate($input){

        // get user from session ID

        $user = User::find($_SESSION['user'])->first();

        if (!$user){
            return false;
        }

        if (password_verify($input, $user->password)){
            return true;
        }

        return false;

    }

}