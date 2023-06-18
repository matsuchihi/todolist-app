<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Folder;
use App\Models\Task;

class TaskTest extends TestCase
{
       use DatabaseTransactions;

    protected $user;
    protected $folders;
    protected $tasks;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->folders = Folder::factory()->create(['user_id' => $this->user->id]);
        $this->tasks = Task::factory()->create(['folder_id' => $this->folders->id]);
        $this->actingAs($this->user);
        
    }
    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {

        /**
         * 他のユーザーのページにアクセスした時
         */
        // テスト用ユーザーを作成
        $jiro = User::factory()->create(['name' => 'jiro']);

        // アクセス権のないフォルダを作成
        $imaccessibleFolder = Folder::factory()->create(['user_id' => $jiro->id]);

        // アクセス権のあるフォルダにアクセス
        $response = $this
            ->get('/folders/' . $imaccessibleFolder->id . '/tasks');

        // 200 OKが返されることをアサート
        $response->assertStatus(403);

        /**
         * 存在しないページへアクセスした時
         */
        $response = $this
            ->get('/folders/999/tasks');

        $response->assertstatus(404);

        /**
         * フォルダを取得してタスクを取得し、ページを表示する
         */
        
        $response = $this
            ->get('/folders/' . $this->folders->id . '/tasks');

        $response->assertOk();
    }

    public function test_showCreateForm()
    {
    
        $response = $this
            ->get('folders/999/tasks/create')
            ->assertStatus(404);
        $response = $this
            ->get('folders/' . $this->folders->id . '/tasks/create')
            ->assertOk();
    }

    public function test_create()
    {
        $task = Task::factory($this->folders)->create(['title' => 'test', 'due_date' => '2023/06/21']);
        $reponse = $this
            ->post('/folders/' . $this->folders->id . '/tasks/create')
            ->assertStatus(302);
    }

    public function test_showEditForm()
    {
        $reponse = $this
            ->get('/folders/' . $this->folders->id . '/tasks/create')
            ->assertStatus(200);
    }

    public function test_taskCreate()
    {
        
        
        //今日以前の日付を入力した場合
        $reponse = $this
            ->from('/folders/' . $this->folders->id . '/tasks/create')
            ->post('/folders/' . $this->folders->id . '/tasks/create', [
                'title' => 'misa',
                'due_date' => '2023/05/18'
            ])
            ->assertRedirect('/folders/' . $this->folders->id . '/tasks/create');

        //バリデーション通過時
        $reponse = $this
            ->post('/folders/' . $this->folders->id . '/tasks/create', [
                'title' => 'misa',
                'due_date' => '2023/06/30'
            ])
            ->assertRedirect('/folders/' . $this->folders->id . '/tasks');

        $this->assertDatabaseHas('tasks', [
            'due_date' => '2023/06/30',
            'title' => 'misa',

        ]);
    }
    public function test_edit(){
        $response = $this
        ->post('/folders/'.$this->folders->id.'/tasks/'.$this->tasks->id.'/edit',['title'=>'test','status'=>2,'due_date'=>'2023/06/25'])
        ->assertRedirect('/folders/'. $this->folders->id  .'/tasks');
        $this->assertDatabaseHas('tasks', [
            'due_date' => '2023/06/25',
            'title' => 'test',
            'status'=>2
        ]);
    }
}
