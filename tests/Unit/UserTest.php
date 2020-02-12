<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_user_has_tools()
    {
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->tools);
    }
}
