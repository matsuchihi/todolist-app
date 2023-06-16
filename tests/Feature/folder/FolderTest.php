<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Folder;
use PHPUnit\TextUI\XmlConfiguration\Logging\Logging;

class FolderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_showCreateForm(): void
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get('/folders/create');
        $response->assertOK()
            ->assertSee('フォルダを追加する');
    }

    /**
     * フォルダ登録テスト
     * 
     * 
     */
    public function test_create(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/folders/create', [
            'user_id' => $user->id,
            'title' => 'misa'
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('folders', [
            'user_id' => $user->id,
            'title' => 'misa',

        ]);
    }
}
