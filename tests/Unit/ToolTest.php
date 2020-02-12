<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $tool = factory('App\Tool')->create();

        $this->assertEquals("/tools/{$tool->id}", $tool->path());
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $tool = factory('App\Tool')->create();

        $this->assertInstanceOf('App\User', $tool->user);
    }
}
