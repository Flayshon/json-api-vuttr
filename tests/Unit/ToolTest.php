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
}
