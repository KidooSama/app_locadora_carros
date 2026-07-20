<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Cliente;
use App\Models\Carro;
use App\Models\Locacao;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Limpa tabelas para permitir rodar o seeder repetidamente
        Locacao::query()->delete();
        Carro::query()->delete();
        Modelo::query()->delete();
        Marca::query()->delete();
        Cliente::query()->delete();
        User::query()->delete();

        // Usuário padrão para testar login
        User::create([
            'name' => 'Admin',
            'email' => 'admin@teste.com',
            'password' => Hash::make('123456'),
        ]);

        // Marcas e modelos
        $marcas = [
            'Chevrolet' => ['Onix', 'Cruze', 'Tracker'],
            'Volkswagen' => ['Gol', 'Polo', 'T-Cross'],
            'Toyota' => ['Corolla', 'Etios', 'Hilux'],
        ];

        $modelos_ids = [];
        foreach ($marcas as $marca_nome => $modelos) {
            $marca = Marca::create([
                'nome' => $marca_nome,
                'imagem' => '',
            ]);
            $marcas_ids[$marca_nome] = $marca->id;

            foreach ($modelos as $modelo_nome) {
                $modelos_ids[] = Modelo::create([
                    'marca_id' => $marca->id,
                    'nome' => $modelo_nome,
                    'imagem' => '',
                    'numero_portas' => 4,
                    'lugares' => 5,
                    'air_bag' => true,
                    'abs' => true,
                ])->id;
            }
        }

        // Clientes
        $clientes = [
            ['nome' => 'João Silva'],
            ['nome' => 'Maria Oliveira'],
            ['nome' => 'Carlos Souza'],
        ];
        $clientes_ids = [];
        foreach ($clientes as $cliente) {
            $clientes_ids[] = Cliente::create($cliente)->id;
        }

        // Carros
        $carros = [
            ['modelo_id' => $modelos_ids[0], 'placa' => 'ABC1D23', 'disponivel' => true, 'km' => 12000],
            ['modelo_id' => $modelos_ids[1], 'placa' => 'DEF4G56', 'disponivel' => true, 'km' => 8000],
            ['modelo_id' => $modelos_ids[2], 'placa' => 'HIJ7K89', 'disponivel' => false, 'km' => 25000],
        ];
        $carros_ids = [];
        foreach ($carros as $carro) {
            $carros_ids[] = Carro::create($carro)->id;
        }

        // Locações
        Locacao::create([
            'cliente_id' => $clientes_ids[0],
            'carro_id' => $carros_ids[0],
            'data_inicio_periodo' => now()->subDays(2),
            'data_final_previsto_periodo' => now()->addDays(5),
            'data_final_realizado_periodo' => null,
            'valor_diaria' => 150.00,
            'km_inicial' => 12000,
            'km_final' => null,
        ]);

        Locacao::create([
            'cliente_id' => $clientes_ids[1],
            'carro_id' => $carros_ids[2],
            'data_inicio_periodo' => now()->subDays(10),
            'data_final_previsto_periodo' => now()->subDays(5),
            'data_final_realizado_periodo' => now()->subDays(5),
            'valor_diaria' => 120.00,
            'km_inicial' => 25000,
            'km_final' => 25500,
        ]);
    }
}
