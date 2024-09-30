<?php

declare(strict_types=1);

namespace unit\entities\User;

use Codeception\Test\Unit;
use common\entities\User;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $user = User::create(
            $username = 'username',
            $email = 'email@site.com',
            $password = 'password'
        );

        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertFalse($user->isActive());
    }
}