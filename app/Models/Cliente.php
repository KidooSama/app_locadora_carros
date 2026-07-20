<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = ['nome'];
    protected $appends = ['quantidade_locacoes'];
    
    public function locacoes(){
        return $this->hasMany(Locacao::class);   
    }
    
    public function getQuantidadeLocacoesAttribute()
    {
        return $this->locacoes->count();
    }
}
