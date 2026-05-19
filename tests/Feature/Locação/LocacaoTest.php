<?php

namespace Tests\Feature;

use App\Models\Carro;
use App\Models\Cliente;
use App\Models\Locacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocacaoTest extends TestCase
{
    use RefreshDatabase;

    protected $cliente;
    protected $carro;
    protected $auth;

    //--------------------------------
    // HELPERS
    //--------------------------------

    protected function setUp(): void
    {
        parent::setUp();

        $this->auth = $this->autenticarUser();

        $this->cliente = Cliente::factory()->create();

        $this->carro = Carro::factory()->create([
            'disponivel' => true,
            'km' => 50000
        ]);
    }

    public function autenticarUser()
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function authHeader()
    {
        return [
            'Authorization' => 'Bearer ' . $this->auth['token']
        ];
    }

    //--------------------------------
    // CREATE
    //--------------------------------

    public function test_cadastrar_locacao()
    {
        $dados = [

            'cliente_id' => $this->cliente->id,
            'carro_id' => $this->carro->id,
            'data_inicio_periodo' => now()->format('Y-m-d H:i:s'),
            'data_final_previsto_periodo' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'valor_diaria' => 150.50
        ];

        $response = $this->withHeaders(
            $this->authHeader()
        )->postJson('/api/v1/locacao', $dados);

        $response->assertStatus(201);

        $this->assertDatabaseHas('locacoes', [
            'cliente_id' => $this->cliente->id,
            'carro_id' => $this->carro->id,
            'valor_diaria' => 150.50,
            'km_inicial' => 50000
        ]);

        $this->assertDatabaseHas('carros', [
            'id' => $this->carro->id,
            'disponivel' => false
        ]);
    }

    //--------------------------------

    public function test_cadastrar_auth_obrigatorio()
    {
        $dados = [

            'cliente_id' => $this->cliente->id,
            'carro_id' => $this->carro->id,
            'data_inicio_periodo' => now()->format('Y-m-d H:i:s'),
            'data_final_previsto_periodo' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'valor_diaria' => 150.50
        ];

        $response = $this->postJson(
            '/api/v1/locacao',
            $dados
        );

        $response->assertStatus(401);
    }

    //--------------------------------

    public function test_cadastrar_carro_indisponivel()
    {
        $this->carro->disponivel = false;
        $this->carro->save();

        $dados = [

            'cliente_id' => $this->cliente->id,
            'carro_id' => $this->carro->id,
            'data_inicio_periodo' => now()->format('Y-m-d H:i:s'),
            'data_final_previsto_periodo' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'valor_diaria' => 150.50
        ];

        $response = $this->withHeaders(
            $this->authHeader()
        )->postJson('/api/v1/locacao', $dados);

        $response->assertStatus(422);
    }

    //--------------------------------

    public function test_cadastrar_cliente_obrigatorio()
    {
        $dados = [

            'carro_id' => $this->carro->id,
            'data_inicio_periodo' => now()->format('Y-m-d H:i:s'),
            'data_final_previsto_periodo' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'valor_diaria' => 150.50
        ];

        $response = $this->withHeaders(
            $this->authHeader()
        )->postJson('/api/v1/locacao', $dados);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(
            'cliente_id'
        );
    }

    //--------------------------------

    public function test_cadastrar_carro_obrigatorio()
    {
        $dados = [

            'cliente_id' => $this->cliente->id,
            'data_inicio_periodo' => now()->format('Y-m-d H:i:s'),
            'data_final_previsto_periodo' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'valor_diaria' => 150.50
        ];

        $response = $this->withHeaders(
            $this->authHeader()
        )->postJson('/api/v1/locacao', $dados);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(
            'carro_id'
        );
    }

    //--------------------------------
    // READ
    //--------------------------------

    public function test_listar_locacoes()
    {
        Locacao::factory(3)->create();

        $response = $this->withHeaders(
            $this->authHeader()
        )->getJson('/api/v1/locacao');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data'
        ]);
    }

    //--------------------------------

    public function test_busca_locacao_por_id()
    {
        $locacao = Locacao::factory()->create();

        $response = $this->withHeaders(
            $this->authHeader()
        )->getJson("/api/v1/locacao/{$locacao->id}");

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'id' => $locacao->id
        ]);
    }

    //--------------------------------

    public function test_busca_locacao_inexistente()
    {
        $response = $this->withHeaders(
            $this->authHeader()
        )->getJson('/api/v1/locacao/999999');

        $response->assertStatus(404);
    }

    //--------------------------------
    // UPDATE
    //--------------------------------

    public function test_finalizar_locacao()
    {
        $locacao = Locacao::factory()->create([
            'carro_id' => $this->carro->id,
            'data_final_realizado_periodo' => null,
            'km_final' => null
        ]);

        $this->carro->disponivel = false;
        $this->carro->save();

        $dados = [

            'data_final_realizado_periodo' => now()->format('Y-m-d H:i:s'),
            'km_final' => $locacao->km_inicial + 5000

        ];

        $response = $this->withHeaders(
            $this->authHeader()
        )->putJson(
            "/api/v1/locacao/{$locacao->id}",
            $dados
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('locacoes', [
            'id' => $locacao->id,
            'km_final' => 55000
        ]);

        $this->assertDatabaseHas('carros', [
            'id' => $this->carro->id,
            'km' => 55000,
            'disponivel' => true
        ]);
    }

    //--------------------------------

    public function test_atualizar_locacao_inexistente()
    {
        $response = $this->withHeaders(
            $this->authHeader()
        )->putJson('/api/v1/locacao/999999', [

            'km_final' => 60000
        ]);

        $response->assertStatus(404);
    }

    //--------------------------------
    // DELETE
    //--------------------------------

    public function test_deletar_locacao()
    {
        $locacao = Locacao::factory()->create([
            'carro_id' => $this->carro->id
        ]);

        $response = $this->withHeaders(
            $this->authHeader()
        )->deleteJson(
            "/api/v1/locacao/{$locacao->id}"
        );

        $response->assertStatus(200);
    }

    //--------------------------------

    public function test_deletar_locacao_inexistente()
    {
        $response = $this->withHeaders(
            $this->authHeader()
        )->deleteJson('/api/v1/locacao/999999');

        $response->assertStatus(404);
    }

    //--------------------------------

    public function test_deletar_locacao_em_andamento_libera_carro()
    {
        $this->carro->disponivel = false;
        $this->carro->save();

        $locacao = Locacao::factory()->create([
            'carro_id' => $this->carro->id,
            'data_final_realizado_periodo' => null
        ]);

        $response = $this->withHeaders(
            $this->authHeader()
        )->deleteJson(
            "/api/v1/locacao/{$locacao->id}"
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('carros', [
            'id' => $this->carro->id,
            'disponivel' => true
        ]);
    }
}
