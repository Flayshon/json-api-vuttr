<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageToolsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_create_a_tool()
    {
        //$user = factory('App\User')->create();

        $attributes = [
            'title' => $this->faker->company,
            'link' => $this->faker->url,
            'description' => $this->faker->text(140),
            'tags' => $this->faker->words(5),
        ];

        $response = $this->postJson('/tools', $attributes)
            ->assertStatus(201);

        $attributes['id'] = 1;

        $response->assertExactJson($attributes);
    }

    /** @test */
    public function a_user_can_view_a_tool()
    {
        //$user = factory('App\User')->create();

        $tool = factory('App\Tool')->create();

        $this->withoutExceptionHandling();

        $this->getJson($tool->path())
            ->assertExactJson($tool->toArray());
    }

    /** @test */
    public function a_user_can_view_all_tools()
    {
        $tools = [];

        for ($i=0; $i < 3; $i++) {
            $tool = factory('App\Tool')->create();
            array_push($tools, $tool->toArray());
        }

        $response = $this->get('/tools');

        $response->assertExactJson($tools);
    }
}
