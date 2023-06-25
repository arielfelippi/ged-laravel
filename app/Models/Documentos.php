<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentos extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'usuario_id',
        'nome',
        'caminho_arquivo',
        'conteudo',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function permissao()
    {
        return $this->hasOne(DocumentosPermissao::class, 'documento_id');
    }
}
