<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateModeloRequest;
use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\repositories\ModeloRepository;

class ModeloController extends Controller
{
    protected $modelo;

    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $modeloRepository = new ModeloRepository($this->modelo);

        if ($request->has('atributos_marca')) {
            $atributos_marca = 'marca:id,'.$request->atributos_marca;
            $modeloRepository->selectAtributosRegistros($atributos_marca);
        }else {
            $modeloRepository->selectAtributosRegistros('marca');
        }
        if ($request->has('filtro')) {
           $modeloRepository->filtro($request->filtro);            
        }
        if ($request->has('atributos')) {
            $modeloRepository->selectAtributos($request->atributos);
        }
        return  response()->json(['data'=>$modeloRepository->getResultado()], 200);
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
        $request->validate($this->modelo->rules());
        $imagem = $request->file('imagem')->store('imagens/modelos','public');
        $modelo = $this->modelo->create([
            'marca_id'=> $request->marca_id,
            'nome'=> $request->nome,
            'imagem'=> $imagem,
            'numero_portas'=> $request->numero_portas,
            'lugares'=> $request->lugares,
            'air_bag'=> $request->air_bag,
            'abs'=> $request->abs,
        ]);
        return response()->json(['data'=> $modelo, 'message' => 'Criado com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
           return response()->json(['message'=>'Valor não encntrado'], 404);
        }
        return response()->json(['data'=>$modelo], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateModeloRequest $request, $id)
    {
        $modelo = $this->modelo->find($id);
        //dd($request->nome);
        if($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }
       
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
            $modelo->imagem = $request->file('imagem')->store('imagens/modelos','public');
        }  
        $modelo->fill($request->except('imagem'));
        $modelo->save();
        return response()->json($modelo, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['message'=>'Valor Não Encontrado'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);     
        $modelo->delete();
        
        return response()->json(['data'=>$modelo], 200);
   
    }
}
