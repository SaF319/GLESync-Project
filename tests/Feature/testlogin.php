<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuarios;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_correcto_redirige_al_dashboard()
    {
        $user = Usuarios::factory()->create([
            'email' => 'test@mail.com',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@mail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_falla_con_credenciales_invalidas()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@mail.com',
            'password' => 'badpass',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /** @test */
    public function email_es_requerido_para_login()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function password_es_requerida_para_login()
    {
        $response = $this->post('/login', [
            'email' => 'test@mail.com',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }


}
