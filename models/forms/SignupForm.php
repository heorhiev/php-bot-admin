<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    protected $user;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
        ];
    }

    public function signup()
    {
        if ($this->validate()) {
            $this->user = new User();
            $this->user->username = $this->username;
            $this->user->email = $this->email;
            $this->user->setPassword($this->password);
            $this->user->generateAuthKey();

            return $this->user->save();
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

}
