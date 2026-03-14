<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use Illuminate\Http\Request;
use App\repositories\CarroRepository;

class CarroController extends Controller
{
    protected $carro;

    public function __construct(Carro $carro){
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $carroRepository = new CarroRepository($this->carro);

        if ($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
            $carroRepository->selectAtributosRegistros($atributos_modelo);
        }else {
            $carroRepository->selectAtributosRegistros('modelo');
        }
        if ($request->has('filtro')) {
           $carroRepository->filtro($request->filtro);            
        }
        if ($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }
        return  response()->json(['data'=>$carroRepository->getResultado()], 200);
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
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());      
        $carro = $this->carro->create([
            'modelo_id'=> $request->modelo_id,
            'placa'=> $request->placa,
            'disponivel'=> $request->disponivel,
            'km'=> $request->km,
            
        ]);
        return response()->json(['data'=> $carro,'message' => 'Criado com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null) {
           return response()->json(['message'=>'Valor não encontrado'], 404);
        }
        return response()->json(['data'=>$carro], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function edit(Carro $carro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarroRequest $request, $id)
    {
        $carro = $this->carro->find($id);
        if($carro === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }
        $carro->fill($request->all());
        $carro->save();
        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(['message'=>'Valor Não Encontrado'], 404);
        }   
        $carro->delete();
        
        return response()->json(['data'=>$carro], 200);
    }
}
