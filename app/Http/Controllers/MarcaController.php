<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

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
    public function index()
    {   $marca = $this->marca->all();

        return  response()->json(['data'=>$marca], 200);
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
      
        $marca= $this->marca->create($request->all());
        return response()->json(['data'=> $marca,'message' => 'Criado com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer   $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
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
    public function update(Request $request, $id)
    {
       $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['message'=>'Valor Não Encontrado'], 404);
        }else{
            $request->validate($this->marca->rules(),$this->marca->feedback());
            $marca->update($request->all());
        }
       return response()->json(['data'=>$marca], 200);
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
        }else{
            $marca->delete();
        }
        return response()->json(['data'=>$marca], 200);
    }
}
