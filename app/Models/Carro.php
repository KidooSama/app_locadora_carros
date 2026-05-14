<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modelo;

class Carro extends Model
{
    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];
    use HasFactory;
    
    public function modelo(){
        return $this->belongsTo(Modelo::class);
    }
}
