<?php

namespace tests\unit\models;

use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        verify($user = User::findIdentity(1))->notEmpty();
        verify($user->username)->equals('group4');

        verify(User::findIdentity(999))->empty();
    }

    public function testFindUserByAccessToken()
    {
        verify($user = User::findIdentityByAccessToken('100-token'))->notEmpty();
        verify($user->username)->equals('group4');

        verify(User::findIdentityByAccessToken('non-existing'))->empty();
    }
}
