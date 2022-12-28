<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function checkHeader()
    {
        $token = "16|hCome1BhAYQzuNTK58yeXFWGpKBVmd8xJN1XWJOb";
        return $this->withHeader('Authorization', 'Bearer ' . $token);     
    }
     
    public function testGetBlog()
    {
        $this->checkHeader();
        $this->json('GET', '/api/blog')
        ->assertStatus(200)
        ->assertJsonStructure([
            "status",
            "message",
            "data",
        ]);
    }

    public function testRequiredFieldsForCreateBlog()
    {
        $this->checkHeader();
        $this->json('POST', '/api/blog', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson(["errors"=> [
                "title"=> [
                    "The title field is required."
                ],
                "content"=> [
                    "The content field is required."
                ]
        ]]);
    }

    public function testSuccessCreateBlog()
    {
        $param = [
            "title" => "How to create api",
            "content" => "Lorem ipsum dolor sit amet",
        ];
        $this->checkHeader();
        $this->json('POST', '/api/blog', $param, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "user_id",
                    "title",
                    "slug",
                    "content",
                    "updated_at",
                    "created_at",
                    "id",
                ]
            ]);
    }

    public function testCheckIdNotFoundUpdateBlog()
    {
        $param = [
            "title" => "How to create api",
            "content" => "Lorem ipsum dolor sit amet",
        ];

        $this->checkHeader();
        $this->json('PUT', '/api/blog/100', $param, ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJson([
                "message"=> "Blog not found"
            ]);
    }

    public function testRequiredFieldsForUpdateBlog()
    {
        $param = [
            "title" => "",
            "content" => "",
        ];

        $this->checkHeader();
        $this->json('PUT', '/api/blog/1', $param, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson(["errors"=> [
                "title"=> [
                    "The title field is required."
                ],
                "content"=> [
                    "The content field is required."
                ]
        ]]);
    }

    public function testSuccessUpdateBlog()
    {
        $param = [
            "title" => "How to create api 2",
            "content" => "Lorem ipsum dolor sit amet 2",
        ];

        $this->checkHeader();
        $this->json('PUT', '/api/blog/1', $param, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "user_id",
                    "title",
                    "slug",
                    "content",
                    "updated_at",
                    "created_at",
                    "id",
                ]
            ]);
    }

    public function testCheckIdNotFoundDeleteBlog()
    {
        $this->checkHeader();
        $this->json('DELETE', '/api/blog/100', ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJson([
                "message"=> "Blog not found"
            ]);
    }

    public function tesDeleteSuccessBlog()
    {
        $this->checkHeader();
        $this->json('DELETE', '/api/blog/1', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "message"=> "Blog deleted"
            ]);
    }

    public function testCheckIdNotFoundDetailBlog()
    {
        $this->checkHeader();
        $this->json('GET', '/api/blog/100', ['Accept' => 'application/json'])
            ->assertStatus(404)
            ->assertJson([
                "message"=> "Blog not found"
            ]);
    }

    public function testDetailSuccessBlog()
    {
        $this->checkHeader();
        $this->json('GET', '/api/blog/1', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "data" => [
                    "id",
                    "user_id",
                    "title",
                    "slug",
                    "content",
                    "updated_at",
                    "created_at",
                ]
            ]);
    }
}
