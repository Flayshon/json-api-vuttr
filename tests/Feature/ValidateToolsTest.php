<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AttachJwtToken;
use Tests\TestCase;

class ValidateToolsTest extends TestCase
{
    use RefreshDatabase, WithFaker, AttachJwtToken;

    /** @test */
    public function a_tool_have_required_attributes()
    {
        $attributes = [
            'title',
            'description',
            'link',
            'tags'
        ];

        $user = factory('App\User')->create();
        $this->actingAs($user);

        foreach ($attributes as $attribute) {
            $tool = $this->setupInvalidTool($attribute, $user->id);

            $this->postJson('api/tools', $tool)
                ->assertStatus(422)
                ->assertJsonFragment([
                    'errors' => [
                        $attribute => [
                            "The $attribute field is required."
                        ]
                    ]
                ]);

            $this->assertDatabaseMissing('tools', ['user_id' => $tool['user_id']]);
        }
    }

    /**
     * Returns a Tool attributes array with one of the attributes initialized as blank.
     *
     * @param string $attribute
     * @param int $user_id
     * @return array
     */
    private function setupInvalidTool(string $attribute, int $user_id)
    {
        return factory('App\Tool')->raw([$attribute => '', 'user_id' => $user_id]);
    }
}
