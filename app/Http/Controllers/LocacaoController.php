<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Http\Requests\StoreLocacaoRequest;
use App\Http\Requests\UpdateLocacaoRequest;
use Illuminate\Http\Request;
use App\repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    protected $locacao;

    public function __construct(locacao $locacao){
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new locacaoRepository($this->locacao);

        if ($request->has('filtro')) {
           $locacaoRepository->filtro($request->filtro);            
        }
        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        }
        return  response()->json(['data'=>$locacaoRepository->getResultado()], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLocacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocacaoRequest $request)
    {
        $locacao = $this->locacao->fill($request->all());
        $locacao->save();
        return response()->json(['data'=> $locacao,'message' => 'Criado com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
           return response()->json(['message'=>'Valor não encontrado'], 404);
        }
        return response()->json(['data'=>$locacao], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacaoRequest  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocacaoRequest $request, $id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }
        $locacao->fill($request->all());
        $locacao->save();
        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(['message'=>'Valor Não Encontrado'], 404);
        }   
        $locacao->delete();
        
        return response()->json(['data'=>$locacao], 200);
    }
}
