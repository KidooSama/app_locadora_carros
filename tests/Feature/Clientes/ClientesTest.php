<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ClienteTest extends TestCase
{
    use RefreshDatabase;
    protected $auth;

    //--------------------------------
    // HELPERS 
    //--------------------------------
    protected function setUp():void{ 
        parent::setUp();
         $this->auth = $this->autenticarUser();
    
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


    public function test_cadastrar_cliente()
    {
        $dados= [
            'nome' => 'Kido o Sama'
        ]; 
        
        $response = $this->withHeaders($this->authHeader())->postJson('/api/v1/cliente', $dados);
        $response->assertStatus(201);
        $this->assertDatabaseHas('clientes', [
            'nome' => 'Kido o Sama'
        ]);
    }

// ------------------------------------------

    public function test_cadastrar_auth_obrigatorio()
    {
        $dados= [
            'nome' => 'Kido o Sama'
        ]; 
        
        $response = $this->postJson('/api/v1/cliente', $dados);
        $response->assertStatus(401);

    }

// ------------------------------------------

    public function test_cadastrar_placa_eh_obrigatoria()
    {
        $dados =[
            
        ];

        $response = $this->withHeaders($this->authHeader())
        ->postJson('/api/v1/cliente', $dados);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('nome');
    }

    // ------------------------------------------
    // READ TEST
    // ------------------------------------------

    public function test_listar_clientes()
    {
        Cliente::factory(3)->create();

        $response = $this->withHeaders(
            $this->authHeader()
        )->getJson('/api/v1/cliente');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_busca_cliente_por_id()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->withHeaders($this->authHeader())
        ->getJson("/api/v1/cliente/{$cliente->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'nome' => $cliente->nome
        ]);
    }

    public function test_busca_cliente_inexistente()
    {
        $response = $this->withHeaders(
            $this->authHeader()
        )->getJson('/api/v1/cliente/999999');
        $response->assertStatus(404);
    }

    public function test_filtrar_cliente_por_placa()
    {
        $cliente = Cliente::factory(2)->create();

        $response = $this->withHeaders($this->authHeader())
        ->getJson("/api/v1/cliente?filtro=nome:=:{$cliente[1]->nome}");
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    // ------------------------------------------
    // UPDATE TEST
    // ------------------------------------------

    public function test_atualizar_cliente()
    {
        $cliente = Cliente::factory()->createOne();
        $dados = [
            'nome' =>  'jof53421t3412t34anex'
        ];
        $response = $this->withHeaders($this->authHeader())
        ->putJson("/api/v1/cliente/{$cliente->id}",$dados);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clientes', [
            'nome' =>  'jof53421t3412t34anex'
        ]);
    }

    // ------------------------------------------
    
    public function test_atualizar_auth_obrigatorio()
    {
        $cliente = Cliente::factory()->createOne();
        $dados = [
            'nome' =>  'jof53421t3412t34anex'
        ];
        $response = $this->putJson("/api/v1/cliente/{$cliente->id}",$dados);
        $response->assertStatus(401);
        $this->assertDatabaseMissing('clientes', [
            'nome' =>  'jof53421t3412t34anex'
        ]);
    }
        
    // ------------------------------------------
    
    public function test_atualizar_nenhum_campo_cliente()
    {
        $cliente = Cliente::factory()->createOne();
        $dados = [
            
        ];
        $response = $this->withHeaders($this->authHeader())
        ->putJson("/api/v1/cliente/{$cliente->id}",$dados);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clientes', [
            'nome' =>  $cliente->nome
        ]);
    }
        
    // ------------------------------------------
    
    public function test_atualizar_cliente_inexistente()
    {
        
        $dados = [            
            'nome' =>  'jof53421t3412t34anex'
        ];
        $response = $this->withHeaders($this->authHeader())
        ->putJson("/api/v1/cliente/999999",$dados);
        $response->assertStatus(404);

    }

    // ------------------------------------------
    // DELETE TEST
    // ------------------------------------------

    public function test_deletar_cliente()
    {
        $cliente = Cliente::factory()->create();
        $response = $this->withHeaders($this->authHeader())
        ->deleteJson("/api/v1/cliente/{$cliente->id}");
        $response->assertStatus(200);

    }

    // ------------------------------------------

    public function test_deletar_cliente_inexistente()
    {
        $cliente = Cliente::factory()->create();
        $response = $this->withHeaders($this->authHeader())
        ->deleteJson("/api/v1/cliente/99999");
        $response->assertStatus(404);

    }

    // ------------------------------------------

    public function test_deletar_auth_obrigatorio()
    {
        $cliente = Cliente::factory()->create();
        $response = $this->deleteJson("/api/v1/cliente/{$cliente->id}");
        $response->assertStatus(401);

    }
}
