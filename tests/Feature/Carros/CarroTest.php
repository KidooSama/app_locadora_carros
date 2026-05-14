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

    public function test_cadastrar_auth_obrigatorio()
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

    public function test_cadastrar_placa_eh_obrigatoria()
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

    public function test_cadastrar_modelo_id_obrigatoria()
    {
        $dados =[
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

    public function test_cadastrar_placa_regex()
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

    public function test_cadastrar_carro_modeloid_inexistente()
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

    public function test_cadastrar_nao_permite_placa_duplicada()
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
    // READ TEST
    // ------------------------------------------

    public function test_listar_carros()
    {
        Carro::factory(3)->create();

        $response = $this->withHeaders(
            $this->authHeader()
        )->getJson('/api/v1/carro');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_busca_carro_por_id()
    {
        $carro = Carro::factory()->create();

        $response = $this->withHeaders($this->authHeader())
        ->getJson("/api/v1/carro/{$carro->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'placa' => $carro->placa
        ]);
    }

    public function test_busca_carro_inexistente()
    {
        $response = $this->withHeaders(
            $this->authHeader()
        )->getJson('/api/v1/carro/999999');
        $response->assertStatus(404);
    }

    public function test_filtrar_carro_por_placa()
    {
        $carro = Carro::factory(2)->create();

        $response = $this->withHeaders($this->authHeader())
        ->getJson("/api/v1/carro?filtro=placa:=:{$carro[1]->placa}");
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
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
            'disponivel' => false,
            'km' => 50000
        ]);
    }
    public function test_atualizar_carro_placa_igual()
    {
        $carro = Carro::factory()->createOne();
        $dados = [
            'modelo_id' => $carro->modelo->id,
            'placa' => $carro->placa,
            'disponivel' => false,
            'km' => 50000
        ];
        $response = $this->withHeaders($this->authHeader())
        ->putJson("/api/v1/carro/{$carro->id}",$dados);

        $response->assertStatus(200);
        $this->assertDatabaseHas('carros', [
            'id' => $carro->id,
            'placa' => $carro->placa,
            'disponivel' => false,
            'km' => 50000
        ]);
    }
        
    // ------------------------------------------
    
    public function test_atualizar_auth_obrigatorio()
    {
        $carro = Carro::factory()->createOne();
        $dados = [
            'modelo_id' => $carro->modelo->id,
            'placa' => 'XYZ1A99',
            'disponivel' => false,
            'km' => 50000
        ];
        $response = $this->putJson("/api/v1/carro/{$carro->id}",$dados);
        $response->assertStatus(401);
        $this->assertDatabaseMissing('carros', [
            'placa' => 'XYZ1A99',
            'disponivel' => false,
            'km' => 50000
        ]);
    }
        
    // ------------------------------------------
    
    public function test_atualizar_um_campo_carro()
    {
        $carro = Carro::factory()->createOne();
        $dados = [
            'modelo_id' => $carro->modelo->id,
            'km' => 50000
        ];
        $response = $this->withHeaders($this->authHeader())
        ->putJson("/api/v1/carro/{$carro->id}",$dados);

        $response->assertStatus(200);
        $this->assertDatabaseHas('carros', [
            'id' => $carro->id,
            'placa' => $carro->placa,
            'disponivel' => $carro->disponivel,
            'km' => 50000
        ]);
    }
        
    // ------------------------------------------
    
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

    }
        
    // ------------------------------------------
    
    public function test_atualizar_carro_inexistente()
    {
        
        $dados = [            
            'disponivel' => false,
            'km' => 50000
        ];
        $response = $this->withHeaders($this->authHeader())
        ->putJson("/api/v1/carro/999999",$dados);
        $response->assertStatus(404);

    }

    // ------------------------------------------
    // DELETE TEST
    // ------------------------------------------

    public function test_deletar_carro()
    {
        $carro = Carro::factory()->create();
        $response = $this->withHeaders($this->authHeader())
        ->deleteJson("/api/v1/carro/{$carro->id}");
        $response->assertStatus(200);

    }

    // ------------------------------------------

    public function test_deletar_carro_inexistente()
    {
        $carro = Carro::factory()->create();
        $response = $this->withHeaders($this->authHeader())
        ->deleteJson("/api/v1/carro/99999");
        $response->assertStatus(404);

    }

    // ------------------------------------------

    public function test_deletar_auth_obrigatorio()
    {
        $carro = Carro::factory()->create();
        $response = $this->deleteJson("/api/v1/carro/{$carro->id}");
        $response->assertStatus(401);

    }
}
