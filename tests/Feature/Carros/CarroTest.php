<?php

namespace Tests\Feature;

use App\Models\Carro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Override;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Tests\TestCase;


class CarroTest extends TestCase
{
    use RefreshDatabase;

    public function autenticarUser(){
        $user = User::factory()->createOne();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */

        // gera token JWT
        $token = auth('api')->login($user);
    
        return [
            'user'=> $user,
            'token'=> $token

        ];
    }

    public function test_cadastrar_carro()
    {
        $auth = $this->autenticarUser();
              
        $dados= [

            'modelo_id' => 15,
            'placa' => 'ABC1D23',
            'disponivel' => true,
            'km' => 1000

        ]; 
        // faz requisição fake
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token']
        ])->postJson('/api/v1/carro', $dados);

        // verifica status HTTP
        $response->assertStatus(201);
        
        $this->assertDatabaseHas('carros', [
            'placa' => 'ABC1D23'
        ]);
        $response->dumpJson();

    }
    // public function test_placa_e_obrigatoria()
    // {
    //     $response = $this->postJson('/api/v1/carro', [

    //         'modelo_id' => 1,
    //         'placa' => '',
    //         'disponivel' => true,
    //         'km' => 1000

    //     ]);

    //     $response->assertStatus(422);
    //       $response->dumpHeaders();
    // }
}
