<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequiredFieldsForLogin()
    {
        $this->json('POST', '/api/login', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson(["errors"=> [
                "email"=> [
                    "The email field is required."
                ],
                "password"=> [
                    "The password field is required."
                ]
        ]]);
    }

    public function testAccountDoesntMatchLogin()
    {
        $userData = [
            "email" => "user@gmail.com",
            "password" => "123123",
        ];

        $this->json('POST', '/api/login', $userData, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson(["message"=> "Email & Password does not match with our record."]);
    }

    public function testAccountSuccessLogin()
    {
        $userData = [
            "email" => "user@gmail.com",
            "password" => "12345",
        ];

        $this->json('POST', '/api/login', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "token",
                "type"
            ]);
    }
}
