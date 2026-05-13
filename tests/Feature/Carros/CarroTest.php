<?php

namespace Tests\Feature;

use App\Models\Carro;
use App\Models\Modelo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CarroTest extends TestCase
{
    use RefreshDatabase;
    protected $modelo;
    protected $auth;

    //--------------------------------
    // HELPERS 
    //--------------------------------
    protected function setUp():void{ 
        parent::setUp();
         $this->auth = $this->autenticarUser();
         $this->modelo = Modelo::factory()->create();
    
    }
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
    public function authHeader(){
        return  ['Authorization' => 'Bearer ' . $this->auth['token']];
       
    }



    // ------------------------------------------
    // CREATE
    // ------------------------------------------


    public function test_cadastrar_carro()
    {
        $dados= [
            'modelo_id' => $this->modelo->id,
            'placa' => 'ABC1D23',
            'disponivel' => true,
            'km' => 1000
        ]; 
        
        $response = $this->withHeaders($this->authHeader())->postJson('/api/v1/carro', $dados);
        $response->assertStatus(201);
        $this->assertDatabaseHas('carros', [
            'placa' => 'ABC1D23',
            'disponivel' => true,
            'km' => 1000
        ]);
    }

// ------------------------------------------

    public function test_auth_obrigatorio()
    {
        $dados= [
            'modelo_id' => $this->modelo->id,
            'placa' => 'ABC1D23',
            'disponivel' => true,
            'km' => 1000
        ]; 
        
        $response = $this->postJson('/api/v1/carro', $dados);
        $response->assertStatus(401);

    }

// ------------------------------------------

    public function test_placa_e_obrigatoria()
    {
        $dados =[
            'modelo_id' => $this->modelo->id,
            'placa' => '',
            'disponivel' => true,
            'km' => 1000
        ];

        $response = $this->withHeaders($this->authHeader())
        ->postJson('/api/v1/carro', $dados);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('placa');
    }

 // ------------------------------------------

    public function test_modelo_id_obrigatoria()
    {
        $dados =[
            'modelo_id' => '',
            'placa' => 'ABC1D23',
            'disponivel' => true,
            'km' => 1000
        ];

        $response = $this->withHeaders($this->authHeader())
        ->postJson('/api/v1/carro', $dados);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modelo_id');
    }

// ------------------------------------------   

    public function test_placa_regex()
    {
        $dados =[
            'modelo_id' => $this->modelo->id,
            'placa' => 'AAAAAA2',
            'disponivel' => false,
            'km' => 10000
        ];

        $response = $this->withHeaders($this->authHeader())
        ->postJson('/api/v1/carro', $dados);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('placa');
    }

 // ------------------------------------------

    public function test_modelo_inexistente()
    {
        $dados = [
            'modelo_id' => '999999',
            'placa' => 'ABC1D23',
            'disponivel' => false,
            'km' => 10000
        ];

        $response = $this->withHeaders($this->authHeader())
        ->postJson('/api/v1/carro', $dados);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('modelo_id');
    }

    // ------------------------------------------

    public function test_nao_permite_placa_duplicada()
    {
        $dados =[
            'modelo_id' => $this->modelo->id,
            'placa' => 'ABC1D23',
            'disponivel' => false,
            'km' => 10000
        ];

        $response = $this->withHeaders($this->authHeader())
        ->postJson('/api/v1/carro', $dados);

        $response->assertStatus(201);

        $response = $this->withHeaders($this->authHeader())
        ->postJson('/api/v1/carro', $dados);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('placa');
    }

    // ------------------------------------------
    // UPDATE TEST
    // ------------------------------------------

    public function test_atualizar_carro()
    {
        $carro = Carro::factory()->createOne();
        $dados = [
            'modelo_id' => $carro->modelo->id,
            'placa' => 'XYZ1A99',
            'disponivel' => false,
            'km' => 50000
        ];
        $response = $this->withHeaders($this->authHeader())
        ->putJson("/api/v1/carro/{$carro->id}",$dados);

        $response->assertStatus(200);
        $this->assertDatabaseHas('carros', [
            'id' => $carro->id,
            'placa' => 'XYZ1A99',
            'km' => 50000
        ]);
    }
    public function test_atualizar_placa_ja_existente()
    {
        $carro = Carro::factory(2)->create();
        
        $dados = [
            'modelo_id' => $carro[0]->modelo->id,
            'placa' => $carro[1]->placa,
            'disponivel' => false,
            'km' => 50000
        ];
        $response = $this->withHeaders($this->authHeader())
        ->putJson("/api/v1/carro/{$carro[0]->id}",$dados);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('placa');
        $response->dumpJson();
        
    }
}
