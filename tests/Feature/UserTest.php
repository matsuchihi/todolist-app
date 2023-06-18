<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Folder;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user = User::factory()->create();
        if ($folder = Folder::factory(2)->create([
            'user_id' => $user->id,
        ])) {
            $response = $this
                ->actingAs($user)
                ->get('/');

            $response->assertRedirect();
        } else {
            $response = $this
                ->actingAs($user)
                ->get('/');
            $response->asserOK();
        }

       
    }
    
}
