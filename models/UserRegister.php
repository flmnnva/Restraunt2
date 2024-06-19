<?php

namespace app\models;
class UserRegister extends User
{
    public $password_confirmation='';
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['password','compare','compareAttribute'=>'password']
            ]
            );
        }
}