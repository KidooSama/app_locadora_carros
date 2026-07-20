<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Http\Requests\StoreLocacaoRequest;
use App\Http\Requests\UpdateLocacaoRequest;
use App\Models\Carro;
use Illuminate\Http\Request;
use App\repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    protected $locacao;
    protected $carro;
    public function __construct(locacao $locacao, Carro $carro){
        $this->locacao = $locacao;
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        // Relacionamentos
        $locacaoRepository->withObj([
            'cliente',
            'carro.modelo.marca'
        ]);

        // Filtros simples da tabela locacoes
        if ($request->has('filtro')) {

            $locacaoRepository->filtro($request->filtro);
        }
        if ($request->has('disponivel')) {

            $locacaoRepository->filtro($request->filtro);
        }

        // Seleção de atributos da locação
        if ($request->has('atributos')) {

            $locacaoRepository->selectAtributos(
                $request->atributos
            );
        }

        return response()->json($locacaoRepository->getResultadoPaginado(5),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocacaoRequest $request)
    {
        $carro = $this->carro->find($request->carro_id);
        if (!$carro) {
            return response()->json(['message' => 'O carro não existe'], 404);
        }
        if (!$carro->disponivel) {
           return response()->json([
            'message' => 'O carro precisa estar disponível'], 422);
        }
        
        $locacao = $this->locacao->fill($request->all());
        $locacao->km_inicial = $carro->km;
        $locacao->save();
        $carro->disponivel = false;
        $carro->save();
        return response()->json($locacao->load('cliente', 'carro.modelo'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
           return response()->json(['message'=>'Valor não encontrado'], 404);
        }
        return response()->json($locacao->load('cliente', 'carro.modelo'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacaoRequest  $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocacaoRequest $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json([
                'message' => 'Impossível realizar a atualização. O recurso solicitado não existe'
            ], 404);
        }
        if ($locacao->data_final_realizado_periodo !== null) {
            return response()->json([
                'message' => 'Locação finalizada não pode ser alterada'
            ], 422);
        }

        // -----------------------------------------
        // ALTERAÇÃO DE CARRO
        // -----------------------------------------

        if ($request->has('carro_id') && $request->carro_id != $locacao->carro_id) {

            $novoCarro = $this->carro->find($request->carro_id);

            if (!$novoCarro->disponivel) {
                return response()->json(['message' => 'O carro precisa estar disponível'], 422);
            }
            // carro antigo
            $carroAntigo = $locacao->carro;

            $carroAntigo->disponivel = true;
            $carroAntigo->save();

            // novo carro
            $novoCarro->disponivel = false;
            $novoCarro->save();

            // novo km inicial
            $locacao->km_inicial = $novoCarro->km;
        }

        // -----------------------------------------
        // FINALIZAÇÃO
        // -----------------------------------------
        if ($request->has('data_final_realizado_periodo') && $locacao->data_final_realizado_periodo === null){
            /* FINALIZAÇÃO DE LOCAÇÃO
             *
             * Aqui o update funciona também como ação de finalizar:
             * quando o front enviar `data_final_realizado_periodo` e a locação ainda
             * não tiver sido finalizada, o sistema entende que é o fechamento.
             *
             * Regras aplicadas:
             *  - data final >= data inicial
             *  - km_final >= km_inicial
             *  - o carro recebe o km final e volta a ficar disponível
             */
            if ($request->data_final_realizado_periodo < $locacao->data_inicio_periodo) {
                return response()->json(['message' => 'A data final realizada não pode ser menor que a data inicial'], 422);
            }
            if ($request->km_final < $locacao->km_inicial) {
                return response()->json(['message' => "O valor final da quilometragem precisa ser maior que {$locacao->km_inicial}"], 422);
            }

            $carro = $locacao->carro;

            $carro->km = $request->km_final;
            $carro->disponivel = true;
            $carro->save();
        }

        // -----------------------------------------
        // UPDATE
        // -----------------------------------------

        $locacao->fill($request->all());
        $locacao->save();

        return response()->json(
            $locacao->load('cliente', 'carro.modelo'),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(['message'=>'Valor Não Encontrado'], 404);
        }
        if ($locacao->data_final_realizado_periodo === null) {
            //Cancelar Locação:
            $carro = $locacao->carro;
            $carro->disponivel = true;
            $carro->save();
        }

        $locacao->delete();
        return response()->json(['data'=>$locacao], 200);
    }
}
