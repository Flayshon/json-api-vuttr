<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AttachJwtToken;
use Tests\TestCase;

class ManageToolsTest extends TestCase
{
    use RefreshDatabase, WithFaker, AttachJwtToken;

    /** @test */
    public function guests_cannot_manage_tools()
    {
        $tool = factory('App\Tool')->create();

        $this->get('api/tools')
            ->assertStatus(401);

        $this->getJson('api' . $tool->path())
            ->assertStatus(401);

        $this->patchJson('api' . $tool->path(), $tool->toArray())
            ->assertStatus(401);
        
        $this->postJson('api/tools', $tool->toArray())
            ->assertStatus(401);
        
        $this->deleteJson('api' . $tool->path())
            ->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_cannot_manage_the_tools_of_others()
    {
        $this->actingAs(factory('App\User')->create());

        $tool = factory('App\Tool')->create();

        $this->getJson('api' . $tool->path())
            ->assertStatus(403);

        $this->patchJson('api' . $tool->path(), $tool->toArray())
            ->assertStatus(403);
        
        $this->deleteJson('api' . $tool->path())
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_create_a_tool()
    {
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
    public function a_user_can_view_their_tool()
    {
        $tool = factory('App\Tool')->create();

        $this->actingAs($tool->user)
            ->getJson('api' . $tool->path())
            ->assertExactJson($tool->toArray());
    }

    /** @test */
    public function a_user_can_view_all_their_tools()
    {
        $user = factory('App\User')->create();

        $tools = [];

        for ($i = 0; $i < 3; $i++) {
            $tool = factory('App\Tool')->create(['user_id' => $user->id]);
            array_push($tools, $tool->toArray());
        }

        $this->actingAs($user)
            ->getJson('api/tools')
            ->assertExactJson($tools);
    }

    /** @test */
    public function a_user_can_view_all_tools_with_a_certain_tag()
    {
        $user = factory('App\User')->create();

        // Generating a set of tools
        $tools = [];
        for ($i = 0; $i < 3; $i++) {
            $tool = factory('App\Tool')->create(['user_id' => $user->id]);
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

        $this->actingAs($user)
            ->getJson("api/tools?tag={$chosenTag}")
            ->assertExactJson($filteredTools);
    }

    /** @test */
    public function a_user_can_delete_a_tool()
    {
        $tool = factory('App\Tool')->create();

        $this->actingAs($tool->user)
            ->delete('api' . $tool->path())
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

        $this->actingAs($tool->user)
            ->patchJson('api' . $tool->path(), $attributes)
            ->assertStatus(204);

        $attributes['tags'] = json_encode($attributes['tags']);
        $this->assertDatabaseHas('tools', $attributes);
    }
}
