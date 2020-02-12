<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class ManageToolsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function actingAs(Authenticatable $user, $driver = null)
    {
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', 'Bearer ' . $token);

        return $this;
    }

    /** @test */
    public function a_user_can_create_a_tool()
    {
        $this->withoutExceptionHandling();

        $user = factory('App\User')->create();

        $attributes = [
            'title' => $this->faker->company,
            'link' => $this->faker->url,
            'description' => $this->faker->text(140),
            'tags' => $this->faker->words(5),
        ];

        $response = $this->actingAs($user)
            ->postJson('api/tools', $attributes)
            ->assertStatus(201);

        $responseArray = $response->decodeResponseJson();

        $attributes['id'] = $responseArray['id'];
        $attributes['user_id'] = $user->id;
        $response->assertExactJson($attributes);

        $attributes['tags'] = json_encode($attributes['tags']);
        $this->assertDatabaseHas('tools', $attributes);
    }

    /** @test */
    public function a_user_can_view_a_tool()
    {
        $user = factory('App\User')->create();

        $tool = factory('App\Tool')->create();

        $this->actingAs($user)
            ->getJson('api' . $tool->path())
            ->assertExactJson($tool->toArray());
    }

    /** @test */
    public function a_user_can_view_all_tools()
    {
        $tools = [];

        for ($i = 0; $i < 3; $i++) {
            $tool = factory('App\Tool')->create();
            array_push($tools, $tool->toArray());
        }

        $response = $this->getJson('api/tools');

        $response->assertExactJson($tools);
    }

    /** @test */
    public function a_user_can_view_all_tools_with_a_certain_tag()
    {
        // Generating a set of tools
        $tools = [];
        for ($i = 0; $i < 3; $i++) {
            $tool = factory('App\Tool')->create();
            array_push($tools, $tool->toArray());
        }

        // Choosing a tag to search for
        $tags = $tool->tags;
        $chosenTag = array_pop($tags);

        // Filtering the tools matching the chosen tag
        $filteredTools = [];
        foreach ($tools as $tool) {
            if (in_array($chosenTag, $tool['tags'])) {
                array_push($filteredTools, $tool);
            }
        }

        $response = $this->getJson("api/tools?tag={$chosenTag}");

        $response->assertExactJson($filteredTools);
    }

    /** @test */
    public function a_user_can_delete_a_tool()
    {
        $tool = factory('App\Tool')->create();

        $this->delete('api' . $tool->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('tools', $tool->only('id'));
    }

    /** @test */
    public function a_user_can_update_a_tool()
    {
        $tool = factory('App\Tool')->create();

        $attributes = [
            'title' => $this->faker->company,
            'link' => $this->faker->url,
            'description' => $this->faker->text(140),
            'tags' => $this->faker->words(5),
        ];

        $this->patchJson('api' . $tool->path(), $attributes)
            ->assertStatus(204);

        $attributes['tags'] = json_encode($attributes['tags']);
        $this->assertDatabaseHas('tools', $attributes);
    }
}
