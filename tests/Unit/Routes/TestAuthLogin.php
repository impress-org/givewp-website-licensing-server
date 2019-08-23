<?php

namespace Test\Unit\Routes;

use Tests\TestCase;

/**
 * Class TestAuthLogin
 * @package Test\Unit\Routes
 */
class TestAuthLogin extends TestCase
{
    public function testShouldReturnToken(): void
    {
        $this->json('POST', 'auth/login', ['email' => 'xyz@test.com', 'password' => '12345'])
            ->seeStatusCode(200)
            ->seeJsonStructure(['token']);
    }

    public function testShouldReturnUserNotFoundError(): void
    {
        $this->json('POST', 'auth/login', ['email' => 'abs@test.com', 'password' => '12345'])
            ->seeStatusCode(400)
            ->seeJsonContains(['msg' => 'User does not exist.']);
    }

    public function testShouldReturnInvalidPasswordInputError(): void
    {
        $this->json('POST', 'auth/login', ['email' => 'abs@test.com', 'password' => ''])
            ->seeStatusCode(422)
            ->seeJsonContains(['password' => ['validation.required']]);
    }

    public function testShouldReturnInvalidUserInputError(): void
    {
        $this->json('POST', 'auth/login', ['email' => '', 'password' => '12345'])
            ->seeStatusCode(422)
            ->seeJsonContains(['email' => ['validation.required']]);
    }

    public function testShouldReturnInvalidUserOrPasswordError(): void
    {
        $this->json('POST', 'auth/login', ['email' => 'xyz@test.com', 'password' => '1234'])
            ->seeStatusCode(400)
            ->seeJsonContains(['msg' => 'Email or password is wrong.']);
    }
}
