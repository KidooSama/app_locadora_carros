<?php

namespace App\repositories;
use Illuminate\Database\Eloquent\Model;

class ModeloRepository{
    protected $model;
    public function __construct(Model $model){
        $this->model = $model;       
    }
    public function selectAtributosRegistros($atributos){
        $this->model = $this->model->with($atributos);
    }
    public function filtro($filtros){
        $filtros = explode(';',$filtros);
        foreach ($filtros as $condicao) {          
            $condicao = explode(':',$condicao);
            $this->model = $this->model->where($condicao[0], $condicao[1], $condicao[2]);
        }
    }
    public function selectAtributos($atributos){
       $this->model = $this->model->select(explode(',',$atributos));
    
    }
    public function getResultado(){
        return $this->model->get();
    }



}
?>