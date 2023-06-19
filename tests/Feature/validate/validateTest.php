<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class validateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_loginValidate(): void
    {
        $from = 'http://localhost/login';
        $url = route('login');

        $this->get($from);
        $this->post($url, [])->assertRedirect($from);

        // emailの入力チェック
        $this->post($url, ['email' => ''])->assertInvalid('email');
        $this->post($url, ['email' => 'dasf', 'password' => 'dasfa'])->assertInvalid('email');
        //passwordの入力チェック
        $this->post($url, ['password' => ''])->assertInvalid('password');
        $this->post($url, ['password' => 'dafadsfadf'])->assertInvalid('email');
    }
    public function test_registerValidate(): void
    {
        $from = 'http://localhost/register';
        $url = route('register');

        $this->get($from);
        $this->post($url, [])->assertRedirect($from);
        //nameが未入力
        $this->post($url, ['name' => ''])->assertInvalid(['name','email','password']);
        //nameが文字列じゃない時
        $this->post($url, ['name' => ['aa' => 'bb']])->assertInValid(['name' => 'ユーザー名は文字列を指定してください。']);
        //nameが256文字以上の時
        $this->post($url, ['name' => str_repeat('a', 256)])->assertInValid(['name' => 'ユーザー名は、255文字以下で指定してください。']);

        //emailが未入力
        $this->post($url, ['email' => ''])->assertInvalid(['name', 'email', 'password']);
        //emailが文字列じゃない時
        $this->post($url, ['email' => ['aa' => 'bb']])->assertInValid(['email' => 'メールアドレスは文字列を指定してください。']);
        //emailが256文字以上の時
        $this->post($url, ['email' => str_repeat('a', 256)])->assertInValid(['email' => 'メールアドレスは、255文字以下で指定してください。']);
        //emailが正しい形式か
        $this->post($url, ['email' => 'test+-.._1@example.com'])->assertInValid(['email' => 'メールアドレスには、有効なメールアドレスを指定してください。']);
        //emailが一意か
        $user = User::factory()->create();
        $this->post($url, ['email' => $user->email])->assertInValid(['email' => 'メールアドレスの値は既に存在しています。']);

        //パスワード未入力
        $this->post($url, ['password' => ''])->assertInvalid(['name', 'email', 'password']);
        //パスワードが文字列かどうか
        $this->post($url, ['password' => ['aa' => 'bb']])->assertInvalid(['password' => 'パスワードは文字列を指定してください。']);
        //パスワードが８文字以上か
        $this->post($url, ['password' => str_repeat('a', 7)])->assertInValid(['password' => 'パスワードは、8文字以上で指定してください。']);
        //パスワード確認と一致するか
        $this->post($url, ['password' => 'testtest','password_confirmation' => 'test'])->assertInValid(['password' => 'パスワードと、確認フィールドとが、一致していません。']);

    }
}
