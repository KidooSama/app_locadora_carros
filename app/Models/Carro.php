<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modelo;

class Carro extends Model
{
    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];
    use HasFactory;
     protected $appends = ['descricao'];
    
    public function modelo(){
        return $this->belongsTo(Modelo::class);
    }
    public function locacoes(){
        return $this->hasMany(Locacao::class);   
    }
    public function getDescricaoAttribute(){
        return $this->id.' | ' .$this->modelo->marca->nome.' - '.$this->modelo->nome.' - '.$this->placa;
    }
    
}
