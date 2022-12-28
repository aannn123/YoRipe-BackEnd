<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function checkHeader()
    {
        $token = "57|Y08gb4O3boRW0k75518yYpHPaTx5ulwL75J4iLhp"; // change token from your login role admin
        return $this->withHeader('Authorization', 'Bearer ' . $token);     
    }
    
    public function testAccessNotAdmin()
    {
        $token = "16|hCome1BhAYQzuNTK58yeXFWGpKBVmd8xJN1XWJOb"; //change token from your login roles other than admin
        $this->withHeader('Authorization', 'Bearer ' . $token)   
                ->json('POST', '/api/admin/create/user', ['Accept' => 'application/json'])
                ->assertStatus(401)
                ->assertJson([
                    "message"=> "You are not an admin"
            ]);
    }

    public function testEmailUniqueForCreateUser()
    {
        $param = [
            "email" => "user@gmail.com"
        ];
        $this->checkHeader();
        $this->json('POST', '/api/admin/create/user', $param, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson(["errors"=> [
                "email"=> [
                    "The email has already been taken."
                ]
        ]]);
    }

    public function testRequiredFieldsForCreateUser()
    {
        $this->checkHeader();
        $this->json('POST', '/api/admin/create/user', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson(["errors"=> [
                "name"=> [
                    "The name field is required."
                ],
                "email"=> [
                    "The email field is required."
                ],
                "password"=> [
                    "The password field is required."
                ],
                "role_id"=> [
                    "The role id field is required."
                ]
        ]]);
    }

    public function testRoleidNotFoundForCreateUser()
    {
        $param = [
            "name" => "Test",
            "email" => "test123@gmail.com",
            "password" => "12345",
            "password_confirmation" => "12345",
            "role_id" => 5
        ];
        $this->checkHeader();
        $this->json('POST', '/api/admin/create/user', $param, ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJson(["message" => "Role not found"]);
    }

    public function testCreateUserSuccess()
    {
        $param = [
            "name" => "Test",
            "email" => "test1234@gmail.com",
            "password" => "12345",
            "password_confirmation" => "12345",
            "role_id" => 3
        ];
        $this->checkHeader();
        $this->json('POST', '/api/admin/create/user', $param, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "name",
                    "email",
                    "password",
                    "role_id",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            ]);
    }
}
