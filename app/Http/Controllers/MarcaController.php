<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMarcaRequest;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\repositories\MarcaRepository;

class MarcaController extends Controller
{
    protected $marca;

    public function __construct(Marca $marca){
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $marcaRepository = new MarcaRepository($this->marca);

        if ($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistros($atributos_modelos);
        }else {
            $marcaRepository->selectAtributosRegistros('modelos');
        }
        if ($request->has('filtro')) {
           $marcaRepository->filtro($request->filtro);            
        }
        if ($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }
        return  response()->json(['data'=>$marcaRepository->getResultado()], 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(),$this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');
        
      
        $marca= $this->marca->create([
            'nome'=> $request->nome,
            'imagem'=>$imagem_urn
        ]);
        return response()->json(['data'=> $marca], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer   $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if ($marca === null) {
            return response()->json(['message'=>'Valor Não Encontrado'], 404);
        }else{
            return response()->json(['data'=>$marca], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarcaRequest $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json([
                'erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'
            ], 404);
        }

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
            $marca->imagem = $request->file('imagem')->store('imagens', 'public');
        }

        $marca->fill($request->except('imagem'));

        $marca->save();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['message'=>'Valor Não Encontrado'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);     
        $marca->delete();
        
        return response()->json(['data'=>$marca], 200);
    }
}
