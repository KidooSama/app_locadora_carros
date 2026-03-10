<?php

namespace App\Models;
use App\Models\Modelo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules(){
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id,
            'imagem' => 'required|image|mimes:png'
        ];
    }
    public function feedback(){
        return [
            'require' => 'Campo Necessario',
            'nome.unique' => 'Ja existe esse nome',
            'imagem.mimes' => 'Precisa ser .png',
            'imagem.image' => 'Precisa ser uma imagem',
        ];
    }
    public function modelos(){
        return $this->hasMany(Modelo::class);
    }
}
