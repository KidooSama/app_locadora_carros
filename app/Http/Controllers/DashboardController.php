<?php

namespace App\Http\Controllers;
use App\Models\Carro;
use App\Models\Locacao;
use App\Models\Cliente;
use App\Models\Marca;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCarros = Carro::count();

        $totalClientes = Cliente::count();

        $totalLocacoes = Locacao::count();

        $receitaTotal = Locacao::sum('valor_diaria');

        $carrosDisponiveis = Carro::where('disponivel', 1)->count();

        $carrosLocados = Carro::where('disponivel', 0)->count();

        $locacoesAndamentoQt = Locacao::where('data_final_realizado_periodo', null)->count();
        
        $locacoesProximas = Locacao::whereNull('data_final_realizado_periodo')
            ->with(['carro.modelo', 'cliente'])
            ->orderBy('data_final_previsto_periodo')
            ->take(3)
            ->get();

        return response()->json([
            'total_carros' => $totalCarros,
            'total_clientes' => $totalClientes,
            'total_locacoes' => $totalLocacoes,
            'carros_disponiveis' => $carrosDisponiveis,
            'carros_locados' => $carrosLocados,
            'receita_total' => $receitaTotal,
            'locacoes_proximas' => $locacoesProximas,
            'locacoes_andamento_qt' => $locacoesAndamentoQt,
        ]);
    }
}
